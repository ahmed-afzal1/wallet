<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Paymentgateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Classes\GeniusMailer;
use App\Models\Accountbalance;
use App\Models\Deposit;
use Carbon\Carbon;

class PaytmController extends Controller
{
    public function store(Request $request){
        $currency = Currency::findOrFail($request->currency);
        $fixedDepositCharge = $currency->fixed_deposit_charge;
        $percentageDepositCharge = $currency->percentage_deposit_charge;

        $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
        $cost = $fixedDepositCharge + $amountInPercentage;
        $amountToPay = $request->amount + $cost;

        $paymentGateway = $request->paymentmethod;

        if($currency->code != "INR")
        {
            return redirect()->back()->with('unsuccess','Please Select INR Currency For Paytm.');
        }
        
        if(!($request->amount>= $currency->minimum_deposit_amount && $request->amount<= $currency->maximum_deposit_amount)){
            return back()->with('error','Amount should be between minimum-'.$currency->minimum_deposit_amount .' maximum-'.$currency->maximum_deposit_amount .' deposit amount.');
        }

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
        $transaction->status = 'pending';
        $transaction->save();
        $transaction_id = $transaction->id;


        Session::put('paypal_data',$request->all());
        Session::put('transaction_cost',$cost);
        Session::put('transaction_id',$transaction_id);
        Session::put('randomString',$randomString);


        $data_for_request = $this->handlePaytmRequest($randomString, $amountToPay,$paymentGateway);
	    $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
	    $paramList = $data_for_request['paramList'];
	    $checkSum = $data_for_request['checkSum'];
	    return view( 'frontend.paytm-merchant-form', compact( 'paytm_txn_url', 'paramList', 'checkSum' ) );
    }

    public function handlePaytmRequest( $transaction_id, $amount,$paymentGateway) {
        $gs = Generalsetting::first();
        $gateway = Paymentgateway::where('keyword',$paymentGateway)->first();

        $gatewayInformation = json_decode($gateway->information);

    
            // Load all functions of encdec_paytm.php and config-paytm.php
            $this->getAllEncdecFunc();
            // $this->getConfigPaytmSettings();
            $checkSum = "";
            $paramList = array();
            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = $gatewayInformation->merchant;
            $paramList["ORDER_ID"] = $transaction_id;
            $paramList["CUST_ID"] = $transaction_id;
            $paramList["INDUSTRY_TYPE_ID"] = $gatewayInformation->industry;
            $paramList["CHANNEL_ID"] = 'WEB';
            $paramList["TXN_AMOUNT"] = $amount;
            $paramList["WEBSITE"] = $gatewayInformation->website;
            $paramList["CALLBACK_URL"] = route('paytm.notify');
            $paytm_merchant_key = $gatewayInformation->secret;
            //Here checksum string will return by getChecksumFromArray() function.
            $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );
            return array(
                'checkSum' => $checkSum,
                'paramList' => $paramList
            );
        }

    	function getAllEncdecFunc() {
            function encrypt_e($input, $ky) {
                $key   = html_entity_decode($ky);
                $iv = "@@@@&&&&####$$$$";
                $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
                return $data;
        }

        function decrypt_e($crypt, $ky) {
            $key   = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
            return $data;
        }

        function pkcs5_pad_e($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }

        function pkcs5_unpad_e($text) {
            $pad = ord($text(strlen($text) - 1));
            if ($pad > strlen($text))
                return false;
            return substr($text, 0, -1 * $pad);
        }

        function generateSalt_e($length) {
            $random = "";
            srand((double) microtime() * 1000000);
            $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
            $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
            $data .= "0FGH45OP89";
            for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
            }
            return $random;
        }

        function checkString_e($value) {
            if ($value == 'null')
                $value = '';
            return $value;
        }

        function getChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getChecksumFromString($str, $key) {
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function verifychecksum_e($arrayList, $key, $checksumvalue) {
            $arrayList = removeCheckSumParam($arrayList);
            ksort($arrayList);
            $str = getArray2StrForVerify($arrayList);
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function verifychecksum_eFromStr($str, $key, $checksumvalue) {
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function getArray2Str($arrayList) {
            $findme   = 'REFUND';
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pos = strpos($value, $findme);
                $pospipe = strpos($value, $findmepipe);
                if ($pos !== false || $pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function getArray2StrForVerify($arrayList) {
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function redirect2PG($paramList, $key) {
            $hashString = getchecksumFromArray($paramList, $key);
            $checksum = encrypt_e($hashString, $key);
        }

        function removeCheckSumParam($arrayList) {
            if (isset($arrayList["CHECKSUMHASH"])) {
                unset($arrayList["CHECKSUMHASH"]);
            }
            return $arrayList;
        }

        function getTxnStatus($requestParamList) {
            return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
        }

        function getTxnStatusNew($requestParamList) {
            return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
        }

        function initiateTxnRefund($requestParamList) {
            $CHECKSUM = getRefundChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
            $requestParamList["CHECKSUM"] = $CHECKSUM;
            return callAPI(PAYTM_REFUND_URL, $requestParamList);
        }

        function callAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

        function callNewAPI($apiURL, $requestParamList) {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData =json_encode($requestParamList);
            $postData = 'JsonData='.urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData))
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse,true);
            return $responseParamList;
        }

        function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getRefundArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getRefundArray2Str($arrayList) {
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pospipe = strpos($value, $findmepipe);
                if ($pospipe !== false)
                {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

            function callRefundAPI($refundApiURL, $requestParamList) {
                $jsonResponse = "";
                $responseParamList = array();
                $JsonData =json_encode($requestParamList);
                $postData = 'JsonData='.urlencode($JsonData);
                $ch = curl_init($apiURL);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_URL, $refundApiURL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $jsonResponse = curl_exec($ch);
                $responseParamList = json_decode($jsonResponse,true);
                return $responseParamList;
            }
        }
        /**
         * Config Paytm Settings from config_paytm.php file of paytm kit
         */
        function getConfigPaytmSettings() {
            $gateway = Paymentgateway::where('keyword','Paytm')->first();

            $gatewayInformation = json_decode($gateway->information);

            $gs = Generalsetting::first();
        
            if ($gs->paytm_mode == 'sandbox') {
            define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
            } elseif ($gs->paytm_mode == 'live') {
            define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
            }
    
            define('PAYTM_MERCHANT_KEY', $gatewayInformation->secret); //Change this constant's value with Merchant key downloaded from portal
            define('PAYTM_MERCHANT_MID', $gatewayInformation->merchant); //Change this constant's value with MID (Merchant ID) received from Paytm
            define('PAYTM_MERCHANT_WEBSITE', $gatewayInformation->website); //Change this constant's value with Website name received from Paytm
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
            if (PAYTM_ENVIRONMENT == 'PROD') {
                $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
                $PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
            }
            define('PAYTM_REFUND_URL', '');
            define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
        }

        public function paytmCallback( Request $request ) {
            
            $input = Session::get('paypal_data');
            $randomString = Session::get('randomString');
            $cost = Session::get('transaction_cost');

            $transaction_id = $request['ORDERID'];
            if ( 'TXN_SUCCESS' === $request['STATUS'] ) {
                $transaction_id = $request['TXNID'];
                $transaction = Transaction::where( 'transaction_id',$randomString)->first();

                if (isset($transaction)) {
                    $transaction->update(['method_transaction_id'=>$transaction_id, 'status'=>'success']);
    
                    $data = array(
                        'user_id' => Auth::user()->id,
                        'user_email' => Auth::user()->email,
                        'amount' => $input['amount'],
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
                        $totalAmount = $addAccountBalance->balance + $input['amount'];
                        $addAccountBalance->balance = $totalAmount;
                        $addAccountBalance->update();
                    }else{
                        $initAccountBalance = new Accountbalance();
                        $initAccountBalance->user_id = Auth::user()->id;
                        $initAccountBalance->user_email = Auth::user()->email;
                        $initAccountBalance->balance = $input['amount'];
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
                }

                Session::forget('randomString');
                Session::forget('paypal_data');
                Session::forget('transaction_cost');

                return redirect()->route('user-deposit-money')->with('success', 'Deposit Amount Successfully.');

    
            } else if( 'TXN_FAILURE' === $request['STATUS'] ){
    
                return redirect()->back()->with('unsuccess', 'Payment Cancelled.');
            }
        }
}
