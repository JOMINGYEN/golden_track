<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wards extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'xaphuongthitran_name', 'xaphuongthitran_type', 'quanhuyen_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_xaphuongthitran';
}
