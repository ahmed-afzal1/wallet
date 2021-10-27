<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Models\Supportticket as ST;
use Illuminate\Http\Request;
use App\Helpers\Autoload;
use App\Http\Traits\UserLocalization;
use App\Models\Admin;
use App\Models\AdminUserMessage;
use App\Models\Supportticket;
use Carbon\Carbon;
use Validator;
use Session;
use Auth;
use Illuminate\Support\Facades\App;

class SupportController extends Controller
{
    use UserLocalization;

    public function index()
    {
        $data['ticketlist'] = ST::where('user_email',Auth::guard('web')->user()->email)->orderBy('ticket_status','asc')->orderBy('id','desc')->get();
        return view('userpanel.support.createticket',$data);
    }

    public function createticket(Request $request)
    {
        $rules = [
            'subject'=> 'required|unique:supporttickets|max:100',
            'message'=> 'required',
        ];

        $customs = [
            'subject.required' => 'Subject field is required',
            'subject.unique' => 'Subject field should be unique',
            'message.required' => 'Message field is required',
        ];

        $validator = Validator::make(request()->all(), $rules,$customs);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $ticketId = Autoload::alphaNumeric(8,'upper');
        while(ST::where('ticket_id',$ticketId)->exists()){
            $ticketId = Autoload::alphaNumeric(8,'upper');
        }
        $data = array(
            'user_email' => Auth::guard('web')->user()->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ticket_id' => $ticketId,
            'ticket_status' => 'pending',
            'created_at' => Carbon::now(),
        );
        ST::insert($data);
        $msg = [
            'type' => 'success',
            'message' => __('Ticket created successfully'),
        ];
        return response()->json($msg);
    }

    public function view($id){
        $ticket = Supportticket::findOrFail($id);
        $data['replies'] = AdminUserMessage::where('supportticket_id',$ticket->id)->get();
        $data['admin'] = Admin::where('role_id',1)->first();
        $data['data'] = $ticket;
        return view('userpanel.support.viewticket',$data);
    }

    public function reply(Request $request){
        $data = new AdminUserMessage();
        $input = $request->all();

        $data->fill($input)->save();

        $msg = 'Message send successfully.';
        return redirect()->back()->with('success',$msg);
    }
}
