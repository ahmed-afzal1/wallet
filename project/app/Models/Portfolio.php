<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['title','slug','subtitle','details','photo','photo1'];

    public $timestamps = false;
}
