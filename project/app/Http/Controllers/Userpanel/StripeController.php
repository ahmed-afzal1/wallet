<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\BookingMail;
use App\Helpers\PriceHelper;
use App\Models\Accountbalance;
use App\Models\Craditcard;
use App\Models\Currency;
use App\Models\Deposit;
use Config;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App\Models\Order;
use App\Models\HotelOrderItem;
use App\Models\Generalsetting;
use App\Models\Paymentgateway;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Session;
use Auth;
use App\Classes\GeniusMailer;
use Illuminate\Support\Carbon;

class StripeController extends Controller
{
    public function __construct()
    {
        $stripe = Paymentgateway::whereKeyword('Stripe')->first();
        $stripedata = $stripe->convertAutoData();
        Config::set('services.stripe.key',$stripedata['key']);
        Config::set('services.stripe.secret',$stripedata['secret']);
    }


    public function store(Request $request){
            
            $currency = Currency::findOrFail($request->currency);
            
            
            if($currency->code != "USD")
            {
                return redirect()->back()->with('unsuccess','Please Select USD Currency For Stripe.');
            }
            
            if(!($request->amount>= $currency->minimum_deposit_amount && $request->amount<= $currency->maximum_deposit_amount)){
                return back()->with('error','Amount should be between minimum-'.$currency->minimum_deposit_amount .' maximum-'.$currency->maximum_deposit_amount .' deposit amount.');
            }
            
            
            $fixedDepositCharge = $currency->fixed_deposit_charge;
            $percentageDepositCharge = $currency->percentage_deposit_charge;

            $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
            $cost = $fixedDepositCharge + $amountInPercentage;
            $amountToPay = $request->amount + $cost;

            if($request->card_id == 0){
                $request->validate([
                    'amount' => 'required|numeric',
                    'paymentmethod' => 'required',
                    'currency' => 'required',
                    'cardNumber' => 'required',
                    'cardCVC' => 'required',
                    'month' => 'required',
                    'year' => 'required',
                ]);


                if($request->isCheckCardSave == 1){
                    $data = array(
                        'user_id' => Auth::guard('web')->user()->id,
                        'user_email' => Auth::guard('web')->user()->email,
                        'card_owner_name' => Auth::guard('web')->user()->username ,
                        'card_number' => $request->cardNumber,
                        'card_cvc' => $request->cardCVC,
                        'month' => $request->month,
                        'year' => $request->year,
                    );
                    Craditcard::insert($data);
                }
                

          
                $title = 'Deposit money';
                $currency_code = Currency::findOrFail($request->currency)->code;    
               
                $stripe = Stripe::make(Config::get('services.stripe.secret'));
                try{
                    $token = $stripe->tokens()->create([
                        'card' =>[
                                'number' => $request->cardNumber,
                                'exp_month' => $request->month,
                                'exp_year' => $request->year,
                                'cvc' => $request->cardCVC,
                            ],
                        ]);
    
                    if (!isset($token['id'])) {
                        return back()->with('error','Token Problem With Your Token.');
                    }
    
                    $charge = $stripe->charges()->create([
                        'card' => $token['id'],
                        'currency' =>  $currency_code,
                        'amount' => $amountToPay,
                        'description' => $title,
                    ]);
    
                    if ($charge['status'] == 'succeeded') {

                        $randomString = Str::random(40);
                        $transaction = new Transaction();
                        $transaction->user_id = Auth::user()->id;
                        $transaction->transaction_type = 'deposit';
                        $transaction->transaction_id = $randomString;
                        $transaction->deposit_chargeid = $charge['id'];
                        $transaction->deposit_transid = $charge['balance_transaction'];
                        $transaction->transaction_currency = $request->currency;
                        $transaction->amount = $request->amount;
                        $transaction->method = $request->paymentmethod;
                        $transaction->transaction_cost = $cost;
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
                            'craditcard_id' => $charge['id'],
                            'deposit_status' => 'complete',
                            'created_at' => Carbon::now(),
                        );

                        $data['transaction_id'] = $charge['balance_transaction'];
                        Deposit::insert($data);
                        
                        // pronob
                        
                       if(!Accountbalance::where('user_id',Auth::user()->id)->where('currency_id',$request->currency)->exists()){
                           $new= new Accountbalance();
                           $new->user_id=Auth::user()->id();
                           $new->user_email=Auth::user()->email;
                           $new->balance=$request->amount;
                           $new->currency=$request->currency;
                           Accountbalance::insert($new);
                       }
                        
                        // end pronob

                        $addAccountBalance = Accountbalance::where('currency_id',$request->currency)->where('user_id',Auth::user()->id)->first();
                        if($addAccountBalance){
                            $totalAmount = $addAccountBalance->balance + $request->amount;
                            $addAccountBalance->balance = $totalAmount;
                            $addAccountBalance->update();
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
                    
                }catch (Exception $e){
                    return back()->with('unsuccess', $e->getMessage());
                }catch (\Cartalyst\Stripe\Exception\CardErrorException $e){
                    return back()->with('unsuccess', $e->getMessage());
                }catch (\Cartalyst\Stripe\Exception\MissingParameterException $e){
                    return back()->with('unsuccess', $e->getMessage());
                }
            }else{

                $request->validate([
                    'amount' => 'required|numeric',
                    'paymentmethod' => 'required',
                    'currency' => 'required',
                ]);

                $creditCardId = Craditcard::findOrFail($request->card_id);
          
                $title = 'Deposit money';
                $currency_code = Currency::findOrFail($request->currency)->code;    
               
                $stripe = Stripe::make(Config::get('services.stripe.secret'));
                try{
                    $token = $stripe->tokens()->create([
                        'card' =>[
                                'number' => $creditCardId->card_number,
                                'exp_month' => $creditCardId->month,
                                'exp_year' => $creditCardId->year,
                                'cvc' => $creditCardId->card_cvc,
                            ],
                        ]);
    
                    if (!isset($token['id'])) {
                        return back()->with('error','Token Problem With Your Token.');
                    }
    
                    $charge = $stripe->charges()->create([
                        'card' => $token['id'],
                        'currency' =>  $currency_code,
                        'amount' => $amountToPay,
                        'description' => $title,
                    ]);
    
                    if ($charge['status'] == 'succeeded') {

                        $randomString = Str::random(40);
                        $transaction = new Transaction();
                        $transaction->user_id = Auth::user()->id;
                        $transaction->transaction_type = 'deposit';
                        $transaction->transaction_id = $randomString;
                        $transaction->deposit_chargeid = $charge['id'];
                        $transaction->deposit_transid = $charge['balance_transaction'];
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
                            'craditcard_id' => $charge['id'],
                            'deposit_status' => 'complete',
                            'created_at' => Carbon::now(),
                        );
                        $data['transaction_id'] = $charge['balance_transaction'];
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
                    
                }catch (Exception $e){
                    return back()->with('unsuccess', $e->getMessage());
                }catch (\Cartalyst\Stripe\Exception\CardErrorException $e){
                    return back()->with('unsuccess', $e->getMessage());
                }catch (\Cartalyst\Stripe\Exception\MissingParameterException $e){
                    return back()->with('unsuccess', $e->getMessage());
                }
            }
    }
}

