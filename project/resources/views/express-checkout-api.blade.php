<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>{{$gs->title}}</title>
    <!-- favicon -->
	<link rel="icon"  type="image/x-icon" href="{{asset('assets/backend/images/logo/'.$gs->favicon)}}"/>
    <!--     Fonts and icons   -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.7/css/all.css">
    <!-- Material Kit CSS -->
    <link href="{{asset('assets/userpanel/css/bootstrap.css')}}" rel="stylesheet" />

    <!-- Main Style CSS -->
    @yield('styles')
    <link href="{{asset('assets/userpanel/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/userpanel/css/searchbox.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/userpanel/css/responsive.css')}}" rel="stylesheet" />

 </head>
<body>
@if($gs->is_loader == 1)
	<div class="freeloader" id="freeloader" style="background: url({{asset('assets/backend/images/loader/'.$gs->loader)}}) no-repeat scroll center center #FFF;"></div>
@endif

<div class="container">

    <h2 class="text-center"> Express Payment </h2>
<br><br>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="paypal-logo-header text-center">
                        <img src="{{asset('assets/backend/images/logo/'.$gs->logo)}}" alt="paypal logo">
                        <p class="paypal-addToCart pull-right">
                            <i class="fa fa-cart-arrow-down"></i>
                            <span>${{Session::get('amount')}} USD</span>
                        </p>
                    </div>
                    <div id="e-payment">
                        @if(auth()->guest())
                        <form class="form-horizontal paypal-login" id="ApiLogin" method="POST" action="{{ route('api.login') }}">
                            <h4 class="text-center">Log In to {{$gs->title}} </h4>
                            {{ csrf_field() }}
                            <p id="resp" class="text-danger"></p>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="submit" name="paypal_btn" class="btn btn-block paypal_btn" value="Log In">
                                </div>
                            </div>
                        </form>
                        @else
                            <form id="payNow" method="POST" action="{{ route('api.payment') }}">
                                @csrf
                                <p id="resp" class="text-danger"></p>
                                <div class="paypal-pay-area">
                                    <h3>Hi, {{auth()->user()->name}}</h3>
                                    <div class="paypal-ship-area">
                                        <p>You are paying ${{Session::get('amount')}} to {{Session::get('payee')}} account.</p>
                                    </div>
                                </div>
                                <!-- Button -->
                                <div class="form-group">
                                        <button type="submit" onclick="CompletePayNow(this)" class="btn btn-block paypal_btn" id="ConfirmButton"><strong>Confirm Payment</strong></button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>

                <div class="col-lg-6 mt-5">
                    <div class="paypal-login-rightside">
                        <h3 class="text-center">
                            @if(\App\User::where('email',Session::get('payee'))->first()->business_name != null)
                                {{\App\User::where('email',Session::get('payee'))->first()->business_name}}'s Store
                            @else
                                {{Session::get('payee')}}
                            @endif
                        </h3>
                        <br>
                        <div class="paypal-single-div text-center">
                            <div class="pull-left">
                                <strong>Iten Name: </strong>{{Session::get('item_name')}}
                            </div>

                        </div>
                        <div class="paypal-single-div text-center">
                            <div class="pull-left">
                                <strong>Item Code: </strong>{{Session::get('item_number')}}
                            </div>

                        </div>
                        <div class="paypal-single-div text-center">
                            <div class="pull-left">
                                <strong>Total: </strong>${{Session::get('amount')}} USD
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="paypal-login-footer">
        <a href="{!!Session::get('cancel_return')!!}" target="_self"> Cancel and Return</a>
        <ul class="nav float-right">
            <li class="nav-item"><a class="nav-link active" href="">Policies</a></li>
            <li  class="nav-item"><a class="nav-link active" href="">Terms</a></li>
            <li class="nav-item"><a class="nav-link active" href="">Privacy</a></li>
            <li class="nav-item"><a class="nav-link active" href="">Feedback</a></li>
            <li class="nav-item"><a class="nav-link active" href="">© 1999-2017
                    <i class="fa fa-lock"></i></a>
            </li>
        </ul>
    </div>

</div>




{{-- <!-- Starting of paypal login area -->

 --}}

    <!--   Core JS Files   -->
    <script src="{{asset('assets/userpanel/js/core/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/core/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/bootstrap.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/main.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/backend/js/notify.js')}}"  type="text/javascript"></script>
    <script>
        function ChangeUrl(page, url) {
            if (typeof (history.pushState) != "undefined") {
                var obj = { Page: page, Url: url };
                history.pushState(obj, obj.Page, obj.Url);
            } else {
                alert("Browser does not support HTML5.");
            }
        }

        $('#ApiLogin').submit(function (e) {
        $(".freeloader").fadeIn();
        var postData = $(this).serializeArray();
        var formURL = $(this).attr('action');

        $.ajax({
            url: formURL,
            type: 'POST',
            data: postData,
            success: function(response,status,xhr) {
                setTimeout(function(){

                    if (response.status === "Success"){
                        $(".freeloader").fadeOut();
                        $("#e-payment").html(response.data);
                        ChangeUrl('EXpress Payment', '{{url('/')}}/express/web/pay?token={{request()->token}}');
                    }else{
                        $(".freeloader").fadeOut();
                        $("#resp").html(response.message);
                    }

                }, 1000);

            },
            error: function(jqXHR, textStatus, errorThrown) {

            },
            complete: function() {

            }
        });

        e.preventDefault();	//STOP default action
        e.unbind();
    });
    </script>
	<script src="{{asset('/assets/front/js/express.js')}}"></script>
    <script src="{{asset('assets/userpanel/js/custom.js')}}" type="text/javascript"></script>

    <script>
        var mainurl = '{{url('/')}}';
        $(window).load(function(){
            setTimeout(function(){
                $('.freeloader').fadeOut(1000);
            },1000)
        });


    //function CompletePayNow(pay) {
    //$(pay).parents('form:first').submit(function (go) {


    </script>

    @yield('script')
</body>
</html>
