<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\AdminUserMessage;
use Illuminate\Http\Request;
use App\Models\Supportticket as ST;
use App\Models\Supportticket;
use Session;
use Auth;
use DataTables;

class SupportController extends Controller
{
    use Localization;

    public function datatables(){

        $datas = ST::orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->editColumn('ticket_status', function(ST $data) {
                                if($data->ticket_status == 'pending'){
                                    return '<button class="btn badge-danger btn-sm">Pending</button>';
                                }elseif($data->ticket_status == 'processing'){
                                    return '<button class="btn badge-warning btn-sm">Processing</button>';
                                }
                                else{
                                    return '<button class="btn badge-success btn-sm">Completed</button>';
                                }

                            })
                            ->addColumn('action', function(ST $data) {
                                return '<div class="action-list"><a href="'. route('admin.support.ticket.view',$data->id) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View') .'</a><a href="javascript:;" data-href="' . route('admin.support.ticket.delete',$data->id) . '" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->rawColumns(['ticket_status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index()
    {
        $data['ticketlist'] = ST::orderBy('ticket_status','desc')->get();
        return view('admin.support.ticketlist',$data);
    }

    // view ticket details
    public function view()
    {
        $ticket = ST::where('id',request()->id)->first();
        $data['replies'] = AdminUserMessage::where('supportticket_id',$ticket->id)->get();
        $data['admin'] = Auth::guard('admin')->user();
        $data['ticketinfo'] = $ticket;

        return view('admin.support.details',$data);
    }

    public function messageshow($id)
    {
        $conv = ST::findOrfail($id);
        $data['replies'] = AdminUserMessage::where('supportticket_id',$conv->id)->get();
        $data['admin'] = Auth::guard('admin')->user();

        return view('load.message',$data);                 
    }   

    // change ticket status
    public function changestatus(Request $request)
    {
            $tid=ST::where('id',$request->tid)->first();
        if($tid->exists()){

            if($tid->ticket_status=='completed'){

                $msg = [
                    'type' => 'warn',
                    'message' => __("You can't update  completed ticket"),
                ];

                return response()->json($msg);
            }
            else{
                ST::where('id',$request->tid)->update(['ticket_status'=> $request->tstatus]);

                $msg = [
                    'type' => 'success',
                    'message' => __("status updated successfully."),
                ];

                return response()->json($msg);
            }

        }else{
            $msg = [
                'type' => 'warn',
                'message' => __("invalid Ticket No"),
            ];
            return response()->json($msg);
        }
    }



    // delete ticket
    public function delete($id)
    {
        $messages = AdminUserMessage::where('supportticket_id',$id)->get();
        if($messages){
            foreach($messages as $message){
                $message->delete();
            }
        }
        $data = ST::where('id',$id)->delete();

        $msg = 'Data Deleted successfully.';
        return response()->json($msg);
    }

    public function message(Request $request){
        $data = new AdminUserMessage();
        $input = $request->all();

        $data->fill($input)->save();

        $msg = 'Message send successfully.';
        return response()->json($msg);
    }


}
