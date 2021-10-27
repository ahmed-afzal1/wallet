<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriberController extends Controller
{


    public function create(Request $request)
    {

        $subs = Subscriber::where('subscriber','=',$request->email)->first();
        if(isset($subs)){
        return response()->json(array('errors' => [ 0 =>  'This Email Has Already Been Taken.']));
        }
        $subscribe = new Subscriber;
        $data = array(
            'subscriber' => $request->email,
            'status' => 1,
            'created_at' => date('Y-m-d'),

        );
        Subscriber::insert($data);
        $msg = [
            'type' => 'success',
            'message' => __("Subscribe successfully"),
        ];
        return response()->json($msg);



    }
}
