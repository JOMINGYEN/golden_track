<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\UserAccount;
use DB;
use Session;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Redirect;
session_start();
class AdminHomeController extends Controller
{

    public function index(){
    	return view('admin.pages.auth.login');
    }

    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    public function ShowDashboard(){
        $this->AuthLogin();
    	return view('admin.pages.dashboard');
    }
    public function Login(Request $request){
        $admin_email=$request->admin_email;
        $admin_password=md5($request->admin_password);
        // $result = DB::table('tbl_users')->where('user_email',$admin_email)
        // ->where('user_password',$admin_password)->first();
        $email=UserAccount::where('user_email',$admin_email)->first();
        if(!$email){
            return Redirect::to('/admin')->with('error','Tài khoản không tồn tại!');
        }else{
            if($email->user_password != $admin_password){
                $user_login_fail=UserAccount::find($email->id);
                $user_login_fail->user_login_fail +=1;
                $user_login_fail->save();
                return Redirect::to('/admin')->with('error','Sai mật khẩu!');
            }else{
                if($email->user_login_fail >= 5){
                    return Redirect::to('/get-email-admin')->with('error','Bạn đã đăng nhập sai quá số lần quy định!');
                }else{
                    if($email->loainguoidung_id ==2 || $email->loainguoidung_id ==1 ||$email->loainguoidung_id ==3){
                        $user_login_fail=UserAccount::find($email->id);
                        $user_login_fail->user_login_fail = 0;
                        $user_login_fail->save();
                        $admin_update=Admin::where('user_id',$user_login_fail->id)->first();
                        if(!$admin_update){
                            $admin_update_email=Admin::where('admin_email',$user_login_fail->user_email)->first();
                            $admin_update_email->user_id=$email->id;
                            $admin_update_email->save();
                        }
                        Session::put('admin_name',$email->user_ten);
                        Session::put('admin_id',$email->id);
                        Session::put('admin_image',$user_login_fail->admin_anh);
                        Session::put('admin_role',$email->loainguoidung_id);
                        return Redirect::to('/dashboard');
                    }else{
                        return Redirect::to('/admin')->with('error','Bạn không có quyền truy cập!');
                    }
                }
            }
        }
    }

    public function Logout(){
        $this->AuthLogin();
        Session::forget('admin_name');
        Session::forget('admin_id');
        Session::forget('admin_role');
        return Redirect::to('/admin');
    }

    public function ShowStaff(){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            // $all_staff = Brand::orderBy('id','DESC')->paginate(5);
            $user=UserAccount::all();
            $staff=Admin::all();
            foreach($user as $key=>$value){
                foreach($staff as $k =>$v){
                    if($value->user_email==$v->admin_email){
                        $staff_update=Admin::find($v->id);
                        $staff_update->user_id=$value->id;
                        $staff_update->save();
                    }
                }
            }
            $get_admin=UserAccount::where('loainguoidung_id',1)->first();
            $all_staff = Admin::orderBy('id','DESC')->whereNotIn('user_id',[$get_admin->id])->paginate(5);
            return view('admin.pages.staff.staff')->with('all_staff',$all_staff);
        }

    }
    public function ShowStaffAdd(){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.staff.staff_add');
        }
    }

    public function StaffAddSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'staff_name' => 'bail|required|max:255|min:6',
                'staff_email' => 'bail|required|email|max:255',
                'staff_password' => 'bail|required|max:255|min:6',
                'staff_password_confirm' => 'bail|required|max:255|min:6'
            ],
            [
                'required' => 'Không được để trống',
                'email' => 'Email sai định dạng',
                'max' => 'Quá dài',
                'min' => 'Quá ngắn',
            ],);
            if ($data['staff_password']!=$data['staff_password_confirm']) {
                return Redirect::to('/staff-add')->with('error','Mật khẩu xác nhận không đúng!');
            } else {
                $get_user=UserAccount::where('user_email',$data['staff_email'])->first();
                $get_admin=Admin::where('admin_email',$data['staff_email'])->first();
                if($get_user && $get_admin){
                    return Redirect::to('/staff-add')->with('error','Tạo tài khoản không thành công, tài khoản đã tồn tại!');
                }else{
                    $staff=new Admin();
                    $user_acc=new UserAccount();
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
                    $staff->admin_ten=$data['staff_name'];
                    $staff->admin_email=$data['staff_email'];
                    $staff->admin_trang_thai=1;
                    $staff->admin_ngay_vao_lam=$date;
                    $user_acc->user_ten=$data['staff_name'];
                    $user_acc->user_email=$data['staff_email'];
                    $user_acc->user_password=md5($data['staff_password']);
                    $user_acc->loainguoidung_id=$data['admin_role'];
                    $staff->save();
                    $user_acc->save();
                    return Redirect::to('/staff-add')->with('message', 'Tạo tài khoản thành công!');
                }
            }
        }
    }

    public function ShowStaffEdit($staff_id){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            $staff=Admin::find($staff_id);
            return view('admin.pages.staff.staff_edit')->with('staff', $staff);
        }
    }

    public function StaffEditSave(Request $request, $staff_id){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'staff_first_name' => 'bail|required|max:255|min:6',
                'staff_last_name' => 'bail|required|max:255|min:6',
                'staff_phone_number' => 'bail|required|max:255|min:10',
                'staff_address' => 'bail|required|max:255|min:20',
                'staff_admin_id' => 'bail|required|max:255|min:15',
                'staff_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
            ],
            [
                'required' => 'Không được để trống',
                'max' => 'Quá dài',
                'min' => 'Quá ngắn',
                'mimes' => 'Sai định dạng ảnh'
            ]);
            $get_admin=Admin::where('admin_id',$data['staff_admin_id'])->whereNotIn('id',[$staff_id])->first();
            if($get_admin){
                return Redirect::to('/staff-edit/'.$staff_id)->with('error','Cập nhật không thành công, mã căn cước đã tồn tại!');
            }else{
                $staff=Admin::find($staff_id);
                $staff->admin_ho=$data['staff_first_name'];
                $staff->admin_ten=$data['staff_last_name'];
                $staff->admin_so_dien_thoai=$data['staff_phone_number'];
                $staff->admin_dia_chi=$data['staff_address'];
                $staff->admin_id=$data['staff_admin_id'];
                $staff->admin_gioi_tinh=$data['staff_gender'];
                $staff->admin_trang_thai=$data['staff_status'];
                $old_name_img=$staff->admin_anh;
                $get_image = $request->file('staff_img');
                $path = 'public/uploads/admin/staff/';
                if ($get_image) {
                    if($path.$get_image && $path.$get_image!=$path.$old_name_img){
                        return Redirect::to('/staff-edit/'.$staff_id)->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                    }else{
                        if ($old_name_img!=null) {
                            unlink($path.$old_name_img);
                        }
                        $get_name_image = $get_image->getClientOriginalName();
                        $name_image = current(explode('.', $get_name_image));
                        $new_image =  $name_image.'.'.$get_image->getClientOriginalExtension();
                        $get_image->move($path, $new_image);
                        $staff->admin_anh  = $new_image;
                        $staff->save();
                        return Redirect::to('/staff-edit/'.$staff_id)->with('message', 'Cập nhật thành công');
                    }
                }else{
                    if($old_name_img!=null){
                        $staff->admin_anh = $old_name_img;
                        $staff->save();
                        return Redirect::to('/staff-edit/'.$staff_id)->with('message', 'Cập nhật thành công');
                    }else{
                        return Redirect::to('/staff-edit/'.$staff_id)->with('error','Cập nhật không thành công, vui lòng chọn ảnh!');
                    }
                }
            }
        }
    }

    public function ShowStaffMyAccount(){
        $this->AuthLogin();
        $user_id=Session::get('admin_id');
        $staff=Admin::where('user_id',$user_id)->first();
        return view('admin.pages.staff.staff_my_account')->with('staff', $staff);
    }

    public function StaffUpdateMyAccount(Request $request, $staff_id){
        $this->AuthLogin();
        $data=$request->all();
        $this->validate($request,[
            'staff_first_name' => 'bail|required|max:255|min:6',
            'staff_last_name' => 'bail|required|max:255|min:6',
            'staff_phone_number' => 'bail|required|max:255|min:10',
            'staff_email' => 'bail|required|email',
            'staff_address' => 'bail|required|max:255|min:20',
            'staff_admin_id' => 'bail|required|max:255|min:15',
            'staff_img' => 'bail|mimes:jpeg,jpg,png,gif|max:10000'
        ],
        [
            'required' => 'Không được để trống',
            'email' => 'Email sai định dạng',
            'max' => 'Quá dài',
            'min' => 'Quá ngắn',
            'mimes' => 'Sai định dạng ảnh'
        ]);
        $get_admin=Admin::where('admin_id',$data['staff_admin_id'])->whereNotIn('id',[$staff_id])->first();
        if($get_admin){
            return Redirect::to('/staff-my-account')->with('error','Cập nhật không thành công, mã căn cước đã tồn tại!');
        }else{
            $staff=Admin::find($staff_id);
            $staff->admin_ho=$data['staff_first_name'];
            $staff->admin_ten=$data['staff_last_name'];
            $staff->admin_so_dien_thoai=$data['staff_phone_number'];
            $staff->admin_dia_chi=$data['staff_address'];
            $staff->admin_id=$data['staff_admin_id'];
            $staff->admin_gioi_tinh=$data['staff_gender'];
            $staff->admin_trang_thai=$data['staff_status'];
            $old_name_img=$staff->admin_anh;
            $get_image = $request->file('staff_img');
            $path = 'public/uploads/admin/staff/';
            if ($get_image) {
                if($path.$get_image && $path.$get_image!=$path.$old_name_img){
                    return Redirect::to('/staff-my-account')->with('error', 'Cập nhật không thành công, tên ảnh đã tồn tại vui lòng chọn ảnh khác!');
                }else{
                    if ($old_name_img!=null) {
                        unlink($path.$old_name_img);
                    }
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.', $get_name_image));
                    $new_image =  $name_image.rand(0, 99).'.'.$get_image->getClientOriginalExtension();
                    $get_image->move($path, $new_image);
                    $staff->admin_anh  = $new_image;
                    $staff->save();
                    return Redirect::to('/staff-my-account')->with('message', 'Cập nhật thành công');
                }
            }else{
                if($old_name_img!=null){
                    $staff->admin_anh = $old_name_img;
                    $staff->save();
                    return Redirect::to('/staff-my-account')->with('message', 'Cập nhật thành công');
                }else{
                    return Redirect::to('/staff-my-account')->with('error','Cập nhật không thành công, vui lòng chọn ảnh!');
                }
            }
        }
    }

    public function ShowStaffChangeEmail(){
        $this->AuthLogin();
        return view('admin.pages.staff.staff_my_account_change_email');
    }

    public function StaffChangeEmailSave(Request $request){
        $this->AuthLogin();
        $data=$request->all();
        $this->validate($request,[
            'staff_email' => 'bail|required|email',
            'staff_new_email' => 'bail|required|email',
            'staff_password' => 'bail|required'
        ],
        [
            'required' => 'Không được để trống',
            'email' => 'Email sai định dạng'
        ]);
        $user_staff=UserAccount::where('user_email', $data['staff_email'])->first();
        if (!$user_staff) {
            return Redirect::to('/staff-my-account-change-email')->with('error', 'Email không chính xác!');
        } else {
            if ($data['staff_email'] == $data['staff_new_email']) {
                return Redirect::to('/staff-my-account-change-email')->with('error',  'Email không chính xác!');
            } else {
                $user_staff_email=UserAccount::where('user_email', $data['staff_new_email'])->whereNotIn('id',[$user_staff->id])->first();
                if($user_staff_email){
                    return Redirect::to('/staff-my-account-change-email')->with('error',  'Email không chính xác!');
                }else{
                    $user_staff_password=UserAccount::where('user_password',md5($data['staff_password']))->where('id',$user_staff->id)->first();
                    if(!$user_staff_password){
                        return Redirect::to('/staff-my-account-change-email')->with('error','Mật khẩu không chính xác!');
                    }else{
                        $user_staff_update=UserAccount::find($user_staff->id);
                        $user_staff_update->user_email=$data['staff_new_email'];
                        $admin_email_update=Admin::where('user_id',$user_staff_update->id)->first();
                        $admin_email_update->admin_email=$data['staff_new_email'];
                        $user_staff_update->save();
                        $admin_email_update->save();
                        Session::forget('admin_name');
                        Session::forget('admin_id');
                        Session::forget('admin_role');
                        return Redirect::to('/admin')->with('message','Thay đổi email đăng nhập thành công, vui lòng đăng nhập lại!');
                    }
                }
            }
        }
    }

    public function ShowStaffChangePassword(){
        $this->AuthLogin();
        return view('admin.pages.staff.staff_my_account_change_password');
    }

    public function StaffChangePasswordSave(Request $request){
        $this->AuthLogin();
        $data=$request->all();
        $this->validate($request,[
            'my_account_old_password' => 'bail|required|max:255|min:6',
            'my_account_new_password' => 'bail|required|max:255|min:6',
            'my_account_confirm_new_password' => 'bail|required|max:255|min:6'
        ],
        [
            'required' => 'Không được để trống',
            'max' => 'Quá dài',
            'min' => 'Quá ngắn',
        ]);
        $user_id=Session::get('admin_id');
        $user_staff=UserAccount::where('id',$user_id)->where('user_password',md5($data['my_account_old_password']))->first();
        if(!$user_staff){
            return Redirect::to('/staff-my-account-change-password')->with('error','Mật khẩu không chính xác!');
        }else{
            if($data['my_account_new_password'] != $data['my_account_confirm_new_password'] ){
                return Redirect::to('/staff-my-account-change-password')->with('error','Mật khẩu xác nhận không chính xác!');
            }else{
                $user_staff_update=UserAccount::find($user_id);
                $user_staff_update->user_email=$user_staff->user_email;
                $user_staff_update->user_password=md5($data['my_account_new_password']);
                $user_staff_update->save();
                Session::forget('admin_name');
                Session::forget('admin_id');
                Session::forget('admin_role');
                return Redirect::to('/admin')->with('message','Đổi mật khẩu thành công, vui lòng đăng nhập lại!');
            }
        }
    }


    public function ShowAdminChangePasswordStaff(){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.auth.admin_change_password_staff');
        }
    }

    public function AdminChangePasswordStaffSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'staff_new_password' => 'bail|required|max:255|min:6',
                'staff_confirm_new_password' => 'bail|required|max:255|min:6'
            ],
            [
                'required' => 'Không được để trống',
                'max' => 'Quá dài',
                'min' => 'Quá ngắn',
            ]);
            $user_staff=UserAccount::where('user_email', $data['staff_email'])->first();
            if (!$user_staff) {
                return Redirect::to('/admin-change-password-staff')->with('error', 'Email không chính xác!');
            } else {
                if ($data['staff_new_password'] != $data['staff_confirm_new_password']) {
                    return Redirect::to('/admin-change-password-staff')->with('error', 'Mật khẩu xác nhận không chính xác!');
                } else {
                    $user_staff_update=UserAccount::find($user_staff->id);
                    $user_staff_update->user_password=md5($data['staff_new_password']);
                    $user_staff_update->save();
                    return Redirect::to('/admin-change-password-staff')->with('message', 'Đổi mật khẩu thành công!');
                }
            }
        }
    }

    public function ShowAdminChangeEmailStaff(){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            return view('admin.pages.auth.admin_change_email_staff');
        }
    }

    public function AdminChangeEmailStaffSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')!=1){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'staff_email' => 'bail|required|email',
                'staff_new_email' => 'bail|required|email'
            ],
            [
                'email' => 'Email sai định dạng',
                'required' => 'Không được để trống',

            ]);
            $user_staff=UserAccount::where('user_email', $data['staff_email'])->first();
            if (!$user_staff) {
                return Redirect::to('/admin-change-email-staff')->with('error', 'Email không chính xác!');
            } else {
                if ($data['staff_new_email'] == $data['staff_email']) {
                    return Redirect::to('/admin-change-email-staff')->with('error','Email không chính xác!');
                } else {
                    $user_staff_email=UserAccount::where('user_email', $data['staff_new_email'])->whereNotIn('id',[$user_staff->id])->first();
                    if($user_staff_email){
                        return Redirect::to('/admin-change-email-staff')->with('error', 'Email không chính xác!');
                    }else{
                        $user_staff_update=UserAccount::find($user_staff->id);
                        $user_staff_update->user_email=$data['staff_new_email'];
                        $admin_email_update=Admin::where('user_id',$user_staff_update->id)->first();
                        $admin_email_update->admin_email=$data['staff_new_email'];
                        $user_staff_update->save();
                        $admin_email_update->save();
                        return Redirect::to('/admin-change-email-staff')->with('message', 'Đổi email đăng nhập thành công!');
                    }
                }
            }
        }
    }

    public function GetEmailResetPassword(){
    	return view('admin.pages.auth.get_email_reset_password');
    }

    public function VerificationResetPasswordStaff(Request $request){
        $data=$request->all();
        $now=time();
        $get_email=Admin::where('admin_email',$data['verification_password'])->first();
        $get_email_user=UserAccount::where('user_email',$data['verification_password'])->first();
        if(!$get_email && !$get_email_user){
            return Redirect::to('/get-email-admin')->with('error','Tài khoản không tồn tại!');
        }else{
            $verification_code=substr(str_shuffle(str_repeat("QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuioplkjhgfdsazxcvbnm", 5)), 0,5).substr(str_shuffle(str_repeat("0123456789", 5)), 0,5);
            $to_name="SHOES";
            $to_mail=$data['verification_password'];
            $title_mail = "Mã Xác Thực Từ SHOES SHOP";
            $data=array("name"=>"SHOES SHOP","body"=>$verification_code);
            $verification[] = array(
                'verification_pass_time' => $now + 300,
                'verification_pass_code' => $verification_code,
                'verification_pass_email' => $to_mail,
            );
            Session::put('verification_password_staff',$verification);
            Mail::send('layout.verification_email',  $data, function($message) use ($to_name,$to_mail, $title_mail){
                $message->to($to_mail)->subject( $title_mail);//send this mail with subject
                $message->from($to_mail, $to_name, $title_mail);//send from this mail
            });
            return Redirect::to('/reset-password-admin')->with('message','Chúng tôi đã gửi mã xác minh đến email của bạn, hãy nhập mã xác minh để đặt lại mật khẩu!');
        }
    }

    public function ShowResetPasswordStaff(){
        return view('admin.pages.auth.reset_password');
    }

    public function ResetPasswordStaff(Request $request){
        $data=$request->all();
        $now=time();
        $this->validate($request,[
            'admin_reset_new_password' => 'bail|required|max:255|min:6',
            'admin_reset_confirm_new_password' => 'bail|required|max:255|min:6'
        ],
        [
            'required' => 'Không được để trống',
            'max' => 'Quá dài',
            'min' => 'Quá ngắn',
        ]);
        $verification=Session::get('verification_password_staff');
        if($data['admin_reset_new_password']!= $data['admin_reset_confirm_new_password']){
            return Redirect::to('/reset-password-admin')->with('error','Mật khẩu xác nhận không chính xác!');
        }else{
            if(!isset($verification)){
                return Redirect::to('/verification-email-admin')->with('error','Nhập email của bạn để đặt lại mật khẩu!');
            }else{
                foreach($verification as $key=>$value){
                    $verification_time=$value['verification_pass_time'];
                    $verification_code=$value['verification_pass_code'];
                    $verification_email=$value['verification_pass_email'];
                    break;
                }
                if($verification_code != $data['admin_reset_password_verification_code'] || $verification_email != $data['admin_reset_confirm_email']){
                    return Redirect::to('/reset-password-admin')->with('error','Mã xác minh hoặc email không chính xác!');
                }else{
                    if($now > $verification_time){
                        Session::forget('verification_password_staff');
                        return Redirect::to('/verification-email-admin')->with('error','Mã xác minh đã hết hạn!');
                    }else{
                        $get_email_user=UserAccount::where('user_email',$verification_email)->first();
                        if ($get_email_user) {
    $user_acc = UserAccount::find($get_email_user->id);
} else {
    return Redirect::to('/reset-password-admin')->with('error', 'Tài khoản không tồn tại');
}
                        $user_acc->user_password=md5($data['admin_reset_new_password']);
                        $user_acc->user_login_fail=0;
                        $user_acc->remember_token=$verification_code;
                        $user_acc->save();
                        Session::forget('verification_password_staff');
                        return Redirect::to('/admin')->with('message','Đổi mật khẩu thành công!');
                    }
                }
            }
        }
    }
}