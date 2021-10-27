<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Craditcard extends Model
{
    protected $fillable = [
            "user_id",
            "user_email",
            "bank_name",
            "card_holder_name",
            "card_number",
            "card_type",
            "card_cvc",
            "month",
            "year",
            "card_currency",
            "is_primary",
            "is_approved",
            "status",
        ];

    public function user()
    {
        return $this->belongsTo('App\User','user_email','email');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','card_currency','id');
    }
    
    public function userr(){
        return $this->belongsTo('App\User');
    }

}
