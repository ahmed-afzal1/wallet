<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['title', 'details'];
    public $timestamps = false;
}
