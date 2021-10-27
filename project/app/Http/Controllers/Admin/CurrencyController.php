<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Countries;
use DataTable;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Session;

class CurrencyController extends Controller
{

    use Localization;


    public function currencyDatatables(){

        $datas = Currency::orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->addColumn('action', function(Currency $data) {
                                $default = $data->is_default == 1 ? '<a href="javascript:;" class="btn btn-primary btn-sm btn-rounded ml-2"><i class="fa fa-check"></i> Default</a>' : '<a class="status btn btn-primary btn-sm btn-rounded ml-2" href="javascript:;" data-href="' . route('admin.currency.status',['id1'=>$data->id,'id2'=>1]) . '">'.__('Set Default').'</a>';
                                $charge  = '<a href="'. route('admin.charge.currency',$data->id) .'"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('Charge') .'</a>';
                                return '<div class="action-list">'.$charge.'<a href="'. route('admin.edit.currency',$data->id) . '"  class="btn btn-primary btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('Edit') .'</a><button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin.currency.delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button>'.$default.'</div>';
                            })
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index()
    {
        $data['currencydata'] = Currency::get();
        $data['countries'] = Countries::get();
        return view('admin.currency.index',$data);
    }


    public function create(Request $request){
        $data['currencydata'] = Currency::get();
        $data['countries'] = Countries::get();

        return view('admin.currency.create',$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => 'required',
            "country_id" => 'required|unique:currencies',
            "sign" => 'required|unique:currencies',
            "code" => 'required|unique:currencies',
            "rate" => 'required',
        ];

        $customs = [
            'name.required' => __('This Name field is required.'),
            'country_id.required' => __('This Country field is required.'),
            'country_id.unique' => __('This Country has already been taken.'),
            'sign.required' => __('This Sign field is required.'),
            'sign.unique' => __('This Sign has already been taken.'),
            'code.required' => __('This Code field is required.'),
            'code.unique' => __('This Code has already been taken.'),
            'rate.required' => __('This rate field is required'),
        ];

       $validator = Validator::make($request->all(),$rules,$customs);

       if($validator->fails()){
           return response()->json(array('errors'=>$validator->getMessageBag()->toArray()));
       }

       $data = array(
        "name" => $request->name,
        "country_id" => $request->country_id,
        "sign" => $request->sign,
        "code" => $request->code,
        "rate" => $request->rate,
       );
       Currency::insert($data);


       //--- Redirect Section        
       $msg = __('New Data Added Successfully.');
       return response()->json($msg);      
       //--- Redirect Section Ends 
    }


    public function edit($id)
    {
        $data['currencydata'] = Currency::find($id);
        $data['countries'] = Countries::get();
        return view('admin.currency.edit',$data);
    }



    public function status($id1,$id2)
    {
        $data = Currency::findOrFail($id1);
        $data->is_default = $id2;
        $data->update();
        $data = Currency::where('id','!=',$id1)->update(['is_default' => 0]);
        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }



    public function update(Request $request, $id)
    {
        
        $request->validate([
            "name" => 'required',
            "country_id" => ['required',
                            Rule::unique('currencies')->ignore($id),
                        ],
            "sign" => ['required',
                        Rule::unique('currencies')->ignore($id),
                    ],
            "code" => ['required',
                        Rule::unique('currencies')->ignore($id),
                    ],
            "rate" => 'required',
        ]);

        $data = array(
         "name" => $request->name,
         "country_id" => $request->country_id,
         "sign" => $request->sign,
         "code" => $request->code,
         "rate" => $request->rate,
        );

        Currency::where('id',$id)->update($data);

       //--- Redirect Section        
       $msg = __('Data Updated Successfully.');
       return response()->json($msg);      
       //--- Redirect Section Ends 
    }


    public function charge($id){
        $data['charge'] = Currency::findOrFail($id);
        return view('admin.currency.charge',$data);
    }

    public function chargeupdate(Request $request,$id){
        $data = Currency::findOrFail($id);

        $data->fixed_withdraw_charge = $request->fixed_withdraw_charge;
        $data->percentage_withdraw_charge = $request->percentage_withdraw_charge;
        $data->minimum_withdraw_amount = $request->minimum_withdraw_amount;
        $data->maximum_withdraw_amount = $request->maximum_withdraw_amount;

        $data->fixed_deposit_charge = $request->fixed_deposit_charge;
        $data->percentage_deposit_charge = $request->percentage_deposit_charge;
        $data->minimum_deposit_amount = $request->minimum_deposit_amount;
        $data->maximum_deposit_amount = $request->maximum_deposit_amount;

        $data->fixed_transaction_charge = $request->fixed_transaction_charge;
        $data->percentage_transaction_charge = $request->percentage_transaction_charge;
        $data->transaction_limit = $request->transaction_limit;
        $data->transaction_limit_amount = $request->transaction_limit_amount;
        $data->minimum_transaction_amount = $request->minimum_transaction_amount;
        $data->maximum_transaction_amount = $request->maximum_transaction_amount;

        $data->fixed_request_charge = $request->fixed_request_charge;
        $data->percentage_request_charge = $request->percentage_request_charge;
        $data->minimum_request_money = $request->minimum_request_money;
        $data->maximum_request_money = $request->maximum_request_money;
        $data->money_request_per_day = $request->money_request_per_day;

        $data->update();

        //--- Redirect Section        
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);      
       //--- Redirect Section Ends 
    }


    public function updatestatus()
    {
        $data = array('status' => request()->status);
        if(Currency::where('id',request()->id)->update($data)){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data  = Currency::findOrFail($id);
        if(Transaction::where('transaction_currency',$data->id)->exists()){

            $msg = "This Currency Has Transaction";
            return response()->json($msg);
        }
        

        if($id == 1)
        {
            $msg = "You don't have access to remove this Currency";
            return response()->json($msg);
        }
        if($data->is_default == 1)
        {
            $msg = "You can not remove default Currency.";
            return response()->json($msg);

        }
        $data->delete();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}
