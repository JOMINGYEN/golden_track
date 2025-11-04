<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'sanpham_ma_san_pham', 'sanpham_ten','sanpham_gia_ban', 'sanpham_mo_ta','sanpham_anh','sanpham_nguoi_su_dung','sanpham_mau_sac',
        'sanpham_tinh_nang','sanpham_noi_san_xuat','sanpham_phu_kien','sanpham_chat_lieu','sanpham_bao_hanh',
        'sanpham_trang_thai','loaisanpham_id','thuonghieu_id','dongsanpham_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_sanpham';

    public function ProductType(){
        return $this->belongsTo('App\Models\ProductType','loaisanpham_id');
    }
    public function Brand(){
        return $this->belongsTo('App\Models\Brand','thuonghieu_id');
    }
    public function Collection(){
        return $this->belongsTo('App\Models\Collection','dongsanpham_id');
    }
    public function ProductImportDetail(){
        return $this->hasMany('App\Models\ProductImportDetail');
    }
    public function ProductInStock(){
        return $this->hasMany('App\Models\ProductInStock');
    }
    public function ProductDiscount(){
        return $this->hasMany('App\Models\ProductDiscount');
    }
    public function OrderDetail(){
        return $this->hasMany('App\Models\OrderDetail');
    }
    public function Comment(){
        return $this->hasMany('App\Models\Comment');
    }
    public function ProductImage(){
        return $this->hasMany('App\Models\ProductImage');
    }
    public function ProductViews(){
        return $this->hasMany('App\Models\ProductViews');
    }
}
