@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
             {{__("Request Money")}}
        </h4>
        <form action="{{route('user-request-money-create')}}" method="POST">
            @csrf
            @include('flashmessage')
            <input type="hidden" id="page" value="receive">
            <div class="row">


                <div class="col-lg-12">
                    <div class="form-group usersearchbox" >
                        <div id="useridentity"> <strong>{{__("Name:")}}</strong> <span>{{__("useridentity")}}</span> </div>
                        <div class="input-group flex-nowrap">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="userimage" data-src="{{asset('/assets/user/images/user.jpg')}}"><img class="rounded-circle" src="{{asset('assets/user/images/user.jpg')}}" alt=""></span>
                            </div>
                            <input type="email" class="form-control" name="request_to" id="searchuser" placeholder="{{__("Enter Email")}}" data-href="{{route('user-search')}}" data-exist="{{route('user-exist')}}" required autocomplete="off">
                            <ul id="searchuserdata" class="rounded card">
                            </ul>
                        </div>
                        <p id="usernotfound" class="d-none"></p>
                    </div>
                </div>



                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="currency" class="bmd-label-floating" >{{__("Select your Currency")}}<span class="text-danger">*</span></label>
                        <select class="form-control" name="currency_id" required data-style="btn btn-link" id="requestcurrencycode" data-cburl="{{route('balanceconvert')}}">
                            <option disabled selected>-- {{__("Select Currency")}} --</option>
                
                            @foreach ($currency as $item)
                                @php
                                    $wallet = App\Models\Accountbalance::where('user_id',$userId)->where('currency_id',$item->id)->first();
                                @endphp
                                <option data-minamount="{{$item->minimum_request_money ? $item->minimum_request_money : 0}}" data-maxamount="{{$item->maximum_request_money ? $item->maximum_request_money : 0}}" value="{{$item->id}}" {{($item->id == $userinfo->default_currency)?'selected':'' }}>{{$item->code}} ({{$wallet ? round($wallet->balance,2) : 0}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                
                <div class="col-lg-6">
                    <div class="form-group  mb-0">
                        <label for="amount" class="bmd-label-floating">{{__("Enter Amount")}} <small id="balanceout" class="text-danger"></small> <span class="text-danger">*</span></label>
                        <input type="number" class="form-control allownDecimal" value="0" autocomplete="off" required name="amount" step="any" id="reqtransamount" placeholder="{{__('Enter Your Request Amount')}}">
                    </div>
                    <small class="m-0"><strong>{{__("Transaction Cost")}}:</strong> <span id="transcost">0.00</span></small>
                    <input type="hidden" value="0.00" name="transaction_cost" class="transcost" id="trans_cost_route" data-url="{{route('user-transcost')}}">
                </div>


                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInput1" class="bmd-label-floating">{{__("Reference")}} ({{__("Optional")}})</label>
                        <textarea class="form-control textarea" name="referance" id="exampleInput1" placeholder="{{__("Reference")}}"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-round  request-btn">{{__("Request Money")}}</button>
                </div>
            </div>
        </form>
    </div>



@endsection
@section('script')
<script src="{{asset('assets/user/js/transaction.js')}}"></script>
@endsection

