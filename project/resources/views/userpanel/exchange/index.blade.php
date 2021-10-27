@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
            {{__("Exchange Currency")}}
        </h4>
        <form action="{{route('user.moneyexchange')}}" method="POST">
            @csrf
            @include('flashmessage')
            <input type="hidden" id="page" value="exchange">
            <div class="row">

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="currency"  class="bmd-label-floating" >{{__("From Wallet")}} <span id="top-balance">( <b>{{__('Available')}} : <span class="show-walltet-balance text-success">{{round($default_wallet->balance,2)}}</span> <span class="show-wallet">{{$default_wallet->currency->code}}</span></b>)</span></label>
                        <select class="form-control" name="from_currency_id" data-style="btn btn-link" id="from_wallet" required>
                        <option selected disabled>{{__("Select Wallet")}}</option>
                            @forelse ($from_wallets as $from_wallet)
                                <option value="{{$from_wallet->currency_id}}" {{$from_wallet->currency_id == $user->default_currency ? 'selected':''}} data-balance="{{round($from_wallet->balance,2)}}" data-currency_code="{{$from_wallet->currency->code}}">{{__($from_wallet->currency->code)}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="currency"  class="bmd-label-floating" >{{__("To Wallet")}} <span id="bottom-balance" style="display: none">( <b>{{__('Available')}} : <span class="toWalletBalance text-success"></span> <span class="to-wallet"></span></b>)</span></label>
                        <select class="form-control" name="currency_id" data-style="btn btn-link" id="to_wallet" required>
                        <option selected disabled>{{__("Select Wallet")}}</option>
                            @forelse ($to_wallets as $to_wallet)
                                <option value="{{$to_wallet->currency_id}}" data-balanceToWallet="{{$to_wallet->balance}}" data-currencyCodeToWallet="{{$to_wallet->currency->code}}">{{__($to_wallet->currency->code)}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group  mb-0">
                        <label for="amount" class="bmd-label-floating">{{__("Enter Amount")}} <small id="balanceout"></small> <span class="text-danger">*</span></label>
                        <input type="number" class="form-control allownDecimal amountcheck" name="amount" id="transamount" placeholder="Enter Amount" autocomplete="off" value="" required>
                    </div>
                    <p id="inputamount" class="text-danger"></p>
                </div>



                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-round sendbtn">{{__('Exchange')}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script src="{{asset('assets/user/js/transaction.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#from_wallet').change(function(){
                $('.show-walltet-balance').text($(this).find(':selected').attr('data-balance'));
                $('.show-wallet').text($(this).find(':selected').attr('data-currency_code'));
                var x = $(this).val();

                var url = mainurl+'/account/from-wallet/'+x;
                $.ajax({
                    type        : 'GET',
                    url         : url,
                    contentType : false,
                    processData : false,
                    data        : {},
                    success     : function(data){
                                    $("#to_wallet").html(data);
                    }
                });
                
            });

            $('#to_wallet').change(function(){
                $('#bottom-balance').css('display','inline-block');
                $('.toWalletBalance').text($(this).find(':selected').attr('data-balanceToWallet'));
                $('.to-wallet').text($(this).find(':selected').attr('data-currencyCodeToWallet'));
            });
        })
    </script>
@endsection


