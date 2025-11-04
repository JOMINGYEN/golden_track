<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'quanhuyen_name', 'quanhuyen_type', 'tinhthanhpho_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_quanhuyen';

}
