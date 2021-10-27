@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Withdraw')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-withdraw-money')}}" class="text-muted">{{__('Withdraw')}}</a></li>
    </ol>
</div>


@php
    $user = App\User::findOrFail($withdraw->user_id);
@endphp
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    {{__('Withdraw Details')}}
                    @if ($withdraw->withdraw_status == 'complete')
                    <span class="float-right"><button class="btn btn-success btn-sm">{{__("Completed")}}</button></span></h4>
                    @else
                    <span class="float-right"><a href="{{route('admin.withdraw.approve',$withdraw->id)}}" class="btn btn-sm btn-warning">{{__("Approve Withdraw")}}</a></span>
                    @endif
                </h4>
                <hr>
                </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group row  m-0">
                            <div class="offset-sm-2 col-sm-8">
    
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("User Email")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic pb-0  form-control text-dark  border-0 ">
                                    <img src="{{($user->photo != null)? asset($user->photo):asset('assets/backend/images/users/user.png')}}" class="rounded-circle" width="50" height="50" alt="--">
                                    <a href="{{route('admin.userprofile',['user' => $user->id])}}" class="pl-2"> {{ucwords($user->name)}} ({{$withdraw->user_email}})</a>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Amount")}}:</label>
                            <div class="col-sm-8">
                                {{$currencySign}} {{$withdraw->amount}}
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label  text-right font-weight-bold">{{__("Withdraw Cost")}}:</label>
                            <div class="col-sm-8">
                                {{$currencySign}} {{$withdraw->transaction_cost}}
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Transaction Currency")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($withdraw->currency->code)}}</p>
                            </div>
                        </div>

                        {{-- <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Bank Account")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($withdraw->accountNumber->account_number)}}</p>
                            </div>
                        </div> --}}

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Customer name")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($user->name)}}</p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Customer email")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ $user->email}}</p>
                            </div>
                        </div>

                        @if ($withdraw->account_type != "Bank")
                            <div class="form-group row  m-0">
                                <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{$withdraw->account_type}} Email:</label>
                                <div class="col-sm-8">
                                    <p> {{$withdraw->user_email}} </p>
                                </div>
                            </div>
                        @endif

                        @if ($withdraw->account_type == "Bank")
                            <div class="form-group row  m-0">
                                <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__('Bank Account')}}:</label>
                                <div class="col-sm-8">
                                    <p> {{$withdraw->account_number}} </p>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Account Type")}}:</label>
                            <div class="col-sm-8">
                               <p> {{ucwords($withdraw->account_type)}}</p>
                            </div>
                        </div>

                        @if ($withdraw->account_type == 'Bank')
                            <div class="form-group row  m-0">
                                <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Account Number")}}:</label>
                                <div class="col-sm-8">
                                <p> {{ucwords($withdraw->account_number)}}</p>
                                </div>
                            </div>
                        @endif

                                         
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Method Transaction Id")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    @if ($withdraw->transaction_id)
                                        {{$withdraw->transaction_id}}
                                    @else
                                        {{__("None")}}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Created At")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    {{\Carbon\Carbon::parse($withdraw->created_at)->isoFormat('LLLL')}}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__("Expected Delivery")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    {{\Carbon\Carbon::parse($withdraw->expected_delivery_date)->isoFormat('LLLL')}}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right  font-weight-bold">{{__(" Withdraw  status")}}:</label>
                            <div class="col-sm-8">
                                <p>
                                    <small class="btn btn-sm btn-light">{{ucwords($withdraw->withdraw_status)}}</small>
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
