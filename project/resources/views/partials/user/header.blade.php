    <!-- Main Menu Area Start -->
    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">                 
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="{{route('home')}}">
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
									<a class="nav-link" href="{{ route('home') }}">{{ __('FAQ') }}</a>
								</li>

                                @if($gs->is_contact)
								<li class="nav-item">
									<a class="nav-link" href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a>
								</li>
								 @endif

                            </ul>
                        </div>
                        @if($userinfo)
                            <div class="user-profile">
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="rounded-circle" src="{{($userinfo->photo == null)?asset('assets/images/user.jpg'):asset($userinfo->photo)}}" alt="user">
                                    </a>
                                    <ul class="dropdown-menu" >
                                        <li><a class="dropdown-item" href="{{route('user-dashboard')}}"><i class="far fa-user-circle"></i> {{__("Dashboard")}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('user-profile')}}"><i class="far fa-user-circle"></i> {{__("Profile")}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('user.logout')}}"><i class="fas fa-sign-out-alt"></i> {{__("Logout")}}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu Area End -->
 