<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Autoload;
use App\Helpers\Convert;
use App\Http\Traits\UserLocalization;
use App\Models\Language;
use App\Models\Supportticket;
use App\Models\Transaction;
use App\Models\Localization;
use Carbon\Carbon;
use Validator;
use App\User;
use Session;
use Hash;
use Auth;

class DashboardController extends Controller
{

    use UserLocalization;
    
    /*
    |--------------------------------------------------------------------------
    | View Dashboard
    |--------------------------------------------------------------------------
    |
    | This method only for View user dashboard,  It called by {user-dashboard} route.
    |
    */
    public function index()
    {
        $data['user'] = Auth::user();  
        $lastweek = Transaction::where(['sender'=>Auth::user()->email,'transaction_type'=>'send'])
                            ->orderBy('created_at','desc')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->whereYear('created_at', date('Y'))->get();  

        $data['recentsend'] = count($lastweek->where('transaction_type', 'send'));
        $data['recentwithdraw'] = count($lastweek->where('transaction_type', 'withdraw'));
        $data['supportticket'] = Supportticket::where(['user_email'=>Auth::user()->email,'ticket_status'=>'pending'])->count();

        $data['profilecomplete'] = Autoload::procom();


        $data['transactioninfo'] = Transaction::where('sender',Auth::user()->email)->orderBy('created_at','desc')->limit(5)->get();  
        Autoload::airlog('user dashboard');
        return view('userpanel.dashboard',$data);
    }

    

        

    /*
    |--------------------------------------------------------------------------
    | View Profile
    |--------------------------------------------------------------------------
    |
    | This method only for View user profile,  It called by {user-profile} route.
    |
    */
    public function profile()
    {
        return view('userpanel.profile');
    }


    
    /*
    |--------------------------------------------------------------------------
    | Change Password 
    |--------------------------------------------------------------------------
    |
    | This method only for  user change password,  It called by {user-change-password} route.
    |
    */
    public function changepassword(Request $request)
    {
        $data = AUth::guard('web')->user();
        $rules =
        [
            'currentpassword' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ];
        

        $validator = Validator::make(request()->all(), $rules);
        
        if ($validator->fails()) {
        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        if(!Hash::check($request->currentpassword,$data->password)){
            $msg = array(
                'type' => 'warn',
                'message' => 'Password Dosen\'t Match.'
            );
            return response()->json($msg);         
        }

        $newdata = array(
            'password' => Hash::make($request->password)
        );

        if(User::where('id',$data->id)->update($newdata)){
            $msg = array(
                'type' => 'success',
                'message' => 'Password update successfully'
            );
        }else{
            $msg = array(
                'type' => 'warn',
                'message' => 'Something .'
            );
        }
        return response()->json($msg);  
    }




    /*
    |--------------------------------------------------------------------------
    | Change user address information
    |--------------------------------------------------------------------------
    |
    | This method only for Change user address information,  It called by {user-info-update} route.
    |
    */
    public function updateuserinfo(Request $request)
    {
        $data = AUth::guard('web')->user();
        $rules =
        [
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'city' => 'required',
            'country' => 'required',
        ];
        

        $validator = Validator::make(request()->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        if(!Hash::check($request->password,$data->password)){
            return response()->json(array('errors' => ['Password does\'t match!']));         
        }

        $newdata = $request->all();
        unset($newdata['_token']);
        unset($newdata['password']);
        if(User::where('id',$data->id)->update($newdata)){
            $msg = array(
                'type' => 'success',
                'message' => 'Information update successfully'
            );
        }else{
            $msg = array(
                'type' => 'warn',
                'message' => 'Something is worng please try again.'
            );
        }
        return response()->json($msg);  
    }





    /*
    |--------------------------------------------------------------------------
    | Change user address information
    |--------------------------------------------------------------------------
    |
    | This method only for Change user address information,  It called by {user-info-update} route.
    |
    */

    public function userpersonalinfo(Request $request)
    {
        $data = AUth::guard('web')->user();
        $rules =
        [
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
        ];
        

        $validator = Validator::make(request()->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        if(!Hash::check($request->password,$data->password)){
            return response()->json(array('errors' => ['Password does\'t match!']));         
        }

        $newdata = $request->all();
        unset($newdata['_token']);
        unset($newdata['password']);
        if(User::where('id',$data->id)->update($newdata)){
            $msg = array(
                'type' => 'success',
                'message' => 'Information update successfully'
            );
        }else{
            $msg = array(
                'type' => 'warn',
                'message' => 'Something is worng please try again.'
            );
        }
        return response()->json($msg);  
    }







    /*
    |--------------------------------------------------------------------------
    | User Profile setting
    |--------------------------------------------------------------------------
    |
    | This method only for User Profile setting,  It called by {user-account-setting} route.
    |
    */

    public function settingpageview(Request $request)
    {
        $data['language'] = Localization::orderBy('id','desc')->get();
        return view('userpanel.accountsetting', $data);
    }


    /*
    |--------------------------------------------------------------------------
    | User Profile setting
    |--------------------------------------------------------------------------
    |
    | This method only for User Profile setting,  It called by {user-account-setting} route.
    |
    */


    public function setting(Request $request)
    {
        $data = AUth::guard('web')->user();
        $rules =
        [
            'language' => 'required',
        ];
        

        $validator = Validator::make(request()->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        if(!Hash::check($request->password,$data->password)){
            return response()->json(array('errors' => ['Password does\'t match!']));         
        }

        $newdata = $request->all();
        unset($newdata['_token']);
        unset($newdata['password']);
        if(User::where('id',$data->id)->update($newdata)){
            $msg = array(
                'type' => 'success',
                'message' => 'Information update successfully'
            );
        }else{
            $msg = array(
                'type' => 'warn',
                'message' => 'Something is worng please try again.'
            );
        }
        return response()->json($msg);  
    }

    public function defaultcurrency(){
        User::where('id',Auth::guard('web')->user()->id)->update(['default_currency' => request()->default_currency]);
        Session::flash('Success','Your default currency update successfully.');
        return redirect()->back();
    }


}
