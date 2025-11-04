<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'khuyenmai_id', 'sanpham_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_sanphamkhuyenmai';
    public function Discount(){
        return $this->belongsTo('App\Models\Discount','khuyenmai_id');
    }
    public function Product(){
        return $this->belongsTo('App\Models\Product','sanpham_id');
    }
}
