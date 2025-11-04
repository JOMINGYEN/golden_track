<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sanpham_id',
        'chitietdondathang_ma_don_dat_hang',
        'chitietdondathang_don_gia',
        'chitietdondathang_so_luong',
        'dondathang_id'
    ];

    protected $primaryKey = 'id';
    protected $table = 'tbl_chitietdondathang';

    public function Product()
    {
        return $this->belongsTo('App\Models\Product', 'sanpham_id');
    }

    public function Order()
    {
        return $this->belongsTo('App\Models\Order', 'dondathang_id');
    }
}
