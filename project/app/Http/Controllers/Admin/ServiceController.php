<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;

use Validator;

class ServiceController extends Controller
{
    use Localization;

    //*** JSON Request
    public function datatables()
    {
         $datas = Service::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Service $data) {
                                $photo = $data->photo ? url('assets/images/services/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->editColumn('title', function(Service $data) {
                                $title = mb_strlen(strip_tags($data->title),'utf-8') > 250 ? mb_substr(strip_tags($data->title),0,250,'utf-8').'...' : strip_tags($data->title);
                                return  $title;
                            })
                            ->editColumn('details', function(Service $data) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })


                            ->addColumn('action', function(Service $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-service-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-service-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.service.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.service.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'title' => 'required',
            'subtitle' => 'required',
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
            'slug' => 'unique:services'
        ];

        $customs = [
            'title.required' => 'Title field is required',
            'subtitle.required' => 'SubTitle field is required',
            'photo.required' => 'Photo field is required',
            'slug.unique' => 'Slug Should be unique',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($request->all(), $rules,$customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Service();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/services',$name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Data Added Successfully.').' '.'<a href="'.route('admin-service-index').'">'.__('View Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Service::findOrFail($id);
        return view('admin.service.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'title' => 'required',
            'subtitle' => 'required',
            'photo'      => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'unique:services,slug,'.$id
        ];

        $customs = [
            'title.required' => 'Title field is required',
            'subtitle.required' => 'SubTitle field is required',
            'slug.unique' => 'Slug Should be unique',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($request->all(), $rules,$customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Service::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo'))
        {
            if($data->photo != null)
            {
                if(file_exists(base_path('../assets/images/services/'.$data->photo))){
                    unlink(base_path('../assets/images/services/'.$data->photo));
                }
            }
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/services',$name);
            $input['photo'] = $name;
        }
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('Data Updated Successfully.').' '.'<a href="'.route('admin-service-index').'">'.__('View Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Service::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if(file_exists(base_path('../assets/images/services/'.$data->photo))){
            unlink(base_path('../assets/images/services/'.$data->photo));
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}

