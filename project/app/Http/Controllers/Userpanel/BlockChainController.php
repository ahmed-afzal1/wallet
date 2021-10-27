<?php

namespace App\Http\Controllers\Userpanel;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Accountbalance;
use App\Models\Deposit;
use Illuminate\Support\Facades\Session;

class BlockChainController extends BaseController
{
    public function blockInvest()
    {
        return view('frontend.blockchain');
    }

    public function chaincallback()
    {

        $data = Paymentgateway::whereKeyword('BlockChain')->first();
        $blockChainData = $data->convertAutoData();

        $real_secret  = $blockChainData['secret_string'];

        $des = $_SERVER['QUERY_STRING'];


        $bitTran = $_GET['transaction_hash'];
        $bitAddr = $_GET['address'];

        $trans_id = Input::get('transx_id');
        $getSec = Input::get('secret');

        if ($real_secret == $getSec){

            if (Transaction::where('transaction_id ',$trans_id)->exists()){



                $deposits = $_GET['value']/100000000;

                $trans_id = Session::get('transaction_id');

                $transaction = Transaction::findOrFail($trans_id);
                $transaction->status = "success";
                $transaction->update();

                $currency_amount = Session::get('currency_value');
                $cost = Session::get('cost');
                $currency = Session::get('curr_code');

                $data = array(
                    'user_id' => Auth::user()->id,
                    'user_email' => Auth::user()->email,
                    'amount' => $currency_amount,
                    'cost' => $cost,
                    'method' => 'BlockChain',
                    'currency' => $currency->id,
                    'deposit_status' => 'complete',
                    'created_at' => Carbon::now(),
                );
                
                $data['transaction_id'] = strtoupper($this->goRandomString(4).Str::random(3).$this->goRandomString(4));
                Deposit::insert($data);
                
           
    
                $addAccountBalance = Accountbalance::where('currency_id',$currency->id)->where('user_id',Auth::user()->id)->first();
    
                    if($addAccountBalance){
                        $totalAmount = $addAccountBalance->balance + $currency_amount;
                        $addAccountBalance->balance = $totalAmount;
                        $addAccountBalance->update();         
    
                    }else{
                        $initAccountBalance = new Accountbalance();
                        $initAccountBalance->user_id = Auth::user()->id;
                        $initAccountBalance->user_email = Auth::user()->email;
                        $initAccountBalance->balance = $currency_amount;
                        $initAccountBalance->currency_id = $currency->id;
                        $initAccountBalance->status = 1;
                        $initAccountBalance->save();
                    }
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

                return "*ok*";
            
            }

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





    public function deposit(Request $request)
    {
        $data = Paymentgateway::whereKeyword('BlockChain')->first();
        $blockChainData = $data->convertAutoData();

        if($request->amount > 0){

            $acc = Auth::user()->id;
            $item_number = Str::random(4).time();

            $currency = Currency::findOrFail($request->currency);
            $fixedDepositCharge = $currency->fixed_deposit_charge;
            $percentageDepositCharge = $currency->percentage_deposit_charge;
    
            $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
            $cost = $fixedDepositCharge + $amountInPercentage;
            $amountToPay = $request->amount + $cost;
            $currency_amount = $amountToPay;

            $item_amount = $request->amount;
            $currency_code = $currency->code;
            $amount = file_get_contents('https://blockchain.info/tobtc?currency='.$currency_code.'&value='.$currency_amount);

            $secret = $blockChainData['secret_string'];
            $my_xpub = $blockChainData['xPub'];
            $my_api_key = $blockChainData['api_key'];
            $my_gap = $blockChainData['gap_limit'];


            $my_callback_url = url('/').'/blockchain/notify?transx_id='.$item_number.'&secret='.$secret;

            $ttt = 'https://www.google.com/';
            $root_url = 'https://api.blockchain.info/v2/receive';

            $parameters = 'xpub=' .$my_xpub. '&callback=' .urlencode($ttt). '&key='.$my_api_key.'&gap_limit='.$my_gap;
                
            $response = file_get_contents($root_url . '?' . $parameters);
            
            $object = json_decode($response);
        
            $address = $object->address;


            $randomString = Str::random(40);
            $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->transaction_type = 'deposit';
            $transaction->transaction_id = $item_number;
            $transaction->transaction_currency = $request->currency;
            $transaction->amount = $request->amount;
            $transaction->transaction_cost = $cost;
            $transaction->method = $request->paymentmethod;
            $transaction->reason = "Account Deposit";
            $transaction->status = 'pending';
            $transaction->save();
            $transaction_id = $transaction->id;


        session(['address' => $address,'amount' => $amount,'currency_value' => $currency_amount,'currency_sign' => $currency->sign,'accountnumber' => $acc, 'transaction_id'=>$transaction_id, 'cost' => $cost, 'curr' => $currency]);

        return redirect('invest/bitcoin');

        }
        return redirect()->back()->with('error','Please enter a valid amount.')->withInput();
    }



}
