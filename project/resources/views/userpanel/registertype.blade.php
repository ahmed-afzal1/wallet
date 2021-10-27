@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mx-auto">
                <div class="card py-4 rounded" >
                    <div class="card-body">
                        @include('flashmessage')
                        <form action="{{route('user.register.form')}}" method="get">

                            <div class="form-group">
                                <div class="radio">
                                    <input type="radio" class="big" id="radio-1" name="type" value="personal" required>
                                    <label for="radio-1">{{__('Personal')}}</label>
                                    <p>{{__('Shop online or send money. All without sharing your payment info.')}}</p>
                                </div>
                                <br>
                                <div class="radio">
                                    <input type="radio" class="big" id="radio-2" name="type" value="business" required>
                                    <label for="radio-2">{{__('Business')}}</label>
                                    <p>{{__('Shop online or send money. All without sharing your payment info.')}}</p>
                                </div>
                            </div>


                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-5 control-label"></label>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info" >{{__('Next')}}</button>
                                </div>
                            </div>

                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection