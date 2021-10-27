@extends('layouts.user')

@section('content')

		  
          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
				{{_("Recent Transactions")}}
              </h3>
            </div>
            <div class="content">
              <div class="heading-menu">
                  <div class="row">
                      <div class="col-md-2 col-2">
                        <h6>
                            {{__("Date")}}
                        </h6>
                      </div>
                      <div class="col-md-3 col-4">
                          <h6>
                              {{__("Action")}}
                          </h6>
                      </div>
                      <div class="col-md-3 col-3">
                          <h6>
                              {{__("Amount")}} 
                          </h6>
                      </div>
                      <div class="col-md-2 col-3">
                          <h6>
                              {{__("Status")}}
                          </h6>
                      </div>
                    </div>
			  </div>
			  
			  
              <div class="accordion" id="accordionTransactions">

				@forelse ($transactioninfo as $key => $value)
				<div class="single-accordion">
					<div class="accordion-header {{($key == 0)?'collapsed':''}}" data-toggle="collapse" data-target="#collapseOne{{$key}}" aria-expanded="{{($key == 0)?'true':'false'}}" aria-controls="collapseOne{{$key}}">
						<div class="row"> 
						  <div class="col-md-2 col-2">
							<div class="box">
							  <h6>{{\Carbon\Carbon::parse($value->created_at)->format('d M Y')}}</h6>
							</div>
						  </div>
						  <div class="col-md-3 col-4">
							<div class="box">
							  	<h5>
									@if($value->method != null && $value->method == 'bankaccount')
										{{ucwords($value->bankaccount->bank_name)}}
									@endif
								</h5>
								<p>
									{{__("Money")}} {{ucwords($value->transaction_type)}}
								</p>
							</div>
						  </div>
						  <div class="col-md-3 col-3 d-flex">
							<div class="box">
							  <p class="amount">
								  {{App\Models\Currency::convertwithcurrencyrate($value->amount,$value->transaction_currency,true)}} ({{$value->currency->code}})
							  </p>
							</div>
						  </div>
						  <div class="col-md-2 col-3 d-flex">
							<div class="box">
							  <p>{{ucwords($value->status)}}</p>
							</div>
						  </div>
						  <div class="col-md-2 remove-sm d-flex">
							<div class="box">
								<i class="material-icons plus">
									add
									</i>
								<i class="material-icons minus">
									  remove
									</i>
							</div>
						  </div>
						</div>
					</div>
				
					<div id="collapseOne{{$key}}" class="collapse {{($key == 0)?'show':''}}" data-parent="#accordionTransactions">
					  <div class="accordion-body">
						  <div class="row">
							  <div class="col-lg-6">
								  <div class="transaction-info">
									  	@if ($value->transaction_type == 'withdraw')
									 		<p><strong>{{ucwords($value->transaction_type)}}  {{__("Method")}}:</strong> &nbsp; {{($value->method == 'bankaccount')?'Bank Account':''}} </p>
											@if ($value->method == 'bankaccount')
												<p><strong>{{__("Account Number")}}: </strong> &nbsp; &nbsp;{{$value->bankaccount->account_number}}</p>
												<p><strong>{{__("Bank Name")}}: </strong> &nbsp; &nbsp;{{ucfirst($value->bankaccount->bank_name)}}</p>
											@else
												<p><strong>{{__("Stripe Transaction")}}:</strong> txn_1En4xnLYzRkN5IL22RUgo53w</p>
												<p><strong>{{__("Stripe Charge ID")}}:</strong> ch_1En4xnLYzRkN5IL2pf3f7BHw</p>
											@endif
										@elseif ($value->transaction_type == 'send')
											<p><strong>{{__("Receiver Name")}}:</strong> {{ucfirst($value->receiveruser->name)}}</p>
											<p><strong>{{__("Receiver Email")}}:</strong> {{$value->receiveruser->email}}</p>
										@elseif ($value->transaction_type == 'deposit')
											{{-- <p><strong>{{__("Card Number")}}:</strong> {{ucfirst($value->craditcard->card_number)}}</p> --}}
										@endif

									  <p><strong>{{__("Date")}}:</strong> {{\Carbon\Carbon::parse($value->created_at)->isoFormat('LLLL')}}</p>
									  <hr>
									  <p><strong>{{__("Transaction")}} #:</strong> {{$value->transaction_id}} </p>
									  <p><a class="btn btn-warning btn-sm" href="{{route('user-transaction-details',['tid'=>$value->transaction_id,'type'=>$value->transaction_type])}}">{{__("Details")}} </a></p>
								  </div>
							  </div>
							  <div class="col-lg-6">
								  <div class="transaction-info2">
									  <p><strong> {{ucwords($value->transaction_type)}} {{__("Amount")}}: <small> ({{$value->currency->code}})</small></strong> <strong> {{App\Models\Currency::convertwithcurrencyrate($value->amount,$value->transaction_currency,true)}}</strong></p>
									  <p><strong>{{__("Charge")}}: <small>({{($value->costpay == 'sender' || $value->costpay == 'deposit' || $value->costpay == 'withdraw')?'You paid':'Receiver paid'}})</small></strong> <strong> {{App\Models\Currency::convertwithcurrencyrate($value->transaction_cost,$value->transaction_currency,true)}}</strong></p>
									  <hr>
									  <p><strong>{{__("Total")}}:</strong><strong> {{App\Models\Currency::convertwithcurrencyrate(($value->amount+$value->transaction_cost),$value->transaction_currency,true)}}</strong> </p>
									  <p>
									  <strong>{{__("Reference")}}:</strong> &nbsp;  {{$value->referece}}
									  </p>
									 
								  </div>
							  </div>
						  </div>
					  </div>
					</div>
				  </div>
  
				@empty
					<h5 class="text-danger text-center pb-3 pt-5 font-italic">{{__("No transaction record avialable")}}.</h5>
				@endforelse

				<div class="mt-2">
					{{$transactioninfo->links()}}
				</div>
			  </div>
			  
	
            </div>
          </div>
        
@endsection

