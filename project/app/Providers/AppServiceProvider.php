<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Helpers\TransactionAutoComplete as TA;
use App\Helpers\Autoload;
use App\Helpers\Gomail;
use App\Models\Localization;
use App\Models\Paymentgateway;
use Auth;
use View;
use App\Http\Controllers\ToolsController as Tools;
use Illuminate\Http\Request;
use App\Models\Accountbalance as AB;
use App\Models\Admin;
use App\Models\Userlogin;
use App\Models\Analytic;
use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Currency;
use App\Models\Withdraw;
use App\Models\Craditcard;
use App\Models\Bankaccount;
use App\Models\Generalsetting as GS;
use App\Models\Supportticket as ST;
use App\Models\Role;
use App\User;
use Session;
use Route;
use DB;
use Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public $user = null;
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() 
    {   

        Schema::defaultStringLength(191);


        view()->composer('*',function($settings){
            $admininfo = $userinfo = $roles = $unseenmessage = $avblanguage = null;
            $generelsettings =  GS::find(1);
            $deposittotal = Deposit::where('deposit_status','pending')->count();
            $tickettotal = ST::where('ticket_status','pending')->count();
            $withwrawtotal = Withdraw::where('withdraw_status','pending')->count();

            if (Auth::guard('admin')->check())
            {
                $unseenmessage = ST::where('seen',0)->get();
                $admininfo = Admin::findOrFail(Auth::guard('admin')->user()->id);
                $roles = Role::get();
                if($admininfo->role_id != 0){
                    $admininfo['role'] = Role::where('id',$admininfo->role_id)->first();
                    Auth::guard('admin')->user()['role'] = $admininfo['role'];
                }else{
                    $admininfo['role'] = null;
                    Auth::guard('admin')->user()['role'] = null;
                }
            }
            
            if(Auth::guard('web')->user()){
                $userinfo = Auth::guard('web')->user();
                $avblanguage =  Localization::where('status',1)->get();
                $accountbalance = AB::where('user_email',Auth::guard('web')->user()->email)->get();
                if($accountbalance != null){
                    $userinfo['acc_balance'] = $accountbalance;
                    $dcurrency = Auth::guard('web')->user()->default_currency;
                    if(Auth::guard('web')->user()->default_currency == null){
                        $dcurrency = $generelsettings->default_currency;
                    }
                    $defaultcurrency = Currency::where('id',$dcurrency)->first();

                    $accbalance = AB::where(['user_email'=>Auth::guard('web')->user()->email,'currency_id'=>$defaultcurrency->id])->first();
                    
                    if($accbalance == null){
                        $accbalance = AB::where(['user_email'=>Auth::guard('web')->user()->email,'currency_id'=>1])->first();
                    }

                    if($defaultcurrency->sign_position == 'left'){
                        $userinfo['def_acc_balance'] = $defaultcurrency->sign.round($accbalance->balance,2);
                    }else{
                        $userinfo['def_acc_balance'] =round($accbalance->balance,2).$defaultcurrency->sign;
                    }
                }
            }

            if (Auth::guard('admin')->check())
            {
                $userid = Admin::find(Auth::guard('admin')->user()->id);
                if($userid->status == 0){
                    Auth::guard('admin')->logout();
                }
            }
            if(Auth::guard('web')->user()){
                $userid = User::find(Auth::guard('web')->user()->id);
                if($userid->status == 0){
                    Auth::guard('web')->logout();
                }
            }  
           
            
            $paystackGateway = Paymentgateway::where('keyword','Paystack')->first();

            $paystackInformation = json_decode($paystackGateway->information);

            
            $settings->with('paystackInformation',$paystackInformation);
            $settings->with('userinfo',$userinfo);
            $settings->with('admininfo',$admininfo);
            $settings->with('gs',$generelsettings);
            $settings->with('roles',$roles);
            $settings->with('deposittotal',$deposittotal);
            $settings->with('withwrawtotal',$withwrawtotal);
            $settings->with('tickettotal',$tickettotal);
            $settings->with('unseenmessage',$unseenmessage);
            $settings->with('avblanguage',$avblanguage);
        });
    }
}
