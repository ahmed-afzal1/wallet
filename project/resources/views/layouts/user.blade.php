<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>{{$gs->title}}</title>

	<link rel="icon"  type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.7/css/all.css">

    <link href="{{asset('assets/user/css/bootstrap.css')}}" rel="stylesheet" />

    @yield('styles')
    <link href="{{asset('assets/user/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/user/css/searchbox.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/user/css/responsive.css')}}" rel="stylesheet" />

    </head>
<body>


    @include('partials.user.header')
    @include('partials.user.aside')

    <div class="col-lg-9">
        @yield('content')
    </div>
    @include('partials.user.footer')


    <!--   Core JS Files   -->
    <script src="{{asset('assets/user/js/core/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/user/js/core/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/user/js/bootstrap.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/user/js/main.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/backend/js/notify.js')}}"  type="text/javascript"></script>
    <script src="{{asset('assets/user/js/custom.js')}}" type="text/javascript"></script>

    <script>
        'use strict';
        var mainurl = '{{url('/')}}';
    </script> 
    @yield('script')
</body>
</html>