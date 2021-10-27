<?php

namespace App\Http\Controllers\Admin;
use DataTables;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class SliderController extends Controller
{
    use Localization;
    public function datatables()
    {
         $datas = Slider::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Slider $data) {
                                $photo = $data->photo ? url('assets/images/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" style="width:60%;" alt="Image">';
                            })
                            ->editColumn('title', function(Slider $data) {
                                $title = strlen(strip_tags($data->title_text)) > 250 ? substr(strip_tags($data->title_text),0,250).'...' : strip_tags($data->title_text);
                                return  $title;
                            })
                            ->addColumn('action', function(Slider $data) {
                                return '<div class="action-list"><a href="'. route('admin-sl-edit',$data->id) . '"  class="btn btn-success btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('Edit') .'</a><button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-sl-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index()
    {
        return view('admin.slider.index');
    }

    public function create(){
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $input = request()->all();

        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $customs = [
            'photo.required' => 'Photo field is required',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($input,$rules,$customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new Slider();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();

        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Slider::findOrFail($id);
        return view('admin.slider.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $input = request()->all();

        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $customs = [
            'photo.required' => 'Photo field is required',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($input,$rules,$customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = Slider::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images',$name);
                @unlink('/assets/images/'.$data->photo);

                $input['photo'] = $name;
            }

        $data->update($input);

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }
    
    public function destroy($id)
    {
        $data = Slider::findOrFail($id);

        if($data->photo == null){
            $data->delete();
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
        }
        @unlink('/assets/images/'.$data->photo);

        $data->delete();
  
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

}
