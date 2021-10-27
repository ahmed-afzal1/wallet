<?php 


namespace App\Helpers;

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
use App\Models\Localization;
use App\Models\Generalsetting as GS;
use App\Models\Supportticket as ST;
use App\Models\Role;
use App\User;
use Session;
use Route;
use View;
use Auth;
use DB;
use Log;


class  Autoload {
    public $data = null;
    public $user = null;




    /*
    |--------------------------------------------------------------------------
    | Automatic load data for every pages
    |--------------------------------------------------------------------------
    |
    | This method only for  Automatic load data for every pages
    |
    */

    public static function defaultData(){
        View::composer('*', function ($view)
        {
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
           
            
            return  view()->share([
                'userinfo'=>$userinfo,
                'admininfo'=>$admininfo,
                'gs'=>$generelsettings,
                'roles'=>$roles,
                'deposittotal'=>$deposittotal,
                'withwrawtotal'=>$withwrawtotal,
                'tickettotal'=>$tickettotal, 
                'unseenmessage'=>$unseenmessage,
                'avblanguage'=>$avblanguage,
                ]);
        });
    }







    /*
    |--------------------------------------------------------------------------
    | logout user/admin
    |--------------------------------------------------------------------------
    |
    | This method only for  Automatic logout user/admin when user status 0
    |
    */
    public static function logout(){
        View::composer('*', function ($view)
        {
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
        });
    }
    









    /*
    |--------------------------------------------------------------------------
    | User Agent Information
    |--------------------------------------------------------------------------
    |
    | This method only for User agent information, an user visit form where, which device, browser, ip etc
    | 
    |
    */
    public static function agent()
    {
        
            $getInfo = new UserAgents();

            $userInfo = array();
            $ip = $getInfo->getIp();

            $location = "Unknown";

            $browser = $getInfo->showInfo('browser')." ".$getInfo->showInfo('version');
            $userInfo['os'] = $getInfo->showInfo('os');
            $userInfo['browser'] = $browser;
            $os =  $userInfo['os'];


            if ($ip != "::1"){
                $location = $getInfo->getLocation($ip);
            }
            $userInfo['location'] = $location;
            $userInfo['ip'] =$ip;
            $userInfo['login_time'] = date('Y-m-d H:i:s');
            

            $user = null;
            if (Auth::guard('admin')->check())
            {
                $user= Auth::guard('admin')->user();
                $userInfo['user_type'] = 'admin';
            }
            if(Auth::guard('web')->user()){
                $user = Auth::guard('web')->user();
                $userInfo['user_type'] = 'user';
             }
            $userInfo['user_id'] = $user->id;
            $userInfo['login_time'] = date('Y-m-d H:i:s');
            $userInfo['created_at'] = date('Y-m-d');

            Userlogin::insert($userInfo);


            if (Analytic::where('type','browser')->where('info',$browser)->count() > 0){
                $browserup = Analytic::where('type','browser')->where('info',$browser)->first();
                $browsers['total'] = $browserup->total + 1;
                $browserup->update($browsers);
            }else{
                $browsers = new Analytic();
                $browsers->type = 'browser';
                $browsers->info = $browser;
                $browsers->total = 1;
                $browsers->save();
            }

            if (Analytic::where('type','os')->where('info',$os)->count() > 0){
                $oss = Analytic::where('type','os')->where('info',$os)->first();
                $browserss['total'] = $oss->total + 1;
                $oss->update($browserss);
            }else{
                $browsers = new Analytic();
                $browsers->type = 'os';
                $browsers->info = $os;
                $browsers->total = 1;
                $browsers->save();
            }
            return true;


    }







    /*
    |--------------------------------------------------------------------------
    | User Agent logout Information
    |--------------------------------------------------------------------------
    |
    | This method only for set User logout time 
    | 
    |
    */
    public static function logoutAgent()
    {
        
            $user = null;
            if (Auth::guard('admin')->check())
            {
                $user= Auth::guard('admin')->user();
                $userInfo['user_type'] = 'admin';
            }
            if(Auth::guard('web')->user()){
                $user = Auth::guard('web')->user();
                $userInfo['user_type'] = 'user';
            }
            $logedinUserData = null;
            if($user != null){
                $userInfo['user_id'] = $user->id;
                $where = array('user_id'=>1,'user_type'=>$userInfo['user_type']);
                $logedinUserData = Userlogin::where($where)->latest('login_time')->orderBy('id','desc')->limit(2)->get();
            }
            if($logedinUserData != null){
                if(count($logedinUserData) == 2){
                    if($logedinUserData[1]->logout_time == null){
                        $updateAutomaticDestroyed = array('logout_time'=>'Session Expired');
                        Userlogin::where('id',$logedinUserData[1]->id)->update($updateAutomaticDestroyed);
                    }
                }
                $logoutTime = array('logout_time'=>date('Y-m-d H:i:s'));
                Userlogin::where('id',$logedinUserData[0]->id)->update($logoutTime);
            }
            return true;
    }

    




    /*
    |--------------------------------------------------------------------------
    | User privilege Information
    |--------------------------------------------------------------------------
    |
    | This method only for set User logout time 
    | 
    |
    */
    public static function role()
    {  
        $roles = Role::first();
        return $roles;
    }

    




    /*
    |--------------------------------------------------------------------------
    | System custom log
    |--------------------------------------------------------------------------
    |
    | This method only for storage user's activities day by day, the function is work by called in any class,
    | this data is see only admin ,
    | create new file daily, the file name is defiend with date
    |
    */
    public static function airlog($data)
    {

        // check here the log file exixts or not 
        // and create new file daily, the file name is defiend with date 
        if(!file_exists(base_path('storage/activities'))){
            fopen(base_path('storage/activities'),'w');
            if(!file_exists(base_path('storage/activities/'.date('Ymd').'.log'))){
                fopen(base_path('storage/activities/').date('Ymd').'.log','w');
            }
        }else{
            if(!file_exists(base_path('storage/activities/'.date('Ymd').'.log'))){
                fopen(base_path('storage/activities/').date('Ymd').'.log','w');
            }
        }
        

        $data = str_replace('[','{',$data);
        $data = str_replace(']','}',$data);
        // check user's type 
        $usersdata = null;
        $user = null;
        if (Auth::guard('admin')->check())
        {
            $usersdata = Auth::guard('admin')->user();
        }
        if(Auth::guard('web')->user()){
            $usersdata = Auth::guard('web')->user();
        }
        $info = null;
        // write user's information by default
        if($usersdata != null){
            $info = '[Username: '.$usersdata->name.'] [Email: '.$usersdata->email.']';
        }

        // check array type, array associative or index
        function isAssoc(array $arr)
        {
            if (array() === $arr) return false;
            return array_keys($arr) !== range(0, count($arr) - 1);
        }
        // make data to string if data is an array 
        if(is_array($data)){
            if(isAssoc($data)){
                foreach($data as $key => $val){
                    $info .= ' ['.$key.': '.$val.'] ';
                }
            }else{
                foreach($data as $key => $val){
                    $info .= ' ['.$val.'] ';
                }
            }
        }else{
            $info =  $info.' '.$data;
        }
       
        // Call to data storage in log file 
        Log::channel('airlog')->info($info);

    }

    




    /*
    |--------------------------------------------------------------------------
    | genarel setings data
    |--------------------------------------------------------------------------
    |
    | This method only for call genarel settings data
    |
    */
    public static function gs()
    {
        return GS::find(1);
    }






    /*
    |--------------------------------------------------------------------------
    | genarel setings data
    |--------------------------------------------------------------------------
    |
    | This method only for call genarel settings data
    |
    */
    public static function incrementDate($day,$date=null)
    {
        if($date == null){
            $date = date("Y-m-d");
        }
        $totime = strtotime($day." day", strtotime($date));
        $new_date = date("Y-m-d", $totime);
        return $new_date;
    }


    public static function transactionId()
    {
        $transactionID = date('ymd').rand(1,999999);
        while(Transaction::where('transaction_id',$transactionID)->exists() || Deposit::where('transaction_id',$transactionID)->exists() || Withdraw::where('transaction_id',$transactionID)->exists()){
            $transactionID = date('ymd').rand(1,999999);
        }
        return $transactionID;
    }





    // alphaNumeric id genarator
    public static  function alphaNumeric($length,$cond = null)
  {
        if($cond = 'upper'){
          $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }elseif($cond = 'lower'){
            $chars = "1234567890abcdefghijklmnopqrstuvwxyz";
        }else{
            $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        }

      $charLen = strlen($chars)-1;
      $id  = '';

      for ($i = 0; $i < $length; $i++) {
              $id .= $chars[mt_rand(0,$charLen)];
      }
      return ($id);
  }



    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }



    public static function proPic($photo = null,$type = 'admin')
    {
        if($photo != null){
            if($type == 'admin'){
                return asset('assets/backend/images/admins/'.$photo);
            }else if($type == 'user'){

                return asset($photo);
            }
        }else{
            return asset('assets/backend/images/users/user.png');
        }
    }

    // profile completencss check 
    public static function procom($photo = null,$type = 'admin')
    {
      $userdata = Auth::guard('web')->user();
      $complete = 15;
        if($userdata->alt_phone != null){
            $complete += 2;
        }
        if($userdata->alt_email != null){
            $complete += 2;
        }
        if($userdata->featured != null){
            $complete += 2;
        }
        if($userdata->website != null){
            $complete += 2;
        }
        if($userdata->fax != null){
            $complete += 2;
        }
        if($userdata->dob != null){
            $complete += 2;
        }
        if($userdata->gender != null){
            $complete += 2;
        }

        if($userdata->about != null){
            $complete += 5;
        }
        if($userdata->photo != null){
            $complete += 5;
        }
        if($userdata->address != null){
            $complete += 5;
        }
        if($userdata->city != null){
            $complete += 5;
        }
        if($userdata->state != null){
            $complete += 5;
        }
        if($userdata->postalcode != null){
            $complete += 5;
        }

        if(Craditcard::where('user_email',$userdata->email)->exists()){
            $complete += 15;
        }
        
        if(Bankaccount::where('user_email',$userdata->email)->exists()){
            $complete += 15;
        }

        return $complete;
    }



}