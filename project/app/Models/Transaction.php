<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ["user_id","transaction_type","transaction_id","is_api","amount","transaction_cost","transaction_currency","costpay","sender","sender_is","receiver","receiver_is","deposit_method","deposit_transid","deposit_chargeid","manual_number","reason","method","method_transaction_id","reffer_url","expected_delivery_date","status"];


    public function senderuser(){
        return $this->belongsTo('App\User','sender','email');
    }

    public function receiveruser(){
        return $this->belongsTo('App\User','receiver','email');
    }

    public function currency(){
        return $this->belongsTo('App\Models\Currency','transaction_currency','id');
    }

    public function bankaccount(){
        return $this->belongsTo('App\Models\Bankaccount','method_transaction_id','id');
    }

    public function craditcard(){
        return $this->belongsTo('App\Models\Craditcard','method_transaction_id','id');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }


}


