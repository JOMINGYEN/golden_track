<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\models\HeaderShow;
use Illuminate\Support\Facades\Redirect;
session_start();

class HeaderShowController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $all_headershow=HeaderShow::orderby('id', 'DESC')->paginate(5);
            return view('admin.pages.headershow.headershow')->with('all_headershow', $all_headershow);
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
    public function HeaderShowAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.headershow.headershow_add');
        }
    }

    public function HeaderShowSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'header_content' => 'bail|required|max:255|min:6',
                'header_link' => 'bail|required|max:255|min:6',
                'header_no' => 'bail|required'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài'
            ]);
            $get_number=HeaderShow::where('headerquangcao_thu_tu', $data['header_no'])->first();
            if ($get_number) {
                return Redirect::to('/headershow-add')->with('error', 'Thêm không thành công, số thứ tự đã tồn tại');
            } else {
                $headershow=new HeaderShow();
                $headershow->headerquangcao_noi_dung = $data['header_content'];
                $headershow->headerquangcao_lien_ket = $data['header_link'];
                $headershow->headerquangcao_thu_tu = $data['header_no'];
                $headershow->headerquangcao_trang_thai = $data['header_status'];
                $headershow->save();
                return Redirect::to('/headershow')->with('message', 'Thêm thành công!');
            }
        }
    }

    public function UnactiveHeaderShow($headershow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_headershow=HeaderShow::find($headershow_id);
            if (!$unactive_headershow) {
                return Redirect::to('/headershow')->with('error', 'Không tồn tại!');
            } else {
                $unactive_headershow->headerquangcao_trang_thai=0;
                $unactive_headershow->save();
                return Redirect::to('/headershow')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveHeaderShow($headershow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_headershow=HeaderShow::find($headershow_id);
            if (!$active_headershow) {
                return Redirect::to('/headershow')->with('error', 'Không tồn tại!');
            } else {
                $active_headershow->headerquangcao_trang_thai=1;
                $active_headershow->save();
                return Redirect::to('/headershow')->with('message', 'Hiển thị thành công!');
            }
        }
    }

    public function HeaderShowEdit($headershow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_headershow=HeaderShow::find($headershow_id);
            if (!$edit_headershow) {
                return Redirect::to('/headershow')->with('error', 'Không tồn tại!');
            } else {
                return view('admin.pages.headershow.headershow_edit')
            ->with('headershow', $edit_headershow);
            }
        }
    }

    public function HeaderShowSaveEdit(Request $request,$headershow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'header_content' => 'bail|required|max:255|min:6',
                'header_link' => 'bail|required|max:255|min:6',
                'header_no' => 'bail|required'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
            ]);
            $get_number=HeaderShow::where('headerquangcao_thu_tu', $data['header_no'])->whereNotIn('id', [$headershow_id])->first();
            if ($get_number) {
                return Redirect::to('/headershow-edit/'.$headershow_id)->with('error', 'Cập nhật không thành công, số thứ tự đã tồn tại');
            } else {
                $headershow=HeaderShow::find($headershow_id);
                $headershow->headerquangcao_noi_dung = $data['header_content'];
                $headershow->headerquangcao_lien_ket = $data['header_link'];
                $headershow->headerquangcao_thu_tu = $data['header_no'];
                $headershow->headerquangcao_trang_thai = $data['header_status'];
                $headershow->save();
                return Redirect::to('/headershow')->with('message', 'Cập nhật thành công!');
            }
        }
    }
    public function HeaderShowDelete($headershow_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $delete_headershow=HeaderShow::find($headershow_id);
            if (!$delete_headershow) {
                return Redirect::to('/headershow')->with('error', 'Không tồn tại!');
            } else {
                $delete_headershow->delete();
                return Redirect::to('/headershow')->with('message', 'Xóa thành công!');
            }
        }
    }
}
