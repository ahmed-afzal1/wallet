<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Pagesetting;
use Illuminate\Support\Facades\Input;
use Validator;

class PortfolioController extends Controller
{
    use Localization;

    //*** JSON Request
    public function datatables()
    {
         $datas = Portfolio::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return DataTables::of($datas)
                            ->editColumn('photo', function(Portfolio $data) {
                                $photo = $data->photo ? url('assets/images/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->addColumn('action', function(Portfolio $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-portfolio-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-portfolio-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
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
        return view('admin.portfolio.index',compact('ps'));
    }

    //*** GET Request
    public function create()
    {
        return view('admin.portfolio.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
               'photo1'      => 'required|mimes:jpeg,jpg,png,svg',
               'slug' => 'unique:portfolios'
                ];

                $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Portfolio();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);
            $input['photo'] = $name;
        }
        if ($file = $request->file('photo1'))
         {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);
            $input['photo1'] = $name;
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
        $data = Portfolio::findOrFail($id);
        return view('admin.portfolio.edit',compact('data'));
    }


    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'mimes:jpeg,jpg,png,svg',
               'photo1'      => 'mimes:jpeg,jpg,png,svg',
               'slug' => 'unique:portfolios,slug,'.$id
                ];

                $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Portfolio::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images',$name);
                @unlink('assets/images/'.$data->photo);

                $input['photo'] = $name;
            }


            if ($file = $request->file('photo1'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images',$name);
                @unlink('assets/images/'.$data->photo1);

                $input['photo1'] = $name;
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
        $data = Portfolio::findOrFail($id);
   
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        @unlink('assets/images/'.$data->photo);
        @unlink('assets/images/'.$data->photo1);

        $data->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
