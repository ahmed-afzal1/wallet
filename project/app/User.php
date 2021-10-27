<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username','email','alt_email','acc_type','verify_token','email_verified_at','password','phone','alt_phone','business_name','api_key','current_balance','website','about','photo','dob','gender','fax','country','address','city','state','postalcode','featured','language','default_currency','transaction_limit','transaction_limit_amount','account_status','is_referral','referrar','referral_commission_amount','referral_commission_amount_status','status','remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function currency(){
        return $this->belongsTo('App\Models\Currency','default_currency','id');
    }

    public function cards(){
        return $this->hasMany('App\Models\Craditcard','user_id');
    }

    public function accountBalance(){
        return $this->hasMany('App\Models\Accountbalance');
    }

    public function withdraws(){
        return $this->hasMany('App\Models\Withdraw');
    }

    public function bankAccounts(){
        return $this->hasMany('App\Models\Bankaccount');
    }

    public function messages(){
        return $this->hasMany('App\Models\AdminUserMessage');
    }

    public function transactions(){
        return $this->hasMany('App\Models\Transaction');
    }
}
