<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserLocalization;
use App\Models\Accountbalance;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ExchangeController extends Controller
{
    use UserLocalization;

    public function exchange(){
        $user = auth()->user();
        $defaultCurrency = $user->default_currency;
        $data['default_wallet'] = Accountbalance::where('currency_id',$defaultCurrency)->where('user_email',$user->email)->first();
        $data['user'] = $user;
        $data['from_wallets'] = Accountbalance::where('user_email',$user->email)->get();
        $data['to_wallets'] = Accountbalance::where('currency_id','!=',$defaultCurrency)->where('user_email',$user->email)->get();
        return view('userpanel.exchange.index',$data);
    }

    public function fromWallet($id){
        $user = auth()->user();
        $datas = Accountbalance::where('currency_id','!=',$id)->where('user_email',$user->email)->get();
        $output = '<option value="">Select Wallet</option>';
        foreach($datas as $data){
            $output .= '<option value="'.$data->currency_id.'" data-balanceToWallet="'.round($data->balance,2).'" data-currencyCodeToWallet="'.$data->currency->code.'">'.$data->currency->code.'</option>';
        }
        return $output;
    }

    public function moneyexchange(Request $request){
        $roles = [
            'from_currency_id' => 'required',
            'currency_id' => 'required',
            'amount' => 'bail|required|integer|gt:0'
        ];

        $customs = [
            'from_currency_id.required' => 'From Wallet is required',
            'currency_id.required' => 'To Wallet is required',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount should be numeric',
            'amount.gt' => 'Amount should be greater then 0',
        ];

        $validator = Validator::make($request->all(),$roles,$customs);

        $user = auth()->user();


        $amount = doubleval($request->amount);
        $fromWallet = Accountbalance::where('user_email',$user->email)->where('currency_id',$request->from_currency_id)->first();
        $toWallet = Accountbalance::where('user_email',$user->email)->where('currency_id',$request->currency_id)->first();
        $fromCurrency = Currency::where('id',$request->from_currency_id)->first();
        $toCurrency = Currency::where('id',$request->currency_id)->first();


        if($amount>0 && $amount != 0){
            if($fromWallet->balance >= $amount){
                $baseCurrencyAmount = round($amount/$fromCurrency->rate,2);
                $amountToAdd = round($toCurrency->rate*$baseCurrencyAmount,2);
                
                $fromWallet->decrement('balance',$amount);
                $toWallet->increment('balance',$amountToAdd);

                $msg = 'Amount exchange successfully';
                return redirect()->back()->with('success',$msg);
            }else{
                $msg = 'You don,t have sufficient balance';
                return redirect()->back()->with('Warning',$msg);
            }
        }else{
            $msg = 'Amount should be grater then 0';
            return redirect()->back()->with('Warning',$msg);
        }

    }
}
