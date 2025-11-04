<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogNews extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'baiviet_tieu_de', 'baiviet_noi_dung', 'baiviet_anh','baiviet_trang_thai'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_baiviet';
}
