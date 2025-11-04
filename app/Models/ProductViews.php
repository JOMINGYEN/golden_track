<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductViews extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'viewssanpham_views','viewssanpham_ngay_xem','sanpham_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_viewssanpham';

     public function Product(){
        return $this->belongsTo('App\Models\Product','sanpham_id');
    }
}
