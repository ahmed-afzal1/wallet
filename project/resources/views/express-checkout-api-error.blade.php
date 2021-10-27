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
    <div class="card">
        <div class="card-body">
            <div class="paypal-login-header">
                <h2 class="text-center"> Express Payment </h2>
            </div>
            <br><br>
            <div class="paypal-login-area mx-auto">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="paypal-logo-header  text-center">
                            <img  src="{{asset('assets/backend/images/logo/'.$gs->logo)}}" alt="paypal logo">
                        </div>
                        <h1></h1>
                        @if(Session::has('message'))
                            <h3 class="text-center ">{{ Session::get('message') }}</h3>
                        @endif
                        <h4 class="text-center mt-4">Sorry some problem with your request,<br>Please Try Again Correctly.</h4>
                        <a href="{{Session::get('cancel_return')}}" class="btn btn-block paypal_btn"> Cancel and Return </a>

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


    <!--   Core JS Files   -->
    <script src="{{asset('assets/userpanel/js/core/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/core/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/bootstrap.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/userpanel/js/main.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/backend/js/notify.js')}}"  type="text/javascript"></script>
	<script src="{{asset('/assets/backend/js/login.js')}}"></script>
    <script src="{{asset('assets/userpanel/js/custom.js')}}" type="text/javascript"></script>

    <script>
        var mainurl = '{{url('/')}}';

        $(window).load(function(){
            setTimeout(function(){
                $('.freeloader').fadeOut(1000);
            },1000)
        });

    </script> 

    @yield('script')
</body>
</html>