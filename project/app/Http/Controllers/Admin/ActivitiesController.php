<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Userlogin;
use App\Helpers\Autoload;
use App\Http\Traits\Localization;
use App\User;
use Carbon\Carbon;
use DataTables;

class ActivitiesController extends Controller
{
    use Localization;
    public function datatables(){

        $datas = Userlogin::where('created_at',Carbon::today())->orderBy('id','desc')->limit(100)->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->editColumn('user_id',function(Userlogin $data){
                                 return $data->user->name;
                            })
                            ->addColumn('email',function(Userlogin $data){
                                return $data->user->email;
                            })
                            ->editColumn('logout_time',function(Userlogin $data){
                                $logout_time = $data->logout_time ? $data->logout_time : '<span class="btn btn-sm badge-success">Online</span>';
                                return $logout_time;
                            })
                            ->rawColumns(['user_id','email','logout_time'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }




    public function getLoginActivities()
    {
        return view('admin.activities.login');
    }


      

    public function deleteLoginActivities()
    {
        return view('admin.activities.login');
    }





}
