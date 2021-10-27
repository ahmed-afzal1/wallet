<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id','currency_id','name','email','amount','reference','status','token'];

    public $timestamps = false;

    public function getemailAttribute($email)
    {
        return strtolower($email);
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
