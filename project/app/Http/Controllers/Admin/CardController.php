<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Http\Request;
use App\Models\Craditcard;
use App\Models\Transaction;
use DataTables;

class CardController extends Controller
{
    use Localization;


    public function datatables()
    {

        $cardslist = Craditcard::orderBy('id','desc')->get();

        return DataTables::of($cardslist)
                            ->addColumn('action', function(Craditcard $data) {
                                return '<div class="action-list"><a href="'. route('admin.cradit.show',$data->id) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Card') .'</a><a href="javascript:;" data-href="' . route('admin.cradit.delete',$data->id) . '" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->addColumn('status', function(Craditcard $data) {
                                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                                return '<div class="btn-group mb-1">
                                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '.$status .'
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.craditcard.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Activate").'</a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.craditcard.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Deactivate").'</a>
                                            </div>
                                        </div>';
                            })
                            ->editColumn('card_owner_name',function($cardslist){
                                    return ucwords($cardslist->card_owner_name);
                            })
                            ->rawColumns(['action','card_owner_name','status'])
                            ->toJson();
                }

    public function delete($id){
        if(Transaction::where(['transaction_type' => 'deposit', 'method_transaction_id' => $id ])->exists()){
            $msg = "You can't delete this data, Allready created transaction by this Card.";
            return response()->json($msg);
        }

        Craditcard::where('id',$id)->delete();
        // response message 


        //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function index()
    {
        return view('admin.card.index');
    }


    //*** GET Request Status
    public function status($id1,$id2)
    {
        $data = Craditcard::findOrFail($id1);
        $data->status = $id2;
        $data->is_approved = 'approved';
        $data->update();

        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function show($id){
        $data['cardinfo'] = Craditcard::where('id',$id)->first();
        return view('backend.card.showcraditcard',$data);
    }


    public function approve($id){

        $data = Craditcard::where('id',$id)->update(['is_approved'=>'approved']);
         return $data;
     }


}
