<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'dondathang_ma_don_dat_hang', 'dondathang_ngay_dat_hang','dondathang_ghi_chu','dondathang_tong_tien',
        'dondathang_tinh_trang_thanh_toan','dondathang_trang_thai','dondathang_ma_giam_gia','dondathang_giam_gia','dondathang_phi_van_chuyen','khachhang_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_dondathang';

    public function OrderDetail(){
        return $this->hasMany('App\Models\OrderDetail');
    }
    public function Delivery(){
        return $this->hasOne('App\Models\Delivery');
    }
    public function Customer(){
        return $this->belongsTo('App\Models\Customer','khachhang_id');
    }
    public function Coupon(){
        return $this->belongsTo('App\Models\Coupon','makhuyenmai_id');
    }
    public function TransportFee(){
        return $this->belongsTo('App\Models\TransportFee','phivanchuyen_id');
    }
}
