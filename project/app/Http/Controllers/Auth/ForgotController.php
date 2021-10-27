<?php

namespace App\Http\Controllers\Auth;

use App\Classes\GeniusMailer;
use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Generalsetting;
use Illuminate\Support\Str;

class ForgotController extends BaseController
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLoginForm()
    {
      return view('auth.forgot');
    }

    public function forgot(Request $request)
    {
      $gs = Generalsetting::findOrFail(1);
      $input =  $request->all();
      if (User::where('email', '=', $request->email)->count() > 0) {
      // user found
      $user = User::where('email', '=', $request->email)->first();
      $autopass = Str::random(8);
      $input['password'] = bcrypt($autopass);
      $user->update($input);
      $subject = __("Reset Password Request");
      $msg = __("Your New Password is : ").$autopass;
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
      return back()->withSuccess('Your Password Reseted Successfully. Please Check your email for new Password.');
      }
      else{
      // user not found
      return back()->with('Warning',__('No Account Found With This Email.'));    
      }  
    }

}
