<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'dongsanpham_ten', 'dongsanpham_mo_ta', 'dongsanpham_anh','dongsanpham_trang_thai'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_dongsanpham';
    public function product(){
        return $this->hasMany('App\Models\Product');
    }
}
