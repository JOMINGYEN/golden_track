<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public $timestamps = false;
    protected $fillable = [
         'giaohang_nguoi_nhan','giaohang_nguoi_nhan_email','giaohang_nguoi_nhan_so_dien_thoai',
        'giaohang_nguoi_nhan_dia_chi','giaohang_phuong_thuc_thanh_toan','giaohang_tong_tien_thanh_toan',
        'giaohang_trang_thai','giaohang_ma_don_dat_hang','dondathang_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_giaohang';

    public function Order(){
        return $this->belongsTo('App\Models\Order','dondathang_id');
    }
}
