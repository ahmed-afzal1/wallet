<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use DataTables;
use App\Http\Traits\Localization;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use Localization;
    public function datatables()
    {
         $datas = Faq::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('details', function(Faq $data) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(Faq $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-faq-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-faq-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.faq.index');
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        //--- Logic Section
        $data = new Faq();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.'.'<a href="'.route("admin-faq-index").'">View Faq Lists</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function edit($id)
    {
        $data = Faq::findOrFail($id);
        return view('admin.faq.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = Faq::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.'.'<a href="'.route("admin-faq-index").'">View Faq Lists</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function destroy($id)
    {
        $data = Faq::findOrFail($id);
        $data->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
