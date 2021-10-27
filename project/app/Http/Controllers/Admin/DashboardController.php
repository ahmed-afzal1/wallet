<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountbalance as AB;
use App\Models\Supportticket as ST;
use Illuminate\Http\Request;
use App\Helpers\Autoload;
use InvalidArgumentException;
use App\Http\Traits\Localization;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Userlogin;
use App\Models\Withdraw;
use App\Models\Deposit;
use DataTables;
use Validator;
use Session;
use App\User;
use Auth;
use Hash;
use View;
use Zip;

class DashboardController extends Controller
{
    use Localization;

    /*
    |--------------------------------------------------------------------------
    | Dashboard Routes
    |--------------------------------------------------------------------------
    |
    | This method only for Admin Dashboard view, an user can view his/her dashboard by this method to hit {admin.dashboard} route.
    |
    */

    public function index()
    {
        $montharray = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $transPerMonth= array();
        for ($i=1; $i<=12; $i++){
            $month = date('m',strtotime('-'.$i.' month'));
            $transPerMonth[$month] = Transaction::whereMonth('created_at', $month)->sum('amount');
        }
        ksort($transPerMonth);
        foreach($montharray as $value){
            if(!array_key_exists( $value,$transPerMonth)){
                $transPerMonth[$value] = 0;
            }
        }

         
        $data['transPerMonth'] =  $transPerMonth;

        $data['tranThisMonth'] = array();

        $data['tranThisMonth'] =  Transaction::groupBy('transaction_type')
                                    ->selectRaw('sum(amount) as sum, transaction_type')
                                    ->pluck('sum','transaction_type');

        $data['latestticketlist'] = ST::orderBy('ticket_status','asc')->orderBy('id','desc')->limit(5)->get();
        $data['latesttransaction'] = Transaction::orderBy('id','desc')->limit(5)->get();
        
        $new_check =0;
        foreach(AB::get() as $ab){
            
            $ab_currency = Currency::find($ab->currency_id);
           
            $new_check += $ab->balance / $ab_currency->rate;
        }

        $new_bal= Currency::where('is_default',1)->first();
        $new_balance=$new_bal->rate;
        $total=$new_balance*$new_check;
        $data['totalbalance'] = $total;
        
 
        
      $new_check2 =0;
      
      $new_check3=0;
      foreach(Transaction::get() as $ts){
          $ts_currency= Currency::find($ts->transaction_currency);
          
          $new_check2 += $ts->transaction_cost/ $ts_currency->rate;
          $new_check3 += $ts->amount/ $ts_currency->rate;
          
      }
      $total_profit=$new_balance*$new_check2;
      $total_transaction=$new_balance*$new_check3;
      $data['totalprofit'] = $total_profit;
      $data['transactionTotal'] = $total_transaction ;
      
        // end profit
        
        
        // withdraw Total
        
        
        $new_check4=0;
         
         foreach(Withdraw::get() as $withdraw){
             $withdraw_currency=Currency::find($withdraw->transaction_currency);
             $new_check4 += $withdraw->amount/  $withdraw_currency->rate;
           
         }
         $total_withdraw=$new_balance*$new_check4;
         $data['withdrawTotal'] = $total_withdraw;
         
        // withdraw total
        
        // Total deposite
        
        $new_check5=0;
        foreach(Deposit::get() as $deposite){
            $deposite_currency=currency::find($deposite->currency);
            $new_check5 += $deposite->amount /  $deposite_currency->rate;
        }
        
        $total_deposite=$new_balance*$new_check5;
         $data['depositetotal'] = $total_deposite;
        
        
       
        
        $data['user']=User::get();
      
          

        return view('admin.dashboard',$data,compact('new_bal'));
    }

    public function profile()
    { 
        return view('admin.profile');
    }



    public function changepassword(Request $request,$id)
    {
        
        //--- Validation Section

            $data = Admin::findOrFail($id);

            $rules =
            [
                'currentpassword' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ];

            $customs = [
                'password.confirmed' => 'Confirmation password does not match',
            ];
            
            

            $validator = Validator::make(request()->all(), $rules, $customs);
            
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }


            if(!Hash::check($request->currentpassword,$data->password)){
                return response()->json(array('errors' => ['Current Password Dosen\'t Match.']));         
            }
            $newdata = array(
                'password' => Hash::make($request->password)
            );
           

            if($data->update($newdata)){
                $msg = 'Password update successfully';
            }else{
                $msg = 'Something went wrong!';
            }
            return response()->json(array('errors' => $msg));
    }


    
    public function userslist()
    {
        return view('admin.user.index');
    }



    public function userslistdatatable()
    {
        $data = User::get();
        return DataTables::of($data)
            ->editColumn('name',function($data){
                return ucwords($data->name);
            })
            ->addColumn('status',function($data){
                $class = $data->status == 1 ? 'bg-success' : 'bg-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select changestatus '.$class.'"><option data-val="1" value="'.route('admin.userstatus',['id'=> $data->id,'status' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.userstatus',['id'=> $data->id,'status' => 0]) .'" '.$ns.'>Deactivated</option>/select></div>';
            })
            ->addColumn('action',function($data){
                $action = '<a href="' . route('admin.userprofile',['user' => $data->id]) . '" class="btn btn-warning mx-1 load-page"> <i class="fas fa-eye"></i> Profile </a><a href="javascript:;" data-href="' . route('admin.userdelete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete btn btn-danger confirm-delete  mx-1"><i class="fas fa-trash-alt"></i> Delete</a>';
                return $action;
            })
            ->rawColumns(['name','status','action'])
            ->toJson();
    }



    public function permitless()
    {
        return view('auth.permitless');
    }



    public function userprofile()
    {
        Autoload::airlog('User visit profile');
        $data = array(
            'sendmoney' => 0,
            'depositmoney' => 0,
            'withdrawmoney' => 0,
        );
        $data['userdata'] = User::find(request()->user);
        $data['useraccbal'] = AB::where('user_email', $data['userdata']->email)->get();
        $data['usertrancbal'] = Transaction::select('transaction_id','transaction_type','transaction_currency','amount','created_at')->where('user_id', request()->user)->orderBy('created_at','desc')->limit(5)->get();
        $transhistory = Transaction::select('transaction_id','transaction_type','transaction_currency','amount')->where('sender', $data['userdata']->email)->get();
        $data["depositmoney"] = Deposit::where('user_email', $data['userdata']->email)->sum('amount');
        $data["pendinsendgmoney"] = Transaction::where(['sender' => $data['userdata']->email,'status'=>'pending'])->count();
        $data["pendinwithdrawreq"] = Withdraw::where(['user_email' => $data['userdata']->email,'status'=>'pending'])->count();
        $data["userlogin"] = Userlogin::where(['user_id' => $data['userdata']->id])->orderBy('login_time','desc')->first();
        $limit = Userlogin::where(['user_id' => $data['userdata']->id])->orderBy('login_time','desc')->count();
        $data["alluserlogins"] = Userlogin::where(['user_id' => $data['userdata']->id])->orderBy('login_time','desc')->skip(1)->take($limit)->get();

        foreach($transhistory as $value){
            if($value->transaction_type  == 'send'){
                $data["sendmoney"] += $value->amount ;
            }elseif($value->transaction_type == 'withdraw'){
                $data["withdrawmoney"] += $value->amount;
            }
        }

        $data['useraccbaltotal'] = 0;
        foreach($data['useraccbal'] as $value){
            $data['useraccbaltotal'] += $value->balance;
        }

        return view('admin.user.profile',$data);
    }


  

    

    /*
    |--------------------------------------------------------------------------
    | System Activities View
    |--------------------------------------------------------------------------
    |
    | This method only for Read and view  System Activities, this method called {admin.activities} route.  
    |
    */
    public function systemActivities(Request $request)
    {
        
        $logdata =  null;
        $data['logdata'] = '';
        $reqdate = date('Ymd').'.log';
       
        if($request->date != null){
            $reqdate = str_replace('-','',$request->date); 
            $reqdate = $reqdate.'.log'; 
        }
        $file = null;
        if(file_exists(base_path('storage/activities/'.$reqdate))){
            $file = fopen('storage/activities/'.$reqdate,"r");
            while(! feof($file))
            {
                $logdata[] = fgets($file);
            }
            fclose($file);
            $data['logdata'] = array_reverse($logdata);
        }
       
        return view('admin.activities.system',$data);
    }

    




    /*
    |--------------------------------------------------------------------------
    | System Activities View
    |--------------------------------------------------------------------------
    |
    | This method only for Read and view  System Activities, this method called {admin.activities} route.  
    |
    */
    public function loginActivities()
    {
        $logdata = null;
        $file = fopen('storage/activities/'.date('Ymd').'.log',"r");
        while(! feof($file))
        {
            $logdata[] = fgets($file);
        }
        fclose($file);
        $data['logdata'] = array_reverse($logdata);
        return view('admin.activities.system',$data);
    }


    /*
    |--------------------------------------------------------------------------
    | User status update method
    |--------------------------------------------------------------------------
    |
    | This method only for view  user  status update , this method called {admin.userstatus} route.   
    |  Status  active or deactive declared by 1/0
    |
    */
    public function userstatus()
    {
        Autoload::airlog('User update status');
        $data = array('status' => request()->status);
        User::where('id',request()->id)->update($data);
        return 1;
    }


    /*
    |--------------------------------------------------------------------------
    | User destroy/delete method
    |--------------------------------------------------------------------------
    |
    | This method only for delete/destroy user profile , this method called {admin.userdelete} route.   
    | 
    |
    */
    public function userdelete($id){
    
        $data = User::find($id);
        Autoload::airlog('Delete User'.$data->email);
        if($data != null){
            if (file_exists(public_path().'/userpanel/images/'.$data->photo)) {
                unlink(public_path().'/userpanel/images/'.$data->photo);
            }
        }
        User::where('id',$id)->delete();
        $message = __("User Deleted Successfully");
        Session::flash('Success', __("User Deleted Successfully"));
        return redirect()->back();
    }

    public function generate_bkup()
    {
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            $bkuplink = url($chk);
        }
        return view('admin.movetoserver',compact('bkuplink','chk'));
    }

    public function clear_bkup()
    {
        $destination  = public_path().'/install';
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $handle = fopen('backup.txt','w+');
        fwrite($handle,"");
        fclose($handle);
        return redirect()->back()->with('success','Backup file Deleted Successfully!');
    }

    public function activation()
    {
        $activation_data = "";
        if (file_exists(public_path().'/project/license.txt')){
            $license = file_get_contents(public_path().'/project/license.txt');
            if ($license != ""){
                $activation_data = "<i style='color:darkgreen;' class='icofont-check-circled icofont-4x'></i><br><h3 style='color:darkgreen;'>Your System is Activated!</h3><br> Your License Key:  <b>".$license."</b>";
            }
        }
        return view('admin.activation',compact('activation_data'));
    }

    public function activation_submit(Request $request)
    {
        $purchase_code =  $request->pcode;
        $my_script =  'GeniusCart';
        $my_domain = url('/');

        $varUrl = str_replace (' ', '%20', config('services.genius.ocean').'purchase112662activate.php?code='.$purchase_code.'&domain='.$my_domain.'&script='.$my_script);

        if( ini_get('allow_url_fopen') ) {
            $contents = file_get_contents($varUrl);
        }else{
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $varUrl);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            curl_close($ch);
        }

        $chk = json_decode($contents,true);

        if($chk['status'] != "success")
        {

            $msg = $chk['message'];
            return response()->json($msg);

        }else{
            $this->setUp($chk['p2'],$chk['lData']);

            if (file_exists(public_path().'/rooted.txt')){
                unlink(public_path().'/rooted.txt');
            }

            $fpbt = fopen(public_path().'/project/license.txt', 'w');
            fwrite($fpbt, $purchase_code);
            fclose($fpbt);

            $msg = 'Congratulation!! Your System is successfully Activated.';
            return response()->json($msg);
        }
    }

    function setUp($mtFile,$goFileData){
        $fpa = fopen(public_path().$mtFile, 'w');
        fwrite($fpa, $goFileData);
        fclose($fpa);
    }

    public function movescript(){
        ini_set('max_execution_time', 3000);

        $destination  = public_path().'/install';
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }

        $src = base_path().'/vendor/update';
        $this->recurse_copy($src,$destination);
        $files = public_path();
        $bkupname = 'GeniusCart-By-GeniusOcean-'.date('Y-m-d').'.zip';

        $zip = Zip::create($bkupname)->add($files, true);
        $zip->close();

        $handle = fopen('backup.txt','w+');
        fwrite($handle,$bkupname);
        fclose($handle);

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname],200);
    }

    public function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }


}
