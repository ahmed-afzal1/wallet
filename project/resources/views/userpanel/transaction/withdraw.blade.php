@extends('layouts.user') 

@section('content')

<div class="money-form">
    <h4 class="title">
        {{__("Withdraw Money")}}
    </h4>
    @include('userpanel.includes.form-both')
    <input type="hidden" value="withwraw" id="page">

    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <p class="pl-4 m-0" style="color:#856404"><strong>Note: </strong></p>
        <ul>
            <li id="minwithdrawamount"></li>
            <li id="maxwithdrawamount"> </li>
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata" action="{{route('user-withdraw-create')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="payment-method"  class="bmd-label-floating" >{{__('Select your Payment method')}}</label>
                    <select class="form-control" name="account_type" data-style="btn btn-link" id="withdraw-method" required>
                        <option selected disabled>{{__("Select Withdraw Method")}}</option>
                        @forelse ($paymentgateways as $value)
                            <option value="{{$value->name}}">{{ucwords($value->name)}}</option>
                        @empty
                            
                        @endforelse
                        <option value="Bank">{{__('Bank')}}</option>
                    </select>
                </div>
          </div>

          <div class="col-lg-12 d-none mail-section">
            <div class="form-group">
                <label for="amount" class="bmd-label-floating">{{__("Enter Account Email")}} <span class="text-danger">*</span> <span id="withdrawminamount" class="text-danger"></span></label>
                <input type="email" name="user_email" class="form-control userEmail" placeholder="{{__("Enter Account Email")}}">
            </div>
        </div>

        <div class="col-lg-12 d-none bank-section">
            <div class="col-lg-12">
                <h5>
                    {{__('Select Bank Account')}} :
                </h5>
            </div>
            <div class="col-lg-12">
                @foreach ($bankaccounts as $key => $account)
                    @php
                        $bank_number_length = strlen($account->account_number);
                    @endphp
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="customRadio{{$key+1}}" name="bank_id" class="custom-control-input bank-check" value="{{$account->id}}">
                    <label class="custom-control-label"  for="customRadio{{$key+1}}"> {{str_repeat("*",$bank_number_length)}} {{substr($account->account_number, -4)}}</label>
                </div>
                @endforeach
            </div>

            <div class="col-lg-12">
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="customRadio0" name="bank_id" class="custom-control-input bank-check" value="0">
                    <label class="custom-control-label" for="customRadio0"> {{__('Add New Bank')}}</label>
                </div>
            </div>

            <div class="col-lg-12 d-none bank-show">

            <div class="border p-3 mt-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label text-dark">{{__("Country")}}:<span class="text-danger">*</span></label>
                            <select name="country" class="form-control withdraw-require"  id="">
                                <option selected disabled> {{__('-- Select Country --')}}</option>
                                @forelse ($countries  as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @empty
                                @endforelse
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label text-dark">{{__("Bank Name")}}:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control withdraw-require" name="bank_name" id="recipient-name" placeholder="{{__("Bank Name")}}">
                        </div>
                    </div>
                </div>
   
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_name" class="col-form-label text-dark">{{__("Account Name")}}:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control withdraw-require" name="account_name" id="account_name" placeholder="{{__("Account Name")}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_number" class="col-form-label text-dark">{{__("Account Number")}}:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control allowIntiger withdraw-require" name="account_number" id="account_number" placeholder="{{__("Account Number")}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="swift_code" class="col-form-label text-dark">{{__("Swift Code")}}:</label>
                            <input type="text" class="form-control withdraw-require" name="swift_code" id="swift_code" placeholder="{{__("Swift Code")}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="routing_number" class="col-form-label text-dark">{{__("Routing Number")}}:</label>
                            <input type="text" class="form-control withdraw-require" name="routing_number" id="routing_number" placeholder="{{__("Routing Number")}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="checkbox" id="banksave" name="isCheckBankSave" value="1">
                            <label for="banksave"> {{__('Save for future withdraw')}}?</label><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <div class="col-lg-12 bank-currency">
            <div class="form-group">
                <label for="currency"  class="bmd-label-floating" >{{__("Select your Currency")}}</label>
                <select class="form-control" name="currency" data-style="btn btn-link" id="withdrawcurrency" data-route="{{route('user-withdraw-amount-check')}}">
                <option selected disabled>{{__("Select Currency")}}</option>
                    @forelse ($currency as $value)
                        @php
                           $wallet = App\Models\Accountbalance::where('user_id',$userId)->where('currency_id',$value->id)->first();
                        @endphp
                        <option {{($userinfo->default_currency == $value->id)?'selected':''}} value="{{$value->id}}" data-sign="{{$value->sign}}" data-inrate="{{$value->minimum_withdraw_amount ? $value->minimum_withdraw_amount : 0}}" data-inratemaxamount="{{$value->maximum_withdraw_amount ? $value->maximum_withdraw_amount : 0}}" data-fixedCharge="{{$value->fixed_withdraw_charge ? $value->fixed_withdraw_charge : 0}}" data-percentageCharge="{{$value->percentage_withdraw_charge ? $value->percentage_withdraw_charge : 0}}">{{ucwords($value->code)}} ({{$wallet ? round($wallet->balance,2) : 0}})</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="amount" class="bmd-label-floating">{{__("Enter Amount")}} <span class="text-danger">*</span> <span id="withdrawminamount" class="text-danger"></span></label>
                <input type="text" name="amount" min="{{$defaultCurrency->minimum_deposit_amount ? $defaultCurrency->minimum_deposit_amount : 0}}"  max="{{$defaultCurrency->maximum_deposit_amount ? $defaultCurrency->maximum_deposit_amount : 0}}"  class="form-control allownDecimal" id="withdrawamount" step="any" value="{{$defaultCurrency->minimum_deposit_amount ? $defaultCurrency->minimum_deposit_amount : 0}}" placeholder="Enter Amount" required>
                <small>{{__('Withdraw Cost')}}: <span id="transactioncost" class="text-dark">0.00</span></small>
            </div>
        </div>
        <div class="col-lg-12">
            <p id="balancemsg" class="text-danger text-center"></p>
            <button type="submit" class="btn btn-primary btn-round" id="withdrawsubmitbtn">{{__('Withdraw')}}</button>
        </div>

        </div>
    </form>
</div>
@endsection
@section('script')
<script src="{{asset('assets/userpanel/js/transaction.js')}}"></script>
@endsection