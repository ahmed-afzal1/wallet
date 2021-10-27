@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Currency')}}
        <a href="{{ route('admin-currency-index') }}" class="btn back-btn btn-sm btn-info"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
    </h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-currency-index')}}" class="text-muted">{{__('Currency')}}</a></li>
    </ol>
</div>
<div class="row mb-3">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <form class="geniusform" class="form-content-right" action="{{route('admin.chargeupdate.currency',$charge->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('includes.admin.form-both')

                    <p><strong>{{__('Update Withdraw Info')}}</strong></p>
                    <hr>
                    <div class="form-group row">
                        <label for="fixed_withdraw_charge" class="col-sm-3 col-form-label">{{__('Fixed Withdraw Charge')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->fixed_withdraw_charge}}" name="fixed_withdraw_charge" id="fixed_withdraw_charge" placeholder="{{__('Fixed Withdraw Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="percentage_withdraw_charge" class="col-sm-3 col-form-label">{{__('Percentage Withdraw Charge')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->percentage_withdraw_charge}}" name="percentage_withdraw_charge" id="percentage_withdraw_charge" placeholder="{{__('Percentage Withdraw Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="minimum_withdraw_amount" class="col-sm-3 col-form-label">{{__('Minimum Withdraw')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->minimum_withdraw_amount}}" name="minimum_withdraw_amount" id="minimum_withdraw_amount" placeholder="{{__('Minimum Withdraw')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="maximum_withdraw_amount" class="col-sm-3 col-form-label">{{__('Maximum Withdraw')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->maximum_withdraw_amount}}" name="maximum_withdraw_amount" id="maximum_withdraw_amount" placeholder="{{__('Maximum Withdraw')}}" required>
                        </div>
                    </div>

                    <p><strong>Update deposit Info</strong></p>
                    <hr>

                    <div class="form-group row">
                        <label for="fixed_deposit_charge" class="col-sm-3 col-form-label">{{__('Fixed Deposit Charge')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->fixed_deposit_charge}}" name="fixed_deposit_charge" id="fixed_deposit_charge" placeholder="{{__('Fixed Deposit Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="percentage_deposit_charge" class="col-sm-3 col-form-label">{{__('Percentage Deposit Charge')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->percentage_deposit_charge}}" name="percentage_deposit_charge" id="percentage_deposit_charge" placeholder="{{__('Percentage Deposit Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="minimum_deposit_amount" class="col-sm-3 col-form-label">{{__('Minimum Deposit')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->minimum_deposit_amount}}" name="minimum_deposit_amount" id="minimum_deposit_amount" placeholder="{{__('Minimum Deposit')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="maximum_deposit_amount" class="col-sm-3 col-form-label">{{__('Maximum Deposit')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->maximum_deposit_amount}}" name="maximum_deposit_amount" id="maximum_deposit_amount" placeholder="{{__('Maximum Deposit')}}" required>
                        </div>
                    </div>


                    <p><strong>{{__('Update Transaction Info')}}</strong></p>
                    <hr>
                    <div class="form-group row">
                        <label for="fixed_transaction_charge" class="col-sm-3 col-form-label">{{__('Fixed Transaction Charge')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->fixed_transaction_charge}}" name="fixed_transaction_charge" id="fixed_transaction_charge" placeholder="{{__('Fixed Transaction Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="percentage_transaction_charge" class="col-sm-3 col-form-label">{{__('Percentage Transaction Charge')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->percentage_transaction_charge}}" name="percentage_transaction_charge" id="percentage_transaction_charge" placeholder="{{__('Percentage Transaction Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="transaction_limit" class="col-sm-3 col-form-label">{{__('Transaction Limit (Day)')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->transaction_limit}}" name="transaction_limit" id="transaction_limit" placeholder="{{__('Transaction Limit (Day)')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="transaction_limit" class="col-sm-3 col-form-label">{{__('Transaction Limit Amount')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->transaction_limit_amount}}" name="transaction_limit_amount" id="transaction_limit_amount" placeholder="{{__('Transaction Limit Amount')}}" required>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="minimum_transaction_amount" class="col-sm-3 col-form-label">{{__('Minimum Transaction Amount')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->minimum_transaction_amount}}" name="minimum_transaction_amount" id="minimum_transaction_amount" placeholder="{{__('Minimum Transaction Amount')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="maximum_transaction_amount" class="col-sm-3 col-form-label">{{__('Maximum Transaction Amount')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->maximum_transaction_amount}}" name="maximum_transaction_amount" id="maximum_transaction_amount" placeholder="{{__('Maximum Transaction Amount')}}" required>
                        </div>
                    </div>

                    <p><strong>Update Request Money Info</strong></p>
                    <hr>

                    <div class="form-group row">
                        <label for="fixed_request_charge" class="col-sm-3 col-form-label">{{__('Fixed Request Charge')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->fixed_request_charge}}" name="fixed_request_charge" id="fixed_request_charge" placeholder="{{__('Fixed Request Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="percentage_request_charge" class="col-sm-3 col-form-label">{{__('Percentage Request Charge')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->percentage_request_charge}}" name="percentage_request_charge" id="percentage_request_charge" placeholder="{{__('Percentage Request Charge')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="minimum_request_money" class="col-sm-3 col-form-label">{{__('Minimum Request Money')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->minimum_request_money}}" name="minimum_request_money" id="minimum_request_money" placeholder="{{__('Minimum Request Money')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="maximum_request_money" class="col-sm-3 col-form-label">{{__('Maximum Request Money')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->maximum_request_money}}" name="maximum_request_money" id="maximum_request_money" placeholder="{{__('Maximum Request Money')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="money_request_per_day" class="col-sm-3 col-form-label">{{__('Request Limit(Day)')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control allownDecimal" value="{{$charge->money_request_per_day}}" name="money_request_per_day" id="money_request_per_day" placeholder="{{__('5 Request')}}" required>
                        </div>
                    </div>

                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</div>


@endsection
