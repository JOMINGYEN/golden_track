<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlideShow extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'slidequangcao_tieu_de', 'sildequangcao_noi_dung', 'sildequangcao_anh','slidequangcao_lien_ket','slidequangcao_thu_tu',
        'slidequangcao_trang_thai'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_slidequangcao';
}
