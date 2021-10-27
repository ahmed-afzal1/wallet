<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bankaccount;
use App\Models\Craditcard;
use DataTables;

class BankaccountsController extends Controller
{
    use Localization;

    public function index()
    {
        return view('admin.bankaccount.index');
    }

    public function datatables()
    {

         $accontlist = Bankaccount::orderBy('id','desc')->get();

        return DataTables::of($accontlist)
            ->addColumn('action', function(Bankaccount $data) {
                return '<div class="action-list"><a href="'. route('admin.bankaccount.show',$data->id) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Account') .'</a><a href="javascript:;" data-href="' . route('admin.bankaccount.delete',$data->id) . '" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->addColumn('status', function(Bankaccount $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.$status .'
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.bankaccount.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Activate").'</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.bankaccount.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Deactivate").'</a>
                            </div>
                        </div>';
            })
            ->editColumn('account_name',function($accontlist){
                    return ucwords($accontlist->account_name);
            })
            ->editColumn('bank_name',function($accontlist){
                    return ucwords($accontlist->bank_name);
            })
            ->rawColumns(['action','account_name','bank_name','status'])
            ->toJson();
    }



    public function bankaccountshow($id){
        $data['accountinfo'] = Bankaccount::where('id',$id)->first();
        return view('admin.bankaccount.showaccount',$data);
    }

    public function approveaccount($id){

       $data = Bankaccount::where('id',$id)->update(['is_approved'=>'approved']);
        return $data;
    }

    public function delete($id){
        if(Transaction::where(['transaction_type' => 'withdraw', 'method_transaction_id' => $id ])->exists()){
            return response()->json('You can not delete this data, Allready created transaction by this account number.'); 
        }

        Bankaccount::where('id',$id)->delete();

        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
        
    }

    public function bankaccountstatus(){

        Bankaccount::where('id',request()->id)->update(['status'=>request()->status]);
        // response message 
        $msg = array(
            'message' =>  "Data Deleted Successfully.",
            'type' => 'success'
        );
        if(request()->status == 1){
            $msg['message'] = 'Account activated successfully';
        }else{
            $msg['message'] = 'Account deactivated successfully';
        }
        return $msg;
    }



    //*** GET Request Status
    public function status($id1,$id2)
    {
         $data = Bankaccount::findOrFail($id1);
         $data->status = $id2;
         $data->is_approved = 'approved';
         $data->update();

        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
 
}
