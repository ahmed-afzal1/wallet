<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\BaseController;
use App\Http\Traits\UserLocalization;
use App\Models\Accountbalance;
use App\Models\Currency;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoiceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth',['except' => ['linkPay','login']]);
    }
    public function invoicelist(){
        $data['invoices'] = Invoice::where('user_id',auth()->user()->id)->orderby('id','desc')->get();
        return view('userpanel.invoices.index',$data);
    }

    public function invoice(){
        $data['currencies'] = Currency::orderBy('id','desc')->get();
        return view('userpanel.invoices.create',$data);
    }

    public function invoiceSend(Request $request){
        $input = $request->all();
        $data = new Invoice();
        $input['user_id'] = auth()->user()->id;
        $input['token'] = Str::random(4).time();
        $data->fill($input)->save();

        $msg = 'Invoice Created Successfully';
        return redirect()->back()->with('success',$msg);
    }


    public function linkdetails($id){
        $link = Invoice::findOrFail($id);
        $currency = Currency::whereId($link->currency_id)->first();

        $details = '<table class="table" style="table-layout:fixed;">
                        <tbody>
                        <tr>
                            <td class="whatis">Payment Link ID#</td>
                            <td>'.str_pad($link->id, 9, '0', STR_PAD_LEFT).'</td>
                        </tr>
                        
                        <tr>
                            <td class="whatis">Created Date:</td>
                            <td>'.date('H:i d/m/Y',strtotime($link->created_at)).'</td>
                        </tr>

                        <tr>
                            <td class="whatis">Customer Name</td>
                            <td>'.$link->name.'</td>
                        </tr>
                        <tr>
                            <td class="whatis">Customer Email:</td>
                            <td>'.$link->email.'</td>
                        </tr>
                        <tr>
                            <td class="whatis">Requested Total:</td>
                            <td>'.$currency->sign.$link->amount.'</td>
                        </tr>';
                        if($link->status == 'pending'){
                            $details .=' <tr><div>
                                            <td colspan="2"><h4 class="pull-left">Payment Link: </h4>
                                            <input onclick="myFunction()" type="text" class="form-control" value="'.url('/account/invoice/link').'?p='.$link->token.'" readonly id="myInput">
                                            <button style="bottom:48px;right: 25px;" onclick="myFunction()" class="copyBtn" id="copy_btn"><i class="fa fa-copy"></i> Copy Link</button></td>
                                        </div></tr>
                                    
                                    
                                    <script>
                                        function myFunction() {
                                            var copyText = document.getElementById("myInput");
                                            copyText.select();
                                            copyText.setSelectionRange(0, 99999)
                                            document.execCommand("copy");
                                            $("#copy_btn").html("<i class=\'fa fa-copy\'></i> Copied!");
                                        }
                                    </script>';
                        }else{
                            $details .=' <tr>
                                            <td class="whatis">Transaction ID:</td>
                                            <td>'.fgh.'</td>
                                        </tr>';
                        }        
                $details .='      </tbody>
                            </table>';

                return $details;
        }

        public function linkPay(){
            
            $defaultCurrency= Currency::first();
            $currency = Currency::whereId($defaultCurrency->id)->first();
            $token = $_GET['p'];
            
            if(Invoice::where('token',$token)->where('status','pending')->exists()){
    
                $payData = Invoice::where('token',$token)->first();
    
                session(['link_pay_tkn'=> $token]);
                return view('userpanel.payment-by-link',compact('payData','currency'));
            }else{
                
                return view('userpanel.payment-by-link-error',compact('currency'));
            }
        }

        public function login(Request $request){

            $rules = [
                'email'   => 'required|email',
                'password' => 'required'
            ];

            $customs = [
                'email.required' => 'email is required',
                'email.email' => 'field should be email type',
                'password.required' => 'password is required',
            ];
            $validator = Validator::make($request->all(),$rules,$customs);

            $token = Session::get('link_pay_tkn');

            // Attempt to log the user in
            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                // Autoload::agent();

                if(Auth::guard('web')->user()->email_verified_at == null)
                {
                    Auth::guard('web')->logout();
                    $msg = array(
                        'type' => 'warn',
                        'message' => "Your Email is not Verified!"
                    );
                    return response()->json(array('errors' => $msg));     
                }

                if(Auth::guard('web')->user()->status == 0){
                    $msg = array(
                        'type' => 'warn',
                        'message' => "Your Account Has Been Banned.!"
                    );
                    return response()->json(array('errors' => $msg)); 
                }
                return response()->json(route('user.account.link',['p'=>$token]));
            }else{
                $msg = array(
                    'type' => 'warn',
                    'message' => "Credentials Doesn\'t Match !"
                );
                return response()->json(array('errors' => $msg));  
            }
        }

        public function invoiceMoney(Request $request){
            $user = auth()->user();
            
            if($user->email == $request->email){
                Session::flash('Warning',__('You can,t send money yourself.'));
                return redirect()->back();
            }

            $isWallet = Accountbalance::where('user_id',$user->id)->where('currency_id',$request->currency)->exists();

            if($isWallet){
                $wallet = Accountbalance::where('user_id',$user->id)->where('currency_id',$request->currency)->first();
                $toWallet = Accountbalance::where('user_email',$request->email)->where('currency_id',$request->currency)->exists();
                if($toWallet ){
                    if($wallet->balance>= $request->amount){
                        $getWallet = Accountbalance::where('user_email',$request->email)->where('currency_id',$request->currency)->first();

                        $wallet->decrement('balance',$request->amount);
                        $getWallet->increment('balance',$request->amount);
        
                        $msg = 'Money send successfully';
                        return redirect()->back()->with('success',$msg);
                    }else{
                        Session::flash('Warning',__('You don,t have sufficient balance.'));
                        return redirect()->back();
                    }
                }else{
                    Session::flash('Warning',__('Receiver does not have any wallet with this currency.'));
                    return redirect()->back();
                }

            }else{
                Session::flash('Warning',__('You don,t have any wallet with this currency.'));
                return redirect()->back();
            }
        }
}
