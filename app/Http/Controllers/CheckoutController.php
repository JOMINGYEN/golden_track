<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\ProductInStock;
use App\Models\AboutStore;
use App\Models\City;
use App\Models\Province;
use App\Models\TransportFee;
use App\Models\Wards;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Customer;
use App\Models\UserAccount;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\ProductType;
use App\Models\HeaderShow;
use App\Models\OrderDetail;
use Session;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Redirect;
session_start();

class CheckoutController extends Controller
{
    public function Index(){
        $cart=Session::get('cart');
        if($cart==false){
            return Redirect::to('/cart')->with('error','There Are No Products In The Cart');
        }
        $get_about_us_bottom=AboutStore::orderby('cuahang_thu_tu','ASC')->first();
        $all_product_type=ProductType::where('loaisanpham_trang_thai','1')->orderBy('id','DESC')->get();
        $all_brand=Brand::where('thuonghieu_trang_thai','1')->orderBy('id','DESC')->get();
        $all_collection=Collection::where('dongsanpham_trang_thai','1')->orderBy('id','DESC')->get();
        $all_header=HeaderShow::where('headerquangcao_trang_thai','1')
        ->orderby('headerquangcao_thu_tu','ASC')->get();
        if($all_header->count()>0){
            foreach($all_header as $key=>$value){
                $thu_tu_header=$value->headerquangcao_thu_tu;
                break;
            }
        }else{
            $all_header=null;
            $thu_tu_header=null;
        }
        $city=City::orderby('id','ASC')->get();
        return view('client.pages.checkout.checkout')
        ->with('city',$city)
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }
    public function SelectTransportFeeHome(Request $request){
        $this->DeleteTransportFee();
        $data =$request->all();
        if($data['action']){
            $output = '';
            if($data['action']=="city"){
                $select_province=Province::where('tinhthanhpho_id',$data['ma_id'])->orderby('id','ASC')->get();
                $output.='<option>---Chọn Quận Huyện---</option>';
                foreach($select_province as $key =>$province){
                    $output.='<option value="'.$province->id.'">'.$province->quanhuyen_name.'</option>';
                }
            }else{
    			$select_wards = Wards::where('quanhuyen_id',$data['ma_id'])->orderby('id','ASC')->get();
    			$output.='<option>---Chọn Xã Phường Thị trấn---</option>';
    			foreach($select_wards as $k => $ward){
    				$output.='<option value="'.$ward->id.'">'.$ward->xaphuongthitran_name.'</option>';
    			}
    		}
            echo $output;
        }
    }

    public function SelectAddress(Request $request){
        $this->DeleteTransportFee();
        $data =$request->all();
        if($data['action']){
            $output = '';
            if($data['action']=="order_city"){
                $select_province=Province::where('tinhthanhpho_id',$data['ma_id'])->orderby('id','ASC')->get();
                $output.='<option value="-1">---Chọn Quận Huyện---</option>';
                foreach($select_province as $key =>$province){
                    $output.='<option value="'.$province->id.'">'.$province->quanhuyen_name.'</option>';
                }
            }else{
    			$select_wards = Wards::where('quanhuyen_id',$data['ma_id'])->orderby('id','ASC')->get();
    			$output.='<option value="-1">---Chọn Xã Phường Thị trấn---</option>';
    			foreach($select_wards as $k => $ward){
    				$output.='<option value="'.$ward->id.'">'.$ward->xaphuongthitran_name.'</option>';
    			}
    		}
            echo $output;
        }
    }

    public function DeleteTransportFee(){
        $feeship =Session::get('feeship');
        if($feeship){
            Session::forget('feeship');
        }
    }
    public function DeleteFeeship(){
        $coupon =Session::get('coupon');
        if($coupon){
            $this->DeleteTransportFee();
            return Redirect::to('/checkout')->with('message','Xóa phí vận chuyển thành công!');
        }else{
            return Redirect::to('/checkout')->with('error','Không tồn tại phí vận chuyển, phí vận chuyển mặc định!');
        }
    }

    public function DeleteCoupon(){
        $coupon =Session::get('coupon');
        if($coupon){
            Session::forget('coupon');
            return Redirect::to('/checkout')->with('message','Xóa mã khuyến mãi thành công!');
        }else{
            return Redirect::to('/checkout')->with('error','Không tồn tại mã giảm giá!');
        }
    }

    public function CheckTransportFee(Request $request){
        $data=$request->all();
        $feeship=TransportFee::where('tinhthanhpho_id',$data['city'])->where('quanhuyen_id',$data['province'])
        ->where('xaphuong_id',$data['wards'])->first();
        if($feeship){
            if(Session::get('feeship')){
                $is_va=0;
                foreach ($feeship as $key => $val) {
                	if ($val['id'] == $data['fee_id']) {
                		$is_va++;
                	}
                }
                if($is_va==0){
                    $fee[]=array(
                        'fee_id'=>$feeship['id'],
                        'fee'=>$feeship['phivanchuyen_phi_van_chuyen'],
                        'fee_day'=>$feeship['phivanchuyen_ngay_giao_hang_du_kien'],
                    );
                    Session::put('feeship', $fee);
                }
            }else{
                $fee[]=array(
                    'fee_id'=>$feeship['id'],
                    'fee'=>$feeship['phivanchuyen_phi_van_chuyen'],
                    'fee_day'=>$feeship['phivanchuyen_ngay_giao_hang_du_kien'],
                );
                Session::put('feeship', $fee);
            }
        }else{
            $fee[]=array(
                'fee_id'=>null,
                'fee'=>35000,
                'fee_day'=>3,
            );
            Session::put('feeship', $fee);
        }
        Session::save();
        return Redirect::to('/checkout')->with('message','Thêm phí vận chuyển thành công!');
    }

    public function OrderCheckoutSave(Request $request){
        $data=$request->all();
        $this->validate($request,[
            'order_checkout_name' => 'bail|required|max:255|min:6',
            'order_checkout_email' => 'bail|required|email',
            'order_checkout_phone_number' => 'bail|required|min:10',
            'order_checkout_address' => 'bail|required|max:255|min:3',
        ],
        [
            'required' => 'Không được để trống',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài'
        ]);
        $order_detail=Session::get('cart');
        $order_transport_fee=Session::get('feeship');
        $order_coupon=Session::get('coupon');
        $order_code=substr(str_shuffle(str_repeat("RGWUB", 5)), 0,2).substr(str_shuffle(str_repeat("0123456789", 5)), 0,6);
        $order_old=Order::where('dondathang_ma_don_dat_hang',$order_code)->first();
        if (!$order_detail) {
            return Redirect::to('/shop-now')->with('message','Chưa có sản phẩm trong giỏ hàng!');
        }else{
            if($data['order_city']==-1 || $data['order_province']==-1 || $data['order_wards']==-1){
                return Redirect::to('/checkout')->with('error','Vui lòng chọn địa chỉ vận chuyển!');
            }else{
                if (!$order_old) {
                    $order_code = substr(str_shuffle(str_repeat("RGWUB", 5)), 0, 2).substr(str_shuffle(str_repeat("0123456789", 5)), 0, 6);
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $order_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
                    $order=new Order();
                    $order->dondathang_ma_don_dat_hang=$order_code;
                    $order->dondathang_ngay_dat_hang=$order_date;
                    if ($data['order_checkout_note']!=null ||$data['order_checkout_note']!='') {
                        $order->dondathang_ghi_chu=$data['order_checkout_note'];
                    } else {
                        $order->dondathang_ghi_chu='';
                    }
                    $order->khachhang_id=Session::get('customer_id');
                    $order->dondathang_tinh_trang_thanh_toan=0;//chưa thanh toán COD
                    $order->dondathang_trang_thai=0;
                    $order_delivery=new Delivery();
                    $ci=City::find($data['order_city']);
                    $prov=Province::find($data['order_province']);
                    $wards=Wards::find($data['order_wards']);
                    if ($ci && $prov && $wards) {
                        $address=$data['order_checkout_address'].','.$wards->xaphuongthitran_name.','.$prov->quanhuyen_name.','.$ci->tinhthanhpho_name;
                        $order_delivery->giaohang_nguoi_nhan_dia_chi=$address;
                    } else {
                        $order_delivery->giaohang_nguoi_nhan_dia_chi=$data['order_checkout_address'];
                    }
                    $order_delivery->giaohang_nguoi_nhan=$data['order_checkout_name'];
                    $order_delivery->giaohang_nguoi_nhan_email=$data['order_checkout_email'];
                    $order_delivery->giaohang_nguoi_nhan_so_dien_thoai=$data['order_checkout_phone_number'];
                    $order_delivery->giaohang_phuong_thuc_thanh_toan=$data['order_checkout_pay_method'];
                    $order_delivery->giaohang_trang_thai=0;
                    $order_delivery->giaohang_ma_don_dat_hang=$order_code;
                    $total=0;
                    foreach($order_detail as $or=>$or_detail){
                        $order_detail=new OrderDetail();
                        $order_detail->chitietdondathang_so_luong=$or_detail['product_quantity'];
                        $order_detail->sanpham_id=$or_detail['product_id'];
                        $order_detail->chitietdondathang_ma_don_dat_hang=$order_code;
                        $order_detail->chitietdondathang_don_gia=$or_detail['product_price'];
                        $total+=($or_detail['product_price']*$or_detail['product_quantity']);
                        $cart_array[] = array(
                            'product_name' => $or_detail['product_name'],
                            'product_price' => $or_detail['product_price'],
                            
                            'product_qty' => $or_detail['product_quantity']
                          );
                        $order_detail->save();
                    }
                    if(isset($order_transport_fee)){
                        foreach($order_transport_fee as $tran=>$fee){
                            $tran_fee=$fee['fee'];
                        }
                    }else{
                        $tran_fee=35000;
                    }
                    if(isset($order_coupon)){
                        foreach($order_coupon as $co=>$cou){
                            if($cou['coupon_type']==1){
                                $discount=$cou['coupon_number'];
                            }else{
                                $discount=$total*($cou['coupon_number']/100);
                            }
                            $update_coupon=Coupon::find($cou['coupon_id']);
                            $update_coupon->makhuyenmai_so_luong -=1;
                            $update_coupon->save();
                            $coupon_code=$cou['coupon_code'];
                            break;
                        }
                        $order->dondathang_ma_giam_gia=$coupon_code;
                        $order->dondathang_tong_tien=$total+$tran_fee-$discount;
                        $order_delivery->giaohang_tong_tien_thanh_toan=$total+$tran_fee-$discount;
                    }else{
                        $coupon_code=null;
                        $discount=0;
                        $order->dondathang_tong_tien=$total+$tran_fee;
                        $order_delivery->giaohang_tong_tien_thanh_toan=$total+$tran_fee;
                    }
                    //send mail
                    $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                    $to_name="SHOES";
                    $title_mail = "Đơn Hàng Mới Từ SHOES SHOP".' - Mã Đơn Hàng: '.$order_code;
                    $customer = Customer::find(Session::get('customer_id'));
                    $data['email'][] = $customer->khachhang_email;
                    $shipping_array = array(
                      'feeship' =>  $tran_fee,
                      'discount' =>  $discount,
                      'customer_name' => $customer->khachhang_ten,
                      'customer_address' => $customer->khachhang_dia_chi,
                      'customer_phone' => $customer->khachhang_so_dien_thoai,
                      'customer_email' => $customer->khachhang_email,
                      'shipping_name' => $data['order_checkout_name'],
                      'shipping_day' => $now,
                      'shipping_email' => $data['order_checkout_email'],
                      'shipping_phone' => $data['order_checkout_phone_number'],
                      'shipping_address' => $order_delivery->giaohang_nguoi_nhan_dia_chi,
                      'shipping_notes' => $data['order_checkout_note'],
                      'shipping_method' => $data['order_checkout_pay_method']

                    );
                    //lay ma giam gia, lay coupon code
                    $ordercode_mail = array(
                      'coupon_code' => $coupon_code,
                      'order_code' => $order_code,
                    );
                    Mail::send('layout.send_mail_order',  ['cart_array'=>$cart_array, 'shipping_array'=>$shipping_array ,'code'=>$ordercode_mail] , function($message) use ($to_name,$title_mail,$data){
                        $message->to($data['email'])->subject($title_mail);//send this mail with subject
                        $message->from($data['email'],$to_name,$title_mail);//send from this mail
                    });
                    $order->dondathang_giam_gia=$discount;
                    $order->dondathang_phi_van_chuyen=$tran_fee;
                    $order->save();
                    $order_delivery->save();
                    // if(isset($data['order_checkout_create_account'])){
                    //     if($data['order_checkout_create_account']==1 && $data['checkout_order_password'] && $data['checkout_order_user_name']){
                    //         $custommer=new Customer();
                    //         $user=new UserAccount();
                    //         $custommer->khachhang_ten=$data['order_checkout_name'];
                    //         $custommer->khachhang_email=$data['order_checkout_email'];
                    //         $custommer->khachhang_dia_chi=$data['order_checkout_address'];
                    //         $custommer->khachhang_so_dien_thoai=$data['order_checkout_phone_number'];
                    //         $custommer->khachhang_trang_thai=1;
                    //         $user->user_ten=$data['checkout_order_user_name'];
                    //         $user->user_email=$data['order_checkout_email'];
                    //         $user->user_password=md5($data['checkout_order_password']);
                    //         $user->loainguoidung_id=4;
                    //         $user->save();
                    //         $custommer->save();
                    //     }
                    // }
                    Session::forget('cart');
                    Session::forget('coupon');
                    Session::forget('feeship');
                    Session::forget('count_cart');
                    return Redirect::to('/my-account')->with('message','Đặt hàng thành công, vui lòng kiểm tra email hoặc đăng nhập để theo dõi đơn hàng!');
                }
            }
        }
    }
}
