<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Role;
use Validator;
use Auth;
use Hash;
use DB;

class StaffController extends Controller
{
    use Localization;


    public function datatables()
    {
        
         $datas = Admin::orderBy('id','desc')->where('id','!=',1)->get();

         //--- Integrating This Collection Into Datatables
         return DataTables::of($datas)
             ->editColumn('name', function(Admin $data) {
                 $name = mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name);
                 $name = '<a class="text-primary" href="'.route('admin.staff.show',[$data->id]).'"'.$data->name.'">'.ucwords($name).'</a>';
                 $privilege ='<small>Privilege:  <span class="badge badge-primary p-1"> '.$data->role->name.'</a> </small>' ;
                 return  $name.'<br>'. $privilege;
             })
             ->editColumn('photo', function(Admin $data) {
                $photo = $data->photo ? url('assets/images/'.$data->photo):url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })

             ->editColumn('about', function(Admin $data) {
                 $about = ($data->about != null) ? mb_strlen(strip_tags($data->about),'utf-8') > 50 ? mb_substr(strip_tags($data->about),0,50,'utf-8').'...' : strip_tags($data->about):'<span class="text-warning">Not Declared!</span>';
                 $about = '<span title="'.$data->about.'">'.$about.'<span>';
                 return  $about;
             })

            ->addColumn('status', function(Admin $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.$status .'
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.staff.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Activate").'</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.staff.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Deactivate").'</a>
                            </div>
                        </div>';
            })
            ->addColumn('action', function(Admin $data) {
                return '<div class="action-list"><a href="'. route('admin.staff.show',$data->id) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('View Details') .'</a><a href="'. route('admin.staff.edit',$data->id) . '"  class="btn btn-success btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('Edit') .'</a><button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin.staff.delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                <i class="fas fa-trash"></i>
              </button></div>';
            })

            ->rawColumns(['name','photo','status','action'])
            ->toJson();
    }



    public function index()
    {
        return view('admin.staff.index');
    }



    public function create(){
        $data['roles'] = Role::orderBy('id','desc')->get();
        return view('admin.staff.create',$data);
    }
    


    public function store(Request $request)
    {
        $input = request()->all();

        //--- Validation Section
        $rules = [
                'name' => 'required',
                'email' => 'unique:admins',
                'photo'      => 'required|mimes:jpeg,jpg,png,svg',
                'password' => 'required',
                'role_id' => 'required',
            ];
        
        $customs = [
            'name.required' => 'Name field is required',
            'email.unique' => 'Email has already been taken!',
            'photo.required' => 'Photo field is required',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
            'password.required' => 'Password field is required',
            'role_id.required' => 'Privilege field is required',
        ];
        
        $validator = Validator::make($input,$rules,$customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends



        //--- Logic Section
        $data = new Admin();
        $input = $request->all();
        if ($file = $request->file('photo')) {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);           
            $input['photo'] = $name;
        } 
        
        $input['password'] = bcrypt($request['password']);
        $input['status'] = 0;
        
        $data->fill($input)->save();
       

        //--- Redirect Section        
            $msg = 'New Data Added Successfully.';
            return response()->json($msg);          
        //--- Redirect Section Ends    
    }



    public function edit($id)
    {
        $data['data'] = Admin::findOrFail($id);
        $data['roles'] = Role::orderBy('id','desc')->get();
        return view('admin.staff.edit',$data);
    }


    public function update(Request $request,$id)
    {
        //--- Validation Section
            $data = Admin::findOrFail($id);  
            $input = $request->all();    

            $rules = [
                'name' => 'required',
                'email' => 'unique:admins,email,'.$id,
                'photo'      => 'mimes:jpeg,jpg,png,svg',
            ];
        
            $customs = [
                'name.required' => 'Name field is required',
                'email.unique' => 'Email has already been taken!',
                'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
            ];
        
            
            if($id != 1){
                $rules = ['role_id' => 'required', ];
                $customs = [
                    'role_id.required' => 'Privilege field is required',
                ];
            }


            $validator = Validator::make(request()->all(), $rules,$customs);
            
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }


            //--- Validation Section Ends
                if ($file = $request->file('photo')) 
                {              
                    $name = time().$file->getClientOriginalName();
                    $file->move('assets/images/',$name);
                    @unlink('assets/images/'.$data->photo);
          
                    $input['photo'] = $name;
                } 
            
                if($request->password != ''){
                    $input['password'] = Hash::make($request->password);
                }else{
                    unset($input['password']);
                }
                unset($input['currentpassword']);
                $data->update($input);

            //--- Redirect Section        
                $msg = 'Data update successfully';
                return response()->json($msg);          
            //--- Redirect Section Ends 
        }
 


    //*** GET Request Status
    public function status($id1,$id2)
    {
        $data = Admin::findOrFail($id1);
        $data->status = $id2;
        $data->update();

        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }



    public function updatestatus()
    {
       
        if(Auth::guard('admin')->user()->role->slug == 'superadmin'){
            $data = array('status' => request()->status);
            Admin::where('id',request()->id)->update($data);
            return 1;
        }else{
            return 0;
        }
    }



    public function show($id)
    {
        $data['staffdata'] = Admin::findOrFail($id);
        return view('admin.staff.show',$data);
    }


    public function destroy($id)
    {
        $data = Admin::findOrFail($id);

        // if admin own profile
    	if($data->id == Auth::guard('admin')->user()->id)
    	{
            return response()->json(array('errors' => 'You can not remove your own profile.'));            
        }

        // if staff superadmin
    	if($data->role->slug == 'superadmin')
    	{
            return response()->json(array('errors' => 'You do not have access to remove this admin!'));            
        }
        

        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();   
             //--- Redirect Section
                $msg = __('Data Deleted Successfully.');
                return response()->json($msg);
            //--- Redirect Section Ends   
        }
        
        @unlink('assets/images/'.$data->photo);

        
        // delete data 
        $data->delete();

        //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
        //--- Redirect Section Ends  
    }


    
}
