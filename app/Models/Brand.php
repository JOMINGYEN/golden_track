<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'thuonghieu_ten', 'thuonghieu_mo_ta', 'thuonghieu_anh','thuonghieu_trang_thai'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_thuonghieu';
    public function product(){
        return $this->hasMany('App\Models\Product');
    }
}
