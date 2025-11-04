<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStore extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'cuahang_tieu_de', 'cuahang_mo_ta', 'cuahang_dia_chi','cuahang_so_dien_thoai',
        'cuahang_email','cuahang_anh','cuahang_thu_tu','cuahang_trang_thai'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_cuahang';
}
