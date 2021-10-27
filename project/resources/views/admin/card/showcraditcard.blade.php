@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Card Details')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.cradit.show',[$cardinfo->id])}}" class="text-muted">{{__('Card Details')}}</a></li>
    </ol>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    {{__('Account Details')}}
                    {{-- @if ($cardinfo->is_approved == 'approved')
                    <span class="float-right"><button class="btn btn-success btn-sm">{{__("Approved")}}</button></span></h4>
                    @else
                    <span class="float-right"><button class="btn btn-warning btn-sm approveaccount" data-href="{{route('admin-approve-craditcard',[$cardinfo->id])}}">{{__("Approve")}}</button></span></h4>
                    @endif --}}
                </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <div class="offset-sm-2 col-sm-8">
                                <p class="font-italic pb-0  form-control text-dark  border-0 ">
                                    <img src="{{($cardinfo->user->photo != null)? asset($cardinfo->user->photo):asset('assets/backend/images/users/user.png')}}" class="rounded-circle" width="50" height="50" alt="--">
                                    <a href="{{route('admin.userprofile',['user' => $cardinfo->user->id])}}" class="pl-4"> </a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("User Email")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> <a href="{{route('admin.userprofile',['user' => $cardinfo->user->id])}}"> {{$cardinfo->user_email}}</a></p>
                            </div>
                        </div>
     
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Card owner Name")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($cardinfo->card_owner_name)}} 
                                </p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Card Number")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($cardinfo->card_number)}} &nbsp;
                                    @if ($cardinfo->is_primary == 1)
                                        <small class="badge badge-primary">{{__("Primary")}}</small>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Card CVC")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{ucwords($cardinfo->card_cvc)}}</p>
                            </div>
                        </div>

                        <div class="form-group row  m-0">
                            <label for="staticEmail" class="col-sm-4 col-form-label text-right font-weight-bold">{{__("Card expaired")}}:</label>
                            <div class="col-sm-8">
                                <p class="font-italic m-0 pb-0  form-control text-dark  border-0"> {{$cardinfo->month}}/{{$cardinfo->year}}</p>
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