<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Helpers\Autoload;
use App\Models\Currency;
use Auth;
use Session;
use App\User;
use DB;
class ToolsController extends Controller
{





    /*
    |--------------------------------------------------------------------------
    | invalid user page
    |--------------------------------------------------------------------------
    |
    | This method only for  Automatic logout user 
    | when user status is 0 or deactivated 
    |
    */

        public static function invalid()
        {
            
            $register = $userdata = null;
            if (Auth::guard('admin')->check())
            {
                $userdata = Admin::find(Auth::guard('admin')->user()->id);
                $register = null;
            }else if(Auth::guard('web')->user()){
                $userdata = User::find(Auth::guard('web')->user()->id);
                $register = route('register');
            }else{
                if(Session::has('invalid') == false){
                    if(request()->type == 'user'){
                        return redirect()->route('login');
                    }else{
                        return redirect()->route('admin.login');
                    }
                }
            } 
            
            return view('auth.invalid',['register'=>$register,'userdata'=>$userdata]);
        }

        public function balanceconvert($cbalance=null,$ccurrency=null)
        {
            return $this->convert(request()->balance,request()->currency);
        }


        public static  function convert($balance=null,$currency=null)
        {
            $data = array(); 
            
            $currencydata = Currency::find($currency);
            if(Auth::guard('web')->user()){
                $user = User::find(Auth::guard('web')->user()->id);
                
                $data['sign'] = $currencydata->sign;
                $generelsettings =  DB::table('generalsettings')->find(1);
                
                if($generelsettings->min_transaction_amount != 0){
                    $data['mintransaction'] =  round(($generelsettings->min_transaction_amount * $currencydata->rate),2);
                }
                if($generelsettings->max_transaction_amount != 0){
                    $data['maxtransaction'] =  round(($generelsettings->max_transaction_amount * $currencydata->rate),2);
                }
                
                $data['balance'] = floatval($balance);   
                $data['balanceinrate'] = Currency::convertwithcurrencyrate($balance,$currency);   
                $data['balanceinusd'] = Currency::convertwithusd($balance,$currency);   
                
                if($currencydata->sign_position == 'left'){
                    $data['signposition'] = 'left';
                    $data['balancewithsign'] = $currencydata->sign.$balance;
                    $data['mintransactionwithsign'] = $currencydata->sign.$data['mintransaction'];
                    $data['maxtransactionwithsign'] = $currencydata->sign.$data['maxtransaction'];
                }else{
                    $data['signposition'] = 'right';
                    $data['mintransactionwithsign'] = $data['mintransaction'].$currencydata->sign;
                    $data['maxtransactionwithsign'] = $data['maxtransaction'].$currencydata->sign;
                    $data['balancewithsign'] =  $data['balance'].$currencydata->sign;
                }
                return $data;
            }
        }
}