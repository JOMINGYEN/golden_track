<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'anhsanpham_ten', 'sanpham_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_anhsanpham';

    public function Product(){
        return $this->belongsTo('App\Models\Product','sanpham_id');
    }
}
