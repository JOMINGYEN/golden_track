<?php

namespace App\Http\Controllers;

use App\Models\BlogNews;
use Illuminate\Http\Request;
use DB;
use File;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();


class ProductNewsController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        $all_product_news=BlogNews::orderby('id','DESC')->paginate(5);
        return view('admin.pages.product_news.product_news')->with('all_product_news',$all_product_news);
    }

    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function ProductNewsAdd(){
        $this->AuthLogin();
    	return view('admin.pages.product_news.product_news_add');
    }

    public function ProductNewsAddSave(Request $request){
        $this->AuthLogin();
        $data=$request->all();
        $product_news=new BlogNews();
        $product_news->baiviet_tieu_de = $data['product_news_title'];
        $product_news->baiviet_noi_dung = $data['product_news_content'];
        $product_news->baiviet_trang_thai = $data['product_news_status'];
        $get_image = $request->file('product_news_img');
        $path = 'public/uploads/admin/productnews';
        //them hinh anh
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image =  $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
            $get_image->move($path,$new_image);
            $product_news->baiviet_anh = $new_image;
            $product_news->save();
            Session::put('message','Add Success');
    	    return Redirect::to('/product-news');
        }
        $product_news->baiviet_anh = '';
        $product_news->save();
        Session::put('message','Add Success');
    	return Redirect::to('/product-news');
    }

    public function UnactiveProductNews($product_news_id){
        $this->AuthLogin();
        $unactive_product_news=BlogNews::find($product_news_id);
        $unactive_product_news->baiviet_trang_thai=0;
        $unactive_product_news->save();
        Session::put('message','Hide Success');
        return Redirect::to('/product-news');
    }
    public function ActiveProductNews($product_news_id){
        $this->AuthLogin();
        $active_product_news=BlogNews::find($product_news_id);
        $active_product_news->baiviet_trang_thai=1;
        $active_product_news->save();
        Session::put('message','Show Success');
        return Redirect::to('/product-news');
    }

    public function ProductNewsEdit($product_news_id){
        $this->AuthLogin();
        $edit_product_news=BlogNews::find($product_news_id);
        return view('admin.pages.product_news.product_news_edit')
        ->with('product_news',$edit_product_news);
    }

    public function ProductNewsEditSave(Request $request,$about_store_id){
        $this->AuthLogin();
        $data=$request->all();
        $product_news=BlogNews::find($about_store_id);
        $product_news->baiviet_tieu_de = $data['product_news_title'];
        $product_news->baiviet_noi_dung = $data['product_news_content'];
        $product_news->baiviet_trang_thai = $data['product_news_status'];
        $get_image = $request->file('product_news_img');
        $old_name=$product_news->baiviet_anh;
        $get_image = $request->file('product_news_img');
        $path = 'public/uploads/admin/productnews/';
        if($get_image){
            unlink($path.$old_name);
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image =  $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
            $get_image->move($path,$new_image);
            $product_news->baiviet_anh= $new_image;
            $product_news->save();
            Session::put('message','Update Success');
            return Redirect::to('/product-news');
        }
        $product_news->baiviet_anh = $old_name;
        $product_news->save();
        Session::put('message','Update Success');
        return Redirect::to('/product-news');
    }
    public function ProductNewsDelete($about_store_id){
        $this->AuthLogin();
        $product_news=BlogNews::find($about_store_id);
        $product_news->delete();
        Session::put('message','Delete Success');
        return Redirect::to('/product-news');
    }
}
