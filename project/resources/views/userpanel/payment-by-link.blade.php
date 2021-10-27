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


<!-- Starting of paypal login area -->
<div class="container">
    <div class="row d-plex justify-content-between">
        <div class="col-lg-5  mr-2">
            <div class="paypal-login-header">
                <img class="pull-left" style="max-width: 100%;height: 30px;" src="{{asset('assets/backend/images/logo/'.$gs->logo)}}" alt="{{$gs->title}}">
                                
                <h2 style="text-align:right;"> {{__('Online Payment')}} </h2>
                @include('flashmessage')
            </div>
            <hr>
            
            <div id="linkPayment">
                <div class="text-center new_link">
                    <p><i class="fa fa-bell-o"></i> {{__('You Have a Payment Request!')}}</p>
                </div>
                <div class="paypal-login-area">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            
                            <div class="row payment_link">
                                <div class="col-lg-6">
                                    <h5>From: </h5>
                                    <h5>{{$payData->user->name}}</h5>
                                    <h5>{{$payData->user->email}}</h5>
                                    <h5>Referance: {{str_pad($payData->id, 9, '0', STR_PAD_LEFT)}}</h5>
                                </div>
                                <div class="col-lg-6">
                                    
                                    <h4>Amount:</h4> 
                                    <h6>{{number_format($payData->amount,2)}} {{$currency->code}}</h6>
    
                                        
                                </div>
                                @if($payData->reference != "")
                                    
                                    <div class="col-lg-12">
                                    <hr>
                                        <h6>Payment Details:</h6> 
                                        <p>{{$payData->reference}}</p>
                                        
                                    </div>
                                @endif

                                <div class="col-lg-12">
                                    <hr>
                                        <div class="form-group text-center">
                                            @if (auth()->check())
                                                <form action="{{route('invoice.money.send')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="invoice_id" value="{{$payData->id}}">
                                                    <input type="hidden" name="email" value="{{$payData->user->email}}">
                                                    <input type="hidden" name="currency" value="{{$currency->id}}">
                                                    <input type="hidden" name="amount" value="{{number_format($payData->amount,2)}}">
                                                    <button type="submit" class="btn btn-info linkPay_btn paypal_btn" id="PayNowButton"><strong>{{__('Pay Now')}}</strong></button>
                                                </form>
                                            @else 
                                                <p class="text-danger">{{__('Please Login to Pay')}}</p>
                                            @endif
                                        </div>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        @if (!auth()->check())
            <div class="col-lg-5">
                <div class="card py-4 mt-5 ml-2 rounded" >
                    <div class="card-body">
                        @include('flashmessage')
                        <form id="loginform" action="{{route('invoice.user.login')}}" method="post">
                            @csrf
        
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__("Email address")}}</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter Your email" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">{{__("Password")}}</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Enter Your Password" required>
                                </div>

                                <div class="text-center submit-btn">
                                    <button type="submit" class="btn btn-info">{{__("Login")}}</button>
                                </div>
                                <div class="form-group text-center">
                                    <p class="m-0"><small>{{__("Don't have an account?")}} <a href="{{route('register.type')}}" class="text-primary">{{__(" Sign Up ")}}</a>  </small></p>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        @endif
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