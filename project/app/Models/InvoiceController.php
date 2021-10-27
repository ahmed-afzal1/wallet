<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserLocalization;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    use UserLocalization;

    public function invoicelist(){
        $data['invoices'] = Invoice::where('user_id',auth()->user()->id)->orderby('id','desc')->get();
        return view('userpanel.invoices.index',$data);
    }

    public function invoice(){
        return view('userpanel.invoices.create');
    }

    public function invoiceSend(Request $request){
        $input = $request->all();
        $data = new Invoice();
        $input['user_id'] = auth()->user()->id;
        $input['token'] = Str::random(4).time();
        $data->fill($input)->save();

        $msg = 'Invoice Created Successfully';
        return redirect()->back()->with('success',$msg);
    }


    public function linkdetails($id){
        $link = Invoice::findOrFail($id);
        // $gs = Settings::findOrFail(1);

        $details = '<table class="table" style="table-layout:fixed;">
                        <tbody>
                        <tr>
                            <td class="whatis">Payment Link ID#</td>
                            <td>'.str_pad($link->id, 9, '0', STR_PAD_LEFT).'</td>
                        </tr>
                        
                        <tr>
                            <td class="whatis">Created Date:</td>
                            <td>'.date('H:i d/m/Y',strtotime($link->created_at)).'</td>
                        </tr>

                        <tr>
                            <td class="whatis">Customer Name</td>
                            <td>'.$link->name.'</td>
                        </tr>
                        <tr>
                            <td class="whatis">Customer Email:</td>
                            <td>'.$link->email.'</td>
                        </tr>
                        <tr>
                            <td class="whatis">Requested Total:</td>
                            <td>'.$link->amount.'</td>
                        </tr>
                        <tr>
                            <td class="whatis">Payment Status:</td>
                            <td>'.ucfirst($link->status).'</td>
                        </tr>';
                    if($link->status == 'pending'){
                            $details .=' <tr><div>
                                            <td colspan="2"><h4 class="pull-left">Payment Link: </h4>
                                            <input onclick="myFunction()" type="text" class="form-control" value="'.url('/account/invoice/link').'?p='.$link->token.'" readonly id="myInput">
                                            <button style="bottom:48px;right: 25px;" onclick="myFunction()" class="copyBtn" id="copy_btn"><i class="fa fa-copy"></i> Copy Link</button></td>
                                        </div></tr>
                                    
                                    
                                    <script>
                                        function myFunction() {
                                            var copyText = document.getElementById("myInput");
                                            copyText.select();
                                            copyText.setSelectionRange(0, 99999)
                                            document.execCommand("copy");
                                            $("#copy_btn").html("<i class=\'fa fa-copy\'></i> Copied!");
                                        }
                                    </script>';
                        }else{
                            $details .=' <tr>
                                            <td class="whatis">Transaction ID:</td>
                                            <td>'.fgh.'</td>
                                        </tr>';
                        }        
                $details .='      </tbody>
                            </table>';

                return $details;
        }

        public function linkPay(){

            $gs = Settings::findOrFail(1);
            $token = $_GET['p'];
            
            if(Invoice::where('token',$token)->where('status','pending')->exists()){
    
                $payData = Invoice::where('token',$token)->first();
    
                session(['link_pay_tkn'=> $token]);
                return view('user.payment-by-link',compact('payData','gs'));
            }else{
                
                return view('user.payment-by-link-error',compact('gs'));
            }
        }
}
