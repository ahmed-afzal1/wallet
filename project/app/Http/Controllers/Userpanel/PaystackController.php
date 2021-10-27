<?php

namespace App\Http\Controllers\Userpanel;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Accountbalance;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Generalsetting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaystackController extends Controller
{
    public function store(Request $request)
    {
        $currency = Currency::findOrFail($request->currency);
        $fixedDepositCharge = $currency->fixed_deposit_charge;
        $percentageDepositCharge = $currency->percentage_deposit_charge;

        $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
        $cost = $fixedDepositCharge + $amountInPercentage;
        $amountToPay = $request->amount + $cost;

        $randomString = Str::random(40);
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->transaction_type = 'deposit';
        $transaction->transaction_id = $randomString;
        $transaction->transaction_currency = $request->currency;
        $transaction->amount = $request->amount;
        $transaction->transaction_cost = $cost;
        $transaction->method = $request->paymentmethod;
        $transaction->reason = "Account Deposit";
        $transaction->status = 'success';
        $transaction->save();

        $data = array(
            'user_id' => Auth::user()->id,
            'user_email' => Auth::user()->email,
            'amount' => $request->amount,
            'cost' => $cost,
            'method' => $request->paymentmethod,
            'currency' => $request->currency,
            'deposit_status' => 'complete',
            'created_at' => Carbon::now(),
        );
        $data['transaction_id'] = $randomString;

        Deposit::insert($data);

        $addAccountBalance = Accountbalance::where('currency_id',$request->currency)->where('user_id',Auth::user()->id)->first();
        if($addAccountBalance){
            $totalAmount = $addAccountBalance->balance + $request->amount;
            $addAccountBalance->balance = $totalAmount;
            $addAccountBalance->update();
        }else{
            $initAccountBalance = new Accountbalance();
            $initAccountBalance->user_id = Auth::user()->id;
            $initAccountBalance->user_email = Auth::user()->email;
            $initAccountBalance->balance = $request->amount;
            $initAccountBalance->currency_id = $request->currency;
            $initAccountBalance->status = 1;
            $initAccountBalance->save();
        }

        $gs = Generalsetting::find(1);

        $user = Auth::user();
    
        //Sending Email To User

        $subject = "Your Deposit Request Placed!!";
        $to = $user->email;
        $msg = "Hello ".$user->name."!<br>Your Deposit Request Placed!.<br>Thank you.";
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
            $to = $user->email;
            $subject = "Your Deposit Request Placed!!";
            $msg = "Hello ".$user->name."!<br>Your Deposit Request Placed!.<br>Thank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);            
        }


        //Sending Email To Admin
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $gs->email,
                'subject' => "Deposit Request Recieved!!",
                'body' => "Hello Admin!<br>You received a new deposit request. <br>Please login to your panel to check. <br>Thank you.",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);            
        }
        else
        {
            $to = $gs->email;
            $subject = "Deposit Request Recieved!!";
            $msg = "Hello Admin!<br>You received a new deposit request. <br>Please login to your panel to check. <br>Thank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }

        return redirect()->back()->with('success', 'Deposit Amount Successfully');
    }
}
