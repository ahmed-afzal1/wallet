<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ToolsController;
use App\Models\Accountbalance as AB;
use App\Models\Generalsetting as GS;
use Illuminate\Support\Str;
use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Moneyrequest;
use App\Models\Paymentgateway;
use App\Models\Craditcard;
use App\Helpers\Autoload;
use App\Http\Traits\UserLocalization;
use App\Models\Accountbalance;
use App\Models\Currency;
use App\Models\Bankaccount;
use App\Models\Countries;
use App\Models\Withdraw;
use App\Models\Deposit;
use Validator;
use App\User;
use Session;
use Carbon\Carbon;
use Auth;
use Hash;

class TransactionController extends Controller
{
    
    use UserLocalization;


    public function alltransaction()
    {
        $data['transactioninfo'] = Transaction::where('user_id',auth()->user()->id)
                                                ->orderBy('created_at','desc')->paginate(10);

        return view('userpanel.transaction.alltransaction',$data);
    }


    public function transactiondetails()
    {
        $transactionid = request()->tid;
        $transactiontype = request()->type;

        
        $data['transactioninfo'] = Transaction::where('transaction_id',$transactionid)->first();
 
        return view('userpanel.transaction.transactiondetails',$data);
    }


    public function depositformview()
    {
        $data['paymentgateways'] = Paymentgateway::where(['status'=>1])->get();  
        $data['currency'] = Currency::where(['status'=>1])->get();  
        $data['cards'] = Craditcard::where('user_id',Auth::user()->id)->where('is_approved','=','approved')->get();
        return view('userpanel.transaction.deposit',$data);
    }



    
    public function depositmoney(Request $request)
    {
        $roles = [
            'amount' => 'required|numeric',
            'paymentmethod' => 'required',
            'currency' => 'required',
        ];

        $validator = Validator::make($request->all(),$roles);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

       
        $data = array(
            'user_email' => Auth::user()->email,
            'amount' => Currency::convertwithusd($request->amount,$request->currency),
            'method' => $request->method,
            'currency' => $request->currency,
            'deposit_status' => 'pending',
            'created_at' => Carbon::now(),
        );
        $data['transaction_id'] = date('ymd').rand(1,999999);
        if(Deposit::insert($data)){
            $msg = [
                'type' => 'success',
                'message' => __("Deposit amount successfully."),
            ];
        }
        return response()->json($msg);
    }

    public function paymentfieldview($gateway)
    {
        $data['payment'] = $gateway;
        $data['cards'] = null;
        if($gateway == 'stripe'){
            $data['cards'] = Craditcard::where(['user_email'=>Auth::guard('web')->user()->email,'status'=>1,'is_approved'=>'approved'])->get();;
        }
        return view('userpanel.includes.payment',$data);
    } 


    /*
    |--------------------------------------------------------------------------
    | User Profile setting
    |--------------------------------------------------------------------------
    |
    | This method only for User Profile setting,  It called by {user-account-setting} route.
    |
    */
   public function sendformview()
   {
       $data['currency'] = AB::where('user_email',Auth::guard('web')->user()->email)->get();
       return view('userpanel.transaction.send', $data);
   }





   public function create(Request $request){

        $gs = GS::find(1);

        // validate input fields 
        $request->validate([
           'receiver' => 'required|email',
           'currency_id' => 'required',
           'amount' => 'required',
        ]);
     

        $user_email = Auth::user()->email;
        $authUser = auth()->user();
        $request_email = $request->receiver;
    
        if($user_email == $request_email){
            Session::flash('Warning',__('You can not send money yourself.'));
            return redirect()->back();
        }

        $currency = Currency::whereId($request->currency_id)->first();
        $getWalletBalance = Accountbalance::where('currency_id',$request->currency_id)->first()->balance;

        $transaction = Transaction::where('user_id',$authUser->id)->where('transaction_currency',$currency->id)->whereDate('created_at', Carbon::today())->get();
        $getTotalTransactionNumber = $transaction->count();
        $getTotalTransactionAmount = $transaction->sum('amount');
        $totalTransactionCost = $currency->fixed_transaction_charge + ($request->amount/100) * $currency->percentage_transaction_charge;


        if($getWalletBalance >= $request->amount){
            if($currency->transaction_limit>=$getTotalTransactionNumber){
                if($request->amount >= $currency->minimum_transaction_amount && $request->amount <= $currency->maximum_transaction_amount){
    
                    if($currency->transaction_limit_amount>=$getTotalTransactionAmount){
    
                        if(!User::where('email',$request->receiver)->exists()){
                            Session::flash('Warning',__('No register user with this email'));
                            return redirect()->back();
                        }
                        $isSenderWallet = Accountbalance::where('user_email',$request->receiver)->where('currency_id',$request->currency_id)->first();
    
                        if(empty($isSenderWallet)){
                            $reciver = User::where('email',$request->receiver)->first();
                            $newWallet = new Accountbalance();
                            $newWallet->user_id = $reciver->id;
                            $newWallet->user_email = $reciver->email;
                            $newWallet->balance = 0;
                            $newWallet->currency_id = $request->currency_id;
                            $newWallet->status = 1;
                            $newWallet->save();
                        }
                        $randomString = Str::random(40);
                        $data = new Transaction();
                        $data->user_id = Auth::user()->id;
                        $data->transaction_type = 'send';
                        $data->transaction_id = $randomString;
                        $data->amount = $request->amount;
                        $data->transaction_cost = $totalTransactionCost;
                        $data->transaction_currency = $request->currency_id;
                        if($request->paycost){
                            $data->costpay = Auth::guard('web')->user()->email;
                        }else{
                            $data->costpay = $request->receiver;
                        }
                        $data->sender = Auth::guard('web')->user()->email;
                        $data->sender_is = 'user';
                        $data->receiver = $request->receiver;
                        $data->receiver_is = 'user';
                        $data->reference = $request->reference;
                        $data->status = 'success';
                        if($gs->is_expected_delivery_date == 1){
                            $expacteddelivery = Autoload::incrementDate($gs->expected_delivery_date,date('Y-m-d'));
                            $data['expected_delivery_date'] = $expacteddelivery;
                        }
    
                        $data->save();
    
                        $reciver_id = User::where('email',$request->receiver)->first()->id;
                        $addAccountBalance = Accountbalance::where('currency_id',$request->currency_id)->where('user_id',$reciver_id)->first();
                        if($addAccountBalance){
                            if($request->paycost){
                                $totalAmount = $addAccountBalance->balance + $request->amount;
                            }else{
                                $totalAmount = $addAccountBalance->balance + $request->amount;
                                $totalAmount = $totalAmount - $totalTransactionCost;
                            }
                            $addAccountBalance->balance = $totalAmount;
                            $addAccountBalance->update();
                        }
    
                        $reduceAccountBalance = Accountbalance::where('currency_id',$request->currency_id)->where('user_id',Auth::user()->id)->first();
                        if($reduceAccountBalance){
                            if($request->paycost){
                                $totalAmount = $reduceAccountBalance->balance - ($request->amount + $totalTransactionCost);
                            }else{
                                $totalAmount = $reduceAccountBalance->balance - $request->amount;
                            }
                            $reduceAccountBalance->balance = $totalAmount;
                            $reduceAccountBalance->update();
                        }
    
                        $gs = GS::find(1);
    
                        $user = Auth::user();
                    
                        //Sending Email To User
                
                        $subject = "Your Money Request Placed!!";
                        $to = $user->email;
                        $msg = "Hello ".$user->name."!<br>Your Money Request Placed!.<br>Thank you.";
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
                           $subject = "Send Money Successfully!!";
                           $msg = "Hello ".$user->name."!<br>Your Money Request Placed!.<br>Thank you.";
                           $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                           mail($to,$subject,$msg,$headers);            
                        }
            
                
                        //Sending Email To Admin
                        if($gs->is_smtp == 1)
                        {
                            $data = [
                                'to' => $gs->email,
                                'subject' => "Money Request Recieved!!",
                                'body' => "Hello Admin!<br>You received a new money request. <br>Please login to your panel to check. <br>Thank you.",
                            ];
                
                            $mailer = new GeniusMailer();
                            $mailer->sendCustomMail($data);            
                        }
                        else
                        {
                           $to = $gs->email;
                           $subject = "Send Money Successfully!!";
                           $msg = "Hello Admin!<br>You received a new money request. <br>Please login to your panel to check. <br>Thank you.";
                           $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                           mail($to,$subject,$msg,$headers);
                        }
    
                        Session::flash('Success', __("Send Money Successfully!"));
                        return redirect()->back();
    
                    }else{
                        Session::flash('Warning',__('You can not send money today!'));
                        return redirect()->back();
                    }
                }else{
                    Session::flash('Warning',__('Transaction amount should be between minimum and maximum amount!'));
                    return redirect()->back();
                }
            }else{
                Session::flash('Warning',__('Your crossed today,s number of transaction limit!'));
                return redirect()->back();
            }
        }else{
            Session::flash('Warning',__('You don,t have sufficient balance!'));
            return redirect()->back();
        }

   }



    /*
    |--------------------------------------------------------------------------
    | User Profile search
    |--------------------------------------------------------------------------
    |
    | This function is for search an user in users table by the keyup in #searchuser 
    | input field using the route is {user-search} from transaction table if user 
    | create a transaction previeusly if transaction table is empty then its call user table 
    | it's return data without users self information
    |
    */
   public function searchuser()
   {
    $data = $userimage = null;

    $user_email = Auth::user()->email;

        if(request()->user){
            $user = trim(request()->user);
            /* 
            | fatch transaction tables users data where sender isn't admin 
            | and optimize users information by looping 
            */
            $senderusers = Transaction::select('sender')->where('sender', 'like', '%'.$user.'%')->where('sender','!=',Auth::guard('web')->user()->email)->where('sender_is','!=','admin')->groupBy(['sender'])->get();
            foreach($senderusers as $val){
                $userimage = $this->imageExistance($val->senderuser->photo);
                $data .= '<li class="useremail" data-image="'.$userimage.'"  data-email="'.$val->sender.'" data-name="'.ucwords($val->senderuser->name).'"><img src="'.$userimage.'" alt="'.$val->id.'"> <span>'.ucwords($val->senderuser->name).'</span></li>';
            }

            /* 
            | fatch transaction tables users data where receiver isn't admin 
            | and optimize users information by looping 
            */
            $receiverusers = Transaction::select('receiver')->where('receiver', 'like', '%'.$user.'%')->where('receiver','!=',Auth::guard('web')->user()->email)->where('receiver_is','!=','admin')->groupBy(['receiver'])->get();
            foreach($receiverusers as $val){
                $userimage = $this->imageExistance($val->receiveruser->photo);
                $data.= '<li class="useremail" data-image="'.$userimage.'"  data-email="'.$val->receiver.'" data-name="'.ucwords($val->receiveruser->name).'"><img src="'.$userimage.'" alt="'.$val->id.'"> <span>'.ucwords($val->receiveruser->name).'</span></li>';
            }
            
            
            /* 
            | hit users tables when searched user is not existance in transaction table 
            | and optimize users information by looping 
            */ 
            
            if($data == null){
                $users = null;
                
                $users = User::select('id','name','email','photo')->where('email', $user)->where('email','!=',$user_email)->first();
                if($users){
                    $userimage = $this->imageExistance($users->photo);
                    $data = array(
                        'single' => true,
                        'image' => $userimage,
                        'name' => ucwords($users->name),
                        'email' => $users->email
                    );
                    $data['data']= '<li class="useremail" data-image="'.$userimage.'"  data-email="'.$users->email.'" data-name="'.ucwords($users->name).'"><img src="'.$userimage.'" alt="'.$users->id.'"> <span>'.ucwords($users->name).'</span></li>';
                }
               
            }
        

            return response()->json($data);
        }else{
            return response()->json($data);
        }

    }



    /*
    |--------------------------------------------------------------------------
    | User photo existstance
    |--------------------------------------------------------------------------
    |
    | check userimage exists or not in user's table 
    | if not exists then return a default image 
    | this method is used in "searchuser" method in this controller
    |
   */
   function imageExistance($data){
        $userimage = null;
        if($data != null){
            $userimage =  asset($data);
        }else{
            $userimage = asset("assets/userpanel/images/user.jpg");
        }
        return $userimage;
   }





    /*
    |--------------------------------------------------------------------------
    | User exists
    |--------------------------------------------------------------------------
    |
    | This method only for check user exists or not,  
    | It called by {user-exist} route.
    |
    */
    public function userexist(){
        if(User::where('email',trim(request()->email))->exists()){
            return User::where('email',trim(request()->email))->first();
        }else{
            return 'false';
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Transaction limit calculation
    |--------------------------------------------------------------------------
    |
    | This method is only for calculation the user transaction limit. 
    | 
    |
   */
    public function translimit($currency = null,$transamount = null){

        if(request()->has('currency')){
            $currency = request()->currency;
        }
        if(request()->has('transamount')){
            $transamount = request()->transamount;
        }

        $gs =  Autoload::gs();
        $where = array(
            'sender'=>Auth::guard('web')->user()->email,
            'sender_is' => 'user',
            'transaction_type' => 'send',
            'created_at' => date('Y-m-d')
        );
        $currencydata = Currency::find($currency);
        $total = Transaction::select('balance')->where($where)->sum('amount');
        $transaction_limit_amount = Auth::guard('web')->user()->transaction_limit_amount;
        $transaction_limit = Auth::guard('web')->user()->transaction_limit;
        if($transaction_limit_amount == 0){
            $transaction_limit_amount = $gs->transaction_limit_amount;
            $transaction_limit = $gs->transaction_limit;
        }
        
        
        $data = array(
            'transaction_limit_amount' => $transaction_limit_amount,
            'max_transaction_amount' => $gs->max_transaction_amount,
            'transaction_limit' => $transaction_limit,
            'transaction_total' => $total + round(($transamount /  $currencydata->rate),2),
        );
        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction cost calculation
    |--------------------------------------------------------------------------
    |
    | This method is only for calculation the user transaction cost. 
    | 
    |
   */
    public function transcost($transamount = null,$currency=null,$type=null){

        if(request()->has('currency')){
            $currency = request()->currency;
        }

        $gs =  Autoload::gs();
        $currencydata = Currency::find($currency);
        if($transamount != null){
            if($type == 'withdraw'){
                $transaction_cost = $currencydata->fixed_withdraw_charge+($transamount/100)*$currencydata->percentage_withdraw_charge;
            }
        }
        return $transaction_cost;
    }

    /*
    |
    | This method is only view Request money form. 
    |
   */
    public function requestformview()
    {
       $data['currency'] = Currency::get();
       $data['userId'] = Auth::user()->id;
       return view('userpanel.transaction.request',$data);
    }


    /*
    |--------------------------------------------------------------------------
    | Request money create
    |--------------------------------------------------------------------------
    |
    | This method is only for create Request money transaction. 
    | 
    |
   */

    public function createmoneyrequest(Request $request)
    {

        $request->validate([
            'request_to' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'transaction_cost' => 'required',
        ]);

        $requestUser = $request->request_to;
        $authUser = Auth::user()->email;
        $requestAmount = $request->amount;

        $moneyRequest = Moneyrequest::where('request_from',Auth::guard('web')->user()->email)->where('currency_id',$request->currency_id)->whereDate('created_at', Carbon::today())->get();
        $getTotalRequestNumber = $moneyRequest->count();


        if($requestUser == $authUser){
            Session::flash('Warning','You can not request money yourself!');
            return redirect()->back();
        }

        if(!User::where('email',$requestUser)->exists()){
            Session::flash('Warning','No register user with this email.');
            return redirect()->back();
        }

        $currency = Currency::findOrFail($request->currency_id);

        $requestTransactionCost = $currency->fixed_request_charge + ($requestAmount/100) * $currency->percentage_request_charge;

        if($currency->money_request_per_day>=$getTotalRequestNumber){
   
            if($requestAmount >= $currency->minimum_request_money && $requestAmount <= $currency->maximum_request_money){
    
                $randomString = Str::random(40);
                $requestid = $randomString;
                while(Moneyrequest::where('request_id',$requestid)->exists()){
                    $requestid = time().$randomString;
                }
    
                $data = array(
                    'request_to' => $request->request_to,
                    'request_from' => Auth::guard('web')->user()->email,
                    'amount' => $requestAmount,
                    'request_id' =>  $requestid,
                    'transaction_cost' => $requestTransactionCost,
                    'currency_id' => $request->currency_id,
                    'referance' => $request->referance,
                    'status' => 'pending',
                );
    
                Moneyrequest::create($data);
    
                Session::flash('Success','Request send successfully.');
                return redirect()->back();
    
            }else{
                Session::flash('Warning','Request Money should be between minium and maximum amount!');
                return redirect()->back();
            }
        }else{
            Session::flash('Warning','Your Number of limit cross Today!');
            return redirect()->back();
        }

    }


    /*
    |
    | This method is only view Request money form. 
    |
   */
    public function moneyrequestreceived()
    {
        $data['requestreceived'] = Moneyrequest::where(['request_to'=>Auth::guard('web')->user()->email])->orderBy('status','asc')->orderBy('created_at','desc')->get();
        return view('userpanel.transaction.receivedrequest',$data);
    }



    public function showmoneyrequest($requestid)
    {
        $data['requestinfo'] = Moneyrequest::where('request_id',$requestid)->first();
        return view('userpanel.transaction.requestshow',$data);
    }


    public function acceptmoneyrequest(Request $request, $requestid)
    {
        $requestdata = Moneyrequest::where('request_id',$requestid)->first();

        $isWalletExist = Accountbalance::where('user_email',$requestdata->request_to)->where('currency_id',$requestdata->currency_id)->exists();

        if($isWalletExist){
            $receiveraccount = AB::where(['user_email'=> $requestdata->request_from,'currency_id'=>$requestdata->currency_id])->first();
            $senderaccount = AB::where(['user_email'=> $requestdata->request_to,'currency_id'=>$requestdata->currency_id])->exists();

            if($senderaccount){
                $senderaccount = AB::where(['user_email'=> $requestdata->request_to,'currency_id'=>$requestdata->currency_id])->first();
                $isWalletExist = Accountbalance::where('user_email',$requestdata->request_to)->where('currency_id',$requestdata->currency_id)->first();
                $amount = $requestdata->amount;
                $balance = $senderaccount->balance;
    
                    if($isWalletExist->balance > $amount){
    
                        $randomString = Str::random(40);
                        $transaction = new Transaction();
                        $transaction->user_id = Auth::user()->id;
                        $transaction->transaction_type = 'send';
                        $transaction->transaction_id = $requestdata->request_id;
                        $transaction->amount = $requestdata->amount;
                        $transaction->transaction_cost = $requestdata->transaction_cost;
                        $transaction->transaction_currency = $requestdata->currency_id;
                        $transaction->sender = $requestdata->request_to;
                        $transaction->receiver = $requestdata->request_from;
                        $transaction->status = 'success';
                        $transaction->save();
    
                        $reciverTotalAmount = $receiveraccount->balance + $amount;
                        $receiveraccount->balance = $reciverTotalAmount;
                        $receiveraccount->update();
        
                        $senderTotalAmount = $senderaccount->balance - ($amount + $requestdata->transaction_cost);
                        $senderaccount->balance = $senderTotalAmount;
                        $senderaccount->update();
    
                        $requestdata->status = 'completed';
                        $requestdata->update();

                        $gs = GS::find(1);
    
                        $user = Auth::user();
                    
                        //Sending Email To User
                
                        $subject = "Money send successfully!!";
                        $to = $user->email;
                        $msg = "Hello ".$user->name."!<br>Request money send successfully!.<br>Thank you.";
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
                           $subject = "Money send successfully!!";
                           $msg = "Hello ".$user->name."!<br>Request money send successfully!.<br>Thank you.";
                           $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                           mail($to,$subject,$msg,$headers);            
                        }
            
                
                        //Sending Email To Admin
                        if($gs->is_smtp == 1)
                        {
                            $data = [
                                'to' => $gs->email,
                                'subject' => "Money send successfully!!",
                                'body' => "Hello Admin!<br>Request money send successfully. <br>Please login to your panel to check. <br>Thank you.",
                            ];
                
                            $mailer = new GeniusMailer();
                            $mailer->sendCustomMail($data);            
                        }
                        else
                        {
                           $to = $gs->email;
                           $subject = "Money send successfully!!";
                           $msg = "Hello Admin!<br>Request money send successfully. <br>Please login to your panel to check. <br>Thank you.";
                           $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                           mail($to,$subject,$msg,$headers);
                        }


    
                           $msg = array(
                                'type' => 'success',
                                'message' => 'Request accept successfuly.',
                            );
                            return response()->json($msg);
    
                    }else{
                        $msg = array(
                            'type' => 'warn',
                            'message' => 'Insufficient account balance!');
                            return response()->json($msg);
                    }
            }else{
                $msg = array(
                    'type' => 'success',
                    'message' => 'You don,t have any wallet with this currency!',
                );
                return response()->json($msg);
            }

        }else{
            $msg = array(
                    'type' => 'warn',
                    'message' => 'You don,t have wallet assoicate with request currency.');
                    return response()->json($msg);
        }

    }

    public function deletemoneyrequest($requestid)
    {
        if(Moneyrequest::where('request_id',$requestid)->delete()){
            Session::flash('Success','Request deleted successfully.');
          
        }else{
            Session::flash('Warning','Request not deleted. please try again.');
        }
        return redirect()->back();
    }


    // withdraw methods 

    public function withdraw()
    {
        $data['bankaccount'] = Bankaccount::where(['user_email'=>Auth::guard('web')->user()->email,'status'=>1])->get();  
        $data['currency'] = Currency::where(['status'=>1])->get();
        $data['paymentgateways'] = Paymentgateway::where(['status'=>1])->get();
        $data['countries'] = Countries::get();
        $data['bankaccounts'] = Bankaccount::where('user_id',Auth::user()->id)->get();
        $data['defaultCurrency'] = Currency::where('is_default',1)->first();
        $data['userId'] = Auth::user()->id;
        
        return view('userpanel.transaction.withdraw',$data);
    }
   

    public function  withdrawbalancecheck()
    {
        $cost = $this->transcost(request()->amount,request()->currency,'withdraw');
        return $cost;
    }


    public function withdrawrequest(Request $request)
    {
        $roles = [
            'account_type' => 'required',
            'amount' => 'required',
            'currency' => 'required',
        ];

        $customs = [
            'account_type.required' => 'Withdraw Method is required.',
            'amount.required' => 'Amount field is required.',
            'currency.required' => 'Currency field is required.',
        ];

        $validator = Validator::make($request->all(),$roles,$customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        


        if(AB::where(['user_email' => Auth::guard('web')->user()->email,'currency_id'=>$request->currency])->exists()){
            $accountbalance = AB::where(['user_email' => Auth::guard('web')->user()->email,'currency_id'=>$request->currency])->first();
            
            if ($request->amount > $accountbalance->balance) {
                return response()->json(array('errors' => [__("Insufficient account balance!!")]));
            }
        }else{
             return response()->json(array('errors' => [__("No Wallet with this currency!!")]));
        }
        
        $currency = Currency::find($request->currency)->currencyBalance;
        
        if(!empty($currency)){
            $transaction_cost = $this->transcost($request->amount,$request->currency,'withdraw');
        }else{
            $transaction_cost = 0;
        }



        $withdrawid = Autoload::alphaNumeric(10);
        $isWallet = Accountbalance::where('currency_id',$request->currency)->where('user_id',Auth::user()->id)->first();
        $reduceAmount = $request->amount + $transaction_cost;

        if($isWallet){
            if($isWallet->balance>$reduceAmount){
                $data = new Withdraw();

                if($request->account_type == 'Bank'){
                    if($request->bank_id == 0){
                        if($request->isCheckBankSave == 1){
                            $isBankAccount = Bankaccount::where('user_id',Auth::user()->id)->where('account_number',$request->account_number)->first();
                            if(empty($isBankAccount)){
                                $bankdata = array(
                                    'user_id' =>  Auth::user()->id,
                                    'user_email' =>  Auth::guard('web')->user()->email,
                                    'country' =>  $request->country,
                                    'bank_name' =>  $request->bank_name,
                                    'account_name' => $request->account_name,
                                    'account_number' => $request->account_number,
                                    'bank_account_currency' =>  $request->currency,
                                    'swift_code' => $request->swift_code,
                                    'routing_number' => $request->routing_number,
                                );
                                Bankaccount::insert($bankdata);
                            }
                        }
                    }else{
                        $getBankAccount = Bankaccount::where('user_id',Auth::user()->id)->first();
                    }
                }

                $randomString = Str::random(40);
                $transaction = new Transaction();
                $transaction->user_id = Auth::user()->id;
                $transaction->transaction_type = 'withdraw';
                $transaction->transaction_id = $randomString;
                $transaction->amount = $request->amount;
                $transaction->transaction_cost = $transaction_cost;
                $transaction->transaction_currency = $request->currency;
                $transaction->method = $request->account_type;
                $transaction->reason = "Payment Withdraw";
                $transaction->status = 'pending';
                $transaction->save();

                $data->user_id = Auth::user()->id;
                $data->user_email = $request->user_email;
                $data->transaction_id = $randomString;
                $data->amount = $request->amount;
                $data->transaction_cost = $transaction_cost;
                $data->account_type = $request->account_type;
                $data->transaction_currency = $request->currency;
                if($request->account_type == 'Bank'){
                    if($request->bank_id == 0){
                        $data->account_number = $request->account_number;
                    }else{
                        $data->account_number = $getBankAccount->account_number;
                    }
                }
                $data->withdraw_status = 'pending';
                $data->save();


                $gs = GS::find(1);

                $user = Auth::user();
            
                //Sending Email To User
        
                $subject = "Your Withdraw Request Placed!!";
                $to = $user->email;
                $msg = "Hello ".$user->name."!<br>Your Withdraw Request Placed!.<br>Thank you.";
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
                   $subject = "Your Withdraw Request Placed!!";
                   $msg = "Hello ".$user->name."!<br>Your Withdraw Request Placed!.<br>Thank you.";
                   $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);            
                }
    
        
                //Sending Email To Admin
                if($gs->is_smtp == 1)
                {
                    $data = [
                        'to' => $gs->email,
                        'subject' => "Withdraw Request Recieved!!",
                        'body' => "Hello Admin!<br>You received a new withdraw request. <br>Please login to your panel to check. <br>Thank you.",
                    ];
        
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                   $to = $gs->email;
                   $subject = "Withdraw Request Recieved!!";
                   $msg = "Hello Admin!<br>You received a new withdraw request. <br>Please login to your panel to check. <br>Thank you.";
                   $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);
                }

                $msg = 'Withdraw request send successfully.';
                return response()->json($msg);
            }else{
                return response()->json(array('errors' => [__("Insufficient account balance!!")]));
            }
        }else{
            return response()->json(array('errors' => [__("No Wallet associate with this account!")]));
        }
    }



    public function refundbalance (Request $request,$id)
    {
        
        $transdata = Transaction::findOrFail($id);
  
        if($transdata == null){      
            Session::flash('Warning', __("Invalid Transaction Id"));
            return redirect()->back();  
        }
        
  
        if($transdata->refund_status == 1){      
            Session::flash('Warning', __("All ready refunded"));
            return redirect()->back();  
        }

        $transdata->refund_status = 1;
        $transdata->save();
        


        $userinfo = AB::where(['user_email'=>$transdata->receiver,'currency_id'=>$transdata->transaction_currency])->first();
        

        $gs = Autoload::gs();
        $amountWithCost = $request->amount + $request->transaction_cost;


        $transaction = new Transaction();

        $transaction->user_id = auth()->user()->id;
        if($gs->transaction_refund_with_cost == 0){
            $transaction->amount = $request->amount;
        }else{
            $transaction->amount = $amountWithCost;
        }

        $transaction->transaction_type = 'refund';
        $transaction->transaction_id = Autoload::transactionId();
        $transaction->transaction_currency = $transdata->transaction_currency;
        $transaction->sender = $request->sender;
        $transaction->receiver = $request->receiver;
        $transaction->sender_is = $transdata->receiver_is;
        $transaction->receiver_is = $transdata->sender_is;
        $transaction->costpay = 'Free Cost';
        $transaction->status = 'success';
        $transdata->refund_status = 1;
        $transaction->created_at = Carbon::now();

        $transaction->save();


        $isRefundReceiver = AB::where(['user_email'=>$transdata->sender,'currency_id'=>$transdata->transaction_currency])->exists();
        $isRefundUser = AB::where(['user_email'=>$transdata->receiver,'currency_id'=>$transdata->transaction_currency])->exists();

        if($gs->transaction_refund_with_cost == 0){
            if($isRefundReceiver && $isRefundUser){
                $userinfo = AB::where(['user_email'=>$transdata->receiver,'currency_id'=>$transdata->transaction_currency])->first();
                if($userinfo->balance>=$request->amount ){
                    $receverinfo = AB::where(['user_email'=>$transdata->sender,'currency_id'=>$transdata->transaction_currency])->first();
                    $receverinfo->increment('balance',$request->amount);

                    $userinfo->decrement('balance',$request->amount);

                    Session::flash('Warning', __("Refund Successfully"));
                    return redirect()->back();
                }else{
                    Session::flash('Warning', __("Reciver insufficient account balance"));
                    return redirect()->back();
                }

            }else{
                Session::flash('Warning', __("You/reciver don,t have this currency wallet"));
                return redirect()->back();
            }
        }else{
            if($isRefundReceiver && $isRefundUser){
                $userinfo = AB::where(['user_email'=>$transdata->receiver,'currency_id'=>$transdata->transaction_currency])->first();
                if($userinfo->balance>=$amountWithCost ){
                    $receverinfo = AB::where(['user_email'=>$transdata->sender,'currency_id'=>$transdata->transaction_currency])->first();
                    $receverinfo->increment('balance',$amountWithCost);

                    $userinfo->decrement('balance',$amountWithCost);

                    Session::flash('Warning', __("Refund Successfully"));
                    return redirect()->back();
                }else{
                    Session::flash('Warning', __("Reciver insufficient account balance"));
                    return redirect()->back();
                }

            }else{
                Session::flash('Warning', __("You/reciver don,t have this currency wallet"));
                return redirect()->back();
            }
        }

    }
}
