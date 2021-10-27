<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ManualController extends Controller
{
    public function store(Request $request){

        if($request->amount > 0){

            $acc = Auth::user()->id;
            $item_number = strtoupper($this->goRandomString(4).Str::random(3).$this->goRandomString(4));

            $currency = Currency::findOrFail($request->currency);
            $fixedDepositCharge = $currency->fixed_deposit_charge;
            $percentageDepositCharge = $currency->percentage_deposit_charge;
    
            $amountInPercentage = round(($percentageDepositCharge*$request->amount)/100,2);
            $cost = $fixedDepositCharge + $amountInPercentage;
            $amountToPay = $request->amount + $cost;
            $currency_amount = $amountToPay;

            $item_amount = $request->amount;
            $currency_code = $currency->code;


            $randomString = strtoupper($this->goRandomString(4).Str::random(3).$this->goRandomString(4));
            $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->transaction_type = 'deposit';
            $transaction->transaction_id = $item_number;
            $transaction->transaction_currency = $request->currency;
            $transaction->amount = $request->amount;
            $transaction->transaction_cost = $cost;
            $transaction->method = $request->paymentmethod;
            $transaction->manual_number = $request->manual_number;
            $transaction->reason = "Account Deposit";
            $transaction->status = 'pending';
            $transaction->save();
            $transaction_id = $transaction->id;


            $data = array(
                'user_id' => Auth::user()->id,
                'user_email' => Auth::user()->email,
                'amount' => $currency_amount,
                'cost' => $cost,
                'method' => 'BlockChain',
                'currency' => $currency->id,
                'deposit_status' => 'pending',
                'created_at' => Carbon::now(),
            );
            
            $data['transaction_id'] = strtoupper($this->goRandomString(4).Str::random(3).$this->goRandomString(4));
            Deposit::insert($data);

            return redirect()->back()->with('success', 'Deposit Amount is in Process.Please wait for admin confirmation');

        }
            return redirect()->back()->with('unsuccess','Please enter a valid amount.');
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
}
