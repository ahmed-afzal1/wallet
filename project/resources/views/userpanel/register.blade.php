@extends('layouts.front')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card py-4 rounded" >
                <div class="card-body">
                    @include('includes.admin.form-login')
                    <form id="registerform" action="{{route('user.register.submit',request()->type)}}" method="post">
                    @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__("Your Name")}}</label>
                            <input type="text" class="form-control " id="exampleInputEmail1" required name="name" placeholder="{{__('Enter Your Name')}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__("Email address")}}</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" required name="email" placeholder="{{__('Enter Your Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__("Country")}}</label>
                            <select name="country"  class="form-control" required>
                                <option value="">-- {{__('Select Country')}} --</option>
                                @foreach (App\Models\Countries::get() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">{{__("Phone")}}</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" required name="phone" placeholder="{{__('Enter Your phone')}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">{{__("Password")}}</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" required name="password" placeholder="{{__('Enter Your Password')}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">{{__("Confirm Password")}}</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" required name="password_confirmation" placeholder="{{__('Enter Confirmation Password')}}">
                        </div>
                        <div class="text-center submit-btn">
                            <button type="submit" class="btn btn-info">{{__("Register")}}</button>
                        </div>
                        <div class="form-group text-center">
                            <p class="m-0"><small>   {{__("Already have an account?")}} <a href="{{route('user.login')}}" class="text-primary"> {{__("Login")}} </a></small></p>
                            {{-- <p class="m-0"> <a href="" class="text-primary">Forgot your password?</a></p> --}}
                        </div>
						<input class="processdata" type="hidden" value="{{ ('Processing...') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection