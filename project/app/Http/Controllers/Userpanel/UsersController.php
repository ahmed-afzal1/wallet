<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Generalsetting as GS;
use App\Models\Accountbalance as AB;
use App\Models\Currency;
use App\Helpers\Autoload;
use App\Mail\Gomail;
use App\User;
use Mail;
use Auth;
use Validator;
use Session;
use App\Models\Generalsetting;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Frontend\BaseController;
use Illuminate\Support\Str;

class UsersController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => ['logout','resentverifylink']]);
    }

    function goRandomString($length = 10) {
        $characters = 'abcdefghijklmnpqrstuvwxyz123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }


    /*
    |--------------------------------------------------------------------------
    | User login view
    |--------------------------------------------------------------------------
    |
    | This is user login page view, this method called by {user-login-view} route. 
    |
    */
    public function showLoginForm()
    {
        return view('userpanel.login');
    }


    /*
    |--------------------------------------------------------------------------
    | User register view
    |--------------------------------------------------------------------------
    |
    | This is user register page view, this method called by {user-register-view} route. 
    |
    */
    public function showRegisterForm()
    {

        return view('userpanel.register');
    }


    public function showRegisterTypeForm()
    {
        return view('userpanel.registertype');
    }


    /*
    |--------------------------------------------------------------------------
    | user login function
    |--------------------------------------------------------------------------
    |
    | This is user login function, this method called by {user-login} route. 
    |
    */
    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email'   => 'required|email',
            'password' => 'required'
          ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        // Attempt to log the user in
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

            if(Auth::guard('web')->user()->email_verified_at == null)
            {
                Auth::guard('web')->logout();

                return response()->json(array('errors' => ['Your Email is not Verified!']));     
            }

            if(Auth::guard('web')->user()->status == 0){
                 return response()->json(array('errors' => ["Your Account Has Been Banned.!"])); 
            }
            return response()->json(route('user-dashboard'));
        }else{
            return response()->json(array('errors' => ['Credentials Doesn\'t Match !']));  
        }

    
    }



    /*
    |--------------------------------------------------------------------------
    | User Register function
    |--------------------------------------------------------------------------
    |
    | This is user login function, this method called by {user-login} route. 
    |
    */
    public function register(Request $request,$type)
    {

        $rules = [
            'name'   => 'required',
            'email'   => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'country' => 'required',
            'phone' => 'required',
        ];
                
        $customs = [
            'name.required' => 'Name field is required',
            'email.required' => 'Email field is required',
            'email.unique' => 'Email should be unique',
            'password.required' => 'Password field is required',
            'password.min' => 'Minimum 6 character required',
            'country.required' => 'Country field is required',
            'phone.required' => 'Phone field is required',
        ];

        $validator = Validator::make($request->all(), $rules,$customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        $data = array(
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
            'acc_type' => $type,
            'password' => Hash::make($request->password),
            'created_at' => date('Y-m-d'),
        );


        // genarate username
        $username = explode('@',$request->email);
        $username = str_replace('.','',$username[0]);

        
        if(User::where('username',$username)->exists()){
            $data['username']  = $username.rand(0,99999);
        }else{
            $data['username'] = $username;
        }

        if($request->type == 'business'){
            $data['api_key'] = Str::random(6).$this->goRandomString(15).Str::random(6);
        }



        $currency = Currency::whereIsDefault(1)->first();
        $gs = GS::first();
        $defaultcurrency = $gs->currency;

        if($currency){
            $data['default_currency'] = $currency->id;
        }else{
            $data['default_currency'] = $defaultcurrency->id;
        }

        if($userId = User::insertGetId($data)){
            AB::insert(array(
                'user_id' => $userId,
                'user_email' => $request->email,
                'balance' => 0,
                'currency_id' => $currency->id,
                'created_at' => date('Y-m-d'),
            ));

            
            $gs = Generalsetting::find(1);
            
            if($gs->is_verification_email == 1){
                $email = $data['email'];
                $name = $data['name'];
                $verify = base64_encode($email.'geniusocean'.$name);
                $verify = base64_encode($verify);
                
    
                $subject = "Verify your email address.";
                $to = $request->email;
    	        $msg = "Dear Customer,<br> We noticed that you need to verify your email address. <a href=".route('user-verify',[$verify]).">Simply click here to verify. </a>";
    
                if($gs->is_smtp == 1)
                {
                    $data = [
                        'to' => $to,
                        'subject' => $subject,
                        'body' => $msg,
                    ];
        
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                   $to = $request->email;
                   $subject = "Verify your email address.!!";
                   $msg = "Dear Customer,<br> We noticed that you need to verify your email address. <a href=".route('user-verify',[$verify]).">Simply click here to verify. </a>";
                   $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);            
                }
                
          	    return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');

            }else{
                $user = User::findOrFail($userId);
                $user->update([
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ]);
                Auth::login($user);

                return response()->json(1);
            }


        }
        return response()->json("Something is wrong. Please try again.");     
    }


    

    /*
    |--------------------------------------------------------------------------
    | User verify function
    |--------------------------------------------------------------------------
    |
    | This is user verify function, this method called by {user-verify} route. 
    |
    */
    public function verify($user)
    {
        $user = base64_decode($user);
        $user = base64_decode($user);
        $user = explode('geniusocean',$user);
        $data = array(
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verify_token' => base64_encode($user[0]),
        );


        $userdata = User::where('email',$user[0])->first();

        if($userdata != null){
            if($userdata->email_verified_at != null && $userdata->verify_token != null){
                Session::flash('Warning','Already Verified.');
                return redirect()->route('user.register.form',['type'=>$userdata->acc_type]);
            }else{
                User::where('email',$user[0])->update($data);
                Auth::login($userdata);
                return redirect()->route('user-dashboard');
            }
            
        }else{
            Session::flash('Warning','Invalid user! Register new one.');
            return redirect()->route('user.register.form',['type'=>$userdata->acc_type]);
        }
        return view('userpanel.verifymessage', $data);
    }




    /*
    |--------------------------------------------------------------------------
    | Resent verify link
    |--------------------------------------------------------------------------
    |
    | This is resent verify link function, this method called by {resentverifylink} route. 
    |
    */
    public function resentverifylink()
    {
        $user = Auth::guard('web')->user();
        if($user != null){
            $data = array(
                'name' => $user->name,
                'email' => $user->email,
            );
            Mail::send('email.registrationconfirmation',['data'=>$data],function($message) use($user){
                    $message->to($user->email);
                    $message->from('abcx@go.com');
                    $message->subject('Email verification.');
                });
            return 1;
        }else{
            return redirect()->route('home');
        }
    }




    /*
    |--------------------------------------------------------------------------
    | User logout function
    |--------------------------------------------------------------------------
    |
    | This is user login function, this method called by {logout} route. 
    |
    */
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('home');
    }
}
