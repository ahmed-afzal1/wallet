<?php

namespace App\Http\Controllers\Admin;

use DataTables as Datatables;
use App\Models\Role;
use App\Helpers\Autoload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Admin;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Models\Menu;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    use Localization;


    public function datatables()
    {
         $datas = Role::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('section', function(Role $data) {
                                $details =  str_replace('_',' ',$data->section);
                                $details =  ucwords($details);
                                return  '<div>'.$details.'</div>';
                            })
                            ->addColumn('action', function(Role $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-role-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>    <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-role-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            }) 
                            ->rawColumns(['section','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        $data['role'] = Role::get();
        return view('admin.role.index',$data);
    }




    //*** GET Request
    public function create()
    {
        return view('admin.role.create');
    }


    //*** POST Request
    public function store(Request $request)
    {
       
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Role();
        $input = $request->all();

        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }

        $data->fill($input)->save();
        //--- Logic Section Ends
        
        //--- Redirect Section
        $msg = __('New Data Added Successfully.').'<a href="'.route('admin-role-index').'">'.__('View Lists.').'</a>';;
        return response()->json($msg);
        //--- Redirect Section Ends    
    
    }




    //*** GET Request
    public function roleexistencecheck(Request $request)
    {
        $result = Role::where('name',str_replace('%20',' ',$request->name))->first();
        if($request->id != 0){
            if($result != null){
                if($request->id == $result->id){
                    return 'YOU';
                }else{
                    return 'YES';
                }
            }else{
                return 'NO';
            }
        }else{
            if($result != null){
                return 'YES';
            }else{
                return 'NO';
            }
        }

    }


    public function edit($id)
    {
        $data['data'] = Role::findOrFail($id);
        $data['sections'] = Menu::get();
        return view('admin.role.edit',$data);
    }


    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = Role::findOrFail($id);
        $input = $request->all();
        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('Data Updated Successfully.').'<a href="'.route('admin-role-index').'">'.__('View Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends    
    }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        $role = array('role_id'=>0);
        Admin::where('role_id',$id)->update($role);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }

    

    public function menudatatables()
    {
         $menudata = Menu::get();
         return Datatables::of($menudata)
                    ->addColumn('name', function(menu $data) {
                        $options = null;
                       return $data->name.'<div class="float-right menu-list"><a data-name="'.$data->name.'" data-id='.$data->id.' htef="'.route("admin-menu-edit",[$data->id]).'" class="btn btn-warning edit btn-sm"  data-toggle="modal" data-target="#editmenu">Edit</a><button  data-href="'.route("admin-menu-delete",[$data->id]).'" class="btn btn-danger ml-2 btn-sm confirm-delete"   data-toggle="modal" data-target="#confirm-delete">Delete</button></div>';
                    }) 
                    ->rawColumns(['name'])
                    ->toJson(); //--- Returning Json Data To Client Side
    }


    public function menustore(Request $request)
    {

        $input = request()->all();
        //--- Validation Section
        $rules = [
            'name' => 'required|unique:menus,name'
            ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $slug = Str::slug(trim($request->name));
        $menudata = Menu::orderBy('position','desc')->first();
        $data = array(
            'name' => $request->name,
            'slug' => $slug,
            'position' => $menudata->position+1,
        );
        if(Menu::insert($data)){
            $msg = 'New Data Added Successfully';
        }else{
            $msg = 'Something is wrong, Please Try again.';
        }
      
        //--- Redirect Section
        return response()->json($msg);
        //--- Redirect Section Ends    
    }



    public function menuupdate(Request $request,$id)
    {

        $input = request()->all();
        //--- Validation Section
        $rules = [
            'name' => 'required|unique:menus,name'
            ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $slug = Str::slug(trim($request->name));
        $data = array(
            'name' => $request->name,
        );
        if(Menu::where('id',$id)->update($data)){
            $msg = 'Data Updated Successfully';
        }else{
            $msg = 'Something is wrong, Please Try again.';
        }
      
        //--- Redirect Section
        return response()->json($msg);
        //--- Redirect Section Ends    
    }




    public function menudestroy($id)
    {
        $data = Menu::where('id',$id)->first();
        $role = Role::get();

        foreach($role as $val){
           $menu = $val->menu;
            $newmenu = '';
            if($menu != null){
                foreach($menu as $key => $exitmenu){

                    if(!Menu::where('id',$exitmenu)->exists()){
                    unset($menu[$key]);
                    }else{
                        if($newmenu != null){
                                $newmenu .= ",".$menu[$key];
                            }else{
                                $newmenu .= $menu[$key]; 
                        }
                    }
                }  
                $newmenu = array('menu' => $newmenu);
                Role::where('id',$val->id)->update($newmenu);
            }
        }

        if(Menu::where('id',$id)->delete()){
            $msg = array(
                'message' =>  "Data Deleted Successfully.",
                'type' => 'success'
            );
        }else{
            $msg = array(
                'message' =>  "Something is wrong, Please Try again.",
                'type' => 'warn'
            );
        }
      
        // //--- Redirect Section
         return response()->json($msg);
        // //--- Redirect Section Ends    
    }


}
