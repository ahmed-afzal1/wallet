<!DOCTYPE html>
<html lang="{{str_replace('_','-',app()->getLocale())}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="geniusocean">
    <meta name="author" content="geniusocean">
    <link href="{{asset('assets/backend/images/logo/'.$gs->favicon)}}" rel="icon">
    <title>{{$gs->title}}</title>
    <link href="{{asset('assets/backend/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/backend/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/backend//vendor/fancybox/fancybox.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/tagify.css') }}" rel="stylesheet">
    <link href="{{asset('assets/backend/css/admin.css')}}" rel="stylesheet">
    <link href="{{asset('assets/backend/css/responsive.css')}}" rel="stylesheet">
    <link href="{{asset('assets/backend/css/custom.css')}}" rel="stylesheet">
    @yield('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                </div>
        
              </a>
        
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                  <i class="fas fa-fw fa-tachometer-alt"></i>
                  <span>{{ __('Dashboard') }}</span></a>
              </li>

              <hr class="sidebar-divider">

              <div class="sidebar-heading">
                {{__("Features")}}
              </div>


        
              @if(Auth::guard('admin')->user()->IsSuper())
                @include('includes.admin.roles.super')
              @else
                @include('includes.admin.roles.normal')
              @endif

              @if(Auth::guard('admin')->user()->IsSuper())
              <p class="version-name p-4 m-2"> {{ __('Version') }}: 3.1</p>
              @endif
        </ul>
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- TopBar -->
                @include('backend.includes.admin.top-navbar')
                <!-- /Topbar -->


                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">

                    @yield('content')

                </div>
                <!---Container Fluid-->
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>{{__("copyright")}} &copy; {{__("2020")}} -  {{date("Y")}} {{__(" developed by")}}
                        <b><a href="http://www.geniusocean.com/" target="_blank">{{__('Genius Ocean')}}</a></b>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    {!! Toastr::message() !!}
    @include('backend.includes.admin.scriptfiles')
    <script>
        var mainurl = "{{url('/')}}";
    </script>

<script>
    @if(Session::has('message'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.success("{{ session('message') }}");
    @endif
  
    @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.error("{{ session('error') }}");
    @endif
  
    @if(Session::has('info'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.info("{{ session('info') }}");
    @endif
  
    @if(Session::has('warning'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.warning("{{ session('warning') }}");
    @endif
  </script>
    @yield('script')
</body>

</html>