<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $fillable = [
    	'user_ten','user_email','user_password','loainguoidung_id','user_login_fail','remember_token','updated_at','created_at'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'tbl_users';

     public function Customer(){
        return $this->hasOne('App\Models\Customer');
    }
    public function Admin(){
        return $this->hasOne('App\Models\Admin');
    }
}
