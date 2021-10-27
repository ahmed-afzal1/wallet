<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Accountbalance;
use App\User;
use Illuminate\Http\Request;
use DataTables;
use Toastr;

class UserController extends Controller
{
    use Localization;
    
    public function datatables(){

        $datas = User::orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->addColumn('status', function(User $data) {
                                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                                return '<div class="btn-group mb-1">
                                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '.$status .'
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.user.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Activate").'</a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.user.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Deactivate").'</a>
                                            </div>
                                        </div>';
                            })
                            ->addColumn('action', function(User $data) {
                                return '<div class="action-list"><a href="'. route('admin.userprofile',['user' => $data->id]) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Details') .'</a><button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin.user.delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index(){
        return view('admin.user.index');
    }

    //*** GET Request Status
    public function status($id1,$id2)
    {
        $data = User::findOrFail($id1);
        $data->status = $id2;
        $data->update();

        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    public function delete($id){
        $data = User::findOrFail($id);
        
        if(!empty($data->cards)){
            foreach($data->cards as $card){
                $card->delete();
            }
        }

        if(!empty($data->accountBalance)){
            foreach($data->accountBalance as $account){
                $account->delete();
            }
        }

        if(!empty($data->withdraws)){
            foreach ($data->withdraws as $withdraw) {
                $withdraw->delete();
            }
        }

        $data->delete();
    }

    public function incrementBalance(Request $request)
    {
        $Balance = Accountbalance::find($request->id);
        $balanceWillAdd = $Balance->balance + $request->amount;
        if($balanceWillAdd < 0){
            return redirect()->back()->with('error','Balance less then 0 not acceptable!!!');
        }
        $Balance->increment('balance',$request->amount);
        return redirect()->back()->with('message','Data Updated Successfully');
    }

    public function decrementBalance(Request $request)
    {
    	$Balance = Accountbalance::find($request->id);
        $balanceWillDecrease = $Balance->balance - $request->amount;

        if($balanceWillDecrease < 0){
            return redirect()->back()->with('error','Balance less then 0 not acceptable!!!');
        }

        $Balance->decrement('balance',$request->amount);
        return redirect()->back()->with('message','Data Updated Successfully');
    }

    public function increment($id){
        $data = Accountbalance::findOrFail($id);
        return $data;
    }
}
