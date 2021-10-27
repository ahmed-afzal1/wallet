<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Deposit;
use App\Models\Currency;

class DepositController extends Controller
{
    use Localization;
    

    public function datatables()
    {

        $datas = Deposit::orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return DataTables::of($datas)
                                ->editColumn('amount', function(Deposit $data) {
                                    $currencySign = Currency::where('id',$data->currency)->first()->sign;
                                    $balance = $data->amount.''.$currencySign;
                                    return $balance;               
                                })
                                ->editColumn('created_at', function(Deposit $data) {
                                    $created_at = \Carbon\Carbon::parse($data->created_at)->format('d M Y');
                                    return $created_at;               
                                })
                                ->addColumn('status', function( $data) {
                                    if($data->deposit_status == 'pending'){
                                        return '<button class="btn badge-warning btn-sm">Pending</button>';
                                    }else{
                                        return '<button class="btn badge-success btn-sm">Complete</button>';
                                    }
                                
                                })
                                ->addColumn('action', function(Deposit $data) {
                                    return '<div class="action-list"><a href="'.route('admin.deposit.details',[$data->id]).'"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View') .'</a></div>';
                                })
           
                                ->rawColumns(['user_email','amount','created_at','status','action'])
                                ->toJson();
    }

    public function index()
    {
        return view('admin.deposit.index');
    }


    public function details($id)
    {
        $data['deposit'] = Deposit::where('id',$id)->first();
        return view('admin.deposit.details', $data);
    }

    public function completedeposit($id)
    {
        $deposit = Deposit::where('id',$id)->first();


        $data = array(
            'transaction_type'=>'deposit',
            'transaction_id' => Autoload::transactionId(),
            'amount' => $deposit->amount,
            'transaction_currency' => $deposit->currency,
            'sender' => $deposit->user_email,
            'sender_is' => 'user',
            'method' => $deposit->method,
            'method_transaction_id' => $deposit->method_transaction_id,
            'expected_delivery_date' => $deposit->method_transaction_id,
            'status' => 'complete',
        );

        Transaction::insert($data);
        Deposit::where('id',$id)->update(['deposit_status'=>'complete']);
        $msg = [
            'type' => 'success',
            'messege' => 'Deposit complete successfully.'
        ];
     return response()->json($msg);
    }
}
