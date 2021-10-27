<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
   
    public function user_withdraw(){
        return $this->belongsTo('App\User');
    }
    
    public function user(){
        return $this->belongsTo('App\User','user_email','email');
    }

    public function currency(){
        return $this->belongsTo('App\Models\Currency','transaction_currency','id');
    }
    
    public function accountNumber(){
        return $this->belongsTo('App\Models\Bankaccount','account_number','id');
    }
    

}
