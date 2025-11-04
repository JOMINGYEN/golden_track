<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\Discount;
use App\Models\ProductImport;
use App\Models\ProductInstock;
use App\Models\ProductImportDetail;

use App\Models\Supplier;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class ProductDiscountController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $all_product_discount = Discount::orderby('id', 'desc')->paginate(5);
            foreach ($all_product_discount as $key=> $value) {
                $today = date("Y-m-d"); // Năm/Tháng/Ngày
            $start_date = date("Y-m-d", strtotime("$value->khuyenmai_ngay_khuyen_mai")); // Năm/Tháng/Ngày
            $end_date = date("Y-m-d", strtotime($start_date . "+ $value->khuyenmai_so_ngay_khuyen_mai  day"));
                if ((strtotime($today) >= strtotime($start_date)) && (strtotime($today) <= strtotime($end_date))) {
                    $discount=Discount::find($value->id);
                    $discount->khuyenmai_trang_thai=1;
                    $discount->save();
                } else {
                    $discount=Discount::find($value->id);
                    $discount->khuyenmai_trang_thai=0;
                    $discount->save();
                }
            }
            $all_product_discount_new = Discount::orderby('id', 'desc')->paginate(5);
            return view('admin.pages.product_discount.product_discount')->with('all_product_discount', $all_product_discount_new);
        }
    }
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/admin')->send();
        }
    }

    public function ProductDiscountAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product_in_stock=ProductInstock::where('sanphamtonkho_so_luong_ton', '>', 0)->first();
            if (!$product_in_stock) {
                return Redirect::to('/product-discount')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $discount=Discount::where('khuyenmai_trang_thai', 1)->get();//lay con km
                if ($discount->count()>0) {
                    foreach ($discount as $key => $value) {
                        $product_discount=ProductDiscount::where('khuyenmai_id', $value->id)->get();//lay sp con km
                        foreach ($product_discount as $k => $v) {
                            $pro_dis[]=$v->sanpham_id;
                        }
                    }
                    $product_import_in_stock=ProductInstock::whereNotIn('sanpham_id', $pro_dis)
                ->where('sanphamtonkho_so_luong_ton', '>', 0)->get();//lay sp k km con ton kho
                    foreach ($product_import_in_stock as $in=>$in_stock) {
                        $pro_id[]=$in_stock->sanpham_id;
                    }
                } else {
                    $product_import_in_stock=ProductInstock::where('sanphamtonkho_so_luong_ton', '>', 0)->get();//lay sp k km con ton kho
                    foreach ($product_import_in_stock as $in=>$in_stock) {
                        $pro_id[]=$in_stock->sanpham_id;
                    }
                }
                if (empty($pro_id)) {
                    return Redirect::to('/product-discount')->with('error', 'Sản phẩm không tồn tại!');
                } else {
                    $product=Product::whereIn('id', $pro_id)->paginate(10);
                    return view('admin.pages.product_discount.product_discount_add')
                ->with('all_product', $product);
                }
            }
        }
    }
    public function ProductDiscountAddSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'product_discount_title' => 'bail|required|max:255|min:6',
                'product_discount_content' => 'bail|required|max:255|min:6',
                'product_discount_number' => 'bail|required',
                'product_discount_time' => 'bail|required|min:1',
                'product_discount_img' => 'bail|mimes:jpeg,jpg,png,gif|required|max:10000'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
                  'mimes' => 'Sai định dạng ảnh',
            ]);
            $today = date("Y-m-d");
            if (empty($data['product_discount_product_id'])) {
                return Redirect::to('/product-discount')->with('message', 'Thêm không thành công, chưa chọn sản phẩm!');
            } else {
                if($data['product_discount_day']<$today){
                    return Redirect::to('/product-discount-add')->with('error', 'Thêm không thành công, ngày khuyến mãi phải lớn hơn ngày hiện tại!');
                }else{
                    $discount=new Discount();
                    $discount->khuyenmai_tieu_de=$data['product_discount_title'];
                    $discount->khuyenmai_noi_dung=$data['product_discount_content'];
                    $discount->khuyenmai_gia_tri=$data['product_discount_number'];
                    $discount->khuyenmai_loai=$data['product_discount_type'];
                    $discount->khuyenmai_so_ngay_khuyen_mai=$data['product_discount_time'];
                    $discount->khuyenmai_ngay_khuyen_mai=$data['product_discount_day'];
                    $discount->khuyenmai_trang_thai=$data['product_discount_status'];
                    $get_image = $request->file('product_discount_img');
                    $path = 'public/uploads/admin/productdiscount';
                    if ($get_image) {
                        $get_name_image = $get_image->getClientOriginalName();
                        $name_image = current(explode('.', $get_name_image));
                        $new_image =  $name_image.rand(0, 99).'.'.$get_image->getClientOriginalExtension();
                        $get_image->move($path, $new_image);
                        $discount->khuyenmai_anh = $new_image;
                        $discount->save();
                    } else {
                        return Redirect::to('/product-discount-add')->with('error', 'Thêm không thành công, vui lòng chọn ảnh!');
                    }
                    foreach ($data['product_discount_product_id'] as $key =>$value) {
                        $product_discount=new ProductDiscount();
                        $product_discount->sanpham_id=$value;
                        $product_discount->khuyenmai_id=$discount->id;
                        $product_discount->save();
                    }
                    return Redirect::to('/product-discount')->with('message', 'Thêm thành công!');
                }
            }
        }
    }

    public function ProductDiscountEdit($product_discount_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $discount=Discount::find($product_discount_id);
            if (!$discount) {
                return Redirect::to('/product-discount')->with('error', 'Không tồn tại!');
            } else {
                $product_discount=ProductDiscount::where('khuyenmai_id', $product_discount_id)->get();//sp km cua tin khuyen mai
            $all_discount=Discount::where('khuyenmai_trang_thai', 1)->get();//lay con km
            if ($all_discount->count()>0) {
                foreach ($all_discount as $key => $value) {
                    $product_discount=ProductDiscount::where('khuyenmai_id', $value->id)->get();//lay sp con km
                    foreach ($product_discount as $k => $v) {
                        $pro_dis[]=$v->sanpham_id;
                    }
                }
                $product_import_in_stock=ProductInstock::whereNotIn('sanpham_id', $pro_dis)
                ->where('sanphamtonkho_so_luong_ton', '>', 0)->get();//lay sp k km con ton kho
                foreach ($product_import_in_stock as $in=>$in_stock) {
                    $pro_id[]=$in_stock->sanpham_id;
                }
            } else {
                $product_import_in_stock=ProductInstock::where('sanphamtonkho_so_luong_ton', '>', 0)->get();//lay sp k km con ton kho
                foreach ($product_import_in_stock as $in=>$in_stock) {
                    $pro_id[]=$in_stock->sanpham_id;
                }
            }
                if (empty($pro_id)) {
                    return Redirect::to('/product-discount')->with('error', 'Sản phẩm không tồn tại!');
                } else {
                    $product=Product::whereIn('id', $pro_id)->paginate(5);
                    return view('admin.pages.product_discount.product_discount_edit')
                ->with('discount', $discount)
                ->with('product_pro', $product)
                ->with('product_dis', $product_discount);
                }
            }
        }
    }


    public function ProductDiscountEditSave(Request $request,$product_discount_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $discount=Discount::find($product_discount_id);
            if (!$discount) {
                return Redirect::to('/product-discount')->with('error', 'Không tồn tại!');
            } else {
                $data=$request->all();
                $this->validate($request,[
                    'product_discount_title' => 'bail|required|max:255|min:6',
                    'product_discount_content' => 'bail|required|max:255|min:6',
                    'product_discount_number' => 'bail|required',
                    'product_discount_time' => 'bail|required|min:1',
                    'product_discount_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
                ],
                [
                    'required' => 'Không được để trống',
                    'min' => 'Quá ngắn',
                    'max' => 'Quá dài',
                    'mimes' => 'Sai định dạng ảnh',
                ]);
                $today = date("Y-m-d");
                if (empty($data['product_discount_product_id'])) {
                    return Redirect::to('/product-discount')->with('error','Cập nhật không thành công, không có sản phẩm!');
                } else {
                    if($data['product_discount_day']<$today){
                        return Redirect::to('/product-discount-edit/'.$product_discount_id)->with('error', 'Cập nhật không thành công, ngày khuyến mãi phải lớn hơn ngày hiện tại!');
                    }else{
                        $discount=Discount::find($product_discount_id);
                        $discount->khuyenmai_tieu_de=$data['product_discount_title'];
                        $discount->khuyenmai_noi_dung=$data['product_discount_content'];
                        $discount->khuyenmai_gia_tri=$data['product_discount_number'];
                        $discount->khuyenmai_loai=$data['product_discount_type'];
                        $discount->khuyenmai_so_ngay_khuyen_mai=$data['product_discount_time'];
                        $discount->khuyenmai_ngay_khuyen_mai=$data['product_discount_day'];
                        $discount->khuyenmai_trang_thai=$data['product_discount_status'];
                        $old_name_img=$discount->khuyenmai_anh;
                        $get_image = $request->file('product_discount_img');
                        $path = 'public/uploads/admin/productdiscount/';
                        if ($get_image) {
                            if ($path.$get_image && $path.$get_image!=$path.$old_name_img) {
                                return Redirect::to('/product-discount-edit/'.$product_discount_id)->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                            } else {
                                if ($old_name_img!=null) {
                                    unlink($path.$old_name_img);
                                }
                                $get_name_image = $get_image->getClientOriginalName();
                                $name_image = current(explode('.', $get_name_image));
                                $new_image =  $name_image.rand(0, 99).'.'.$get_image->getClientOriginalExtension();
                                $get_image->move($path, $new_image);
                                $discount->khuyenmai_anh = $new_image;
                                $discount->save();
                            }
                        } else {
                            if ($old_name_img!=null) {
                                $discount->khuyenmai_anh =  $old_name_img;
                                $discount->save();
                            } else {
                                return Redirect::to('/product-discount-edit/'.$product_discount_id)->with('error', 'Cập nhật không thành công, vui lòng chọn ảnh!');
                            }
                        }
                        ProductDiscount::where('khuyenmai_id', $product_discount_id)->delete();
                        foreach ($data['product_discount_product_id'] as $key =>$value) {
                            $product_discount=new ProductDiscount();
                            $product_discount->sanpham_id=$value;
                            $product_discount->khuyenmai_id=$discount->id;
                            $product_discount->save();
                        }
                        return Redirect::to('/product-discount')->with('message', 'Cập nhật thành công!');
                    }
                }
            }
        }
    }

    public function ShowProductDiscountDetail($discount_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $discount=Discount::find($discount_id);
            if (!$discount) {
                return Redirect::to('/product-discount')->with('error', 'Không tồn tại!');
            } else {
                $product_discount=ProductDiscount::where('khuyenmai_id', $discount_id)->get();
                if ($product_discount->count()>0) {
                    foreach ($product_discount as $key =>$pro_dis) {
                        $pro_id[]=$pro_dis->sanpham_id;
                    }
                } else {
                    $pro_id=null;
                }
                $product_import_in_stock=ProductInstock::whereIn('sanpham_id', $pro_dis)->get();
                $all_product=Product::whereIn('id', $pro_id)->get();//lay sp km
                return view('admin.pages.product_discount.product_discount_show_product')
            ->with('product_discount', $discount)
            ->with('product_import_in_stock', $product_import_in_stock)
            ->with('all_product', $all_product);
            }
        }
    }
    public function DeleteProductDiscount($discount_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $discount=Discount::find($discount_id);
            if (!$discount) {
                return Redirect::to('/product-discount')->with('error', 'Không tồn tại!');
            } else {
                $product_discount=ProductDiscount::where('khuyenmai_id', $discount_id)->get();
                foreach ($product_discount as $key =>$del_dis) {
                    $del_pro_dis=ProductDiscount::find($del_dis->id);
                    $del_pro_dis->delete();
                }
                $discount->delete();
                return Redirect::to('/product-discount')->with('message', 'Xóa thành công!');
            }
        }
    }
}
