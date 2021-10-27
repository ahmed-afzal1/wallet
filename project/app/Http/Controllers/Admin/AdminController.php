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

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);  
    }




    /*
    |--------------------------------------------------------------------------
    | Login form
    |--------------------------------------------------------------------------
    |
    | This method only for  view Admin login form, It called by {admin.login} route.
    |
    */

    public function showLoginForm()
    {
        return view('auth.admin.login');
    }





    /*
    |--------------------------------------------------------------------------
    | Login submit
    |--------------------------------------------------------------------------
    |
    | This method only for  Admin login,  It called by {admin.login.submit} route.
    |
    */

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







    /*
    |--------------------------------------------------------------------------
    | Forgot Form
    |--------------------------------------------------------------------------
    |
    | This method only for  Admin Forgot,  It called by {admin.forgot} route.
    |
    */

    public function showForgotForm()
    {
        return view('backend.auth.forgot');
    }




    /*
    |--------------------------------------------------------------------------
    | Forgot Form submit
    |--------------------------------------------------------------------------
    |
    | This method only for submit Admin Forgot form,  It called by {admin.forgot.submit} route.
    |
    */
    
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





    /*
    |--------------------------------------------------------------------------
    | Admin Logout
    |--------------------------------------------------------------------------
    |
    | This method only for Logout Admin,  It called by {admin.logout} route.
    |
    */
    public function logout()
    {
        // Autoload::airlog('Logout user');
        // Autoload::logoutAgent();
        Auth::guard('admin')->logout();
        return redirect()->route('home');
    }

    
}
