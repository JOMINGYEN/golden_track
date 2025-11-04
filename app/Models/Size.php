<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'size',
        'size_thu_tu',
        'size_trang_thai'
    ];

    protected $primaryKey = 'id';
    protected $table = 'tbl_size';
}
