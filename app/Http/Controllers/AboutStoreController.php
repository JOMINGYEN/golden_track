<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\Models\AboutStore;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Delivery;
use App\Models\ProductType;
use App\Models\ProductInStock;
use App\Models\ProductImportDetail;
use App\Models\ProductImage;
use App\Models\ProductDiscount;
use App\Models\Discount;
use App\Models\Customer;
use App\Models\HeaderShow;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Comment;
use App\Models\SlideShow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Mail;
session_start();


class AboutStoreController extends Controller
{
    public function ShowAboutUS(){
        $all_about_us=AboutStore::orderby('cuahang_thu_tu', 'ASC')->get();
        $all_product_type=ProductType::where('loaisanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
        $all_brand=Brand::where('thuonghieu_trang_thai', '1')->orderBy('id', 'DESC')->get();
        $all_collection=Collection::where('dongsanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
        $all_header=HeaderShow::where('headerquangcao_trang_thai', '1')
        ->orderby('headerquangcao_thu_tu', 'ASC')->get();
        if ($all_header->count()>0) {
            foreach ($all_header as $key=>$value) {
                $thu_tu_header=$value->headerquangcao_thu_tu;
                break;
            }
        } else {
            $all_header=null;
            $thu_tu_header=null;
        }
        $get_about_us_bottom=AboutStore::orderby('cuahang_thu_tu', 'ASC')->first();
        return view('client.pages.about_us.about_us')
        ->with('product_type', $all_product_type)
        ->with('product_brand', $all_brand)
        ->with('all_about_us', $all_about_us)
        ->with('get_about_us_bottom', $get_about_us_bottom)
        ->with('product_collection', $all_collection)
        ->with('header_show', $all_header)
        ->with('header_min', $thu_tu_header);

    }
    public function Index(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $all_about_store=AboutStore::orderby('id', 'DESC')->paginate(5);
            return view('admin.pages.aboutstore.about_store')->with('all_about_store', $all_about_store);
        }
    }

    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function AboutStoreAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.aboutstore.about_store_add');
        }
    }

    public function AboutStoreSave(Request $request)
    {
        $this->AuthLogin();

        $this->validate($request, [
            'about_store_title' => 'bail|required|max:255|min:5',
            'about_store_description' => 'bail|required|max:255|min:5',
            'about_store_address' => 'bail|required|max:255|min:5',
            'about_store_phone_number' => 'bail|required|max:12|min:10',
            'about_store_email' => 'bail|required|email|max:255',
            'about_store_number' => 'bail|required|max:255',
            'about_store_img' => 'bail|mimes:jpeg,jpg,png,gif|required|max:10000'
        ], [
            'required' => 'Không được để trống',
            'email' => 'Email sai định dạng',
            'max' => 'Quá dài',
            'min' => 'Quá ngắn',
            'mimes' => 'Sai định dạng ảnh'
        ]);

        if (Session::get('admin_role') == 3) {
            return Redirect::to('/dashboard');
        } else {
            $data = $request->all();
            $get_about = AboutStore::where('cuahang_thu_tu', $data['about_store_number'])->first();

            if ($get_about) {
                return Redirect::to('/about-store-add')->with('error', 'Thêm không thành công, số thứ tự đã tồn tại!');
            } else {
                $about_store = new AboutStore();
                $about_store->cuahang_tieu_de      = $data['about_store_title'];
                $about_store->cuahang_mo_ta        = $data['about_store_description'];
                $about_store->cuahang_dia_chi      = $data['about_store_address'];
                $about_store->cuahang_so_dien_thoai= $data['about_store_phone_number'];
                $about_store->cuahang_trang_thai   = $data['about_store_status'];
                $about_store->cuahang_thu_tu       = $data['about_store_number'];
                $about_store->cuahang_email        = $data['about_store_email'];

                $get_image = $request->file('about_store_img');
                $path = 'public/uploads/admin/aboutstore/';

                if ($get_image) {
                    $originalName = $get_image->getClientOriginalName();
                    $new_image = time() . '_' . $originalName; // Tên mới tránh trùng

                    $get_image->move($path, $new_image);
                    $about_store->cuahang_anh = $new_image;
                }

                $about_store->save();

                return Redirect::to('/about-store')->with('message', 'Thêm thành công');
            }
        }
    }


    public function UnactiveAboutStore($about_store_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_about_store=AboutStore::find($about_store_id);
            if (!$unactive_about_store) {
                return Redirect::to('/about-store')->with('error', 'Không tồn tại');
            } else {
                $unactive_about_store->cuahang_trang_thai=0;
                $unactive_about_store->save();
                return Redirect::to('/about-store')->with('message', 'Ẩn thành công');
            }
        }
    }
    public function ActiveAboutStore($about_store_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_about_store=AboutStore::find($about_store_id);
            if (!$active_about_store) {
                return Redirect::to('/about-store')->with('error', 'Không tồn tại');
            } else {
                $active_about_store->cuahang_trang_thai=1;
                $active_about_store->save();
                return Redirect::to('/about-store')->with('message', 'Hiển thị thành công');
            }
        }
    }

    public function AboutStoreEdit($about_store_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_about_store=AboutStore::find($about_store_id);
            if (!$edit_about_store) {
                return Redirect::to('/about-store')->with('error', 'Không tồn tại');
            } else {
                return view('admin.pages.aboutstore.about_store_edit')
            ->with('about_store', $edit_about_store);
            }
        }
    }

    public function AboutStoreSaveEdit(Request $request,$about_store_id){
                $get_image = $request->file('about_store_img');
        $path = 'public/uploads/admin/aboutstore/';

        if ($get_image) {
            // Xóa ảnh cũ nếu có
            if ($about_store->cuahang_anh && file_exists($path . $about_store->cuahang_anh)) {
                unlink($path . $about_store->cuahang_anh);
            }

            // Gán tên mới tránh trùng
            $get_name_image = $get_image->getClientOriginalName();
            $image_name = time() . '_' . $get_name_image; // thêm timestamp

            $get_image->move($path, $image_name);
            $about_store->cuahang_anh = $image_name;
        }

    }
    public function AboutStoreDelete($about_store_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $delete_about=AboutStore::find($about_store_id);
            if (!$delete_about) {
                return Redirect::to('/about-store')->with('error', 'Không tồn tại');
            } else {
                $delete_about->delete();
                return Redirect::to('/about-store')->with('message', 'Xóa thành công');
            }
        }
    }
}
