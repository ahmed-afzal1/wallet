<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localization extends Model
{
    protected $fillable = [
        'language',
        'file',
        'is_default',
        'status',
    ];
    
    public function languageInfo()
    {
        return $this->belongsTo('App\Models\Language','language_code','iso_639');
    }
}
