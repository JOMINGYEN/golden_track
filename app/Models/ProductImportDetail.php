<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImportDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'chitietnhap_so_luong_nhap',
        'chitietnhap_gia_nhap',
        'sanpham_id',
        'donnhaphang_id',
        'chitietnhap_ma_don_nhap_hang'
    ];

    protected $primaryKey = 'id';
    protected $table = 'tbl_chitietnhap';

    public function Product()
    {
        return $this->belongsTo('App\Models\Product', 'sanpham_id');
    }

    public function ProductImport()
    {
        return $this->belongsTo('App\Models\ProductImport', 'donnhaphang_id');
    }
}
