<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'khachhang_ho', 'khachhang_ten', 'khachhang_gioi_tinh','khachhang_email','khachhang_anh','khachhang_so_dien_thoai',
        'khachhang_dia_chi','khachhang_trang_thai','user_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_khachhang';

     public function Order(){
        return $this->hasMany('App\Models\Order');
    }
    public function UserAccount(){
        return $this->belongsTo('App\Models\UserAccount','user_id');
    }
    public function Comment(){
        return $this->hasMany('App\Models\Comment');
    }
}
