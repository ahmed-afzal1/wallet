<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Pagesetting;
use Illuminate\Support\Facades\Input;
use Validator;

class ReviewController extends Controller
{

    use Localization;
    //*** JSON Request
    public function datatables()
    {
            $datas = Review::orderBy('id','desc')->get();
            //--- Integrating This Collection Into Datatables
            return Datatables::of($datas)
                            ->editColumn('photo', function(Review $data) {
                                $photo = $data->photo ? url('assets/images/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->editColumn('details', function(Review $data) {
                                $details = strlen(strip_tags($data->details)) > 250 ? substr(strip_tags($data->details),0,250).'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(Review $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-review-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                                </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-review-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                                </button></div>';
                            })
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $ps=Pagesetting::findOrFail(1);
        return view('admin.review.index',compact('ps'));
    }

    //*** GET Request
    public function create()
    {
        return view('admin.review.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Review();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Review::findOrFail($id);
        return view('admin.review.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Review::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images',$name);
                @unlink('assets/images/reviews/'.$data->photo);

                $input['photo'] = $name;
            }
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    
    public function destroy($id)
    {
        $data = Review::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        @unlink('assets/images/reviews/'.$data->photo);

        $data->delete();

        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
