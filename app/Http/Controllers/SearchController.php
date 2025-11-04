<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\ProductType;
use App\Models\ProductInStock;
use App\Models\ProductDiscount;
use App\Models\Discount;
use App\Models\AboutStore;
use App\Models\HeaderShow;
use App\Models\Comment;
use App\Models\Size;
use App\Models\Admin;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\ProductImport;
use Illuminate\Support\Facades\Redirect;
use Mail;
session_start();
class SearchController extends Controller
{
    public function ShowProductSearchHeaderCustomer(Request $request)
    {
        $search_keyword=$request->search_product_customer;
        if ($search_keyword ==null) {
            return redirect()->back();
        }else{
            $all_product_in_stock=ProductInStock::where('sanphamtonkho_so_luong_ton', '>', 0)->orderby('id', 'DESC')->get();
            $comment_customer=Comment::where('binhluan_id_phan_hoi', '=', 0)->where('binhluan_trang_thai', 1)
            ->where('binhluan_diem_danh_gia', '>=', 4)->get();
            if($comment_customer->count()>0){
                foreach ($comment_customer as $key=>$comment) {
                    $pro_rate_id[]=$comment->sanpham_id;
                }
            }else{
                $pro_rate_id=null;
            }
            if ($pro_rate_id!=null) {
                $all_product_rate=Product::where('sanpham_trang_thai', 1)->whereIn('id', $pro_rate_id)->get();
            } else {
                $all_product_rate=Product::where('sanpham_trang_thai', 1)->get();
            }
            if($all_product_in_stock->count()>0){
                foreach($all_product_in_stock as $key =>$in_stock){
                    $product_id[]=$in_stock->sanpham_id;
                }
            }else{
                $product_id[]=null;
            }
            $viewed=Session::get('product_viewed');
            if($viewed){
                foreach($viewed as $key=>$view){
                    $view_id[]=$view['product_id_viewed'];
                }
            }else{
                $view_id[]=null;
            }
            $discount=Discount::where('khuyenmai_trang_thai', 1)->get();//lay tin km
            if($product_id!=null){
                if ($discount->count()>0) {
                    foreach ($discount as $key => $value) {
                        $product_discount=ProductDiscount::where('khuyenmai_id', $value->id)->get();//lay sp dc km
                        foreach ($product_discount as $k => $v) {
                            $pro_dis[]=$v->sanpham_id;//id sp km
                        }
                    }
                    $all_product=Product::whereIn('id', $product_id)->where('sanpham_trang_thai', '1')->whereNotIn('id', $pro_dis)
                    ->where('sanpham_ten', 'like', '%'.$search_keyword.'%')->orderBy('id', 'DESC')->paginate(9);
                    $all_product_viewed=Product::whereIn('id', $view_id)->where('sanpham_trang_thai', '1')
                    ->whereIn('id', $product_id)
                    ->whereNotIn('id', $pro_dis)->orderBy('id', 'DESC')->get();
                }else{
                    $all_product=Product::whereIn('id', $product_id)->where('sanpham_trang_thai', '1')
                    ->where('sanpham_ten', 'like', '%'.$search_keyword.'%')->orderBy('id', 'DESC')->paginate(9);
                    $all_product_viewed=Product::whereIn('id', $view_id)->where('sanpham_trang_thai', '1')
                    ->whereIn('id', $product_id)->orderBy('id', 'DESC')->get();
                }
            }
            $all_product->appends(['search_product_customer' => $search_keyword]);
            $get_about_us_bottom=AboutStore::orderby('cuahang_thu_tu','ASC')->first();
           
            $all_product_type=ProductType::where('loaisanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_brand=Brand::where('thuonghieu_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_collection=Collection::where('dongsanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_header=HeaderShow::where('headerquangcao_trang_thai', '1')
            ->orderby('headerquangcao_thu_tu', 'ASC')->get();
            foreach ($all_header as $key=>$value) {
                $thu_tu_header=$value->headerquangcao_thu_tu;
                break;
            }
            return view('client.pages.products.product')
            ->with('all_product', $all_product)
            ->with('all_product_viewed', $all_product_viewed)
            ->with('search_keyword', $search_keyword)
            ->with('all_product_rate', $all_product_rate)
            ->with('product_type', $all_product_type)
            ->with('product_brand', $all_brand)
            ->with('get_about_us_bottom',$get_about_us_bottom)
            ->with('product_collection', $all_collection)
            ->with('header_show', $all_header)
            
            ->with('comment_customer', $comment_customer)
            ->with('header_min', $thu_tu_header);
        }
    }

    public function ShowProductSearchFilterCustomer(Request $request){
        $search_filter=$request->all();
        $search_customer_brand=$request->search_customer_brand;
        $search_customer_product_type=$request->search_customer_product_type;
        $search_customer_collection=$request->search_customer_collection;
        $search_customer_price=$request->search_customer_price;
        $search_customer_gender=$request->search_customer_gender;
        $search_customer_size=$request->search_customer_size;
        if (!$search_filter  || $search_filter==null) {
            return Redirect::to('/shop-now');
        }elseif ($search_customer_brand ==null && $search_customer_product_type ==null && $search_customer_collection ==null && $search_customer_price ==null && $search_customer_gender ==null && $search_customer_size==null) {
            return Redirect::to('/shop-now');
        }else {
            $search_filter_customer[] = array(
                'search_customer_brand' => $search_customer_brand,
                'search_customer_product_type' =>  $search_customer_product_type,
                'search_customer_collection' =>  $search_customer_collection,
                'search_customer_price' =>  $search_customer_price,
                'search_customer_gender' =>  $search_customer_gender,
                'search_customer_size' =>  $search_customer_size
            );
            if ($search_customer_size!=null ) {
                $in_stock=ProductInStock::where('sanphamtonkho_so_luong_ton', '>', 0)->where('size_id',$search_customer_size)->orderby('id', 'DESC')->first();
                if(!$in_stock){
                    $size_name = Size::find($search_customer_size);
                    return Redirect::to('/shop-now')->with('error','Không có sản phẩm nào size: '.$size_name->size);
                }else{
                    $all_product_in_stock=ProductInStock::where('sanphamtonkho_so_luong_ton', '>', 0)->where('size_id', $search_customer_size)->orderby('id', 'DESC')->get();
                }
            }else {
                $all_product_in_stock=ProductInStock::where('sanphamtonkho_so_luong_ton', '>', 0)->orderby('id', 'DESC')->get();
            }
            $comment_customer=Comment::where('binhluan_id_phan_hoi', '=', 0)->where('binhluan_trang_thai', 1)
            ->where('binhluan_diem_danh_gia', '>=', 4)->get();
            if($comment_customer->count()>0){
                foreach ($comment_customer as $key=>$comment) {
                    $pro_rate_id[]=$comment->sanpham_id;
                }
            }else{
                $pro_rate_id=null;
            }
            if ($pro_rate_id!=null) {
                $all_product_rate=Product::where('sanpham_trang_thai', 1)->whereIn('id', $pro_rate_id)->get();
            } else {
                $all_product_rate=Product::where('sanpham_trang_thai', 1)->get();
            }
            if($all_product_in_stock->count()>0){
                foreach ($all_product_in_stock as $key =>$in_stock) {
                    $product_id[]=$in_stock->sanpham_id;
                }
            }else{
                $product_id=null;
            }
            $viewed=Session::get('product_viewed');
            if($viewed){
                foreach($viewed as $key=>$view){
                    $view_id[]=$view['product_id_viewed'];
                }
            }else{
                $view_id[]=null;
            }
            $discount=Discount::where('khuyenmai_trang_thai', 1)->get();//lay tin km
            if ($discount->count()>0) {
                foreach ($discount as $key => $value) {
                    $product_discount=ProductDiscount::where('khuyenmai_id', $value->id)->get();//lay sp dc km
                    foreach ($product_discount as $k => $v) {
                        $pro_dis[]=$v->sanpham_id;//id sp km
                    }
                }
                $all_product_viewed=Product::whereIn('id',$view_id)->where('sanpham_trang_thai','1')
                ->whereIn('id',$product_id)
                ->whereNotIn('id',$pro_dis)->orderBy('id','DESC')->get();
                if($search_customer_brand!=null){
                    if($search_customer_product_type!=null){//brand!=null
                        if($search_customer_collection!=null){//brand!=null product type != null
                            if($search_customer_gender!=null){//brand!=null product type != null collection != null
                                if($search_customer_price!=null){//brand!=null product type != null collection != null gender!=null price!=null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//gender=null
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type != null

                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//collection = null brand !=null  product type != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }else{//product type = null brand !=null
                        if($search_customer_collection!=null){//product type = null brand !=null collection != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand !=null collection != null gender =null
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }

                            }
                        }else{//product type = null brand !=null collection = null
                            if($search_customer_gender!=null){//product type = null brand !=null collection = null
                                if($search_customer_price!=null){//gender!=null collection = null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand !=null collection = null gender=null
                                if($search_customer_price!=null){//gender=null collection = null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }
                }else{//brand = null
                    if($search_customer_product_type!=null){//brand=null
                        if($search_customer_collection!=null){//brand=null product type != null
                            if($search_customer_gender!=null){//brand=null product type != null collection != null
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//gender=null collection != null brand =null  product type != null
                                if($search_customer_price!=null){//gender=null collection != null brand =null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection! = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//collection = null brand =null  product type != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection = null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{
                                if($search_customer_price!=null){//gender=null collection = null brand =null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }else{//product type = null brand =null
                        if($search_customer_collection!=null){//product type = null brand =null collection != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }

                            }else{//product type = null brand =null collection != null gender =null
                                if($search_customer_price!=null){//gender=null collection != null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//product type = null brand !=null collection = null
                            if($search_customer_gender!=null){//product type = null brand=null collection = null
                                if($search_customer_price!=null){//gender!=null collection = null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand=null collection = null gender=null
                                if($search_customer_price!=null){//gender=null collection = null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')->whereNotIn('id',$pro_dis)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }
                }
            } else {
                $all_product_viewed=Product::whereIn('id',$view_id)->where('sanpham_trang_thai','1')
                ->whereIn('id',$product_id)->orderBy('id','DESC')->get();
                if($search_customer_brand!=null){
                    if($search_customer_product_type!=null){//brand!=null
                        if($search_customer_collection!=null){//brand!=null product type != null
                            if($search_customer_gender!=null){//brand!=null product type != null collection != null
                                if($search_customer_price!=null){//brand!=null product type != null collection != null gender!=null price!=null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//gender=null
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type != null

                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//collection = null brand !=null  product type != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type != null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }else{//product type = null brand !=null
                        if($search_customer_collection!=null){//product type = null brand !=null collection != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand !=null collection != null gender =null
                                if($search_customer_price!=null){//gender=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }

                            }
                        }else{//product type = null brand !=null collection = null
                            if($search_customer_gender!=null){//product type = null brand !=null collection = null
                                if($search_customer_price!=null){//gender!=null collection = null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand !=null collection = null gender=null
                                if($search_customer_price!=null){//gender=null collection = null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('thuonghieu_id',$search_customer_brand )
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand!=null product type = null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('thuonghieu_id',$search_customer_brand )
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }
                }else{//brand = null
                    if($search_customer_product_type!=null){//brand=null
                        if($search_customer_collection!=null){//brand=null product type != null
                            if($search_customer_gender!=null){//brand=null product type != null collection != null
                                if($search_customer_price!=null){//gender!=null collection != null brand !=null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//gender=null collection != null brand =null  product type != null
                                if($search_customer_price!=null){//gender=null collection != null brand =null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection! = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//collection = null brand =null  product type != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection = null brand !=null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{
                                if($search_customer_price!=null){//gender=null collection = null brand =null  product type != null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('loaisanpham_id', $search_customer_product_type)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type != null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('loaisanpham_id', $search_customer_product_type)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }else{//product type = null brand =null
                        if($search_customer_collection!=null){//product type = null brand =null collection != null
                            if($search_customer_gender!=null){
                                if($search_customer_price!=null){//gender!=null collection != null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection != null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }

                            }else{//product type = null brand =null collection != null gender =null
                                if($search_customer_price!=null){//gender=null collection != null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('dongsanpham_id', $search_customer_collection)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection != null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('dongsanpham_id', $search_customer_collection)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }else{//product type = null brand !=null collection = null
                            if($search_customer_gender!=null){//product type = null brand=null collection = null
                                if($search_customer_price!=null){//gender!=null collection = null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection = null gender!=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->where('sanpham_nguoi_su_dung',$search_customer_gender)
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }else{//product type = null brand=null collection = null gender=null
                                if($search_customer_price!=null){//gender=null collection = null brand =null  product type = null
                                    if($search_customer_price==1){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->where('sanpham_gia_ban','<',500000)
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==2){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereBetween('sanpham_gia_ban',[500000,1000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==3){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereBetween('sanpham_gia_ban',[1000000,2000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==4){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereBetween('sanpham_gia_ban',[2000000,5000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==5){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->whereBetween('sanpham_gia_ban',[5000000,10000000])
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }elseif($search_customer_price==6){
                                        $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                        ->orderBy('id', 'DESC')->paginate(9);
                                    }
                                }else{//brand=null product type = null collection = null gender=null price=null
                                    $all_product=Product::whereIn('id',$product_id)->where('sanpham_trang_thai','1')
                                    ->orderBy('id', 'DESC')->paginate(9);
                                }
                            }
                        }
                    }
                }
            }
            $all_product->appends(['search_customer_brand' => $search_customer_brand,
            'search_customer_product_type' => $search_customer_product_type,
            'search_customer_collection' => $search_customer_collection,
            'search_customer_price' => $search_customer_price,
            'search_customer_gender' => $search_customer_gender,
            'search_customer_size' => $search_customer_size ]);
            $get_about_us_bottom=AboutStore::orderby('cuahang_thu_tu','ASC')->first();

            $all_product_type=ProductType::where('loaisanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_brand=Brand::where('thuonghieu_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_collection=Collection::where('dongsanpham_trang_thai', '1')->orderBy('id', 'DESC')->get();
            $all_header=HeaderShow::where('headerquangcao_trang_thai', '1')
            ->orderby('headerquangcao_thu_tu', 'ASC')->get();
            foreach ($all_header as $key=>$value) {
                $thu_tu_header=$value->headerquangcao_thu_tu;
                break;
            }
            return view('client.pages.products.product')
            ->with('all_product', $all_product)
            ->with('search_filter_customer', $search_filter_customer)
            ->with('all_product_rate', $all_product_rate)
            ->with('product_type', $all_product_type)
            ->with('product_brand', $all_brand)
            ->with('get_about_us_bottom',$get_about_us_bottom)
            ->with('product_collection', $all_collection)
            ->with('header_show', $all_header)
            
            ->with('all_product_viewed', $all_product_viewed)
            ->with('comment_customer', $comment_customer)
            ->with('header_min', $thu_tu_header);
        }
    }

    //=====Admin=====
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function AdminSearchStaff(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_keyword=$request->search_staff_keyword;
            $search_gender=$request->search_select_gender;
            if ($search_keyword==null && $search_gender==-1) {
                return Redirect::to('/staff');
            } elseif ($search_keyword!=null && $search_gender==-1) {
                $all_staff=Admin::orwhere('admin_ten', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_dia_chi', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_so_dien_thoai', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_ho', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_email', 'like', '%'.$search_keyword.'%')->orderby('id', 'DESC')->paginate(5);
            } elseif ($search_keyword!=null && $search_gender!=-1) {
                $all_staff=Admin::where('admin_gioi_tinh', '=', $search_gender)->orderby('id', 'DESC')->paginate(5);
                $all_staff=Admin::orwhere('admin_ten', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_dia_chi', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_so_dien_thoai', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_ho', 'like', '%'.$search_keyword.'%')
                ->orwhere('admin_email', 'like', '%'.$search_keyword.'%')->orderby('id', 'DESC')->paginate(5);
            } elseif ($search_keyword==null && $search_gender!=-1) {
                $all_staff=Admin::where('admin_gioi_tinh', '=', $search_gender)->orderby('id', 'DESC')->paginate(5);
            }
            $all_staff->appends(['search_staff_keyword' => $search_keyword,'search_select_gender' => $search_gender]);
            return view('admin.pages.staff.staff')
            ->with('search_keyword', $search_keyword)
            ->with('search_gender', $search_gender)
            ->with('all_staff', $all_staff);
        }
    }

    public function AdminSearchCustomer(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_keyword=$request->search_customer_keyword;
            $search_gender=$request->search_select_gender;
            if ($search_keyword==null && $search_gender==-1) {
                return Redirect::to('/customer');
            } elseif ($search_keyword!=null && $search_gender==-1) {
                $all_customer=Customer::orwhere('khachhang_ten', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_dia_chi', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_so_dien_thoai', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_ho', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_email', 'like', '%'.$search_keyword.'%')->orderby('id', 'DESC')->paginate(5);
            } elseif ($search_keyword!=null && $search_gender!=-1) {
                $all_customer=Customer::orwhere('khachhang_ten', 'like', '%'.$search_keyword.'%')->orderby('id', 'DESC')->paginate(5);
                $all_customer=Customer::orwhere('khachhang_dia_chi', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_so_dien_thoai', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_ho', 'like', '%'.$search_keyword.'%')
            ->orwhere('khachhang_email', 'like', '%'.$search_keyword.'%')
            ->where('khachhang_gioi_tinh', '=', $search_gender)
            ->orderby('id', 'DESC')->paginate(5);
            } elseif ($search_keyword==null && $search_gender!=-1) {
                $all_customer=Customer::where('khachhang_gioi_tinh', '=', $search_gender)
            ->orderby('id', 'DESC')->paginate(5);
            }
            $all_customer->appends(['search_customer_keyword' => $search_keyword,'search_select_gender' => $search_gender]);
            return view('admin.pages.customer.customer')
        ->with('search_gender', $search_gender)
        ->with('search_keyword', $search_keyword)
        ->with('all_customer', $all_customer);
        }
    }

     public function AdminSearchDelivery(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_keyword=$request->search_delivery_keyword;
            $search_delivery_select_status=$request->search_delivery_select_status;
            if ($search_keyword==null && $search_delivery_select_status==-1) {
                return Redirect::to('/delivery');
            } else {
                if($search_keyword!=null){
                    if($search_delivery_select_status!=-1){
                        $all_delivery=Delivery::orwhere('giaohang_nguoi_nhan', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_so_dien_thoai', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_dia_chi', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_email', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_ma_don_dat_hang', 'like', '%'.$search_keyword.'%')
                        ->where('giaohang_trang_thai',$search_delivery_select_status)
                        ->orderby('id', 'DESC')->paginate(5);
                    }else{
                        $all_delivery=Delivery::orwhere('giaohang_nguoi_nhan', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_so_dien_thoai', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_dia_chi', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_nguoi_nhan_email', 'like', '%'.$search_keyword.'%')
                        ->orwhere('giaohang_ma_don_dat_hang', 'like', '%'.$search_keyword.'%')
                        ->orderby('id', 'DESC')->paginate(5);
                    }
                }else{
                    if($search_delivery_select_status!=-1){
                        $all_delivery=Delivery::where('giaohang_trang_thai',$search_delivery_select_status)
                        ->orderby('id', 'DESC')->paginate(5);
                    }else{
                        return Redirect::to('/delivery');
                    }
                }
                $all_delivery->appends(['search_delivery_keyword' => $search_keyword,'search_delivery_select_status' => $search_delivery_select_status]);
            }
            return view('admin.pages.order.delivery')
            ->with('all_delivery', $all_delivery)
            ->with('search_keyword', $search_keyword)
            ->with('search_delivery_select_status', $search_delivery_select_status);
        }
    }

    public function AdminSearchOrder(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_filter=$request->all();
            $search_admin_from_day_order=$request->search_admin_from_day_order;
            $search_admin_to_day_order=$request->search_admin_to_day_order;
            $search_admin_from_total_order=$request->search_admin_from_total_order;
            $search_admin_to_total_order=$request->search_admin_to_total_order;
            $search_order_keyword=$request->search_order_keyword;
            $search_order_select_status=$request->search_order_select_status;
            if (!$search_filter  || $search_filter==null) {
                return Redirect::to('/order');
            } elseif ($search_admin_from_day_order ==null && $search_admin_to_day_order ==null && $search_admin_from_total_order ==null && $search_admin_to_total_order ==null && $search_order_keyword ==null && $search_order_select_status ==-1) {
                return Redirect::to('/order');
            } else {
                $search_filter_admin[] = array(
                    'search_admin_from_day_order' => $search_admin_from_day_order,
                    'search_admin_to_day_order' =>  $search_admin_to_day_order,
                    'search_admin_from_total_order' =>  $search_admin_from_total_order,
                    'search_admin_to_total_order' =>  $search_admin_to_total_order,
                    'search_order_keyword' =>  $search_order_keyword,
                    'search_order_select_status'=>$search_order_select_status
                );
                if($search_order_select_status!=-1){
                    if ($search_order_keyword!=null) {
                        if ($search_admin_from_day_order!=null) {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//all
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {// - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- to_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {// - to_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { // - to_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {// - to_day - from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            }
                        } else {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- from_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//-from_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { //- from_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//-from_day - to_total - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- from_day - to_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- from_day - to_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- from_day - to_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- from_day - to_day - from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            }
                        }
                    } else {
                        if ($search_admin_from_day_order!=null) {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - to_day
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - to_day - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - to_day - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - to_day - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            }
                        } else {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - from_day - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->where('dondathang_trang_thai', $search_order_select_status)
                                    ->paginate(5);
                                    } else {//- keyword - from_day - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day - to_day
                                        $all_order=Order::where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {//- keyword - from_day - to_day - to_total
                                        $all_order=Order::where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { //- keyword - from_day - to_day - from_total
                                        $all_order=Order::where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->where('dondathang_trang_thai', $search_order_select_status)
                                        ->paginate(5);
                                    } else {
                                        $all_order=Order::where('dondathang_trang_thai', $search_order_select_status)->paginate(5);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    if ($search_order_keyword!=null) {
                        if ($search_admin_from_day_order!=null) {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//all
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->paginate(5);
                                    } else {//- to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {// - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->paginate(5);
                                    } else {//- from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                        ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- to_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->paginate(5);
                                    } else {// - to_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { // - to_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->paginate(5);
                                    } else {// - to_day - from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                        ->whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                        ->paginate(5);
                                    }
                                }
                            }
                        } else {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- from_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//-from_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { //- from_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//-from_day - to_total - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- from_day - to_day
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- from_day - to_day - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- from_day - to_day - from_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- from_day - to_day - from_total - to_total
                                        $all_order=Order::where('dondathang_ma_don_dat_hang', 'like', '%'.$search_order_keyword.'%')
                                    ->paginate(5);
                                    }
                                }
                            }
                        }
                    } else {
                        if ($search_admin_from_day_order!=null) {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - to_day
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - to_day - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - to_day - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - to_day - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '>=', $search_admin_from_day_order)
                                    ->paginate(5);
                                    }
                                }
                            }
                        } else {
                            if ($search_admin_to_day_order!=null) {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - from_day - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                    ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day - from_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {//- keyword - from_day - from_total - to_total
                                        $all_order=Order::whereDate('dondathang_ngay_dat_hang', '<=', $search_admin_to_day_order)
                                    ->paginate(5);
                                    }
                                }
                            } else {
                                if ($search_admin_from_total_order!=null) {
                                    if ($search_admin_to_total_order!=null) {//- keyword - from_day - to_day
                                        $all_order=Order::where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                        ->paginate(5);
                                    } else {//- keyword - from_day - to_day - to_total
                                        $all_order=Order::where('dondathang_tong_tien', '>=', $search_admin_from_total_order)
                                        ->paginate(5);
                                    }
                                } else {
                                    if ($search_admin_to_total_order!=null) { //- keyword - from_day - to_day - from_total
                                        $all_order=Order::where('dondathang_tong_tien', '<=', $search_admin_to_total_order)
                                    ->paginate(5);
                                    } else {
                                        return Redirect::to('/shop-now');
                                    }
                                }
                            }
                        }
                    }
                }
                $all_order->appends(['search_admin_from_day_order' => $search_admin_from_day_order,
                'search_admin_to_day_order' => $search_admin_to_day_order,
                'search_admin_from_total_order' => $search_admin_from_total_order,
                'search_admin_to_total_order' => $search_admin_to_total_order,
                'search_order_keyword' => $search_order_keyword,
                'search_order_select_status'=>$search_order_select_status]);
            }
            return view('admin.pages.order.order')
            ->with('search_filter_admin', $search_filter_admin)
            ->with('all_order', $all_order);
        }
    }
    public function AdminSearchProductImport(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_filter=$request->all();
            $search_admin_from_day_import=$request->search_admin_from_day_import;
            $search_admin_to_day_import=$request->search_admin_to_day_import;
            $search_admin_from_total_import=$request->search_admin_from_total_import;
            $search_admin_to_total_import=$request->search_admin_to_total_import;
            $search_import_keyword=$request->search_import_keyword;
            if (!$search_filter  || $search_filter==null) {
                return Redirect::to('/product-import');
            } elseif ($search_admin_from_day_import ==null && $search_admin_to_day_import ==null && $search_admin_from_total_import ==null && $search_admin_to_total_import ==null && $search_import_keyword ==null) {
                return Redirect::to('/product-import');
            } else {
                $search_filter_admin[] = array(
                'search_admin_from_day_import' => $search_admin_from_day_import,
                'search_admin_to_day_import' =>  $search_admin_to_day_import,
                'search_admin_from_total_import' =>  $search_admin_from_total_import,
                'search_admin_to_total_import' =>  $search_admin_to_total_import,
                'search_import_keyword' =>  $search_import_keyword
            );
                if ($search_import_keyword!=null) {
                    if ($search_admin_from_day_import!=null) {
                        if ($search_admin_to_day_import!=null) {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//all
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) {// - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- from_total - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->paginate(5);
                                }
                            }
                        } else {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- to_day
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {// - to_day - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) { // - to_day - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {// - to_day - from_total - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->paginate(5);
                                }
                            }
                        }
                    } else {
                        if ($search_admin_to_day_import!=null) {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- from_day
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//-from_day - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) { //- from_day - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//-from_day - to_total - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->paginate(5);
                                }
                            }
                        } else {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- from_day - to_day
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- from_day - to_day - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) {//- from_day - to_day - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- from_day - to_day - from_total - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_ma_don_nhap_hang', 'like', '%'.$search_import_keyword.'%')
                                ->paginate(5);
                                }
                            }
                        }
                    }
                } else {
                    if ($search_admin_from_day_import!=null) {
                        if ($search_admin_to_day_import!=null) {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- keyword
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) {//- keyword - from_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - from_total - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->paginate(5);
                                }
                            }
                        } else {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- keyword - to_day
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - to_day - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) {//- keyword - to_day - from_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - to_day - from_total - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '>=', $search_admin_from_day_import)
                                ->paginate(5);
                                }
                            }
                        }
                    } else {
                        if ($search_admin_to_day_import!=null) {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- keyword - from_day
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - from_day - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) {//- keyword - from_day - from_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - from_day - from_total - to_total
                                    $all_product_import=ProductImport::whereDate('donnhaphang_ngay_nhap', '<=', $search_admin_to_day_import)
                                ->paginate(5);
                                }
                            }
                        } else {
                            if ($search_admin_from_total_import!=null) {
                                if ($search_admin_to_total_import!=null) {//- keyword - from_day - to_day
                                    $all_product_import=ProductImport::where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {//- keyword - from_day - to_day - to_total
                                    $all_product_import=ProductImport::where('donnhaphang_tong_tien', '>=', $search_admin_from_total_import)
                                ->paginate(5);
                                }
                            } else {
                                if ($search_admin_to_total_import!=null) { //- keyword - from_day - to_day - from_total
                                    $all_product_import=ProductImport::where('donnhaphang_tong_tien', '<=', $search_admin_to_total_import)
                                ->paginate(5);
                                } else {
                                    return Redirect::to('/shop-now');
                                }
                            }
                        }
                    }
                }
                $all_product_import->appends(['search_admin_from_day_import' => $search_admin_from_day_import,
            'search_admin_to_day_import' => $search_admin_to_day_import,
            'search_admin_from_total_import' => $search_admin_from_total_import,
            'search_admin_to_total_import' => $search_admin_to_total_import,
            'search_import_keyword' => $search_import_keyword ]);
            }
            return view('admin.pages.product_import.product_import')
        ->with('search_filter_admin', $search_filter_admin)
        ->with('all_product_import', $all_product_import);
        }
    }

    public function AdminSearchComment(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $search_filter=$request->all();
            $search_admin_from_day_comment=$request->search_admin_from_day_comment;
            $search_admin_to_day_comment=$request->search_admin_to_day_comment;
            $search_select_status_comment=$request->search_select_status_comment;
            if (!$search_filter  || $search_filter==null) {
                return Redirect::to('/comment');
            } elseif ($search_admin_from_day_comment ==null && $search_admin_to_day_comment ==null && $search_select_status_comment == -1) {
                return Redirect::to('/comment');
            } else {
                $search_filter_admin[] = array(
                'search_admin_from_day_comment' => $search_admin_from_day_comment,
                'search_admin_to_day_comment' =>  $search_admin_to_day_comment,
                'search_select_status_comment' =>  $search_select_status_comment
            );
                if ($search_select_status_comment != -1) {
                    if ($search_admin_from_day_comment!=null) {
                        if ($search_admin_to_day_comment!=null) {//all
                            $comment_customer=Comment::where('binhluan_trang_thai', $search_select_status_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->whereDate('binhluan_ngay_danh_gia', '>=', $search_admin_from_day_comment)
                        ->whereDate('binhluan_ngay_danh_gia', '<=', $search_admin_to_day_comment)
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- to_day
                            $comment_customer=Comment::where('binhluan_trang_thai', $search_select_status_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->whereDate('binhluan_ngay_danh_gia', '>=', $search_admin_from_day_comment)
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    } else {
                        if ($search_admin_to_day_comment!=null) {//- from_day
                            $comment_customer=Comment::where('binhluan_trang_thai', $search_select_status_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->whereDate('binhluan_ngay_danh_gia', '<=', $search_admin_to_day_comment)
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- from_day - to_day
                            $comment_customer=Comment::where('binhluan_trang_thai', $search_select_status_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    }
                } else {
                    if ($search_admin_from_day_comment!=null) {
                        if ($search_admin_to_day_comment!=null) {//- status
                            $comment_customer=Comment::whereDate('binhluan_ngay_danh_gia', '>=', $search_admin_from_day_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->whereDate('binhluan_ngay_danh_gia', '<=', $search_admin_to_day_comment)
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- status - to_day
                            $comment_customer=Comment::whereDate('binhluan_ngay_danh_gia', '>=', $search_admin_from_day_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    } else {
                        if ($search_admin_to_day_comment!=null) {//- status - from_day
                            $comment_customer=Comment::whereDate('binhluan_ngay_danh_gia', '<=', $search_admin_to_day_comment)
                        ->where('binhluan_id_phan_hoi', 0)
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- status - from_day - to_day
                            return Redirect::to('/comment');
                        }
                    }
                }
                $comment_customer->appends(['search_admin_from_day_comment' => $search_admin_from_day_comment,
            'search_admin_to_day_comment' => $search_admin_to_day_comment,
            'search_select_status_comment' => $search_select_status_comment ]);
            }
            return view('admin.pages.comment.show_comment')
        ->with('search_filter', $search_filter)
        ->with('comment_customer', $comment_customer);
        }
    }

    public function AdminSearchProduct(Request $request){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $search_filter_from_price=$request->search_admin_from_price_product;
            $search_filter_to_price=$request->search_admin_to_price_product;
            $search_filter_key=$request->search_product_keyword;
            $search_filter=$request->all();
            if ($search_filter_to_price ==null && $search_filter_from_price ==null && $search_filter_key == null) {
                return Redirect::to('/product');
            } else {
                $search_filter_admin[] = array(
                'search_admin_from_price_product' => $search_filter_from_price,
                'search_admin_to_price_product' =>  $search_filter_to_price,
                'search_product_keyword' =>  $search_filter_key
            );
                $search_admin_from_price_product = $search_filter_admin[0]['search_admin_from_price_product'];
                $search_admin_to_price_product =  $search_filter_admin[0]['search_admin_to_price_product'];
                $search_product_keyword =  $search_filter_admin[0]['search_product_keyword'];

                if ($search_product_keyword != null) {
                    if ($search_admin_from_price_product!=null) {
                        if ($search_admin_to_price_product!=null) {//all
                            $all_product=Product::where('sanpham_gia_ban', '>=', $search_admin_from_price_product)
                       ->where('sanpham_gia_ban', '<=', $search_admin_to_price_product)
                       ->orwhere('sanpham_ma_san_pham', 'like', '%'.$search_product_keyword.'%')
                       ->orwhere('sanpham_ten', 'like', '%'.$search_product_keyword.'%')
                       ->orderby('id', 'DESC')->paginate(5);
                        } else {//- to_price
                            $all_product=Product::where('sanpham_gia_ban', '>=', $search_admin_from_price_product)
                        ->orwhere('sanpham_ma_san_pham', 'like', '%'.$search_product_keyword.'%')
                        ->orwhere('sanpham_ten', 'like', '%'.$search_product_keyword.'%')
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    } else {
                        if ($search_admin_to_price_product!=null) {//- from_price
                            $all_product=Product::where('sanpham_gia_ban', '<=', $search_admin_to_price_product)
                        ->orwhere('sanpham_ma_san_pham', 'like', '%'.$search_product_keyword.'%')
                        ->orwhere('sanpham_ten', 'like', '%'.$search_product_keyword.'%')
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- from_price - to_price
                            $all_product=Product::orwhere('sanpham_ma_san_pham', 'like', '%'.$search_product_keyword.'%')
                        ->orwhere('sanpham_ten', 'like', '%'.$search_product_keyword.'%')
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    }
                } else {
                    if ($search_admin_from_price_product!=null) {
                        if ($search_admin_to_price_product!=null) {//- key
                            $all_product=Product::where('sanpham_gia_ban', '>=', $search_admin_from_price_product)
                       ->where('sanpham_gia_ban', '<=', $search_admin_to_price_product)
                       ->orderby('id', 'DESC')->paginate(5);
                        } else {//- key - to_price
                            $all_product=Product::where('sanpham_gia_ban', '>=', $search_admin_from_price_product)
                        ->orderby('id', 'DESC')->paginate(5);
                        }
                    } else {
                        if ($search_admin_to_price_product!=null) {//- key - from_price
                            $all_product=Product::where('sanpham_gia_ban', '<=', $search_admin_to_price_product)
                        ->orderby('id', 'DESC')->paginate(5);
                        } else {//- key - from_price - to_price
                            return Redirect::to('/product');
                        }
                    }
                }
                $all_product->appends(['search_admin_from_price_product' => $search_filter_admin[0]['search_admin_from_price_product'],
            'search_admin_to_price_product' => $search_filter_admin[0]['search_admin_to_price_product'],
            'search_product_keyword' => $search_filter_admin[0]['search_product_keyword'] ]);
            }
            return view('admin.pages.products.product')
        ->with('search_filter_admin', $search_filter_admin)
        ->with('all_product', $all_product);
        }
    }
}