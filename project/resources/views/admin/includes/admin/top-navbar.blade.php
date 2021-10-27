<!-- TopBar -->
<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link pr-0" target="_blank" href="{{ route('home') }}">
                <i class="fas fa-globe fa-fw"></i>
            </a>
          </li>

        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge badge-warning badge-counter">{{count($unseenmessage)}}</span>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  {{__("Message Center")}}
                </h6>
                <div class="unseenmessagebox">
                    @forelse ($unseenmessage as $value)

                    <a class="dropdown-item d-flex align-items-center" href="{{route('admin.support.ticket.view',$value->id)}}">
                        <div class="dropdown-list-image mr-3">
                            <img class="rounded-circle" src="{{App\Helpers\Autoload::proPic($value->user->photo,'user')}}" style="max-width: 60px" alt="">
                            <div class="status-indicator bg-success"></div>
                        </div>
                        <div class="font-weight-bold">
                            <div class="text-truncate">
                                @if (strlen($value->subject) > 50)
                                    {{substr($value->subject,0,50)}}...
                                @else
                                   h5 {{$value->subject}}
                                @endif
                            </div>
                            <div class="small text-gray-500">{{$value->user->name}} · {{Carbon\Carbon::parse($value->created_at)->diffForHumans()}}</div>
                        </div>
                    </a>                    
                    @empty
                    <p class="text-center text-muted my-3">{{"No message at"}}</p>
                    @endforelse
                </div>
                <a class="dropdown-item text-center small text-gray-500" href="{{route('admin.support.ticket')}}">{{__("Read More Messages")}}</a>
            </div>
        </li>
        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="{{asset('assets/backend/images/admins/'.$admininfo->photo)}}" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small">{{ $admininfo->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route('admin.myprofile')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> {{__('Profile')}}
                </a>
                <a class="dropdown-item" href="{{route('admin.staff.edit',[$admininfo->id])}}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> {{__('Settings')}}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('admin.logout')}}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{__('Logout')}}
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- Topbar -->