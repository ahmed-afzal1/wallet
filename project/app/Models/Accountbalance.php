<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accountbalance extends Model
{
   protected $fillable = ['user_id','user_email','balance','currency_id','status'];



    public function currency(){
        return $this->belongsTo("App\Models\Currency");
    }

    public function user(){
        return $this->belongsTo('App\User');
    }


}
