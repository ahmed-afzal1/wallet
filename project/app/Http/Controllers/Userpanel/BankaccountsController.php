<?php

namespace App\Http\Controllers\Userpanel;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserLocalization;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Countries;
use App\Models\Bankaccount;
use App\Models\Craditcard;
use Validator;
use Auth;

class BankaccountsController extends Controller
{
    use UserLocalization;

    public function index()
    {
        $data['currency'] = Currency::get();
        $data['countries'] = Countries::get();
        return view('userpanel.bankaccounts.create',$data);
    }



    public function store(Request $request)
    {
        $roles = [
            'country' => 'required',
            'bank_name' => 'required',
            'bank_account_currency' => 'required',
            'account_name' => 'required',
            'account_number' => 'required|unique:bankaccounts',
            'routing_number' => 'required',
            'swift_code' => 'unique:bankaccounts',
        ];

        $customs = [
            'country.required' => 'Country is required',
            'bank_account_currency.required' => 'Bank Account Currency is required',
            'account_name.required' => 'Account Name is required',
            'account_number.required' => 'Account Number is required',
            'account_number.unique' => 'Account Number is already been taken',
            'routing_number.required' => 'Routing Number is required',
            'swift_code.required' => 'Swift Code is required',
            'swift_code.unique' => 'Swift Code is already been taken',
        ];

        $validator = Validator::make($request->all(),$roles,$customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = array(
            'user_id' =>  Auth::guard('web')->user()->id,
            'user_email' =>  Auth::guard('web')->user()->email,
            'account_type' =>  $request->account_type,
            'country' =>  $request->country,
            'bank_name' =>  $request->bank_name,
            'bank_account_currency' =>  $request->bank_account_currency,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'routing_number' => $request->routing_number,
            'swift_code' => $request->swift_code,

        );

        if(Bankaccount::insert($data)){
            $msg = [
                'type' => 'success',
                'message' => 'Account added successfully.',
                'account'  => true
            ];
        }
        return response()->json($msg);
    }




    public function showbankaccounts()
    {
        $data['bankaccounts'] = Bankaccount::where(['user_email'=>Auth::guard('web')->user()->email])->orderBy('id','desc')->get();
        return view('userpanel.bankaccounts.bankaccountlist',$data);
    }





    public function bankaccountsetprimary($id)
    {

        Bankaccount::where('is_primary',1)->update(['is_primary'=>0]);
        Bankaccount::where('id',$id)->update(['is_primary'=>1]);
        $msg = [
            'type' => 'success',
            'message' => 'Account set primary successfully',
            'account'  => true
        ];
        return response()->json($msg);
    }





    public function craditcardstore(Request $request)
    {

        $roles = [
            'card_owner_name' => 'required',
            'card_currency' => 'required',
            'card_number' => 'required|unique:craditcards',
            'card_cvc' => 'required',
            'month' => 'required',
            'year' => 'required',
        ];

        $customs = [
            'card_owner_name.required' => 'Card Owner Name is required',
            'card_currency.required' => 'Card Currency is required',
            'card_number.required' => 'Card Number is required',
            'card_number.unique' => 'Card Number already been taken!',
            'card_cvc.required' => 'Card CVC is required',
            'month.required' => 'Month is required',
            'year.required' => 'Year is required',
        ];

        $validator = Validator::make($request->all(),$roles,$customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = array(
            'user_id' => $request->user_id,
            'user_email' => Auth::guard('web')->user()->email,
            'card_owner_name' => $request->card_owner_name,
            'card_number' => $request->card_number,
            'card_cvc' => $request->card_cvc,
            'month' => $request->month,
            'year' => $request->year,
        );
        if(Craditcard::insert($data)){
            $msg = [
                'type' => 'success',
                'message' => 'Cradit Card added successfully. This Card is under review, you can use this account after approve by admin',
                'card'  => true
            ];
        }
        return response()->json($msg);
    }





    public function showcraditcards()
    {
        $data['craditcards'] = Craditcard::where(['user_email'=>Auth::guard('web')->user()->email])->orderBy('id','desc')->get();
        return view('userpanel.bankaccounts.craditcardslist',$data);
    }


    public function craditcardsetprimary($id)
    {

        Craditcard::where('is_primary',1)->update(['is_primary'=>0]);
        Craditcard::where('id',$id)->update(['is_primary'=>1]);
        $msg = [
            'type' => 'success',
            'message' => 'Cradit card set primary successfully',
            'card'  => true
        ];
        return response()->json($msg);
    }










}

?>