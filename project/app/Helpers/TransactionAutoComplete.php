<?php
namespace App\Helpers;
use App\Models\Transaction;
use App\Models\Generalsetting as GS;
use App\Models\Accountbalance as AB;
use Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionAutoComplete{
    
    public static function AutoDelivery($transactionid = null){
        
        $amount = 0;
        $gs = GS::find(1);
        $receverdata = array(
            'updated_at' => date('Y-m-d')
        );
        if($transactionid == null){
            if($gs->is_expected_delivery_date == 0){
                return 0;
            }
            $transactionlist = Transaction::where('status','pending')->where('expected_delivery_date', '<=', date('Y-m-d'))->get();
        }else{
            $transactionlist = Transaction::where('transaction_id', $transactionid)->get();
        }
        
        if($transactionlist != null){
            foreach($transactionlist as $value){
                $amount = $value->amount;
                if($value->costpay == 'receiver'){
                    $amount = ($value->amount - $value->transaction_cost);
                }
                // return $value;

                $data = array(
                    'user_id' => Auth::user()->id,
                    'user_email' => $value->receiver,
                    'balance' => $amount,
                    'currency_id' => $value->transaction_currency,
                );

                $user = AB::where(['user_email'=> $value->receiver,'currency_id'=>$value->transaction_currency])->first();

                if($user != null){
                    $receverdata['balance'] = $user->balance + $amount;
                    $receverdata['updated_at'] = date('Y-m-d');
                    AB::where('id',$user->id)->update($receverdata);
                }else{
                    AB::insert($data);
                }
                Transaction::where('id',$value->id)->update(['expected_delivery_date' => date('Y-m-d'),'status'=>'complete']);
           

            }
        }
        return 1;
    }
    

}
