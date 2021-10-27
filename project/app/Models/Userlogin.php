<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userlogin extends Model
{
    public function user(){
        if($this->user_type == 'user') {
            return $this->belongsTo('App\User');
        }
        if($this->user_type == 'admin'){
            return $this->belongsTo('App\Models\Admin');
        }
    }
}
