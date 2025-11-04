<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'makhuyenmai_ma', 'makhuyenmai_ten_ma', 'makhuyenmai_so_luong','makhuyenmai_loai_ma','makhuyenmai_gia_tri'
        ,'makhuyenmai_ngay_bat_dau','makhuyenmai_ngay_ket_thuc','makhuyenmai_user','makhuyenmai_trang_thai'

    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_makhuyenmai';

    public function Order(){
        return $this->hasOne('App\Models\Order');
    }
}
