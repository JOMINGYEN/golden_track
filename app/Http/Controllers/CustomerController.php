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
use Mail;
use Carbon\Carbon;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Str;


session_start();
use Illuminate\Support\Facades\Redirect;
class CustomerController extends Controller
{
    public function Showlogin(){
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
        return view('client.pages.customer.login')
        ->with('product_type',$all_product_type)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_brand',$all_brand)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }
    //register
    public function ShowVerificationEmail(){
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
        return view('client.pages.customer.verification_email')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }

    public function VerificationEmailCustomer(Request $request){
        $data=$request->all();
        $now=time();
        $get_email=Customer::where('khachhang_email',$data['verification_email'])->first();
        $get_email_user=UserAccount::where('user_email',$data['verification_email'])->first();
        if($get_email && $get_email_user){
            return redirect()->back()->with('error','Tài khoản đã tồn tại!');
        }else{
            $verification_code=substr(str_shuffle(str_repeat("QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuioplkjhgfdsazxcvbnm", 5)), 0,5).substr(str_shuffle(str_repeat("0123456789", 5)), 0,5);
            $to_name="SHOES";
            $to_mail=$data['verification_email'];
            $title_mail = "Mã Xác Thực Từ SHOES SHOP";
            $data=array("name"=>"SHOES SHOP","body"=>$verification_code);
            $verification[] = array(
                'verification_time' => $now + 300,
                'verification_code' => $verification_code,
                'verification_email' => $to_mail,
            );
            Session::put('verification_email_customer',$verification);
            Mail::send('layout.verification_email',  $data, function($message) use ($to_name,$to_mail,$title_mail ){
                $message->to($to_mail)->subject($title_mail );//send this mail with subject
                $message->from($to_mail, $to_name,$title_mail );//send from this mail
            });
            return Redirect::to('/register-customer')->with('message','Chúng tôi đã gửi mã xác minh vào email của bạn, hãy nhập mã xác minh để đăng ký tài khoản!');
        }
    }

    public function ShowRegister(){
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
        return view('client.pages.customer.register')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }
    public function RegisterCustomer(Request $request)
    {
        $data = $request->all();
        $now = time();
        $verification = Session::get('verification_email_customer');

        $this->validate($request, [
            'customer_user_name_register' => 'bail|required|max:255|min:6',
            'customer_email_register' => 'bail|required|email|max:255',
            'customer_password_register' => 'bail|required|max:255|min:6',
            'customer_confirm_password_register' => 'bail|required|max:255|min:6'
        ], [
            'required' => 'Không được để trống',
            'email' => 'Email sai định dạng',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài'
        ]);

        if ($data['customer_password_register'] !== $data['customer_confirm_password_register']) {
            return Redirect::to('/register-customer')->with('error', 'Mật khẩu xác nhận không chính xác!');
        }

        if (!isset($verification)) {
            return Redirect::to('/show-verification-email-customer')->with('error', 'Nhập email của bạn để đăng ký!');
        }

        foreach ($verification as $value) {
            $verification_time = $value['verification_time'];
            $verification_code = $value['verification_code'];
            $verification_email = $value['verification_email'];
            break;
        }

        if ($verification_code !== $data['customer_verification_code_register'] || $verification_email !== $data['customer_email_register']) {
            return Redirect::to('/register-customer')->with('error', 'Mã xác minh hoặc email không chính xác!');
        }

        if ($now > $verification_time) {
            Session::forget('verification_email_customer');
            return Redirect::to('/show-verification-email-customer')->with('error', 'Mã xác minh đã hết hạn!');
        }

        // Lưu tài khoản người dùng trước
        $user_acc = new UserAccount();
        $user_acc->user_ten = $data['customer_user_name_register'];
        $user_acc->user_email = $data['customer_email_register'];
        $user_acc->user_password = md5($data['customer_password_register']);
        $user_acc->remember_token = $verification_code;
        $user_acc->user_login_fail = 0;
        $user_acc->loainguoidung_id = 4;
        $user_acc->save(); // lưu trước để có user_id

        // Lưu khách hàng với user_id vừa tạo
        $customer = new Customer();
        $customer->khachhang_ten = $data['customer_user_name_register'];
        $customer->khachhang_email = $data['customer_email_register'];
        $customer->khachhang_trang_thai = 1;
        $customer->user_id = $user_acc->id; // <-- Gán đúng user_id
        $customer->save();

        Session::forget('verification_email_customer');

        return Redirect::to('/login-customer')->with('message', 'Đăng ký tài khoản thành công!');
    }

    //reset password
    public function ShowVerificationResetPassword(){
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
        return view('client.pages.customer.verification_password')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }

    public function VerificationResetPasswordCustomer(Request $request){
        $data=$request->all();
        $now=time();
        $get_email=Customer::where('khachhang_email',$data['verification_password'])->first();
        $get_email_user=UserAccount::where('user_email',$data['verification_password'])->first();
        if(!$get_email && !$get_email_user){
            return redirect()->back()->with('error','Tài khoản không tồn tại!');
        }else{
            $verification_code=substr(str_shuffle(str_repeat("QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuioplkjhgfdsazxcvbnm", 5)), 0,5).substr(str_shuffle(str_repeat("0123456789", 5)), 0,5);
            $to_name="SHOES";
            $to_mail=$data['verification_password'];
            $title_mail = "Mã Xác Thực Từ SHOES SHOP";
            $data=array("name"=>"SHOES Shop","body"=>$verification_code);
            $verification[] = array(
                'verification_pass_time' => $now + 300,
                'verification_pass_code' => $verification_code,
                'verification_pass_email' => $to_mail,
            );
            Session::put('verification_password_customer',$verification);
            Mail::send('layout.verification_email',  $data, function($message) use ($to_name,$to_mail, $title_mail ){
                $message->to($to_mail)->subject( $title_mail);//send this mail with subject
                $message->from($to_mail, $to_name, $title_mail );//send from this mail
            });
            return Redirect::to('/reset-password-customer')->with('message','Chúng tôi đã gửi mã xác minh đến email của bạn, hãy nhập mã xác minh để đặt lại mật khẩu!');
        }
    }

    public function ShowResetPassword(){
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
        return view('client.pages.customer.reset_password')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('header_min',$thu_tu_header);
    }
    public function ResetPasswordCustomer(Request $request){
        $data=$request->all();
        $now=time();
        $this->validate($request,[
            'customer_confirm_password_reset_password' => 'bail|required|max:255|min:6',
            'customer_password_reset_password' => 'bail|required|max:255|min:6'
        ],
        [
            'required' => 'Không được để trống',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài'
        ]);
        $verification=Session::get('verification_password_customer');
        if($data['customer_password_reset_password']!= $data['customer_confirm_password_reset_password']){
            return Redirect::to('/reset-password-customer')->with('error','Mật khẩu xác nhận không chính xác!');
        }else{
            if(!isset($verification)){
                return Redirect::to('/show-verification-password-customer')->with('error','Nhập email của bạn để đặt lại mật khẩu!');
            }else{
                foreach($verification as $key=>$value){
                    $verification_time=$value['verification_pass_time'];
                    $verification_code=$value['verification_pass_code'];
                    $verification_email=$value['verification_pass_email'];
                    break;
                }
                if($verification_code != $data['customer_verification_code_reset_password'] || $verification_email != $data['customer_email_reset_password']){
                    return Redirect::to('/reset-password-customer')->with('error','Mã xác minh hoặc email không chính xác!');
                }elseif($now > $verification_time){
                    Session::forget('verification_password_customer');
                    return Redirect::to('/show-verification-password-customer')->with('error','Mã xác minh đã hết hạn!');
                }else{
                    $get_email_user=UserAccount::where('user_email',$verification_email)->first();
                    $user_acc= UserAccount::find($get_email_user->id);
                    $user_acc->user_password=md5($data['customer_password_reset_password']);
                    $user_acc->user_login_fail=0;
                    $user_acc->remember_token=$verification_code;
                    $user_acc->save();
                    Session::forget('verification_password_customer');
                    return Redirect::to('/login-customer')->with('message','Đặt lại mật khẩu thành công!');
                }
            }
        }
    }
    //Change Password

    public function ChangePasswordCustomer(Request $request, $customer_email){
        $this->CheckLogin();
        $user_account=UserAccount::where('user_email',$customer_email)->first();
        $user_account_update_password=UserAccount::find($user_account->id);
        $data=$request->all();
        $this->validate($request,[
            'change_new_password' => 'bail|required|max:255|min:6',
            'change_confirm_new_password' => 'bail|required|max:255|min:6'
        ],
        [
            'required' => 'Không được để trống',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài'
        ]);
        if(md5($data['change_old_password']) != $user_account_update_password->user_password){
            return redirect()->back()->with('error','Incorrect Password');
        }elseif($data['change_new_password'] != $data['change_confirm_new_password']){
            return redirect()->back()->with('error','Confirmation password is incorrect');
        }else{
            $user_account_update_password->user_password=md5($data['change_new_password']);
            $user_account_update_password->save();
            Session::forget('customer_id');
            Session::forget('customer_name');
            Session::forget('user_id');
            return Redirect::to('/login-customer')->with('message','Đã thay đổi mật khẩu thành công, vui lòng đăng nhập lại!');
        }
    }
    public function CheckLoginCustomer(Request $request)
    {
        // Validate trước
        $this->validate($request, [
            'customer_email_login' => 'required|email|max:255',
            'customer_password_login' => 'required|max:255|min:6'
        ], [
            'customer_email_login.required' => 'Không được để trống',
            'customer_email_login.email' => 'Email sai định dạng',
            'customer_password_login.min' => 'Mật khẩu quá ngắn',
        ]);

        $email = $request->customer_email_login;
        $password = md5($request->customer_password_login);

        // Tìm user
        $user = UserAccount::where('user_email', $email)->first();

        if (!$user) {
            return Redirect::to('/login-customer')->with('error', 'Tài khoản không tồn tại!');
        }

        if ($user->user_password !== $password) {
            $user->user_login_fail += 1;
            $user->save();

            return Redirect::to('/login-customer')->with('error', 'Mật khẩu không chính xác!');
        }

        if ($user->user_login_fail >= 5) {
            return Redirect::to('/show-verification-password-customer')->with('error', 'Bạn đã đăng nhập sai quá số lần quy định, vui lòng đặt lại mật khẩu!');
        }

        if ($user->loainguoidung_id != 4) {
            return Redirect::to('/login-customer')->with('error', 'Không được phép truy cập!');
        }

        // Reset số lần sai nếu đúng mật khẩu
        $user->user_login_fail = 0;
        $user->save();

        // Lấy customer
        $customer = Customer::where('khachhang_email', $user->user_email)->first();

        if (!$customer) {
            return Redirect::to('/login-customer')->with('error', 'Không tìm thấy thông tin khách hàng!');
        }

        $customer->user_id = $user->id;
        $customer->save();

        // Set session
        Session::put('customer_name', $user->user_ten);
        Session::put('user_id', $user->id);
        Session::put('customer_id', $customer->id);

        // Redirect phù hợp
        $prevUrl = url()->previous();
        if (Str::contains($prevUrl, '/login-customer')) {
            return Redirect::to('/');
        }

        return redirect()->back();
    }


    public function CheckLogin(){
        $login=Session::get('customer_id');
        if($login){
            return redirect()->back();
        }else{
            return Redirect::to('/login-customer')->with('error','Vui lòng đăng nhập!')->send();
        }
    }
    public function ShowMyAccount(){
        $this->CheckLogin();
        $get_customer=Customer::find(Session::get('customer_id'));
        $customer_order=Order::where('khachhang_id',$get_customer->id)->orderby('id','DESC')->get();
        foreach($customer_order as $key=>$value){
            $customer_order_detail=OrderDetail::where('chitietdondathang_ma_don_dat_hang',$value->dondathang_ma_don_dat_hang)->get();
            $customer_order_delivery=Delivery::where('giaohang_ma_don_dat_hang',$value->dondathang_ma_don_dat_hang)->first();
            $customer_order_delivery_update=Delivery::find($customer_order_delivery->id);
            $customer_order_delivery_update->dondathang_id=$value->id;
            $customer_order_delivery_update->save();
            foreach($customer_order_detail as $k=>$v){
                if($value->dondathang_ma_don_dat_hang==$v->chitietdondathang_ma_don_dat_hang){
                    $customer_order_detail_update=OrderDetail::find($v->id);
                    $customer_order_detail_update->dondathang_id=$value->id;
                    $customer_order_detail_update->save();
                }
            }
        }
        // $customer_delivery_email=Delivery::where('giaohang_nguoi_nhan_email',$get_customer->khachhang_email)->orderby('id','DESC')->get();
        // if($customer_delivery_email->count()>0){
        //     foreach($customer_delivery_email as $key =>$value){
        //         $order_id[]=$value->dondathang_id;
        //     }
        //     $customer_order_email=Order::whereIn('id',$order_id)->get();
        // }else{
        //     $customer_order_email=null;
        // }
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
        $all_coupon_code = Coupon::where('makhuyenmai_trang_thai', 1)->get();
        if($all_coupon_code->count()>0){
            $all_coupon_code = Coupon::where('makhuyenmai_trang_thai', 1)->get();
        }else{
            $all_coupon_code=null;
        }
        return view('client.pages.customer.show_account')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('all_coupon_code',$all_coupon_code)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('header_min',$thu_tu_header)
        ->with('customer',$get_customer)
        // ->with('customer_all_order_email',$customer_order_email)
        ->with('customer_all_order',$customer_order);
    }

    public function CustomerEditSave(Request $request, $customer_id){
        $this->CheckLogin();
        $data=$request->all();
        $this->validate($request,[
            'customer_first_name' => 'bail|required|max:255|min:6',
            'customer_last_name' => 'bail|required|max:255|min:6',
            'customer_phone_number' => 'bail|required|max:255|min:10',
            'customer_address' => 'bail|required|max:255|min:20',
            'customer_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
        ],
        [
            'required' => 'Không được để trống',
            'min' => 'Quá ngắn',
            'max' => 'Quá dài',
            'mimes' => 'Sai định dạng ảnh'
        ]);
        $customer=Customer::find($customer_id);
        $customer->khachhang_ho=$data['customer_first_name'];
        $customer->khachhang_ten=$data['customer_last_name'];
        
        $customer->khachhang_so_dien_thoai=$data['customer_phone_number'];
        $customer->khachhang_dia_chi=$data['customer_address'];
        $old_name_img=$customer->khachhang_anh;
        $get_image = $request->file('customer_img');
        $path = 'public/uploads/client/customer/';
            if($get_image){
                if($path.$get_image && $path.$get_image!=$path.$old_name_img){
                    return Redirect::to('/my-account')->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                }else{
                    if($old_name_img!=null){
                        unlink($path.$old_name_img);
                    }
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.', $get_name_image));
                    $new_image =  $name_image.'.'.$get_image->getClientOriginalExtension();
                    $get_image->move($path, $new_image);
                    $customer->khachhang_anh  = $new_image;
                    $customer->save();
                    return Redirect::to('/my-account')->with('message', 'Cập nhật thành công!');
                }
            }else{
                if ($old_name_img!=null) {
                    $customer->khachhang_anh = $old_name_img;
                    $customer->save();
                    return Redirect::to('/my-account')->with('message', 'Cập nhật thành công!');
                } else {
                    return Redirect::to('/my-account')->with('error', 'Cập nhật không thành công, vui lòng chọn ảnh!');
                }
            }

    }

    public function CustomerUpdateAddressDelivery(Request $request, $order_id){
        $this->CheckLogin();
        $data=$request->all();
        $order_delivery=Order::find($order_id);
        if($order_delivery->dondathang_trang_thai==0 ||$order_delivery->dondathang_trang_thai==1){
            $delivery_update=Delivery::where('dondathang_id',$order_id)->first();
            $delivery_update->giaohang_ma_don_dat_hang=$order_delivery->dondathang_ma_don_dat_hang;
            $delivery_update->giaohang_nguoi_nhan=$data['delivery_update_name'];
            $delivery_update->giaohang_nguoi_nhan_email=$data['delivery_update_email'];
            $delivery_update->giaohang_nguoi_nhan_so_dien_thoai=$data['delivery_update_phone_number'];
            $ci=City::find($data['order_city']);
            $prov=Province::find($data['order_province']);
            $wards=Wards::find($data['order_wards']);
            if ($ci && $prov && $wards) {
                $address=$data['delivery_update_address'].','.$wards->xaphuongthitran_name.','.$prov->quanhuyen_name.','.$ci->tinhthanhpho_name;
                $delivery_update->giaohang_nguoi_nhan_dia_chi=$address;
            }else {
                $delivery_update->giaohang_nguoi_nhan_dia_chi=$data['delivery_update_address'];
            }
            if ($data['delivery_update_note']!=null ||$data['delivery_update_note']!='') {
                $order_delivery->dondathang_ghi_chu=$data['delivery_update_note'];
                $order_delivery->save();
            } else {
                $order_delivery->dondathang_ghi_chu='';
                $order_delivery->save();
            }
            $delivery_update->save();
            return Redirect::to('/customer-show-order/'.$order_id)->with('message', 'Cập nhật thành công!');
        }else{
            return Redirect::to('/customer-show-order/'.$order_id)->with('error','Cập nhật không thành công, đơn hàng của bạn đang được vận chuyển!');
        }
    }
    public function LogoutCustomer(){
        $this->CheckLogin();
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('user_id');
    }


    public function ShowCustomerOrderDetail($order_id){
        $this->CheckLogin();
        $get_all_order_detail=OrderDetail::where('dondathang_id',$order_id)->get();
        $get_customer=Customer::find(Session::get('customer_id'));
        $customer_order=Order::find($order_id);
        $customer_delivery=Delivery::where('dondathang_id',$order_id)
        ->where('giaohang_ma_don_dat_hang',$customer_order->dondathang_ma_don_dat_hang)->first();
        $city=City::orderby('id','ASC')->get();
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
        return view('client.pages.customer.order_show_detail')
        ->with('product_type',$all_product_type)
        ->with('product_brand',$all_brand)
        ->with('product_collection',$all_collection)
        ->with('header_show',$all_header)
        ->with('get_about_us_bottom',$get_about_us_bottom)
        ->with('header_min',$thu_tu_header)
        ->with('customer',$get_customer)
        ->with('customer_order',$customer_order)
        ->with('all_order_detail',$get_all_order_detail)
        ->with('customer_delivery',$customer_delivery)
        ->with('city',$city);
    }

    public function CustomerCancelOrder($order_id){
        $this->CheckLogin();

        $order = Order::find($order_id);

        $order_delivery = Delivery::where('giaohang_ma_don_dat_hang', $order->dondathang_ma_don_dat_hang)
            ->where('dondathang_id', $order_id)->first();

        $order_delivery_update = Delivery::find($order_delivery->id);
        $order_delivery_update->giaohang_trang_thai = 3; // đã hủy

        $order->dondathang_trang_thai = 4; // trạng thái đơn hàng: đã hủy

        $order_detail = OrderDetail::where('chitietdondathang_ma_don_dat_hang', $order->dondathang_ma_don_dat_hang)
            ->where('dondathang_id', $order_id)->get();

        // Nếu đơn hàng đã thanh toán (1), cập nhật lại tồn kho và hoàn tiền
        if ($order->dondathang_tinh_trang_thanh_toan == 1) {
            foreach ($order_detail as $value) {
                // Lấy thông tin tồn kho theo sản phẩm
                $product_in_stock = ProductInStock::where('sanpham_id', $value->sanpham_id)->first();

                if ($product_in_stock) {
                    $product_in_stock->sanphamtonkho_so_luong_ton += $value->chitietdondathang_so_luong;
                    $product_in_stock->sanphamtonkho_so_luong_da_ban -= $value->chitietdondathang_so_luong;
                    $product_in_stock->save();
                }
            }
            $order->dondathang_tinh_trang_thanh_toan = 2; // đã hủy, hoàn tiền lại
        }
        // Nếu đơn hàng chưa thanh toán (0), đánh dấu là hủy không hoàn tiền
        elseif ($order->dondathang_tinh_trang_thanh_toan == 0) {
            $order->dondathang_tinh_trang_thanh_toan = 3; // đã hủy, không hoàn tiền
        }

        $order->save();
        $order_delivery_update->save();

        return redirect()->back();
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

    public function ShowAllCustomer(){
        $this->AuthLogin();
        $all_customer=Customer::orderby('id','DESC')->paginate(5);
        return view('admin.pages.customer.customer')->with('all_customer',$all_customer);
    }

    public function ShowAllOrderCustomer($customer_id){
        $this->AuthLogin();
        $all_order_customer=Order::where('khachhang_id',$customer_id)->orderby('id','DESC')->paginate(5);
        $customer=Customer::find($customer_id);
        return view('admin.pages.customer.customer_show_order')
        ->with('customer',$customer)
        ->with('all_order_customer',$all_order_customer);
    }

    public function ShowCustomerDetail($customer_id){
        $this->AuthLogin();
        $all_order_customer=Order::where('khachhang_id',$customer_id)->orderby('id','DESC')->paginate(5);
        $customer=Customer::find($customer_id);
        return view('admin.pages.customer.customer_detail')
        ->with('customer',$customer)
        ->with('all_order_customer',$all_order_customer);
    }
}
