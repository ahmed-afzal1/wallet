@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
             {{__("Request Details")}}
             @php
                 if($requestinfo->status == 'pending'){
                    $pendingstatus = '';
                    $completestatus = 'd-none';
                }else{
                    $completestatus = '';
                    $pendingstatus = 'd-none';
                 }
             @endphp
             <button class="btn btn-warning float-right   {{$pendingstatus}} mt-0 btn-sm pending">{{__("Pending")}}</button>
             <button class="btn btn-success  {{$completestatus}}  float-right mt-0 btn-sm completed">{{__("Completed")}}</button>
        </h4>
        @include('flashmessage')
        <form id="requestformdata" action="{{route('user-money-request-accept',['requestid'=>$requestinfo->request_id])}}" method="post">
        @csrf
        <div class="row pt-5">
            
            <div class="col-sm-6">
                <div class="media">
                    <img width="50" height="50" class="rounded-circle" src="{{asset($requestinfo->sender->photo)}}" alt="sender">
                    <div class="media-body pl-3">
                        <small>{{__("Sender")}}</small>
                        <h5 class="mt-0">{{$requestinfo->sender->name}}</h5>
                        <small class="d-block">{{$requestinfo->request_to}}</small>
                        <small  class="d-block text-warning">{{Carbon\Carbon::parse($requestinfo->created_at)->diffForHumans()}}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-0">
                    <h6 class="mt-0 font-weight-normal">{{__("Amount")}}</h6>
                    <strong class="d-block font-weight-light">{{App\Models\Currency::convertwithcurrencyrate(round($requestinfo->amount,2),$requestinfo->currency_id,true)}}</strong>
                    <h6 class="mt-2 font-weight-normal">{{__('Transaction cost')}}</h6>
                    <strong class="font-weight-light">{{App\Models\Currency::convertwithcurrencyrate(round($requestinfo->transaction_cost,2),$requestinfo->currency_id,true)}}</strong>
                    @if ($requestinfo->costpaid_by != null && $requestinfo->costpaid_by == $requestinfo->request_from)
                        <small class="text-primary">( {{__("You paid the cost")}})</small>
                    @elseif($requestinfo->costpaid_by != null && $requestinfo->costpaid_by == $requestinfo->request_to)
                        <small class="text-primary">( {{__("Sender paid the cost")}} )</small>
                    @endif
                </div>
                @if ($requestinfo->status == 'pending')
                <div class="pending">
                    <div class="custom-control custom-checkbox mr-sm-2 d-inline-block mt-2">
                        <input type="checkbox" name="paycost" class="custom-control-input paycost" id="customControlAutosizing">
                        {{-- <label class="custom-control-label  font-weight-light text-primary" for="customControlAutosizing">{{__("I will pay the cost.")}}</label> --}}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-12">
            <button type="submit" class="btn btn-primary btn-round {{$pendingstatus}}">{{__('Accept Request')}}</button>
            </div>
        </div>
    </form>
    </div>

@endsection

