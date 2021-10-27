@extends('layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Deposit Details')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.deposit.details',[$deposit->id])}}" class="text-muted">{{__('Deposit Details')}}</a></li>
    </ol>
</div>


@php
   $user = App\User::findOrFail($deposit->user_id); 
@endphp

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    {{__('Deposit Details')}}
                    @if ($deposit->deposit_status == 'complete')
                    <span class="float-right"><button class="btn btn-success btn-sm">{{__("Completed")}}</button></span></h4>
                    @else
                    <span class="float-right"><button class="btn btn-warning btn-sm approveaccount" data-href="{{route('admin.complete.deposit.menual',[$deposit->id])}}">{{__("Approve Deposit")}}</button></span></h4>
                    @endif
                </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <div class="offset-sm-2 col-sm-8">
                                <p class="font-italic pb-0  form-control text-dark  border-0 ">
                                    <img src="{{($deposit->user->photo != null)? asset($deposit->user->photo):asset('assets/backend/images/users/user.png')}}" class="rounded-circle" width="50" height="50" alt="--">
                                    <a href="{{route('admin.userprofile',['user' => $deposit->user->id])}}" class="pl-4"> </a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("User Email")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> <a href="{{route('admin.userprofile',['user' => $deposit->user->id])}}"> {{ucwords($deposit->user_email)}}</a></p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Customer name")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($user->name)}}</p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Customer email")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($user->email)}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Amount")}}:</label>
                            <div class="col-sm-8">
                                {{App\Models\Currency::convertwithcurrencyrate($deposit->amount,$deposit->currency,true)}}
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Deposit Method")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($deposit->method)}}</p>
                            </div>
                        </div>
                        @if ($deposit->craditcard_id != null)
                        {{-- <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Card Number")}}:</label>
                            <div class="col-sm-8">
                            <p>
                                @php
                                $length = strlen($deposit->craditcard->card_number)-2;
                                for ($i=0; $i <= $length; $i++) { 
                                   echo 'x';
                                }
                                @endphp
                             - {{substr($deposit->craditcard->card_number,-2)}}
                            </p>
                            </div>
                        </div> --}}
                        @endif

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Transaction Id")}}:</label>
                            <div class="col-sm-8">
                               <p> {{$deposit->transaction_id}}</p>
                            </div>
                        </div>

                        @if ($deposit->craditcard_id)
                            <div class="form-group row  m-0">
                                <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Creditcard Id")}}:</label>
                                <div class="col-sm-8">
                                <p> {{$deposit->craditcard_id}}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__("Method Transaction Id")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    @if ($deposit->method_transaction_id)
                                        {{$deposit->method_transaction_id}}
                                    @else
                                        {{__("None")}}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label font-weight-bold">{{__(" Transaction status")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    <small class="btn btn-sm btn-light">{{ucwords($deposit->deposit_status)}}</small>
                                </p>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script >
    (function($){
        "use strict";
    $(document).on('click','.approveaccount',function(){
        $(this).parent().html(`<button class="btn btn-info loadingbtn" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading....
        </button>`);
        
        $.ajax({
            method:"get",
            url:$(this).attr('data-href'),
            success:function(response)
            {
                
                $('.loadingbtn').parent().html(`<button class="btn btn-success btn-sm">Approved</button>`);
            }
        });
    });
    })(jQuery);

    </script>

@endsection