@extends('layouts.user') 

@section('content')
			<div class="transaction-details-area">
				<div class="top-heading">
					<h4 class="title">
						{{__("Transaction Details")}}
					</h4>
						<div class="print-btn d-flex justify-content-between">
							@if(($transactioninfo->transaction_type == 'send') && (auth()->user()->email == $transactioninfo->sender))
								@if ($transactioninfo->refund_status == 0)
									<form action="{{route('user-refund-balance',$transactioninfo->id)}}" method="post" class="mr-2">
										@csrf
										<input type="hidden" name="sender" value="{{$transactioninfo->sender}}">
										<input type="hidden" name="receiver" value="{{$transactioninfo->receiver}}">
										<input type="hidden" name="transaction_currency" value="{{$transactioninfo->transaction_currency}}">
										<input type="hidden" name="transaction_cost" value="{{$transactioninfo->transaction_cost}}">
										<input type="hidden" name="costpay" value="{{$transactioninfo->costpay}}">
										<input type="hidden" name="amount" value="{{$transactioninfo->amount}}">
										<button class="btn btn-sm btn-info" type="submit"><i class="fas fa-undo  mr-2"></i>{{__("Refund")}}</button> 
									</form>
								@endif
							@endif
							<button class="btn btn-sm btn-primary" onclick="window.print()"><i class="fas fa-print mr-2"></i>{{__("Print")}}</button> 
						</div>
				</div>
				<div class="transaction-details-content">
					@include('flashmessage')
					<div class="subscription-payment">
						<div class="left-content">
							<h6><strong>{{__("Money")}} {{$transactioninfo->transaction_type}}</strong></h6>
							<p>{{\Carbon\Carbon::parse($transactioninfo->created_at)->isoFormat('LLLL')}}</p>
							<p>{{__("Transaction ID")}}: {{$transactioninfo->transaction_id}}</p>
							<P>{{__("Payment status")}}: 
								@if ($transactioninfo->status == 'success')
									<span class="btn  btn-outline-success btn-sm ml-2">{{__("Success")}} </span>
								@else
									<span class="btn  btn-outline-success btn-sm ml-2">{{__("Pending")}} </span>
								@endif
							</P>
						</div>
						<div class="right-content">
						<p>{{__("Gross Amount")}}</p>
							<h5>  {{App\Models\Currency::convertwithcurrencyrate($transactioninfo->amount,$transactioninfo->transaction_currency,true)}} {{$transactioninfo->currency->code}}</h5>
							@if ($transactioninfo->refund_status == 1)
							<span class="badge badge-warning">{{__("Refunded")}}</span>
							@endif
						</div>
					</div>

				</div>
				<div class="row px-3 pb-4">
					<div class="col-lg-6">
						<div class="transaction-info">

							@if ($transactioninfo->transaction_type == "withdraw")
								   <p><strong>{{ucwords($transactioninfo->transaction_type)}}  {{__("Method")}}:</strong> &nbsp; {{(isset($value) ? $value->method == 'bankaccount' : '')?'Bank Account':''}} </p>
								  @if ($transactioninfo->method == 'bankaccount')
									  <p><strong>{{__("Account Number")}}: </strong> &nbsp; &nbsp;{{$transactioninfo->bankaccount->account_number}}</p>
									  <p><strong>{{__("Bank Name")}}: </strong> &nbsp; &nbsp;{{ucfirst($transactioninfo->bankaccount->bank_name)}}</p>
								  @else
									  <p><strong>{{__("Stripe Transaction")}}:</strong> txn_1En4xnLYzRkN5IL22RUgo53w</p>
									  <p><strong>{{__("Stripe Charge ID")}}:</strong> ch_1En4xnLYzRkN5IL2pf3f7BHw</p>
								  @endif
							  @elseif ($transactioninfo->transaction_type == 'send')
								  <p><strong>{{__("Receiver Name")}}:</strong> {{ucfirst($transactioninfo->receiveruser->name)}}</p>
								  <p><strong>{{__("Receiver Email")}}:</strong> {{$transactioninfo->receiveruser->email}}</p>
							  @elseif ($transactioninfo->transaction_type == 'deposit')
							  @endif
							<p>
								<strong>{{__("Reference")}}:</strong> &nbsp;  {{$transactioninfo->referece}}
							</p>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="transaction-info2">
							<p><strong> {{ucwords($transactioninfo->transaction_type)}} {{__("Amount")}}: <small> ({{$transactioninfo->currency->code}})</small></strong> <strong> {{App\Models\Currency::convertwithcurrencyrate($transactioninfo->amount,$transactioninfo->transaction_currency,true)}}</strong></p>
							<p><strong>{{__("Charge")}}: <small>({{($transactioninfo->costpay == 'sender' || $transactioninfo->costpay == 'deposit' || $transactioninfo->costpay == 'withdraw')?'You paid':'Receiver paid'}})</small></strong> <strong> {{App\Models\Currency::convertwithcurrencyrate($transactioninfo->transaction_cost,$transactioninfo->transaction_currency,true)}}</strong></p>
							<hr>
							<p><strong>{{__("Total")}}:</strong><strong> {{App\Models\Currency::convertwithcurrencyrate(($transactioninfo->amount+$transactioninfo->transaction_cost),$transactioninfo->transaction_currency,true)}}</strong> </p>
						</div>
					</div>
					<!--<div class="col-lg-6">-->
					<!--	<div class="transaction-info2">-->

					<!--		@if ($transactioninfo->transaction_type == 'received' && $transactioninfo->refund_status != 1)-->
					<!--			<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#staticBackdrop">-->
					<!--				{{__("Refund")}}-->
					<!--			</button>-->
					<!--		@endif-->
					<!--		@if ($transactioninfo->transaction_type == 'send')-->
					<!--			<a href="#" class="btn btn-warning">-->
					<!--				{{__("Disput")}}-->
					<!--			</a>-->
					<!--		@endif-->
					<!--	</div>-->
					<!--</div>-->
				</div>
			</div>
       
            
		
			

<!-- Button trigger modal -->

  
  <!-- Modal -->


@endsection

