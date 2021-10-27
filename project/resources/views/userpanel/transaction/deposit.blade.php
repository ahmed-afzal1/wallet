@extends('layouts.user') 

@section('content')

<div class="money-form">
    @include('loader')
    <h4 class="title">
        {{__("Deposit")}}
    </h4>
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{!! \Session::get('success') !!}</p>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <p>{!! \Session::get('error') !!}</p>
        </div>
    @endif

    @if (\Session::has('unsuccess'))
    <div class="alert alert-danger">
        <p>{!! \Session::get('unsuccess') !!}</p>
    </div>
@endif

    <form id="deposit_form" action="javascript:;" method="POST" class="">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                  <div class="form-group">
                      <label for="amount" class="bmd-label-floating">{{__('Enter Amount')}}</label>
                      <input type="decimal" class="form-control allownDecimal" name="amount" id="amount" placeholder="Enter Amount" required>
                      @error('amount')
                        <div class="alert alert-danger mt-1">{{__('Amount is required.')}}</div>
                      @enderror
                  </div>
            </div>
            <div class="col-lg-12">
                  <div class="form-group">
                    <label for="currency"  class="bmd-label-floating" >{{__('Select your Currency')}}</label>
                    <select class="form-control" data-style="btn btn-link" id="currency" name="currency">
                        <option selected disabled>{{__("Select Currency")}}</option>
                        @forelse ($currency as $value)
                            <option value="{{$value->id}}">{{ucwords($value->code)}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                    @error('currency')
                        <div class="alert alert-danger mt-1">{{__('currency is required.')}}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-12 transaction_number_area d-none">
                <div class="form-group">
                    <label for="amount" class="bmd-label-floating">{{__('Enter Account Details')}}</label>
                    <input type="text" class="form-control" name="manual_number" id="transaction_number" placeholder="Enter Details">
                </div>
          </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label for="payment-method"  class="bmd-label-floating" >{{__("Select your Payment method")}}</label>
                    <select class="form-control paymentmethod1" name="paymentmethod" data-style="btn btn-link" id="paymentmethod1">
                        <option selected disabled>{{__("Select Method")}}</option>
                        @forelse ($paymentgateways as $value)
                            @if ($value->type == 'manual')
                                <option value="{{$value->keyword}}">{{ucwords($value->title)}}</option>
                            @else 
                                <option value="{{$value->keyword}}">{{ucwords($value->name)}}</option>
                            @endif
                        @empty
                            
                        @endforelse
                    </select>

                    @error('paymentmethod')
                        <div class="alert alert-danger mt-1">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-12 d-none string-show">
                <div class="col-lg-12">
                    <h5>
                        {{__('Select Credit Card:')}}
                    </h5>
                </div>
                <div class="col-lg-12">
                    @foreach ($cards as $key => $card)
                        @php
                            $card_number_length = strlen($card->card_number);
                        @endphp
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="customRadio{{$key+1}}" name="card_id" class="custom-control-input card-check" value="{{$card->id}}">
                        <label class="custom-control-label"  for="customRadio{{$key+1}}"> {{str_repeat("*",$card_number_length)}} {{substr($card->card_number, -4)}}</label>
                    </div>
                    @endforeach
                </div>

                <div class="col-lg-12">
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="customRadio0" name="card_id" class="custom-control-input card-check" value="0">
                        <label class="custom-control-label" for="customRadio0"> {{__('Add New Card')}}</label>
                    </div>
                </div>

                <div class="col-lg-12 d-none card-show">
                <div class="border p-3 mt-3">
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="card_number">{{__('Card Number')}}</label>
                                <input type="text" class="form-control card-elements" name="cardNumber" id="validateCard" placeholder="{{ __('Card Number')}}" autocomplete="off">
                                <span id="errCard" class="text-danger"></span>
                                @error('cardNumber')
                                <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cardCVC">{{__('Cvc')}}</label>
                                <input type="text" class="form-control card-elements" id="validateCVC"  placeholder="{{__('Cvc')}}" name="cardCVC" >
                                <span id="errCVC text-danger"></span>
                                @error('cardCVC')
                                <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{__('Month')}}</label>
                                <input type="text" class="form-control card-elements" id="" placeholder="{{__('Month')}}" name="month">
                                @error('name')
                                <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{__('Year')}}</label>
                                <input type="text" class="form-control card-elements" id="" placeholder="{{__('Year')}}" name="year">
                                @error('year')
                                <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id="cardsave" name="isCheckCardSave" value="1">
                                <label for="cardsave"> {{__('Save for future deposit')}}?</label><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="email" value="{{ App\User::findOrFail(auth()->user()->id)->email }}">
        <input type="hidden" name="ref_id" id="ref_id" value="">
        <input type="hidden" name="sub" id="sub" value="0">
        
        <input type="hidden" name="paystackInfo" id="paystackInfo" value="{{$paystackInformation->key}}">
 

            <div class="col-lg-12 " id="methodinputbox">
            </div>
            <div class="col-lg-12">
                <button type="submit" id="final-btn" class="btn btn-primary">{{__("Deposit Money")}}</button>
            </div>
        </div>
    </form>
</div>
<input type="hidden" id="paypal_route" value="{{route('paypal.payment')}}">
<input type="hidden" id="stripe_route" value="{{route('stripe.submit')}}">
<input type="hidden" id="molly_route" value="{{route('molly.submit')}}">
<input type="hidden" id="paytm_route" value="{{route('paytm.submit')}}">
<input type="hidden" id="paystack_route" value="{{route('paystack.submit')}}">
<input type="hidden" id="blockchain_route" value="{{route('blockchain.submit')}}">
<input type="hidden" id="manual_route" value="{{route('manual.submit')}}">
@endsection

@section('script')
    <script src="https://js.paystack.co/v1/inline.js"></script>
@endsection
