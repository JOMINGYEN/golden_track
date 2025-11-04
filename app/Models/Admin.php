<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'admin_ho', 'admin_ten', 'admin_gioi_tinh','admin_email','admin_so_dien_thoai'
        ,'admin_dia_chi','admin_id','admin_anh','admin_ngay_vao_lam','admin_trang_thai','user_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_admin';

    public function ProductImport(){
        return $this->hasMany('App\Models\ProductImport');
    }
    public function UserAccount(){
        return $this->belongsTo('App\Models\UserAccount','user_id');
    }
}
