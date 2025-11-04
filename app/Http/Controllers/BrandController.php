<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Brand;
use App\Models\Product;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class BrandController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $all_brand = Brand::orderBy('id', 'DESC')->paginate(5);
            // $all_brand = Brand::orderBy('id','DESC')->get();
        return view('admin.pages.brand.brand')->with('all_brand', $all_brand);
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
    public function BrandAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.brand.brand_add');
        }
    }

    public function BrandSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'brand_name' => 'bail|required|max:255|min:6',
                'brand_description' => 'bail|required|max:255|min:6',
                'brand_img' => 'bail|mimes:jpeg,jpg,png,gif|required|max:10000'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
                'mimes' => 'Sai định dạng ảnh'
            ]);
            $get_brand=Brand::where('thuonghieu_ten', $data['brand_name'])->first();
            if ($get_brand) {
                return Redirect::to('/brand-add')->with('error', 'Thương hiệu đã tồn tại!');
            } else {
                $brand=new Brand();
                $brand->thuonghieu_ten = $data['brand_name'];
                $brand->thuonghieu_mo_ta = $data['brand_description'];
                $brand->thuonghieu_trang_thai = $data['brand_status'];
                $get_image = $request->file('brand_img');
                $path = 'public/uploads/admin/brand';
                //them hinh anh
                if ($get_image) {
                    if($path.$get_image){
                        return Redirect::to('/collection-add')->with('error', 'Thêm không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                    }else{
                        $get_name_image = $get_image->getClientOriginalName();
                        $name_image = current(explode('.', $get_name_image));
                        $new_image =  $name_image.'.'.$get_image->getClientOriginalExtension();
                        $get_image->move($path, $new_image);
                        $brand->thuonghieu_anh = $new_image;
                        $brand->save();
                        return Redirect::to('/brand')->with('message', 'Thêm thành công!');
                    }
                } else {
                    return Redirect::to('/brand-add')->with('error', 'Thêm không thành công, vui lòng chọn ảnh!');
                }
            }
        }
    }

    public function UnactiveBrand($brand_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_brand=Brand::find($brand_id);
            if (!$unactive_brand) {
                return Redirect::to('/brand')->with('error', 'Thương hiệu không tồn tại!');
            } else {
                $unactive_brand->thuonghieu_trang_thai=0;
                $unactive_brand->save();
                return Redirect::to('/brand')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveBrand($brand_id){
        $this->AuthLogin(); if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_brand=Brand::find($brand_id);
            if (!$active_brand) {
                return Redirect::to('/brand')->with('error', 'Thương hiệu không tồn tại!');
            } else {
                $active_brand->thuonghieu_trang_thai=1;
                $active_brand->save();
                return Redirect::to('/brand')->with('message', 'Hiển thị thành công!');
            }
        }
    }

    public function BrandEdit($brand_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_brand=Brand::find($brand_id);
            if (!$edit_brand) {
                return Redirect::to('/brand')->with('error', 'Thương hiệu không tồn tại!');
            } else {
                return view('admin.pages.brand.brand_edit')->with('brand', $edit_brand);
            }
        }
    }

    public function BrandSaveEdit(Request $request,$brand_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_brand=Brand::find($brand_id);
            if (!$edit_brand) {
                return Redirect::to('/brand')->with('error', 'Thương hiệu không tồn tại!');
            } else {
                $data=$request->all();
                $this->validate($request,[
                    'brand_name' => 'bail|required|max:255|min:6',
                    'brand_description' => 'bail|required|max:255|min:6',
                    'brand_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
                ],
                [
                    'required' => 'Không được để trống',
                    'min' => 'Quá ngắn',
                    'max' => 'Quá dài',
                    'mimes' => 'Sai định dạng ảnh'
                ]);
                $get_brand=Brand::where('thuonghieu_ten', $data['brand_name'])->whereNotIn('id', [$brand_id])->first();
                if ($get_brand) {
                    return Redirect::to('/brand-edit/'.$brand_id)->with('error', 'Thương hiệu đã tồn tại!');
                } else {
                    $brand= Brand::find($brand_id);
                    $brand->thuonghieu_ten = $data['brand_name'];
                    $brand->thuonghieu_mo_ta = $data['brand_description'];
                    $brand->thuonghieu_trang_thai = $data['brand_status'];
                    $old_name_img=$brand->thuonghieu_anh;
                    $get_image = $request->file('brand_img');
                    $path = 'public/uploads/admin/brand/';
                    if ($get_image) {
                        if($path.$get_image && $path.$get_image!=$path.$old_name_img){
                            return Redirect::to('/brand-edit/'.$brand_id)->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                        }else{
                            if ($old_name_img!=null) {
                                unlink($path.$old_name_img);
                            }
                            $get_name_image = $get_image->getClientOriginalName();
                            $name_image = current(explode('.', $get_name_image));
                            $new_image =  $name_image.'.'.$get_image->getClientOriginalExtension();
                            $get_image->move($path, $new_image);
                            $brand->thuonghieu_anh  = $new_image;
                            $brand->save();
                            return Redirect::to('/brand')->with('message', 'Cập nhật thành công!');
                        }
                    } else {
                        if ($old_name_img!=null) {
                            $brand->thuonghieu_anh = $old_name_img;
                            $brand->save();
                            return Redirect::to('/brand')->with('message', 'Cập nhật thành công!');
                        } else {
                            return Redirect::to('/brand-edit/'.$brand_id)->with('error', 'Cập nhật không thành công, vui lòng chọn ảnh!');
                        }
                    }
                }
            }
        }
    }

    public function DeleteBrand($brand_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $brand=Brand::find($brand_id);
            if (!$brand) {
                return Redirect::to('/brand')->with('error', 'Thương hiệu không tồn tại!');
            } else {
                $get_brand=Product::where('thuonghieu_id', $brand_id)->first();
                if ($get_brand) {
                    return Redirect::to('/brand')->with('error', 'Không thể xóa thương hiệu!');
                } else {
                    $delete_brand=Brand::find($brand_id);
                    $delete_brand->delete();
                    return Redirect::to('/brand')->with('message', 'Xóa thành công!');
                }
            }
        }
    }
}
