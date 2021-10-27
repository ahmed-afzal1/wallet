<?php

namespace App\Http\Controllers\Admin;
use App\Models\Pagesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;

class PageSettingController extends Controller
{
    use Localization;

    protected $rules =
    [
        'video_image' => 'mimes:jpeg,jpg,png,svg',
        'video_background'    => 'mimes:jpeg,jpg,png,svg',
        'p_background'    => 'mimes:jpeg,jpg,png,svg',
        'review_background'    => 'mimes:jpeg,jpg,png,svg',
        'c_background'    => 'mimes:jpeg,jpg,png,svg',
    ];


    // Page Settings All post requests will be done in this method
    public function update(Request $request)
    {
        $data = Pagesetting::findOrFail(1);
        $input = $request->all();

            if ($file = $request->file('video_image'))
            {
                $name = time().$file->getClientOriginalName();
                $data->upload($name,$file,$data->video_image);
                $input['video_image'] = $name;
            }
            if ($file = $request->file('video_background'))
            {
                $name = time().$file->getClientOriginalName();
                $data->upload($name,$file,$data->video_background);
                $input['video_background'] = $name;
            }
            if ($file = $request->file('p_background'))
            {
                $name = time().$file->getClientOriginalName();
                $data->upload($name,$file,$data->p_background);
                $input['p_background'] = $name;
            }
            if ($file = $request->file('review_background'))
            {
                $name = time().$file->getClientOriginalName();
                $data->upload($name,$file,$data->review_background);
                $input['review_background'] = $name;
            }
            if ($file = $request->file('c_background'))
            {
                $name = time().$file->getClientOriginalName();
                $data->upload($name,$file,$data->c_background);
                $input['c_background'] = $name;
            }
        $data->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }


    public function homeupdate(Request $request)
    {
        $data = Pagesetting::findOrFail(1);
        $input = $request->all();

        if ($request->slider == ""){
            $input['slider'] = 0;
        }
        if ($request->service == ""){
            $input['service'] = 0;
        }
        if ($request->featured == ""){
            $input['featured'] = 0;
        }
        if ($request->project_section == ""){
            $input['project_section'] = 0;
        }
        if ($request->review_section == ""){
            $input['review_section'] = 0;
        }
        if ($request->blog_section == ""){
            $input['blog_section'] = 0;
        }
       
       
        if ($request->contact_section == ""){
            $input['contact_section'] = 0;
        }
        $data->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }


    public function contact()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.contact',compact('data'));
    }

    public function video()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.video',compact('data'));
    }

    public function present()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.present',compact('data'));
    }

    public function homecontact()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.homecontact',compact('data'));
    }

    public function blog()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.blog',compact('data'));
    }

    public function customize()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.customize',compact('data'));
    }


    public function faqupdate($status)
    {
        $page = Pagesetting::findOrFail(1);
        $page->f_status = $status;
        $page->update();
        Session::flash('success', 'FAQ Status Upated Successfully.');
        return redirect()->back();
    }

    public function contactup($status)
    {
        $page = Pagesetting::findOrFail(1);
        $page->c_status = $status;
        $page->update();
        Session::flash('success', 'Contact Status Upated Successfully.');
        return redirect()->back();
    }


    public function contactupdate(Request $request)
    {
        $page = Pagesetting::findOrFail(1);
        $input = $request->all();
        $page->update($input);
        Session::flash('success', 'Contact page content updated successfully.');
        return redirect()->route('admin-ps-contact');;
    }

}
