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
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MollyController extends Controller
{
    public function __construct()
    {

    }

    public function store(Request $request){

        $currency = Currency::findOrFail($request->currency);
        $fixedDepositCharge = $currency->fixed_deposit_charge;
        $percentageDepositCharge = $currency->percentage_deposit_charge;

        $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
        $cost = $fixedDepositCharge + $amountInPercentage;
        $amountToPay = $request->amount + $cost;

        $available_currency = array(
            'AED',
            'AUD',
            'BGN',
            'BRL',
            'CAD',
            'CHF',
            'CZK',
            'DKK',
            'EUR',
            'GBP',
            'HKD',
            'HRK',
            'HUF',
            'ILS',
            'ISK',
            'JPY',
            'MXN',
            'MYR',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'RON',
            'RUB',
            'SEK',
            'SGD',
            'THB',
            'TWD',
            'USD',
            'ZAR'
            );

            if(!in_array($currency->code,$available_currency))
            {
                return redirect()->back()->with('unsuccess','Invalid Currency For Molly Payment.');
            }
            
            if(!($request->amount>= $currency->minimum_deposit_amount && $request->amount<= $currency->maximum_deposit_amount)){
                return back()->with('error','Amount should be between minimum-'.$currency->minimum_deposit_amount .' maximum-'.$currency->maximum_deposit_amount .' deposit amount.');
            }

            $input = $request->all();
            $settings = Generalsetting::findOrFail(1);
            $title = 'Deposit money';
            $item_amount = Currency::convertwithusd($amountToPay,$request->currency);
            
            // dd($title);

            $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => $currency->code,
                    'value' => ''.sprintf('%0.2f', $item_amount).'', // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => $title,
                'redirectUrl' => route('molly.notify'),
            ]);

            Session::put('payment_id',$payment->id);
            Session::put('transaction_cost',$cost);
            Session::put('paypal_data',$input);
            Session::put('currency_data',$currency->code);
            $payment = Mollie::api()->payments()->get($payment->id);
    
            return redirect($payment->getCheckoutUrl(), 303);

    }

    public function notify(Request $request){
        $input = Session::get('paypal_data');
        $currency = Session::get('currency_data');
        $cost = Session::get('transaction_cost');

        $payment = Mollie::api()->payments()->get(Session::get('payment_id'));

        
        $currency_amount = Currency::convertwithusd($input['amount'],$input['currency']);

        if($payment->status == 'paid'){
            $randomString = Str::random(40);
            $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->transaction_type = 'deposit';
            $transaction->transaction_id = $randomString;

            $transaction->transaction_currency = $input['currency'];
            $transaction->amount = $currency_amount;
            $transaction->method = $input['paymentmethod'];
            $transaction->reason = "Account Deposit";
            $transaction->status = 'success';
            $transaction->save();


            $data = array(
                'user_id' => Auth::user()->id,
                'user_email' => Auth::user()->email,
                'amount' => $currency_amount,
                'cost' => $cost,
                'method' => $input['paymentmethod'],
                'currency' => $input['currency'],
                'deposit_status' => 'complete',
                'created_at' => Carbon::now(),
            );
            $data['transaction_id'] = $randomString;
            Deposit::insert($data);

            $addAccountBalance = Accountbalance::where('currency_id',$input['currency'])->where('user_id',Auth::user()->id)->first();
            if($addAccountBalance){
                $totalAmount = $addAccountBalance->balance + $currency_amount;
                $addAccountBalance->balance = $totalAmount;
                $addAccountBalance->update();
            }else{
                $initAccountBalance = new Accountbalance();
                $initAccountBalance->user_id = Auth::user()->id;
                $initAccountBalance->user_email = Auth::user()->email;
                $initAccountBalance->balance = $currency_amount;
                $initAccountBalance->currency_id = $input['currency'];
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

            return redirect()->route('user-deposit-money')->with('success', 'Deposit Amount Successfully.');
        }else{
            return redirect()->route('user-deposit-money')->with('unsuccess', 'Payment Cancelled.');
        }
    }
}
