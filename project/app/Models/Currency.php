<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Accountbalance;
use App\Models\Generalsetting as GS;
use App\User;
use Auth;
use DB;
class Currency extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'code',
        'sign',
        'rate',
        'fixed_withdraw_charge',
        'percentage_withdraw_charge',
        'minimum_withdraw_amount',
        'maximum_withdraw_amount',
        'fixed_deposit_charge',
        'percentage_deposit_charge',
        'minimum_deposit_amount',
        'maximum_deposit_amount',
        'fixed_transaction_charge',
        'percentage_transaction_charge',
        'transaction_limit_amount',
        'minimum_transaction_amount',
        'maximum_transaction_amount',
        'maximum_transaction_amount',
        'minimum_request_money',
        'maximum_request_money',
        'money_request_per_day',
        'status'
    ];



    /*
    |--------------------------------------------------------------------------
    | Country relation
    |--------------------------------------------------------------------------
    |
    | This method only for get country information by the currency table country_id
    |
    */
    public function country()
    {
        return $this->belongsTo('App\Models\Countries');
    }



    public function currencyBalance(){
        return $this->hasOne('App\Models\Accountbalance');
    }


    /*
    |--------------------------------------------------------------------------
    | Total balance
    |--------------------------------------------------------------------------
    |
    | This method only for get total balance amount by currency 
    |
    */
    public function totalaccountbalance($currency_id)
    {
        return number_format(Accountbalance::where('currency_id',$currency_id)->sum('balance'),2);
    }




    /*
    |--------------------------------------------------------------------------
    | Currency sign position
    |--------------------------------------------------------------------------
    |
    | This method only for place the position of currency sign left or right with balance
    |
    */
    public static function placesign($balance, $currency_id)
    {
        $currency = Currency::find($currency_id);
        if($currency->sign_position == 'left'){
            return $currency->sign.$balance;
        }else{
            return $balance.$currency->sign;
        }
    }



    



    /*
    |--------------------------------------------------------------------------
    | Currency convert with user rate
    |--------------------------------------------------------------------------
    |
    | This method only for convert balance with currency rate
    |
    */
    public static function convertwithrate($balance,$sign = false)
    {

        if(Auth::guard('web')->user()){
            $user = User::find(Auth::guard('web')->user()->id);
            $rate = $user->currency->rate;
        }

        $balance = floatval($balance) * $rate;          
        if($sign == true){
            if($user->currency->sign_position == 'left'){
                return $user->currency->sign.$balance;
            }else{
                return $balance.$user->currency->sign;
            }
        }
        return $balance;
    }



    /*
    |--------------------------------------------------------------------------
    | Currency convert with currency rate
    |--------------------------------------------------------------------------
    |
    | This method only for convert balance with currency rate
    |
    */
    public static function convertwithcurrencyrate($balance,$currency = null,$sign = false)
    {

        $info = Currency::find($currency);
        $balance = floatval($balance);          

        if($sign == true){
            if($info->sign_position == 'left'){
                return $info->sign.$balance;
            }else{
                return $balance.$info->sign;
            }
        }
        return $balance;
    }








    /*
    |--------------------------------------------------------------------------
    | Currency convert with admin rate
    |--------------------------------------------------------------------------
    |
    | This method only for convert balance with currency rate
    |
    */
    public static function convertwithadminrate($balance,$sign = false)
    {
        if(Auth::guard('admin')->user()){
            $data =  Currency::where('id',1)->first();
            $rate = $data->rate;
        }
        $gs = GS::find(1);
        $balance = round((floatval($balance) * $gs->currency->rate),2);
        if($sign == true){
            if($gs->currency->sign_position == 'left'){
                return $gs->currency->sign.$balance;
            }else{
                return $balance.$gs->currency->sign;
            }
        }
        return $balance;
    }






    /*
    |--------------------------------------------------------------------------
    | Currency convert with usd
    |--------------------------------------------------------------------------
    |
    | This method only for convert balance with currency rate
    |
    */
    public static function convertwithusd($value,$currency = null,$sign = false)
    {
        $data =  Currency::where('id',$currency)->first();     
        $balance = $value / $data->rate;
        if($sign == true){
            if($data->sign_position == 'left'){
                return $data->sign.$balance;
            }else{
                return $balance.$data->sign;
            }
        }

        return $balance;
    }

}
