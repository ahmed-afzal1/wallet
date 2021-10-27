<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_email',
        'user_id',
        'amount',
        'cost',
        'currency',
        'transaction_id',
        'method',
        'method_transaction_id',
        'deposit_status',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_email','email');
    }
    
    public function method()
    {
        return $this->belongsTo('App\Models\Paymentgateway','method','id');
    }
    
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','curency','id');
    }
    
    public function craditcard()
    {
        return $this->belongsTo('App\Models\craditcard','craditcard_id','id');
    }

}
