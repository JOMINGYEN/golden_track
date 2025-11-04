<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\Models\Coupon;
use Illuminate\Support\Facades\Redirect;
session_start();

class CouponCodeController extends Controller
{
    public function Index(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $this->CheckCoupon();
            $all_coupon_code=Coupon::orderBy('id', 'DESC')->paginate(5);
            return view('admin.pages.coupon_code.coupon_code')->with('all_coupon_code', $all_coupon_code);
        }
    }

    public function CheckCoupon(){
        $today = date("Y-m-d");
        $all_cou=Coupon::all();
        if($all_cou->count()>0){
            foreach($all_cou as $key => $value){
               if($value->makhuyenmai_ngay_ket_thuc <= $today){
                   if($value->makhuyenmai_so_luong<=0){
                        $coupon_update=Coupon::find($value->id);
                        $coupon_update->makhuyenmai_trang_thai=-2;
                        $coupon_update->save();
                   }else{
                        $coupon_update=Coupon::find($value->id);
                        $coupon_update->makhuyenmai_trang_thai=-1;
                        $coupon_update->save();
                   }
               }
            }
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
    public function CouponCodeAdd(){
        $this->AuthLogin();
        if (Session::get('admin_role')==3) {
            return Redirect::to('/dashboard');
        } else {
            $this->CheckCoupon();
            return view('admin.pages.coupon_code.coupon_code_add');
        }
    }

    public function CouponCodeSave(Request $request){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $data=$request->all();
            $this->validate($request,[
                'coupon_code_name' => 'bail|required|max:255|min:6',
                'coupon_code_code' => 'bail|required|max:255|min:5',
                'coupon_code_quantity' => 'bail|required'
            ],
            [
                'required' => 'Không được để trống',
                'min' => 'Quá ngắn',
                'max' => 'Quá dài'
            ]);
            $today = date("Y-m-d");
            if (empty($data['coupon_code_from_day']) && empty($data['coupon_code_to_day'])) {
                return Redirect::to('/coupon-code-add')->with('error', 'Thêm không thành công, vui lòng chọn ngày!');
            } else {
                if($data['coupon_code_from_day']>$data['coupon_code_to_day']){
                    return Redirect::to('/coupon-code-add')->with('error', 'Thêm không thành công, ngày bắt đầu phải nhỏ hơn ngày kết thúc!');
                }else{
                    if($data['coupon_code_to_day']<$today){
                        return Redirect::to('/coupon-code-add')->with('error', 'Thêm không thành công, ngày khuyến mãi phải lớn hơn ngày hiện tại!');
                    }else{
                        $get_code=Coupon::where('makhuyenmai_ma', $data['coupon_code_code'])->first();
                        if ($get_code) {
                            return Redirect::to('/coupon-code-add')->with('error', 'Thêm không thành công, mã giảm giá đã tồn tại!');
                        } else {

                            $coupon_code=new Coupon();
                            $coupon_code->makhuyenmai_ten_ma = $data['coupon_code_name'];
                            $coupon_code->makhuyenmai_ma = $data['coupon_code_code'];
                            $coupon_code->makhuyenmai_so_luong = $data['coupon_code_quantity'];
                            $coupon_code->makhuyenmai_loai_ma = $data['coupon_code_type'];
                            $coupon_code->makhuyenmai_gia_tri = $data['coupon_code_value'];
                            $coupon_code->makhuyenmai_ngay_bat_dau = $data['coupon_code_from_day'];
                            $coupon_code->makhuyenmai_ngay_ket_thuc = $data['coupon_code_to_day'];
                            if($data['coupon_code_from_day']>$today){
                                $coupon_code->makhuyenmai_trang_thai = 0;
                            }elseif($data['coupon_code_from_day']==$today){
                                $coupon_code->makhuyenmai_trang_thai = 1;
                            }
                            $coupon_code->save();
                            return Redirect::to('/coupon-code')->with('message', 'Thêm mã giảm giá thành công!');
                        }
                    }
                }
            }
        }
    }

    public function UnactiveCouponCode($coupon_code_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $unactive_coupon_code=Coupon::find($coupon_code_id);
            if (!$unactive_coupon_code) {
                return Redirect::to('/coupon-code')->with('error', 'Không tồn tại mã giảm giá!');
            } else {
                $unactive_coupon_code->makhuyenmai_trang_thai=0;
                $unactive_coupon_code->save();
                return Redirect::to('/coupon-code')->with('message', 'Ẩn thành công!');
            }
        }
    }
    public function ActiveCouponCode($coupon_code_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $active_coupon_code=Coupon::find($coupon_code_id);
            if (!$active_coupon_code) {
                return Redirect::to('/coupon-code')->with('error','Không tồn tại mã giảm giá!');
            } else {
                if($active_coupon_code->makhuyenmai_trang_thai=-1){
                    return Redirect::to('/coupon-code')->with('error','Mã giảm giá hết hạn hoặc hết số lượng!');
                }else{
                    $active_coupon_code->makhuyenmai_trang_thai=1;
                    $active_coupon_code->save();
                    return Redirect::to('/coupon-code')->with('message', 'Hiển thị thành công');
                }
            }
        }
    }

    public function CouponCodeEdit($coupon_code_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $edit_coupon_code=Coupon::find($coupon_code_id);
            if (!$edit_coupon_code) {
                return Redirect::to('/coupon-code')->with('error', 'Không tồn tại mã giảm giá!');
            } else {
                $this->CheckCoupon();
                return view('admin.pages.coupon_code.coupon_code_edit')
                ->with('coupon_code', $edit_coupon_code);
            }
        }
    }

    public function CouponCodeSaveEdit(Request $request,$coupon_code_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $coupon=Coupon::find($coupon_code_id);
            if (!$coupon) {
                return Redirect::to('/coupon-code')->with('error', 'Không tồn tại mã giảm giá!');
            } else {
                $data=$request->all();
                $this->validate($request,[
                    'coupon_code_name' => 'bail|required|max:255|min:6',
                    'coupon_code_code' => 'bail|required|max:255|min:5',
                    'coupon_code_quantity' => 'bail|required'
                ],
                [
                    'required' => 'Không được để trống',
                    'min' => 'Quá ngắn',
                    'max' => 'Quá dài'
                ]);
                $today = date("Y-m-d");
                if (empty($data['coupon_code_from_day']) && empty($data['coupon_code_to_day'])) {
                    return Redirect::to('/coupon-code-edit')->with('error', 'Cập nhật không thành công, vui lòng chọn ngày!');
                } else {
                    if($data['coupon_code_from_day']>$data['coupon_code_to_day']){
                        return Redirect::to('/coupon-code-edit/'.$coupon_code_id)->with('error', 'Cập nhật không thành công, ngày bắt đầu phải nhỏ hơn ngày kết thúc!');
                    }else{
                        if($data['coupon_code_to_day']<$today){
                            return Redirect::to('/coupon-code-edit/'.$coupon_code_id)->with('error', 'Cập nhật không thành công, ngày khuyến mãi phải lớn hơn ngày hiện tại!');
                        }else{
                            $get_code=Coupon::where('makhuyenmai_ma', $data['coupon_code_code'])->whereNotIn('id', [$coupon_code_id])->first();
                            if ($get_code) {
                                return Redirect::to('/coupon-code-edit/'.$coupon_code_id)->with('error', 'Cập nhật không thành công, mã giảm giá đã tồn tại!');
                            } else {
                                $coupon_code=Coupon::find($coupon_code_id);
                                $coupon_code->makhuyenmai_ten_ma = $data['coupon_code_name'];
                                $coupon_code->makhuyenmai_ma = $data['coupon_code_code'];
                                $coupon_code->makhuyenmai_so_luong = $data['coupon_code_quantity'];
                                $coupon_code->makhuyenmai_loai_ma = $data['coupon_code_type'];
                                $coupon_code->makhuyenmai_gia_tri = $data['coupon_code_value'];
                                $coupon_code->makhuyenmai_ngay_bat_dau = $data['coupon_code_from_day'];
                                $coupon_code->makhuyenmai_ngay_ket_thuc = $data['coupon_code_to_day'];
                                if ($data['coupon_code_from_day']>$today) {
                                    $coupon_code->makhuyenmai_trang_thai = 0;
                                } elseif ($data['coupon_code_from_day']==$today) {
                                    $coupon_code->makhuyenmai_trang_thai = 1;
                                }
                                $coupon_code->save();
                                return Redirect::to('/coupon-code')->with('message', 'Cập nhật mã giảm giá thành công!');
                            }
                        }
                    }
                }
            }
        }
    }
    public function CouponCodeDelete($coupon_code_id){
        $this->AuthLogin();
        if(Session::get('admin_role')==3){
            return Redirect::to('/dashboard');
        }else{
            $delete_coupon=Coupon::find($coupon_code_id);
            if (!$delete_coupon) {
                return Redirect::to('/coupon-code')->with('error', 'Không tồn tại mã giảm giá!');
            } else {
                $delete_coupon->delete();
                return Redirect::to('/coupon-code')->with('message', 'Xóa mã giảm giá thành công!');
            }
        }
    }
}
