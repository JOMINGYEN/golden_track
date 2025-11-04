<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\models\SlideShow;
use Illuminate\Support\Facades\Redirect;
session_start();

class SlideShowController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $all_slideshow=SlideShow::orderBy('id', 'DESC')->paginate(5);
            return view('admin.pages.slideshow.slideshow')->with('all_slideshow', $all_slideshow);
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
    public function SlideShowAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.slideshow.slideshow_add');
        }
    }

    public function SlideShowSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        } else {
            $data = $request->all();
            $this->validate($request, [
                'slideshow_title' => 'bail|required|max:255|min:6',
                'slideshow_content' => 'bail|required|max:255|min:6',
                'slideshow_link' => 'bail|required|max:255|min:10',
                'slideshow_no' => 'bail|required',
                'slideshow_img' => 'bail|mimes:jpeg,jpg,png,gif|required|max:10000'
            ], [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
                'mimes' => 'Sai định dạng ảnh',
            ]);

            $get_number = SlideShow::where('slidequangcao_thu_tu', $data['slideshow_no'])->first();
            if ($get_number) {
                return Redirect::to('/slideshow-add')->with('error', 'Thêm không thành công, số thứ tự đã tồn tại!');
            }

            $slideshow = new SlideShow();
            $slideshow->slidequangcao_tieu_de = $data['slideshow_title'];
            $slideshow->slidequangcao_noi_dung = $data['slideshow_content'];
            $slideshow->slidequangcao_lien_ket = $data['slideshow_link'];
            $slideshow->slidequangcao_thu_tu = $data['slideshow_no'];
            $slideshow->slidequangcao_trang_thai = $data['slideshow_status'];

            $get_image = $request->file('slideshow_img');
            $path = 'public/uploads/admin/slideshow/';

            if ($get_image) {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
                $extension = $get_image->getClientOriginalExtension();
                $new_image = $name_image . '_' . time() . '.' . $extension;

                $get_image->move($path, $new_image);
                $slideshow->slidequangcao_anh = $new_image;
                $slideshow->save();

                return Redirect::to('/slideshow')->with('message', 'Thêm thành công!');
            } else {
                return Redirect::to('/slideshow')->with('error', 'Thêm không thành công, vui lòng chọn ảnh!');
            }
        }
    }


    public function UnactiveSlideShow($slideshow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_slideshow=SlideShow::find($slideshow_id);
            if (!$unactive_slideshow) {
                return Redirect::to('slideshow')->with('error', 'Không tồn tại!');
            } else {
                $unactive_slideshow->slidequangcao_trang_thai=0;
                $unactive_slideshow->save();
                return Redirect::to('/slideshow')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveSlideShow($slideshow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_slideshow=SlideShow::find($slideshow_id);
            if (!$active_slideshow) {
                return Redirect::to('slideshow')->with('error', 'Không tồn tại!');
            } else {
                $active_slideshow->slidequangcao_trang_thai=1;
                $active_slideshow->save();
                return Redirect::to('/slideshow')->with('message', 'Hiển thị thành công!');
            }
        }
    }

    public function SlideShowEdit($slideshow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_slideshow=SlideShow::find($slideshow_id);
            if (!$edit_slideshow) {
                return Redirect::to('slideshow')->with('error', 'Không tồn tại!');
            } else {
                return view('admin.pages.slideshow.slideshow_edit')
            ->with('slideshow', $edit_slideshow);
            }
        }
    }

    public function SlideShowSaveEdit(Request $request, $slideshow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        } else {
            $data = $request->all();
            $this->validate($request, [
                'slideshow_title' => 'bail|required|max:255|min:6',
                'slideshow_content' => 'bail|required|max:255|min:6',
                'slideshow_link' => 'bail|required|max:255|min:10',
                'slideshow_no' => 'bail|required',
                'slideshow_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
            ], [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
                'mimes' => 'Sai định dạng ảnh',
            ]);

            $get_number = SlideShow::where('slidequangcao_thu_tu', $data['slideshow_no'])
                ->whereNotIn('id', [$slideshow_id])->first();
            if ($get_number) {
                return Redirect::to('/slideshow-edit/' . $slideshow_id)->with('error', 'Update Fail, Number already exists');
            }

            $slideshow = SlideShow::find($slideshow_id);
            $slideshow->slidequangcao_tieu_de = $data['slideshow_title'];
            $slideshow->slidequangcao_noi_dung = $data['slideshow_content'];
            $slideshow->slidequangcao_lien_ket = $data['slideshow_link'];
            $slideshow->slidequangcao_thu_tu = $data['slideshow_no'];
            $slideshow->slidequangcao_trang_thai = $data['slideshow_status'];

            $get_image = $request->file('slideshow_img');
            $path = 'public/uploads/admin/slideshow/';
            $old_name = $slideshow->slidequangcao_anh;

            if ($get_image) {
                // Xóa ảnh cũ
                if ($old_name && file_exists($path . $old_name)) {
                    unlink($path . $old_name);
                }

                $get_name_image = $get_image->getClientOriginalName();
                $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
                $extension = $get_image->getClientOriginalExtension();
                $new_image = $name_image . '_' . time() . '.' . $extension;

                $get_image->move($path, $new_image);
                $slideshow->slidequangcao_anh = $new_image;
                $slideshow->save();

                return Redirect::to('/slideshow')->with('message', 'Cập nhật thành công!');
            } else {
                $slideshow->slidequangcao_anh = $old_name;
                $slideshow->save();

                return Redirect::to('/slideshow')->with('message', 'Cập nhật thành công!');
            }
        }
    }

    public function SlideShowDelete($slideshow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $delete_slideshow=SlideShow::find($slideshow_id);
            if (!$delete_slideshow) {
                return Redirect::to('slideshow')->with('error', 'Không tồn tại!');
            } else {
                $delete_slideshow->delete();
                return Redirect::to('/slideshow')->with('message', 'Xóa thành công!');
            }
        }
    }
}
