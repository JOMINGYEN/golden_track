<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInStock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sanphamtonkho_so_luong_da_ban',
        'sanphamtonkho_so_luong_ton',
        'sanpham_id'
    ];

    protected $primaryKey = 'id';
    protected $table = 'tbl_sanphamtonkho';

    public function Product()
    {
        return $this->belongsTo('App\Models\Product', 'sanpham_id');
    }
    public function Size() {
    return $this->belongsTo(Size::class, 'size_id');
}

}
