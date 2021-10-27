@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Bank Account Details')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.bankaccount.show',[$accountinfo->id])}}" class="text-muted">{{__('admin-bankaccount-show')}}</a></li>
    </ol>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    {{__('Account Details')}}
                    {{-- @if ($accountinfo->is_approved == 'approved')
                    <span class="float-right"><button class="btn btn-success btn-sm">{{__("Approved")}}</button></span></h4>
                    @else
                    <span class="float-right"><button class="btn btn-warning btn-sm approveaccount" data-href="{{route('admin-approve-bankaccount',[$accountinfo->id])}}">{{__("Approve")}}</button></span></h4>
                    @endif --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <div class="offset-sm-2 col-sm-8">
                                <p class="font-italic pb-0  form-control text-dark  border-0 ">
                                    <img src="{{($accountinfo->user->photo != null)? asset($accountinfo->user->photo):asset('assets/backend/images/users/user.png')}}" class="rounded-circle" width="50" height="50" alt="--">
                                    <a href="{{route('admin.userprofile',['user' => $accountinfo->user->id])}}" class="pl-4"> {{ucwords($accountinfo->user->name)}}</a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("User Email")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> <a href="{{route('admin.userprofile',['user' => $accountinfo->user->id])}}"> {{ucwords($accountinfo->user_email)}}</a></p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Account Name")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->account_name)}} &nbsp;
                                    @if ($accountinfo->is_primary == 1)
                                        <small class="badge badge-primary">{{__("Primary")}}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        {{-- <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Account Type")}}:</label>
                            <div class="col-sm-8">
                               <p class="font-italic m-0  pb-0 form-control text-dark border-0"> {{ucwords($accountinfo->account_type)}}</p>
                            </div>
                        </div> --}}
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Account Number")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->account_number)}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Country Name")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->countryinfo->name)}}</p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Bank Name")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->bank_name)}}</p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Account Currency")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->currency->name)}}</p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Swift Code")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->swift_code)}}</p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Routing Number")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($accountinfo->routing_number)}}</p>
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