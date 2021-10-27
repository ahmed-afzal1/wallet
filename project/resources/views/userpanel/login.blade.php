@extends('layouts.front')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-5 mx-auto">
            <div class="card py-4 rounded" >
                <div class="card-body">
                    @include('includes.admin.form-login')
                    <form id="loginform" action="{{route('user.login.submit')}}" method="post">
                        @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__("Email address")}}</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter Your email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">{{__("Password")}}</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Enter Your Password">
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">{{__("Remamber Me.")}}</label>
                            </div>
                            <div class="text-center submit-btn">
                                <button type="submit" class="btn btn-info">{{__("Login")}}</button>
                            </div>
                            <div class="form-group text-center">
                                <p class="m-0"><small>{{__("Don't have an account?")}} <a href="{{route('register.type')}}" class="text-primary">{{__(" Sign Up ")}}</a>  </small></p>
                                <p class="m-0"><small>{{__("Forgot Password")}} <a href="{{route('user.forgot')}}" class="text-primary">{{__("Click Here")}}</a>  </small></p>
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
