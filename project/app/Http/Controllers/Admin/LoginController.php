<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Autoload;
use Validator;
use Session;
use Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);  
    }



    public function showLoginForm()
    {
        return view('admin.login');
    }



    public function login(Request $request)
    {
        //--- Validation Section
        $input = $request->all();
        $rules = [
                    'email'   => 'required|email',
                    'password' => 'required'
                ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            Autoload::agent();
            Autoload::airlog('login Admin');
            if(Auth::guard('admin')->user()->status == 0){
                Session::flash('invalid','admin');
                return response()->json(route('invalid',['admin']));
            }else{
                return response()->json(route('admin.dashboard'));
            }
        }
        // if unsuccessful, then redirect back to the login with the form data
        $msg = array(
            'type' => 'warn',
            'message' => "Credentials Doesn\'t Match !"
        );
        return response()->json(array('errors' => $msg));  
    }



    public function showForgotForm()
    {
        return view('backend.auth.forgot');
    }


    
    public function forgot(Request $request)
    {
        
        Autoload::airlog('Forgot password request');
        $gs = Generalsetting::findOrFail(1);
        $input =  $request->all();
        if (Admin::where('email', '=', $request->email)->count() > 0) {
        // user found
        $admin = Admin::where('email', '=', $request->email)->firstOrFail();
        $autopass = str_random(8);
        $input['password'] = bcrypt($autopass);
        $admin->update($input);
        $subject = "Reset Password Request";
        $msg = "Your New Password is : ".$autopass;
        if($gs->is_smtp == 1)
        {
            $data = [
                    'to' => $request->email,
                    'subject' => $subject,
                    'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);                
        }
        else
        {
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($request->email,$subject,$msg,$headers);            
        }
        return response()->json(__('Your Password Reseted Successfully. Please Check your email for new Password.'));
        }
        else{
        // user not found
        return response()->json(array('errors' => [ 0 => __('No Account Found With This Email.') ]));    
        }  
    }




    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('home');
    }
}
