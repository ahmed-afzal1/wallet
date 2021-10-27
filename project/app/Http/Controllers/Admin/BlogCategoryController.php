<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Support\Str;
use Validator;

class BlogCategoryController extends Controller
{
    use Localization;

    public function datatables()
    {
         $datas = BlogCategory::orderBy('id','desc')->get();
         return Datatables::of($datas)
                            ->addColumn('action', function(BlogCategory $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-cblog-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-cblog-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['action'])
                            ->toJson();
    }


    public function index()
    {
        return view('admin.cblog.index');
    }

    public function create()
    {
        return view('admin.cblog.create');
    }

    //*** POST Request
    public function store(Request $request)
    {

        $data = new BlogCategory;
        $input = $request->all();
        $input['slug'] = Str::slug($request->slug,'-');
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.'.' '.'<a href="'.route('admin-cblog-index').'"> '.__('View Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = BlogCategory::findOrFail($id);
        return view('admin.cblog.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = BlogCategory::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.'.' '.'<a href="'.route('admin-cblog-index').'"> '.__('View Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function destroy($id)
    {
        $data = BlogCategory::findOrFail($id);

        //--- Check If there any blogs available, If Available Then Delete it
        if($data->blogs->count() > 0)
        {
            foreach ($data->blogs as $element) {
                $element->delete();
            }
        }
        $data->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
