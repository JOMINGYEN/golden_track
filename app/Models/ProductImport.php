<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    public $timestamps = false;
    protected $fillable = [
    	'donnhaphang_ma_don_nhap_hang', 'donnhaphang_ngay_nhap', 'donnhaphang_tong_tien','donnhaphang_trang_thai','nhacungcap_id','admin_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_donnhaphang';

    public function Admin(){
        return $this->belongsTo('App\Models\Admin','admin_id');
    }
    public function Supplier(){
        return $this->belongsTo('App\Models\Supplier','nhacungcap_id');
    }
    public function ProductImportDetail(){
        return $this->hasMany('App\Models\ProductImportDetail');
    }
}
