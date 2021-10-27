<?php 


namespace App\Helpers;
use App\Models\Admin;
use App\Models\User;
use App\Models\Generalsetting;
use App\Helpers\Autoload;
use App\Notifications\Verifyuser;
use Mail;
use Auth;
use Notification;
use Illuminate\Notifications\Notifiable;

class Gomail{
    use Notifiable;
    public function __construct()
    {
        $gs = Generalsetting::findOrFail(1);
        Config::set('mail.driver', $gs->mail_host);
        Config::set('mail.host', $gs->smtp_host);
        Config::set('mail.port', $gs->smtp_port);
        Config::set('mail.encryption', $gs->email_encryption);
        Config::set('mail.username', $gs->smtp_user);
        Config::set('mail.password', $gs->smtp_pass);
    }


    public static function sendmail()
    {

        // Notification::route('mail', 'tufayalhossin95@gmail.com')
        //     ->route('nexmo', '5555555555')
        //     ->route('slack', 'no')
        //     ->notify(new Verifyuser('verifyemail'));
            // $user->notify(new Verifyuser('verifyemail'));

            Notification::send('tufayalhossin95@gmail.com', new Verifyuser('verifyemail'));

    //     $gs = Generalsetting::findOrFail(1);
    //     $data = array(
    //         'name' => 'Tufayal Hossin',
    //         'email' => 'tufayalhossin95@gmail.com',
    //     );

    //     $maildata = [
    //         'template' => 'email.registrationconfirmation',
    //         'data' => $data,
    //         'subject' => 'Email verification.',
    //     ];

    //     // $user = $maildata->data;
    //    $con =  0;
    //     if(Mail::send($maildata['template'],['data'=>$data],function($message) use($gs,$maildata){
    //         $message->to('tufayalhossin95@gmail.com');
    //         $message->from($gs->mail_form);
    //         $message->subject($maildata['subject']);
    //     })){
    //         $conf = 1;
    //     }
    //     dd($con);
    }

}