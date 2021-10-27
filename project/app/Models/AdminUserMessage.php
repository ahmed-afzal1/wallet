<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserMessage extends Model
{
    protected $fillable = ['supportticket_id','message','user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
