<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supportticket extends Model
{
    protected $fillable = [
        'user_email',
        'ticket_id',
        'subject',
        'message',
        'ticket_accept_by',
        'ticket_status',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_email','email');
    }
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin','ticket_accept_by','email');
    }
}
