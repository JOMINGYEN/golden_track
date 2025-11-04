<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'binhluan_ten_nguoi_danh_gia', 'binhluan_noi_dung', 'binhluan_diem_danh_gia',
        'binhluan_ngay_danh_gia','binhluan_id_phan_hoi','binhluan_trang_thai',
        'khachhang_id','admin_id','sanpham_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_binhluan';

    public function Product(){
        return $this->belongsTo('App\Models\Product','sanpham_id');
    }
    public function Customer(){
        return $this->belongsTo('App\Models\Customer','khachhang_id');
    }
}
