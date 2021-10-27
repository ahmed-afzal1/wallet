<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Countries;

class Bankaccount extends Model
{
    protected $fillable = [
        'account_type',
        'country',
        'bank_name',
        'bank_account_currency',
        'account_name',
        'account_number',
        'swift_code',
        'routing_number',
        'is_primary',
        'is_approved',
        'status',
    ];

    public function user_bank(){
        return $this->belongsTo('App\User');
    }

    public function user()
    {
        return  $this->belongsTo('App\User','user_email','email');
    }
    public function currency()
    {
        return  $this->belongsTo('App\Models\Currency','bank_account_currency','id');
    }
    public function countryinfo()
    {
        return $this->belongsTo('App\Models\Countries','country','id');
    }
}
