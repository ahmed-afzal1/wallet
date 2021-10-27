<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use DataTables;

class SubscriberController extends Controller
{
    use Localization;

    public function datatables(){
        $datas = Subscriber::orderBy('id','asc')->get();
        return Datatables::of($datas)
                                ->rawColumns([''])
                                ->toJson();
    }

    public function index()
    {
        $data['subscribersList'] = Subscriber::orderBy('id','desc')->get(); 
        return view('admin.subscribers.index',$data);
    }

    public function download()
    {
        //  Code for generating csv file
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=subscribers.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Subscribers Emails'));
        $result = Subscriber::all();
        foreach ($result as $row){
            fputcsv($output, $row->toArray());
        }
        fclose($output);
    }

    public function delete()
    {
        Subscriber::where('id',request()->sid)->delete();
        return redirect()->back(); 
    }
}
