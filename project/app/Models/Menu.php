<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name','slug','options','status'];


    public function getOptionsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }
}
