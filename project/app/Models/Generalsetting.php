<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generalsetting extends Model
{
    protected $fillable = ['base_currency',"status",'is_contact','smtp_port'];

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','base_currency','id');
    }
    
    public function getSociallinkAttribute($value)
    {
        return json_decode($value);
    }

}
