<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Helpers\TransactionAutoComplete as TA;
use App\Models\Generalsetting as GS;
use App\Helpers\Autoload;
use App\Http\Traits\Localization;
use App\Models\Accountbalance;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\User;
use DataTables;
use Carbon;

class TransactionController extends Controller
{
    use Localization;

    public function allTransactions()
    {
        return view('admin.transaction.index');
    }

    public function transactionsDatatable()
    {
        if(request()->transaction_type){
            if(request()->transaction_type == 'all'){
                $datas = Transaction::orderBy('id','desc')->get();
            }else{
                $datas = Transaction::where('transaction_type',request()->transaction_type)->orderBy('id','desc')->get();
            }
        }else{
            $datas = Transaction::orderBy('id','desc')->get();
        }

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->editColumn('created_at',function(Transaction $data){
                                return $data->created_at->toDateString();
                            })
                            ->addColumn('user', function(Transaction $data) {
                                $userEmail = User::findOrFail($data->user_id)->email;
                                return $userEmail;           
                            })
                            ->addColumn('receiver', function(Transaction $data) {
                                $receiver = $data->receiver ? $data->receiver:'-';
                                return $receiver;           
                            })
                            ->editColumn('status', function(Transaction $data) {
                                $status      = $data->status == "success" ? '<button class="btn btn-sm btn-success">'.__("Success").'</button>' : '<button class="btn btn-sm btn-danger">'.__("Pending").'</button>';
                               return $status;
                            })
                            ->editColumn('amount', function(Transaction $data) {
                                $currencySign = Currency::where('id',$data->transaction_currency)->first()->sign;
                                $balance = Currency::convertwithcurrencyrate($data->amount,true);
                                return $balance.''.$currencySign;               
                            })
                            ->addColumn('action', function(Transaction $data) {
                                return '<div class="action-list"><a href="'. route('admin.transaction.details',$data->transaction_id) . '"  class="btn btn-primary btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Details') .'</a></div>';
                            })
                            ->rawColumns(['created_at','user','receiver','status','amount','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    
    public function transactionsdetails($transactionid)
    {
        $data['transactiondetails'] = Transaction::where('transaction_id',$transactionid)->first();
        $transactionid = $data['transactiondetails']['transaction_id'];
        $data['withdrawdetails'] = Withdraw::where('transaction_id',$transactionid)->first();
        return view('admin.transaction.details', $data);
    }

    
    public function completetransaction($transactionid)
    {
        $status = Transaction::where('transaction_id',$transactionid)->first();

        if($status->transaction_type == 'deposit'){
            $status->status = 'success';
            $status->update();

            $addAccountBalance = Accountbalance::where('currency_id',$status->transaction_currency)->where('user_id',$status->user_id)->first();
            $deposit = Deposit::where('transaction_id',$transactionid)->first();
            if($addAccountBalance){
                $totalAmount = $addAccountBalance->balance + $status->amount;
                $addAccountBalance->balance = $totalAmount;
                $addAccountBalance->update();         

            }else{
                $initAccountBalance = new Accountbalance();
                $initAccountBalance->user_id = $deposit->user_id;
                $initAccountBalance->user_email = $deposit->user_email;
                $initAccountBalance->balance = $status->amount;
                $initAccountBalance->currency_id = $status->transaction_currency;
                $initAccountBalance->status = 1;
                $initAccountBalance->save();
            }
            return 1;
        }
        $withdraw = Withdraw::where('transaction_id',$transactionid)->first();
        $isAccount = Accountbalance::where('currency_id',$status->transaction_currency)->where('user_id',$status->user_id)->first();
        $reduceAmount = $status->amount + $status->transaction_cost;
        if($isAccount){
            if($isAccount->balance>$reduceAmount){
                $isAccount->balance = $isAccount->balance - $reduceAmount;
                $isAccount->update();
            }
        }
        $withdraw->withdraw_status = 'complete';
        $withdraw->update();

        $status->status = 'success';
        $status->update();
        return 1;
    }


    public function withdraw()
    {
        return view('admin.withdraw.index');
    }


    public function request()
    {
        return view('backend.withdraw.withdrawrequest');
    }


    public function withdrawdetails($id)
    {
        $withdraw = Withdraw::where('id',$id)->first();
        $data['user'] = User::findOrFail($withdraw->user_id);
        
        $data['currencySign'] = Currency::findOrFail($withdraw->transaction_currency)->sign;
        $data['withdraw'] = $withdraw;
        return view('admin.withdraw.details', $data);
    }

    public function withdrawdelete($id){

    }

    public function withdrawlatalist()
    {
        $datas = Withdraw::orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->editColumn('created_at',function(Withdraw $data){
                                return $data->created_at->toDateString();
                            })
                            ->editColumn('transaction_currency',function(Withdraw $data){
                                $currency = Currency::findOrFail($data->transaction_currency);
                                return $currency->code;
                            })
                            ->editColumn('user_email', function(Withdraw $data) {
                                $userEmail = User::findOrFail($data->user_id)->email;
                                return $userEmail;           
                            })
                            ->addColumn('action', function(Withdraw $data) {
                                return '<div class="action-list"><a href="'. route('admin-withdraw-details',$data->id) . '"  class="btn btn-primary btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Details') .'</a></div>';
                            })
                            ->rawColumns(['created_at','transaction_currency','user_email','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function approve($id){
        $data = Withdraw::findOrFail($id);
        $data->withdraw_status = 'complete';

        $transaction = Transaction::where('transaction_id',$data->transaction_id)->first();
        $transaction->status = 'success';

        $isWallet = Accountbalance::where('currency_id',$data->transaction_currency)->where('user_id',$data->user_id)->first();
        $reduceAmount = $data->amount + $data->transaction_cost;
        if($isWallet){
            if($isWallet->balance>$reduceAmount){
                $isWallet->balance = $isWallet->balance - $reduceAmount;
                $isWallet->update();
            }
        }

        $data->update();
        $transaction->update();

        return redirect()->back()->with('success','Withdraw status successfully');
    }


    public function delete($id){
        Transaction::findOrFail($id)->delete();

         //--- Redirect Section        
            $msg = 'Data Deleted Successfully!';
            return response()->json($msg);          
         //--- Redirect Section Ends 
    }


}
