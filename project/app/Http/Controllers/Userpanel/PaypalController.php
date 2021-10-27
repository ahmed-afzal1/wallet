<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

use App\{
    Models\Order,
    Models\Paymentgateway
};
use App\Models\Accountbalance;
use App\Models\Generalsetting;
use App\Classes\GeniusMailer;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Transaction as AppTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PayPal\{
    Api\Item,
    Api\Payer,
    Api\Amount,
    Api\Payment,
    Api\ItemList,
    Rest\ApiContext,
    Api\Transaction,
    Api\RedirectUrls,
    Api\PaymentExecution,
    Auth\OAuthTokenCredential
};

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $data = Paymentgateway::whereKeyword('paypal')->first();
        $paydata = $data->convertAutoData();
        $paypal_conf = \Config::get('paypal');
        $paypal_conf['client_id'] = $paydata['client_id'];
        $paypal_conf['secret'] = $paydata['client_secret'];
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'paymentmethod' => 'required',
            'currency' => 'required',
        ])->validate();

        $title = 'Deposit money';
        $currency_code = Currency::findOrFail($request->currency)->code;

        $currency = Currency::findOrFail($request->currency);
        $fixedDepositCharge = $currency->fixed_deposit_charge;
        $percentageDepositCharge = $currency->percentage_deposit_charge;

        $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
        $cost = $fixedDepositCharge + $amountInPercentage;
        $amountToPay = $request->amount + $cost;
        $currency_amount = $amountToPay;

        $supported = [
            'AUD','BRL','CAD','CNY','CZK','DKK','EUR','HKD','HUF',
            'ILS','JPY','MYR','MXN','TWD','NZD','NOK',
            'PHP','PLN','GBP','RUB','SGD','SEK','CHF','THB','USD',
        ];


        if(!in_array($currency_code,$supported)){
            return redirect()->back()->with('error', 'This currency not supported paypal checkout'); 
        }
        
        if(!($request->amount>= $currency->minimum_deposit_amount && $request->amount<= $currency->maximum_deposit_amount)){
            return back()->with('error','Amount should be between minimum-'.$currency->minimum_deposit_amount .' maximum-'.$currency->maximum_deposit_amount .' deposit amount.');
        }

        $randomString = Str::random(40);
        $transaction = new AppTransaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->transaction_type = 'deposit';
        $transaction->transaction_id = $randomString;
        $transaction->transaction_currency = $request->currency;
        $transaction->amount = $request->amount;
        $transaction->transaction_cost = $cost;
        $transaction->method = $request->paymentmethod;
        $transaction->reason = "Account Deposit";
        $transaction->status = 'pending';
        $transaction->save();
        $transaction_id = $transaction->id;


        $notify_url = route('paypal.notify');
        $cancel_url = route('user-deposit-money');

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($title) /** item name **/
            ->setCurrency($currency_code)
            ->setQuantity(1)
            ->setPrice($currency_amount); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency($currency_code)
            ->setTotal($currency_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($title.' Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($notify_url) /** Specify return URL **/
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
       
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                return redirect()->back()->with('unsuccess',$ex->getMessage());
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
        /** add payment ID to session **/
              Session::put('paypal_data',$request->all());
              Session::put('transaction_cost',$cost);
              Session::put('paypal_payment_id', $payment->getId());
              Session::put('transaction_id',$transaction_id);
              Session::put('randomString',$randomString);

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        return redirect()->back()->with('unsuccess','Unknown error occurred');
    
              if (isset($redirect_url)) {
                  /** redirect to paypal **/
                  return Redirect::away($redirect_url);
              }
              return redirect()->back()->with('unsuccess','Unknown error occurred');
    
     }


     public function notify(Request $request)
     {

         /** Get the payment ID before session clear **/
         $payment_id = Session::get('paypal_payment_id');
         /** clear the session payment ID **/
         if (empty( $request['PayerID']) || empty( $request['token'])) {
             return redirect()->back()->with('error', 'Payment Failed'); 
         } 
         $payment = Payment::get($payment_id, $this->_api_context);
         $execution = new PaymentExecution();
         $execution->setPayerId($request['PayerID']);

        $input = Session::get('paypal_data');
        $transaction_id = Session::get('transaction_id');
        $randomString = Session::get('randomString');
        $cost = Session::get('transaction_cost');
        $currency_amount = Currency::convertwithusd($input['amount'],$input['currency']);


         /**Execute the payment **/
         $result = $payment->execute($execution, $this->_api_context);

         if ($result->getState() == 'approved') {
             $resp = json_decode($payment, true);

             $transaction = AppTransaction::findOrFail($transaction_id);
             $transaction->status = "success";
             $transaction->method_transaction_id = $resp['transactions'][0]['related_resources'][0]['sale']['id'];
             $transaction->update();

             $data = array(
                'user_id' => Auth::user()->id,
                'user_email' => Auth::user()->email,
                'amount' => $currency_amount,
                'cost' => $cost,
                'method' => $input['paymentmethod'],
                'currency' => $input['currency'],
                'method_transaction_id' => $resp['transactions'][0]['related_resources'][0]['sale']['id'],
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

            Session::forget('paypal_data');
            Session::forget('paypal_payment_id');
            Session::forget('transaction_id');
            Session::forget('randomString');
            Session::forget('transaction_cost');
            return redirect()->route('user-deposit-money')->with('success', 'Deposit Amount Successfully'); 
         }
         
     
}
