@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Transaction')}}
        <a href="{{ route('admin-all-transaction') }}" class="btn back-btn btn-sm btn-info"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
    </h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-all-transaction')}}" class="text-muted">{{__('Transaction')}}</a></li>
    </ol>
</div>


<div class="row mb-3 d-flex justify-content-center">
    <div class="col-12 col-md-10">
        <div class="card">
            <div class="card-header">
                <h4>Transaction Details 
                    @if ($transactiondetails->status == 'pending')
                    <span class="float-right"><button class="btn btn-warning btn-sm completetransaction" id="completetransaction" data-href="{{route('complete-transaction-menual',[$transactiondetails->transaction_id])}}">Complete Transaction</button></span></h4>
                    @endif
                </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover " cellspacing="0" width="100%">
                            @php
                                $transAmount = App\Models\Currency::placesign($transactiondetails->amount,$transactiondetails->transaction_currency);
                                $transFee = App\Models\Currency::convertwithcurrencyrate($transactiondetails->transaction_cost,$transactiondetails->transaction_currency,true);
                                $totalAmount = $transactiondetails->amount + $transactiondetails->transaction_cost;
                                $user = App\User::findOrfail($transactiondetails->user_id);
                            @endphp
                            <tr>
                                <th>{{__("Transaction ID")}}</th>
                                <td>{{$transactiondetails->transaction_id}}</td>
                            </tr>  

                            <tr>
                                <th>{{__("Manual Account Details")}}</th>
                                <td>{{$transactiondetails->manual_number}}</td>
                            </tr>
                            <tr>
                                <th>{{__("Action")}}</th>
                                <td>{{$transactiondetails->reason}}</td>
                            </tr>
                            <tr>
                                <th>{{__("Transaction Amount:")}}</th>
                                <td>{{$transAmount}}</td>
                            </tr>
                            <tr>
                                <th>{{__("Transaction Fee:")}}</th>
                                <td>{{$transFee}}</td>
                            </tr>
                            <tr>
                                <th>{{__("Total Amount:")}}</th>
                                <td>{{ App\Models\Currency::placesign($totalAmount,$transactiondetails->transaction_currency)}}</td>
                            </tr>

                            @if ($transactiondetails->transaction_type == 'deposit' || $transactiondetails->transaction_type == 'withdraw')
                                <tr>
                                    <th>{{__("Account Holder:")}}</th>
                                    <td>{{ucwords($user->name)}} <a href="{{route('admin.userprofile',['user' => $user->id])}}" class="btn btn-sm btn-primary">Profile</a></td>
                                </tr>

                                <tr>
                                    <th>{{__("Account:")}}</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                            @endif
                            @if (!empty($transactiondetails->senderuser->name))
                                <tr>
                                    <th>{{__("Account Holder:")}}</th>
                                    <td>{{ucwords($transactiondetails->senderuser->name)}} <a href="{{route('admin.userprofile',['user' => $transactiondetails->senderuser->id])}}" class="btn btn-sm btn-primary">Profile</a></td>
                                </tr>
                            @endif
                            @if (!empty($transactiondetails->sender))
                                <tr>
                                    <th>{{__("Account:")}}</th>
                                    <td>{{$transactiondetails->sender}}</td>
                                </tr>
                            @endif
                            @if (!empty($transactiondetails->receiveruser->name))
                                <tr>
                                    <th>{{__("Receiver:")}}</th>
                                    <td>{{ucwords($transactiondetails->receiveruser->name)}} <a href="{{route('admin.userprofile',['user' => $transactiondetails->receiveruser->id])}}" class="btn btn-sm btn-primary">Profile</a></td>
                                </tr>
                            @endif
                            @if (!empty($transactiondetails->receiver))
                                <tr>
                                    <th>{{__("Receiver Account:")}}</th>
                                    <td>{{$transactiondetails->receiver}}</td>
                                </tr>
                            @endif

                            @if ($transactiondetails->transaction_type == "deposit")
                                <tr>
                                    <th>{{__("Deposit Method:")}}</th>
                                    <td>{{$transactiondetails->method}}</td>
                                </tr>
                                    @if($transactiondetails->method == "Stripe")
                                        <tr>
                                            <th>{{ $transactiondetails->method }} Charge ID:</th>
                                            <td>{{$transactiondetails->deposit_chargeid}}</td>
                                        </tr>
                                    @endif
                                <tr>
                                    <th>{{ $transactiondetails->method }} Transection ID:</th>
                                    <td>{{$transactiondetails->deposit_transid}}</td>
                                </tr>

                            @elseif($transactiondetails->transaction_type == "withdraw")
                                <tr>
                                    <th>{{$transactiondetails->reason}} Method:</th>
                                    <td>{{$withdrawdetails->account_type}}</td>
                                </tr>

                                @if ($withdrawdetails->account_type != "Bank")
                                    <tr>
                                        <th>{{$withdrawdetails->account_type}} Email:</th>
                                        <td>{{$withdrawdetails->user_email}}</td>
                                    </tr>
                                @endif

                                @if ($withdrawdetails->account_type == "Bank")
                                <tr>
                                    <th>{{__('Bank Account')}}:</th>
                                    <td>{{$withdrawdetails->account_number}}</td>
                                </tr>
                                @endif
                            @endif
                            <tr>
                                <th>{{__("Transaction Date:")}}</th>
                                <td>{{$transactiondetails->created_at}}</td>
                            </tr>
                            <tr>
                                <th>{{__("Status:")}}</th>
                                <td>
                                    @if ($transactiondetails->status == 'pending')
                                    <span class="badge-warning btn btn-sm completetrans">Pending</span>
                                    @else
                                    <span class="badge-success btn btn-sm completetrans">Complete</span>
                                    @endif
                                </td>
                            </tr>
                        
                    </table>
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
    $(document).on('click','.completetransaction',function(){
        $(this).parent().html(`<button class="btn btn-info completetrans" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading....
        </button>`);
        
        $.ajax({
            method:"get",
            url:$(this).attr('data-href'),
            success:function(response)
            {
                let status = parseInt(response);
                if(status == 1){
                    $('.completetrans').parent().html(`<button class="btn btn-success btn-sm">Completed</button>`);
                }
                if(status == 0){
                    $('.completetrans').parent().html(`<button class="btn btn-danger btn-sm">Insufficient balance</button>`);
                }

            }
        });
    });
    })(jQuery);

    </script>

@endsection