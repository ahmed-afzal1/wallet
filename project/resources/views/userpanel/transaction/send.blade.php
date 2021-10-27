@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
             {{__("Send Money")}}
        </h4>
        <form action="{{route('user-send-money')}}" method="POST">
            @csrf
            @include('flashmessage')
            <input type="hidden" id="page" value="send">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-block px-2 py-3">
                        <div class="alert alert-danger d-none" id="maxtranslimit">{{__("You cross your max transaction limit")}}.</div>
                    </div>

                    <div class="form-group usersearchbox" >
                      <label for="searchuser" class="bmd-label-floating">{{__("Receiver Identity")}}</label>
                      <div id="useridentity"> <strong>{{__("Name:")}}</strong> <span>{{__("useridentity")}}</span> </div>
                        <div class="input-group flex-nowrap">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="userimage" data-src="{{asset('/assets/user/images/user.jpg')}}"><img class="rounded-circle" src="{{asset('assets/user/images/user.jpg')}}" alt="..."></span>
                            </div>
                            <input type="email" class="form-control" name="receiver" required autocomplete="off" id="searchuser" placeholder="{{__("Enter Email")}}" data-href="{{route('user-search')}}" data-exist="{{route('user-exist')}}">
                            <ul id="searchuserdata" class="rounded card">
                            </ul>
                        </div>
                        <p id="usernotfound" class="d-none"></p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="currency" class="bmd-label-floating" >{{__("Select your Currency")}}<span class="text-danger">*</span></label>
                        <select class="form-control selectpicker sendcurrency" name="currency_id" required data-style="btn btn-link" id="currencycode">
                            <option disabled selected>-- {{__(" Select Currency")}} --</option>
                            
                            @foreach ($currency as $item)
                                @php
                                    $currencyitem = App\Models\Currency::where('id',$item->currency_id)->first();
                                @endphp
                                <option data-minvalue="{{$currencyitem->minimum_transaction_amount ? $currencyitem->minimum_transaction_amount : 0}}" data-maxvalue="{{$currencyitem->maximum_transaction_amount ? $currencyitem->maximum_transaction_amount : 0}}" data-fixCharge="{{$currencyitem->fixed_transaction_charge ? $currencyitem->fixed_transaction_charge : 0}}" data-percentCharge="{{$currencyitem->percentage_transaction_charge ? $currencyitem->percentage_transaction_charge : 0}}" data-balance="{{$item->balance}}" value="{{$item->currency_id}}" data-currency="{{$item->currency_id}}" {{($item->currency_id == $userinfo->default_currency)?'selected':'' }}>{{$item->currency->code}} ({{round($item->balance,2)}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group  mb-0">
                        <label for="amount" class="bmd-label-floating">{{__("Enter Amount")}} <small id="balanceout"></small> <span class="text-danger">*</span></label>
                        <input type="number" class="form-control allownDecimal amountcheck" name="amount" id="transamount" placeholder="Enter Amount" autocomplete="off" min="1" value="" required>
                    </div>
                    <p id="inputamount" class="text-danger"></p>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">{{__("Reference")}} ({{__("Optional")}})</label>
                            <textarea  class="form-control textarea" id="exampleInput1" name="reference" placeholder="{{__("Reference")}}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mr-sm-2 d-inline-block">
                            <input type="checkbox" name="paycost" class="custom-control-input paycost" id="customControlAutosizing" value="1">
                            <label class="custom-control-label" for="customControlAutosizing">{{__("I will pay the cost")}}</label>
                        </div>
                        <div id="totalbalance" class="float-right">
                            {{__('Total Amount')}}:
                            <strong>0.00</strong>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="transaction_cost" id="totalTransactionCost" value="">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-round sendbtn">{{__('Send Money')}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script src="{{asset('assets/user/js/transaction.js')}}"></script>
@endsection


