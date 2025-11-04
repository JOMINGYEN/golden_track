<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\models\Size;
use App\models\ProductImportDetail;
use App\models\OrderDetail;
use App\Models\Product;
use App\Models\ProductImport;
use App\models\ProductInStock;
use Illuminate\Support\Facades\Redirect;
session_start();

class SizeController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $all_size=Size::orderBy('id', 'DESC')->paginate(5);
            return view('admin.pages.size.size')->with('all_size', $all_size);
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
    public function SizeAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.size.size_add');
        }
    }

    public function SizeSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'size' => 'bail|required|max:255|min:2',
                'size_number' => 'bail|required'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
            ]);
            $get_size=Size::orwhere('size', $data['size'])->orwhere('size_thu_tu', $data['size_number'])->first();
            if ($get_size) {
                return Redirect::to('/size-add')->with('error', 'Thêm không thành công, size hoặc số thứ tự đã tồn tại!');
            } else {
                $size= new Size();
                $size->size = $data['size'];
                $size->size_thu_tu = $data['size_number'];
                $size->size_trang_thai = $data['size_status'];
                $size->save();
                return Redirect::to('/size')->with('message', 'Thêm thành công!');
            }
        }
    }

    public function UnactiveSize($size_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_size=Size::find($size_id);
            if (!$unactive_size) {
                return Redirect::to('/size')->with('error', 'Không tồn tại!');
            } else {
                $unactive_size->size_trang_thai=0;
                $unactive_size->save();
                return Redirect::to('/product-type')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveSize($size_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_size=Size::find($size_id);
            if (!$active_size) {
                return Redirect::to('/size')->with('error', 'Không tồn tại!');
            } else {
                $active_size->size_trang_thai=1;
                $active_size->save();
                return Redirect::to('/product-type')->with('message', 'Hiển thị thành công!');
            }
        }
    }

    public function SizeEdit($size_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_size= Size::find($size_id);
            if (!$edit_size) {
                return Redirect::to('/size')->with('error', 'Không tồn tại!');
            } else {
                return view('admin.pages.size.size_edit')->with('size', $edit_size);
            }
        }
    }

    public function SizeSaveEdit(Request $request,$size_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'size' => 'bail|required|max:255|min:2',
                'size_number' => 'bail|required'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
            ]);
            $get_size=Size::where('size', $data['size'])->whereNotIn('id', [$size_id])->first();
            $get_size_number=Size::where('size_thu_tu', $data['size_number'])->whereNotIn('id', [$size_id])->first();
            if ($get_size || $get_size_number) {
                return Redirect::to('/size-edit/'.$size_id)->with('error', 'Cập nhật không thành công, size hoặc số thứ tự đã tồn tại!');
            } else {
                $size= Size::find($size_id);
                $size->size = $data['size'];
                $size->size_thu_tu = $data['size_number'];
                $size->size_trang_thai = $data['size_status'];
                $size->save();
                return Redirect::to('/size')->with('message', 'Cập nhật thành công!');
            }
        }
    }
    public function SizeDelete($size_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $get_import=ProductImportDetail::where('size_id', $size_id)->first();
            $get_in_stock=ProductInStock::where('size_id', $size_id)->first();
            $get_order=OrderDetail::where('size_id', $size_id)->first();
            if ($get_order || $get_in_stock || $get_import) {
                return Redirect::to('/size')->with('error', 'Không thể xóa!');
            } else {
                $delete_size=Size::find($size_id);
                if (!$delete_size) {
                    return Redirect::to('/size')->with('error', 'Không tồn tại!');
                } else {
                    $delete_size->delete();
                    return Redirect::to('/size')->with('message', 'Xóa thành công!');
                }
            }
        }
    }
}
