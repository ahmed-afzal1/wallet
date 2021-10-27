<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Traits\Localization;
use App\Models\Paymentgateway;
use Illuminate\{
    Http\Request,
};
use Validator;
use DataTables;

class PaymentGatewayController extends Controller
{
    use Localization;
    public function datatables(){

        $datas = Paymentgateway::orderby('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->editColumn('title', function(PaymentGateway $data) {
                                if($data->type == 'automatic'){
                                    return  $data->name;
                                }else{
                                    return  $data->title;
                                }
                            })
                            ->editColumn('details', function(PaymentGateway $data) {
                                if($data->type == 'automatic'){
                                    return $data->getAutoDataText();
                                }else {
                                    if($data->keyword == 'cod'){
                                        return $data->subtitle;
                                    }else{
                                        $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                        return  $details;
                                    }

                                }
                            })
                            ->addColumn('status', function(PaymentGateway $data) {
                                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                                return '<div class="btn-group mb-1">
                                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '.$status .'
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.payment.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Activate").'</a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.payment.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Deactivate").'</a>
                                            </div>
                                        </div>';
                            })
                            ->addColumn('action', function(PaymentGateway $data) {
                                $delete = $data->type == 'manual' ? '<button type="button" data-toggle="modal" data-target="#deleteModal"  data-href="' . route('admin.payment.delete',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                <i class="fas fa-trash"></i>
                              </button>' : '';

                                return '<div class="action-list"><a href="'. route('admin.payment.edit',$data->id) . '"  class="btn btn-info btn-sm mr-2"> <i class="fas fa-edit mr-1"></i>'. __('Edit') .'</a>'.$delete.'</div>';
                            })
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index(){
        
        return view('admin.payment.index');
    }

    public function create(){
        return view('admin.payment.create');
    }


    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'title' => 'required|unique:payment_gateways',
            'details' => 'required',
        ];

        $customs = [
            'title.required' => 'Title field is required.',
            'title.unique' => 'Title Should be unique.',
            'details.required' => 'Description field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new PaymentGateway();
        $input = $request->all();
        $input['type'] = "manual";
        $input['keyword'] = "Manual";
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }


    public function edit($id)
    {
        $data = PaymentGateway::findOrFail($id);
        return view('admin.payment.edit',compact('data'));
    }

    private function setEnv($key, $value,$prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        $data = PaymentGateway::findOrFail($id);
        $prev = '';
        if($data->type == "automatic"){

            //--- Validation Section
            $rules = ['name' => 'unique:payment_gateways,name,'.$id];

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }        
            //--- Validation Section Ends

            //--- Logic Section

            $input = $request->all();  

            $info_data = $input['pkey'];

            if($data->keyword == 'mollie'){
                $paydata = $data->convertAutoData(); 
                $prev = $paydata['key'];
            }   

            if (array_key_exists("sandbox_check",$info_data)){
                $info_data['sandbox_check'] = 1;
            }else{
                if (strpos($data->information, 'sandbox_check') !== false) {
                    $info_data['sandbox_check'] = 0;
                    $text =  $info_data['text'];
                    unset($info_data['text']);
                    $info_data['text'] = $text;
                }
            }
            $input['information'] = json_encode($info_data);
            $data->update($input);


            if($data->keyword == 'mollie'){
                $paydata = $data->convertAutoData(); 
                $this->setEnv('MOLLIE_KEY',$paydata['key'],$prev);

            }     
            //--- Logic Section Ends
        }
        else{
                //--- Validation Section
                $rules = [
                    'title' => 'required|unique:payment_gateways,title,'.$id,
                    'details' => 'required',
                ];

                $customs = [
                    'title.required' => 'Title field is required.',
                    'title.unique' => 'Title Should be unique.',
                    'details.required' => 'Description field is required.',
                ];

                $validator = Validator::make($request->all(), $rules, $customs);

                if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                }
                //--- Validation Section Ends

            //--- Logic Section

            $input = $request->all();  
            $data->update($input);

  
            //--- Logic Section Ends

        }
        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends    
    }
    
    public function status($id1,$id2)
    {
        $data = PaymentGateway::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $mgs = ('Data Update Successfully.');
        return response()->json($mgs);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = PaymentGateway::findOrFail($id);
        if($data->type == 'manual' || $data->keyword != null){
            $data->delete();
        }
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
