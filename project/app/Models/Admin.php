<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Menu;
use App\Models\Userlogin;
class Admin extends Authenticatable
{    
    use Notifiable;
    protected $guard = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','role_id','photo','about','address','remember_token','status'
    ];

    
    public function role()
    {
    	return $this->belongsTo('App\Models\Role');
    }
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function sectionCheck($value){
        $sections = explode(" , ", $this->role->section);
        if (in_array($value, $sections)){
            return true;
        }else{
            return false;
        }
    }

    public function IsSuper(){
        if ($this->id == 1) {
           return true;
        }
        return false;
    }


    public static function loginCheck($id,$type)
    {
        $logindata = [];
        $logindata = Userlogin::where(['user_id'=>$id,'user_type'=>$type])->first();
        if($logindata == null){
            $logindata["login_time"] = "Not Defiend."; 
        }
        return $logindata;
    }

}
