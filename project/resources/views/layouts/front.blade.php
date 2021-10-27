<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="keywords" content="">
	<meta name="author" content="GeniusOcean">
	<title>{{$gs->title}}</title>


	<!-- favicon -->
	<link rel="icon"  type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}"/>
	<!-- bootstrap -->
	<link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
	<!-- Plugin css -->
	<link rel="stylesheet" href="{{ asset('assets/front/css/plugin.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/front/css/animate.css') }}">
	<!-- stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{ asset('assets/front/css/responsive.css') }}">


    <!--Updated CSS-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/styles.php?color='.str_replace('#','',$gs->colors)) }}">
	@yield('styles')

</head>

<body>
@php
	$loader = $gs->is_loader;
@endphp
@if($loader == 1)
	<div class="preloader" id="preloader" style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center #FFF;"></div>
@endif

	<!--Main-Menu Area Start-->
	<div class="mainmenu-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<nav class="navbar navbar-expand-lg navbar-light">
						<a class="navbar-brand" href="{{ route('home') }}">
							<img src="{{asset('assets/images/'.$gs->logo)}}" alt="">
						</a>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu"
							aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse fixed-height" id="main_menu">
							<ul class="navbar-nav ml-auto">
								<li class="nav-item">
									<a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" href="{{ route('front.blog') }}">{{ __('Blog') }}</a>
								</li>

								@if(DB::table('pages')->count() > 0 )
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('Pages')}}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach(DB::table('pages')->orderBy('id','desc')->get() as $data)

                                        @if ($data->status==1)
                                        <li><a class="dropdown-item" href="{{ route('front.page',$data->slug) }}"> <i class="fa fa-angle-double-right"></i>{{ $data->title }}</a></li>

                                        @endif

                                        @endforeach
                                    </ul>
                                </li>
                                @endif


								<li class="nav-item">
									<a class="nav-link" href="{{ route('front.faq') }}">{{ __('FAQ') }}</a>
								</li>

                                @if($gs->is_contact)
								<li class="nav-item">
									<a class="nav-link" href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a>
								</li>
								@endif


								<li class="nav-item">
									@if (auth()->user())
										<a class="nav-link mybtn1" href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a>
									@else
										<a class="nav-link mybtn1" href="{{ route('user.login') }}">{{ __('Login') }}</a>
									@endif
								</li>

							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!--Main-Menu Area Start-->

@yield('content')

<!-- Footer Area Start -->
<footer class="footer" id="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-4">
				<div class="footer-widget about-widget">
					<div class="footer-logo">
						<a href="{{ route('home') }}">
							<img src="{{asset('assets/images/'.$gs->footer_logo)}}" alt="">
						</a>
					</div>
					<div class="text">
						<p>
							{!! $gs->footer_text !!}
						</p>
					</div>

				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="footer-widget address-widget">
					<h4 class="title">
						{{__('ADDRESS')}}
					</h4>
					<ul class="about-info">
						<li>
							<p>
								<i class="fas fa-globe"></i>
								{{$gs->address}}
							</p>
						</li>

						<li>
							<p>
								<a href="tel:00 000 000 000">
									<i class="fas fa-phone"></i>
									{{$gs->phone}}
								</a>
							</p>
						</li>

						<li>
							<p>
								<i class="far fa-envelope"></i>
								<a href="mailto:">
									{{$gs->email}}
								</a>
							</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
					<div class="footer-widget  footer-newsletter-widget">
						<h4 class="title">
							{{__('NEWSLETTER')}}
						</h4>
						<div class="newsletter-form-area">
							<form id="simpleform" action="{{route('user-subscriber-create')}}" method="POST">
								@csrf

								<input type="email" name="email" placeholder="Your email address...">
								<button id="sub-btn1" type="submit">
									<i class="far fa-paper-plane"></i>
								</button>
							</form>
						</div>
						<div class="social-links">
							<h4 class="title">
									{{__("We're social, connect with us")}}:
							</h4>
							<div class="fotter-social-links">
								<ul>


								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="copy-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
						<div class="content">
							<div class="content">
								<p>{!!$gs->copyright!!}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- Footer Area End -->






<!-- Back to Top Start -->
<div class="bottomtotop">
	<i class="fas fa-chevron-right"></i>
</div>
<!-- Back to Top End -->

<script type="text/javascript">
	'use strict';

  var mainurl = "{{url('/')}}";
  var loader      = {{ $gs->is_loader}};


</script>

	<!-- jquery -->
	<script src="{{ asset('assets/front/js/jquery.js') }}"></script>
	<!-- bootstrap -->
	<script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
	<!-- popper -->
	<script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
	<!-- plugin js-->
	<script src="{{ asset('assets/front/js/plugin.js') }}"></script>
	<!-- notify js-->
	<script src="{{ asset('assets/front/js/notify.js') }}"></script>

    <script src="{{asset('/assets/backend/js/login.js')}}"></script>

	<!-- main -->
	<script src="{{ asset('assets/front/js/main.js') }}"></script>
	<!-- custom -->
	<script src="{{ asset('assets/front/js/custom.js') }}"></script>

	@yield('scripts')

</body>

</html>
