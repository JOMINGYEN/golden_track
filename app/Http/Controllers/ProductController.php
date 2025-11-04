<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductInStock;
use App\Models\ProductImportDetail;
use App\Models\ProductImage;
use App\Models\ProductDiscount;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\HeaderShow;
use App\Models\OrderDetail;
use App\Models\ProductImport;
use Illuminate\Support\Facades\Redirect;
session_start();

class ProductController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $all_product=Product::orderby('id', 'desc')->paginate(5)->fragment('all_product');
            return view('admin.pages.products.product')
        ->with('all_product', $all_product);
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

    public function ProductAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $all_product_type=ProductType::orderby('id', 'desc')->get();
            $all_brand=Brand::orderby('id', 'desc')->get();
            $all_collection=Collection::orderby('id', 'desc')->get();
            return view('admin.pages.products.product_add')
        ->with('product_type', $all_product_type)
        ->with('product_brand', $all_brand)
        ->with('product_collection', $all_collection);
        }
    }


    public function ProductSave(Request $request)
    {
        $product = new Product();

        $product->sanpham_ma_san_pham = $request->input('product_code');
        $product->sanpham_ten = $request->input('product_name');
        $product->sanpham_gia_ban = $request->input('product_price');
        $product->sanpham_noi_san_xuat = $request->input('product_production');
        $product->sanpham_mau_sac = $request->input('product_color');
        $product->sanpham_tinh_nang = $request->input('product_feature');
        $product->sanpham_mo_ta = $request->input('product_description');
        $product->sanpham_trang_thai = $request->input('product_status');
        $product->thuonghieu_id = $request->input('brand');
        $product->loaisanpham_id = $request->input('product_type');
        $product->dongsanpham_id = $request->input('collection');

        $get_image = $request->file('product_img');
        $path = 'public/uploads/admin/product';

        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image = $name_image . '_' . time() . '.' . $get_image->getClientOriginalExtension();

            $get_image->move($path, $new_image);
            $product->sanpham_anh = $new_image;

            $product->save();

            // ✅ Tạo tồn kho mặc định sau khi thêm sản phẩm
            $stock = new ProductInStock();
            $stock->sanpham_id = $product->id;
            $stock->sanphamtonkho_so_luong_ton = 10; // số lượng tồn kho mặc định
            $stock->sanphamtonkho_so_luong_da_ban = 0;
            $stock->save();

            return Redirect::to('/product')->with('message', 'Thêm sản phẩm thành công!');
        } else {
            return Redirect::to('/product-add')->with('error', 'Vui lòng chọn ảnh sản phẩm!');
        }
    }




    public function UnactiveProduct($product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_product=Product::find($product_id);
            if (!$unactive_product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $unactive_product->sanpham_trang_thai=0;
                $unactive_product->save();
                return Redirect::to('/product')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveProduct($product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_product=Product::find($product_id);
            if (!$active_product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $active_product->sanpham_trang_thai=1;
                $active_product->save();
                return Redirect::to('/product')->with('message', 'Hiển thị thành công!');
            }
        }
    }
    public function ProductDelete($product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product=Product::find($product_id);
            if (!$product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $get_import_detail=ProductImportDetail::where('sanpham_id', $product_id)->first();
                $get_order_detail=OrderDetail::where('sanpham_id', $product_id)->first();
                $get_image=ProductImage::where('sanpham_id', $product_id)->first();
                $get_in_stock=ProductInStock::where('sanpham_id', $product_id)->first();
                if ($get_image || $get_import_detail ||$get_order_detail ||$get_in_stock) {
                    return Redirect::to('/product')->with('error', 'Không thể xóa!');
                } else {
                    $delete_product=Product::find($product_id);
                    $delete_product->delete();
                    return Redirect::to('/product')->with('message', 'Xóa thành công!');
                }
            }
        }
    }
    public function ProductDeleteAll()
    {
        ProductImage::truncate();
        ProductImportDetail::truncate();
        OrderDetail::truncate();
        ProductInStock::truncate();
        Product::truncate();

        return Redirect::to('/product')->with('message', 'Đã xoá toàn bộ sản phẩm!');
    }

    public function ProductEdit($product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_product=Product::find($product_id);
            if (!$edit_product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $all_product_type=ProductType::orderby('id', 'desc')->get();
                $all_brand=Brand::orderby('id', 'desc')->get();
                $all_collection=Collection::orderby('id', 'desc')->get();
                return view('admin.pages.products.product_edit')
            ->with('product', $edit_product)
            ->with('product_type', $all_product_type)
            ->with('product_brand', $all_brand)
            ->with('product_collection', $all_collection);
            }
        }
    }

    public function ProductSaveEdit(Request $request,$product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'product_code' => 'bail|required|max:255|min:6',
                'product_name' => 'bail|required|max:255|min:6',
                'product_description' => 'bail|required|min:6',
                'product_color' => 'bail|required|max:255|min:6',
                'product_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000',
                'product_feature' => 'bail|required|max:255|min:6',
                'product_production' => 'bail|required|max:255|min:6',
                'product_accessories' => 'bail|required|max:255|min:6',
                'product_material' => 'bail|required|max:255|min:6',
                'product_guarantee' => 'bail|required|max:255|min:6'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài',
                'mimes' => 'Sai định dạng ảnh',
            ]);
            $get_product=Product::where('sanpham_ma_san_pham', $data['product_code'])->whereNotIn('id', [$product_id])->first();
            if ($get_product) {
                return Redirect::to('/product-edit/'.$product_id)->with('error', 'Cập nhật không thành công, sản phẩm đã tồn tại!');
            } else {
                $product= Product::find($product_id);
                $product->sanpham_ma_san_pham = $data['product_code'];
                $product->sanpham_ten = $data['product_name'];
                $product->sanpham_gia_ban = $data['product_price'];
                $product->sanpham_mo_ta = $data['product_description'];
                $product->sanpham_mau_sac = $data['product_color'];
                $product->sanpham_tinh_nang = $data['product_feature'];
                $product->sanpham_noi_san_xuat = $data['product_production'];
                $product->sanpham_phu_kien = $data['product_accessories'];
                $product->sanpham_chat_lieu = $data['product_material'];
                $product->sanpham_bao_hanh = $data['product_guarantee'];
                $product->sanpham_trang_thai = $data['product_status'];
                $product->loaisanpham_id = $data['product_type'];
                $product->thuonghieu_id = $data['brand'];
                $product->dongsanpham_id = $data['collection'];
                $old_name=$product->sanpham_anh;
                $get_image = $request->file('product_img');
                $path = 'public/uploads/admin/product/';
                if ($get_image) {
                    if($path.$get_image && $path.$get_image!=$path.$old_name){
                        return Redirect::to('/product-edit/'.$product_id)->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                    }else{
                        if ($old_name!=null) {
                            unlink($path.$old_name);
                        }
                        $get_name_image = $get_image->getClientOriginalName();
                        $name_image = current(explode('.', $get_name_image));
                        $new_image =  $name_image.'.'.$get_image->getClientOriginalExtension();
                        $get_image->move($path, $new_image);
                        $product->sanpham_anh = $new_image;
                        $product->save();
                        return Redirect::to('/product')->with('message', 'Cập nhật thành công!');
                    }
                } else {
                    if ($old_name!=null) {
                        $product->sanpham_anh = $old_name;
                        $product->save();
                        return Redirect::to('/product')->with('message', 'Cập nhật thành công!');
                    } else {
                        return Redirect::to('/product-edit/'.$product_id)->with('error', 'Cập nhật không thành công, vui lòng chọn ảnh!');
                    }
                }
            }
        }
    }

    public function ShowProductImages($product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product=Product::find($product_id);
            if (!$product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $all_product_images=ProductImage::where('sanpham_id', $product_id)->orderby('id', 'desc')->paginate(5)->fragment('all_product_images');
                return view('admin.pages.products.product_image')
            ->with('product', $product)
            ->with('all_product_images', $all_product_images);
            }
        }
    }

    public function ProductImageAdd(Request $request,$product_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product=Product::find($product_id);
            if (!$product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $get_image = $request->file('product_image');
                // $request->validate([
                //     'product_image' => 'required',
                //     'product_image.*' => 'mimes:jpeg,jpg,png|max:2048'
                // ]);
                if ($get_image) {
                    foreach ($get_image as $image) {
                        $get_name_image = $image->getClientOriginalName();
                        $name_image = current(explode('.', $get_name_image));
                        $new_image =  $name_image.rand(0, 99).'.'.$image->getClientOriginalExtension();
                        $image->move('public/uploads/admin/productimages', $new_image);
                        $product_image = new ProductImage();
                        $product_image->anhsanpham_ten = $new_image;
                        $product_image->sanpham_id = $product_id;
                        $product_image->save();
                    }
                    return Redirect::to('/product-images/'.$product_id)->with('message', 'Thêm ảnh thành công!');
                } else {
                    return Redirect::to('/product-images/'.$product_id)->with('error', 'Thêm hình ảnh không thành công, vui lòng chọn ảnh!');
                }
            }
        }
    }
    public function ProductImageDelete($product_image_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $product=ProductImage::find($product_image_id);
            if (!$product) {
                return Redirect::to('/product')->with('error', 'Sản phẩm không tồn tại!');
            } else {
                $product_id=$product->sanpham_id;
                ProductImage::find($product_image_id)->delete();
                return Redirect::to('/product-images/'.$product_id)->with('message', 'Xóa hình ảnh thành công!');
            }
        }
    }
}
