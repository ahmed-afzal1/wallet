<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Pagesetting;
use Validator;

class BlogController extends Controller
{

    use Localization;

    //*** JSON Request
    public function datatables()
    {
         $datas = Blog::orderBy('id','desc')->get();
 
         return Datatables::of($datas)
                            ->editColumn('photo', function(Blog $data) {
                                $photo = $data->photo ? url('assets/images/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })


                            ->addColumn('action', function(Blog $data) {
                                return '<div class="actions-btn"><a href="' . route('admin-blog-edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                <i class="fas fa-edit"></i> '.__("Edit").'
                              </a>  <button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin-blog-delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button></div>';
                            })
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index()
    {
        $ps=Pagesetting::findOrFail(1);
        return view('admin.blog.index',compact('ps'));
    }


    public function create()
    {
        $cats = BlogCategory::all();
        return view('admin.blog.create',compact('cats'));
    }

    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
                'title' => 'required',
                'category_id' => 'required',
                'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $customs = [
            'title.required' => 'Title field is required',
            'category_id.required' => 'Category field is required',
            'photo.required' => 'Photo field is required',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Blog();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images',$name);
            $input['photo'] = $name;
        }


         // ------------------------TagFormat--------------------------//

            $common_rep   = ["value", "{", "}", "[","]",":","\""];
            $tag = str_replace($common_rep, '', $request->tags);
            $metatag = str_replace($common_rep, '', $request->meta_tag);



        if (!empty($metatag))
        {
            $input['meta_tag'] = $metatag;
        }


        if (!empty($tag))
         {
            $input['tags'] = $tag;
         }
        if ($request->secheck == "")
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
         }
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.'.'<a href="'.route("admin-blog-index").'">View Post Lists</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    public function edit($id)
    {
        $cats = BlogCategory::all();
        $data = Blog::findOrFail($id);
        return view('admin.blog.edit',compact('data','cats'));
    }

    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ];

        $customs = [
            'title.required' => 'Title field is required',
            'category_id.required' => 'Category field is required',
            'photo.mimes' => 'Photo extension should be jpeg, jpg, png, svg.',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Blog::findOrFail($id);
        $input = $request->all();

            if ($file = $request->file('photo'))
            {
                $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move('assets/images',$name);
                @unlink('assets/images/'.$data->photo);
                $input['photo'] = $name;
            }
            $common_rep   = ["value", "{", "}", "[","]",":","\""];
            $tag = str_replace($common_rep, '', $request->tags);
            $metatag = str_replace($common_rep, '', $request->meta_tag);



        if (!empty($metatag))
        {
            $input['meta_tag'] = $metatag;
        }
        if (!empty($tag))
         {
            $input['tags'] = $tag;
         }
        if ($request->secheck == "")
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
         }

        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.'.'<a href="'.route("admin-blog-index").'">View Post Lists</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function destroy($id)
    {
        $data = Blog::findOrFail($id);

        if($data->photo == null){
            $data->delete();
            //--- Redirect Section
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        @unlink('assets/images/'.$data->photo);

        $data->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
