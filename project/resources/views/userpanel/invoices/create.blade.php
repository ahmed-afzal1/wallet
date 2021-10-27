@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
            {{__("Create Invoice")}}
        </h4>
        <form action="{{route('user.account.invoice.submit')}}" method="POST">
            @csrf
            @include('flashmessage')

            <input type="hidden" id="page" value="invoice">

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="{{__('Enter Full Name')}}" required>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{__('Enter Email')}}" required>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12 mb-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="number" name="amount" value="{{ old('amount') }}" class="form-control" placeholder="{{__('Total Amount')}}" required>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" name="currency_id" data-style="btn btn-link" required>
                                <option selected disabled>{{__("Select Wallet")}}</option>
                                @forelse ($currencies as $currency)
                                    <option value="{{$currency->id}}">{{__($currency->code)}}</option>
                                @empty
                                    
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="">
                            <textarea name="reference" class="form-control textarea" placeholder="{{__('Enter Details')}}" required>{{ old('reference') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-round sendbtn">{{__('Create Invoice')}}</button>
                </div>
            </div>

            <div class="row">




            </div>
        </form>
    </div>

@endsection

@section('script')

@endsection


