<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Models\Accountbalance;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Userlogin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Transaction;

class ApiController extends Controller
{
    public function __construct()
    {
        
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

    public function eXpressApi(Request $request){

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application');

        if ($request->method() == "GET"){
            if (session('token') != null){
                $token = Input::get('token');
                $value = session('token');
                if ($token == $value){
                    if (Auth::guard('profile')->check()){
                        return redirect('/express/web/pay?token='.$token);
                    }else{
                        return redirect('/express/web/signin?token='.$token);
                    }
                }else{
                    return view('express-checkout-api-error');
                }
            }else{
            	return view('express-checkout-api-error');
            
            }

        }

        if ($request->cmd == '_verify'){
        
            $cmd_verify = Transaction::where('transid',$request->txn);

            if ($cmd_verify->count() > 0) {
		        if($cmd_verify->first()->amount == $request->amount){
                    return "VERIFIED";
                }
                return "UNVERIFIED";
            }else{

            	return "UNVERIFIED";
            }

        }else {
            
            $post_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

            $cancel_url = $request->cancel_return;
            $payee = $request->business_account;
            $business_count = User::where('email',$payee)->where('acc_type','business')->count();

            $token = Str::random(12).uniqid();
            if ($business_count > 0){
            	if($request->amount > 0){
	            	session([
	                    'token' => $token,
	                    'item_name' => $request->item_name,
                        'item_code' => $request->item_code,
                        'amount' => round($request->amount,2),
	                    'custom' => $request->custom,
	                    'success_return' => $request->success_return,
	                    'notify_url' => $request->notify_url,
	                    'cancel_return' => $cancel_url,
	                    'payee' => $payee,
	                    'origin' => $post_from,
	                    'message' => ''
	                ]);
	                if (auth()->check()){
	                    return redirect('/express/web/pay?token='.$token);
	                }else{
	                    return redirect('/express/web/signin?token='.$token);
	                }
            	}
              	session([
	                'message' => "Amount is not valid.",
	                'cancel_return' => $cancel_url
	            ]);
	            return redirect('/express/web/pay?token='.$token);  
            }
            session([
                'message' => "No Business Account Found.",
                'cancel_return' => $cancel_url
            ]);
            return redirect('/express/web/pay?token='.$token);
        }
    }


    public function eXpressApiForm(){
        $token =  request()->token;
        $value = session('token');
        if ($token == $value){
            return view('express-checkout-api');
        }else{
            return view('express-checkout-api-error');
        }
    }

    public function apiLogin(Request $request){

        if (Auth::attempt(['email' => $request->email,'password' => $request->password], false)){

            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = $_SERVER['HTTP_USER_AGENT'];

            $userlog = new Userlogin();
            $userlog->user_id = Auth::user()->id;
            $userlog->ip = $ip;
            $userlog->browser = $browser;
            $userlog->login_time = date('Y-m-d H:i:s');
            $userlog->save();

            if (Auth::user()->status == 0) {
                Auth::logout();

                return response()->json(['status' => 'Failed','message' => 'Your Account is Suspended'],200);
            }

            return response()->json(['status' => 'Success','message' => 'Login Successful','data' =>'<form id="payNow" method="POST" action="'.route('api.payment').'">
                            '.csrf_field().'
                            <p id="resp"></p>
                            <div class="paypal-pay-area">
                                <h3>Hi, '.Auth::user()->name.'</h3>
                                <div class="paypal-ship-area">
                                    <h3>You paying '.session('amount').' to '.session('payee').' account.</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                    <button type="submit" class="btn btn-block paypal_btn" id="ConfirmButton"><strong>Confirm Payment</strong></button>
                                
                            </div>
                        </form>'], 200);

        }

        return response()->json(['status' => 'Failed','message' => 'Incorrect Username Or Password'],200);
    }

    public function CompletePaymentApi(Request $request){

        if (Auth::check()) {
            $payee = session('payee');
            $pay_amount = session('amount');
            $item_code = session('item_code');
            $item_name = session('item_name');
            $custom = session('custom');
            $origin = session('origin');


            $from = User::findOrFail(Auth::user()->id);
            $toacc = User::where('email', $payee)->first();

            $transcharge = Currency::first();
            $charge_total = $transcharge->fixed_transaction_charge + ($pay_amount/100) * $transcharge->percentage_transaction_charge;

            $charge_final = number_format((float)$charge_total, 2, '.', '');

            $amount = $pay_amount + $charge_total;
            $amount = number_format((float)$amount, 2, '.', '');

            if( Accountbalance::where('user_id',$from->id)->where('currency_id',$transcharge->id)->exists()){
                $fromWallet = Accountbalance::where('user_id',$from->id)->where('currency_id',$transcharge->id)->first();
                $toWallet = Accountbalance::where('user_id',$toacc->id)->where('currency_id',$transcharge->id)->first();

                if ($fromWallet->balance >= $amount){
    
    
                    if($payee == $from->email){
    
                        return response()->json([
                                'status' => 'failed',
                                'amount' => $amount,
                                'message' => 'Sorry, You can not pay on your own.'
                            ]
                            ,200);
                    }
    
    
    
                    $transid = strtoupper($this->goRandomString(4) . Str::random(3) . $this->goRandomString(4));
    
                    $redirect_url = session('success_return')."?amount=".$pay_amount."&fee=".$charge_final."&txn=".$transid."&paid_by=".$from->email."&custom=".$custom;
    
                    $sendertrans = new Transaction();
                    $sendertrans->user_id = Auth::user()->id;
                    $sendertrans->transaction_type = 'send';
                    $sendertrans->transaction_id = $transid;
                    $sendertrans->amount = $amount;
                    $sendertrans->transaction_cost = $charge_final;
                    $sendertrans->transaction_currency = $transcharge->id;
                    $sendertrans->costpay = Auth::guard('web')->user()->email;
                    $sendertrans->sender = Auth::guard('web')->user()->email;
                    $sendertrans->sender_is = 'user';
                    $sendertrans->receiver = $toacc->email;
                    $sendertrans->receiver_is = 'user';
                    $sendertrans->reference = $request->reference;
                    $sendertrans->status = 'success';

                    $sendertrans->save();

                    $fromWallet->decrement('balance',$amount);
                    $toWallet->increment('balance',$pay_amount);
    

    
                    $url = session('notify_url');
                    $myvars = "payment_status=Completed&amount=".$pay_amount."&fee=".$charge_final."&txn=".$transid."&paid_by=".$from->email."&receiver=".$payee."&item_code=".$item_code."&item_name=".$item_name."&custom=".$custom;
    
    
                    $ch = curl_init($url);
    
                    curl_setopt( $ch, CURLOPT_POST, 1);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt( $ch, CURLOPT_HEADER, 0);
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: '.$transcharge->title));
    
                    curl_exec($ch );
    
    
    
    
                    session()->forget('token');
    
                    return response()->json([
                            'status' => 'completed',
                            'amount' => $amount,
                            'item_code' => $item_code,
                            'item_name' => $item_name,
                            'redirect_url' => $redirect_url,
                            'data' => '<div class="paypal-pay-area">
                                                <h3>Hi, '.$from->name.'</h3>
                                                <div class="paypal-ship-area">
                                                    <h3>Congratulation.</h3>
                                                    <h3>Your Payment Completed Successfully.</h3>
                                                        <p>Please wait for redirect or Click on Back Button.</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <a href="'.$redirect_url.'" class="btn btn-block paypal_btn"><strong>Go Back</strong></a>
                                            </div>',
                            'message' => 'Payment Completed Successfully.',
                        ]
                        ,200);
                }else{
                    return response()->json([
                            'status' => 'failed',
                            'amount' => $amount,
                            'message' => 'Insufficient Account Balance.'
                        ]
                        ,200);
                }
            }else{
                return response()->json([
                    'status' => 'failed',
                    'amount' => $amount,
                    'message' => 'You don,t have this currency wallet'
                ]
                ,200);
            }

        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Please Login First.'
                ]
                ,200);
        }
    }

    public function TransactionVerify(){
    
        $apikey = request()->api_key;
        $txn = request()->txn;
        if (User::where('api_key','=',$apikey)->exists()){
            $apiowner = User::where('api_key',$apikey)->first();

            if (Transaction::where('transaction_id',$txn)->where('user_id',$apiowner->id)->count() > 0){
                $trans = Transaction::where('transaction_id',$txn)->where('user_id',$apiowner->id)->first();
                $status = 'pending';
                $info = array();
                if ($trans->status == 1){
                    $status = 'completed';
                }

                if ($trans->transaction_type == "send"){
                    $info = [
                        'credit_from' => $trans->sender,
                        'credit_to' => $trans->receiver,
                        'withdraw_method' => null,
                        'deposit_method' => null
                    ];
                }



                if ($trans->transaction_type == "withdraw"){
                    $info = [
                        'credit_from' => null,
                        'debit_to' => null,
                        'withdraw_method' => $trans->method,
                        'deposit_method' => null
                        ];
                }

                if ($trans->transaction_type == "deposit"){
                    $info = [
                        'credit_from' => null,
                        'debit_to' => null,
                        'withdraw_method' => null,
                        'deposit_method' => $trans->method
                    ];
                }

                return response()->json([
                    'details' =>[
                        'txn_id' => $trans->transaction_id,
                        'txn_reason' => $trans->reason,
                        'txn_amount' => $trans->amount,
                        'txn_fee' => $trans->transaction_cost,
                        'txn_type' => $trans->transaction_type,
                        'txn_reference' => $trans->reference,
                        'txn_status' => $status,
                        'txn_date' => $trans->created_at,
                        'txn_info' => $info
                    ],
                    'success' => true,
                    'error' => false,
                    'message' => 'Transaction Verified'
                ],200);
            }

            return response()->json([
                'details' =>[],
                'success' => false,
                'error' => true,
                'message' => 'No Transaction Found.'
            ],200);

        }else{

            return response()->json([
                'details' =>[],
                'success' => false,
                'error' => true,
                'message' => 'Invalid API KEY'
            ],200);

        }

    }

}
