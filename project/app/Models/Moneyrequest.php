<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moneyrequest extends Model
{
    protected $fillable = ['request_id','request_from','request_to','amount','transaction_cost','currency_id','status','referance','costpaid_by'];


    public function sender()
    {
       return $this->belongsTo('App\User','request_to','email');
    }

}
