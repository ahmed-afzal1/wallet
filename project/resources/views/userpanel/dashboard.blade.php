@extends('layouts.user')

@section('content')

			<div class="row">
				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-danger btn-lg btn-rounded">
								<i class="fas fa-dollar-sign"></i>
							</span>
							<h6 class="mt-4"><strong>{{__("Send Money")}}</strong></h6>
							@if ($recentsend > 0)
								<span>{{$recentsend}} {{__("recent transfers")}} </span>
							@else
								<span>{{__("No recent transfers")}}</span>
							@endif
						</div>
					</div>
				</div>

				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-success btn-lg btn-rounded"><i class="fas fa-database"></i></span>
							<h6 class="mt-4"><strong>{{__("Withdraw Money")}}</strong></h6>
							@if ($recentwithdraw > 0)
								<span>{{$recentwithdraw}} {{__("recent withdraw")}}</span>
							@else
								<span>{{__("No recent withdraw")}}</span>
							@endif
						</div>
					</div>
				</div>

				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-warning btn-lg btn-rounded"><i class="fas fa-bell"></i></span>
							<h6 class="mt-4"><strong>{{__("Ticket Center")}}</strong></h6>
							<span> 
								@if ($supportticket > 0)
									{{$supportticket}}
								@else
									{{__("No")}}
								@endif
								{{__("pending ticket")}}	
							</span> 
							<small><a class="text-warning" href="{{route('user-support-ticket-create')}}">{{__("Create Ticket")}}</a></small>
						</div>
					</div>
				</div>
			</div>

          <div class="profile-componet-area">
            <div class="heading-area">
              <h3 class="title">
                {{__("Profile Completeness")}}
              </h3>
              <span class="persentence">{{$profilecomplete}}%</span>
            </div>

            <div class="content">
              <div class="row">
                <div class="col-lg-3 col-md-6">
                  <div class="single-componet">
                    <div class="icon">
                      <img src="{{asset('assets/userpanel/img/phone.png')}}" alt="">
                    </div>
                    <i class="material-icons">
                      check_circle
                      </i>
                    <p>{{__("Mobile Added")}}</p>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <div class="single-componet">
                    <div class="icon">
                      <img src="{{asset('assets/userpanel/img/envelop.png')}}" alt="">
                    </div>
                    <i class="material-icons">
                      check_circle
                      </i>
                    <p>{{__("Email Added")}}</p>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <a href="{{route('user-bankaccount-create')}}" class="single-componet add">
                    <div class="icon">
                      <img src="{{asset('assets/userpanel/img/card.png')}}" alt="">
                    </div>
                    <i class="material-icons">
						@if (auth()->user()->cards->count()>0)
							check_circle
						@else 
							radio_button_unchecked
						@endif
                      </i>
                    <p>{{__("Add Card")}}</p>
                  </a>
                </div>
				
                <div class="col-lg-3 col-md-6">
                  <a href="{{route('user-bankaccount-create')}}" class="single-componet add">
                    <div class="icon">
						<img src="{{asset('assets/userpanel/img/bank.png')}}" alt="">
                    </div>
                    <i class="material-icons">
						@if (auth()->user()->bankAccounts->count()>0)
							check_circle
						@else 
							radio_button_unchecked
						@endif
                      </i>
                    <p>{{__("Add Bank Acc")}}</p>
                    </a>
                </div>
              </div>
            </div>
		  </div>
		  
          <div class="transaction-area mt-30">
            <div class="heading-area">
              <h3 class="title">
                {{__("Recent Transactions")}}
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
									@if ($value->method != null && $value->method == 'bankaccount')
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
                                            <p><strong>{{__("Receiver Email")}}:</strong> {{$value->receiver}}</p>
										@elseif ($value->transaction_type == 'deposit')
											{{-- <p><strong>{{__("Card Number")}}:</strong> {{ucfirst($value->craditcard->card_number)}}</p> --}}
										@endif

									  <p><strong>{{__("Date")}}:</strong> {{\Carbon\Carbon::parse($value->created_at)->isoFormat('LLLL')}}</p>
									  <hr>
									  <p><strong>{{__("Transaction")}} #:</strong> {{$value->transaction_id}} </p>
									  <strong>{{__("Reference")}}:</strong> &nbsp;  {{$value->referece}}
									  </p>
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
									</div>
							  </div>
						  </div>
					  </div>
					</div>
				  </div>
  
				@empty
					<h5 class="text-danger text-center pb-3 pt-5 font-italic">{{__("No transaction record avialable")}}.</h5>
				@endforelse
			  </div>

			  	@if (count($transactioninfo) > 0) 
			  	<div class="view-more">
				  <a href="{{route('user-alltransaction')}}" class="mybtn1">{{__("View all Transaction")}}</a>
				</div>
				@endif
            </div>
          </div>
        
@endsection

