<!DOCTYPE html>
<html>
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
<!-- Starting of paypal login area -->
<div class="section-padding paypal-login-area-wrapper">
    <div class="freeloader"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="paypal-login-header">
                    
                    <img class="pull-left" style="max-width: 100%;height: 30px;" src="{{asset('assets/backend/images/logo/'.$gs->logo)}}" alt="{{$gs->title}}">
                    
                    <h2 style="text-align:right;"> Online Payment </h2>
                </div>
                <hr>
                
                <div id="linkPayment">
                    
                    <div class="paypal-login-area">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3 class="text-center"><i class='fa fa-times-circle-o fa-2x'></i><br>Sorry, Your Payment Link is Not Valid.<br>Please Try Again with corrct Link.</h3>
                                <hr>  
                                <div class="text-center">
                                    <a href="{{url('/')}}" class="btn linkPay_btn paypal_btn" style="max-width: 150px; padding:8px;"><strong>Visit Website</strong></a>

                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Ending of paypal login area -->


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
</body>
</html>
