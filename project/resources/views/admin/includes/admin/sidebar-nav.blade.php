
<!--
| Priject's Sidebar Menu Area
-->
<ul class="navbar-nav sidebar sidebar-light accordion " id="accordionSidebar">


    <!--
    | Sidebar Brand Area
    -->
    @php
        $permissions = null;
        if($admininfo->role != null || $admininfo->role != 0){
            $permissions = $admininfo->role->menu;
        }

        $permissionmenu = App\Models\Menu::orderBy('position','asc')->get();
        $tickettotal =  App\Models\Supportticket::get()->count();
    @endphp

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.dashboard')}}">
        <div class="sidebar-brand-icon">
            <img src="{{asset('assets/backend/images/logo/GO-logo.jpg')}}" alt="logo">
        </div>
        <div class="sidebar-brand-text mx-3">{{_('GeniusOcean')}}</div>
    </a>
    <hr class="sidebar-divider my-0">

    @foreach($permissionmenu as $item)

    <!--
    | Dashboard  Menu
    -->
    @if($permissions != null and in_array($item->id,$permissions) and $item->slug == 'dashboard')
    <li class="nav-item @yield('dashboard')">
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{__('Dashboard')}}</span></a>
    </li>




    <!--
    | Sidebar  Divider
    -->
    <hr class="sidebar-divider">





    <!--
    | Sidebar heading or menu gorup headding area
    -->
    <div class="sidebar-heading">
        {{__("Features")}}
    </div>



    <!--
    | Customer  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'customer')
    <li class="nav-item @yield('customer')">
        <a class="nav-link" href="{{route('admin.user.index')}}">
            <i class="fas fa-users"></i>
            <span>{{__('Customer List')}}</span>
        </a>
    </li>




    <!--
    | Support ticket  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'support-ticket')
    <li class="nav-item @yield('supportticket')">
        <a class="nav-link" href="{{route('admin.support.ticket')}}">
            <i class="far fa-fw fa-window-maximize"></i>
            <span>{{__('Support Ticket')}} <span class="badge badge-warning float-right font-weight-light" style="font-size:12px">{{$tickettotal}}</span></span>
        </a>
    </li>


    <!--
    | Support ticket  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'subscribers')
    <li class="nav-item @yield('subscribers')">
        <a class="nav-link" href="{{route('admin.subscribers.list')}}">
            <i class="fas fa-users"></i>
            <span>{{__('Subscribers')}}</span>
        </a>
    </li>




    <!--
    | Staff  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'staff')
    <li class="nav-item @yield('staff')">
        <a class="nav-link" href="{{route('admin.staff.index')}}">
            <i class="fas fa-address-card"></i>
            <span>{{__("Staff")}}</span>
        </a>
    </li>




    <!--
    | Role  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'role')
    <li class="nav-item @yield('role')">
        <a class="nav-link" href="{{route('admin-role-index')}}">
            <i class="fas fa-key"></i>
            <span>{{__("Role")}}</span>
        </a>
    </li>






    <!--
    | Activities  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'activities')
    <li class="nav-item @yield('activities')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#activities" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-list"></i>
            <span>{{__('Activities')}}</span>
        </a>
        <div id="activities" class="collapse @yield('activities-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('admin.loginactivities')}}">{{__('Login Activities')}}</a>
                <a class="collapse-item" href="{{route('admin.activities')}}">{{__('System Activities')}}</a>
            </div>
        </div>
    </li>


    <!--
    | Transanction  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'transaction')
    <li class="nav-item @yield('transaction')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transaction" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-money-check-alt"></i>
            <span>{{__('Transaction')}}</span>
        </a>
        <div id="transaction" class="collapse @yield('transaction-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('admin-all-transaction')}}">{{__('All Transaction')}}</a>
                <a class="collapse-item" href="{{route('admin-withdraw-money')}}">{{__('Manage Withdraw')}} <span class="badge badge-warning float-right py-1">{{$withwrawtotal}}</span></a>
                <a class="collapse-item" href="{{route('admin.deposit.money')}}">{{__('Manage Deposit')}} <span class="badge badge-warning float-right py-1">{{$deposittotal}}</span></a>
            </div>
        </div>
    </li>





    <!--
    | Transanction  Menu
    -->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'bank-account')
    <li class="nav-item @yield('bankaccount')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#bankaccount" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-university"></i>
            <span>{{__('Card & Bank Accounts')}}</span>
        </a>
        <div id="bankaccount" class="collapse @yield('bankaccount-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('admin.all.bankaccounts')}}">{{__('Bank Accounts')}}</a>
                <a class="collapse-item" href="{{route('admin.cards.index')}}">{{__('Cards')}}</a>
            </div>
        </div>
    </li>

    <!-----------Manage Blog--------->
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'manage-blog')
    <li class="nav-item @yield('manage-blog')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#manage-blog" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>{{__('Manage Blog')}}</span>
        </a>
        <div id="manage-blog" class="collapse @yield('manage-blog-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item" href="{{ route('admin-cblog-index') }}">{{__('Categories')}}</a>
                <a class="collapse-item" href="{{ route('admin-blog-index') }}">{{ __('Posts') }}</a>
            </div>
        </div>
    </li>
    <!---------End Manage Blog---------->





    <!--
    | Setings  Menu
    -->

    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'homepage-setting')
    <li class="nav-item @yield('homepage-setting')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#homepage-setting" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-home"></i>
            <span>{{__('Home Page Settings')}}</span>
        </a>
        <div id="homepage-setting" class="collapse @yield('homepage-setting-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item" href="{{ route('admin.slider') }}">{{__('Slider')}}</a>
                <a class="collapse-item" href="{{ route('admin-service-index') }}">{{ __('Service Section') }}</a>
                <a class="collapse-item" href="{{ route('admin-portfolio-index') }}">{{ __('Project Section') }}</a>
                <a class="collapse-item" href="{{ route('admin-review-index') }}">{{ __('Review Section') }}</a>
                <a class="collapse-item" href="{{ route('admin-ps-customize') }}">Home Page Customization</a>
            </div>
        </div>
    </li>

    {{-- Menu Page Setting --}}
    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'menupage-setting')
    <li class="nav-item @yield('menupage-setting')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menupage-setting" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-edit"></i>
            <span>{{__('Menu Page Settings')}}</span>
        </a>
        <div id="menupage-setting" class="collapse @yield('menupage-setting-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('admin-ps-contact')  }}">{{ __('Contact Us Page') }}</a>
                <a class="collapse-item" href="{{ route('admin-page-index') }}">{{ __('Other Pages') }}</a>
                <a class="collapse-item" href="{{ route('admin-faq-index') }}">{{ __('Faq') }}</a>
            </div>
        </div>
    </li>



    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'generalsettings')
    <li class="nav-item @yield('generalsettings')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#generalsettings" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-cogs"></i>
            <span>{{__('General Settings')}}</span>
        </a>
        <div id="generalsettings" class="collapse @yield('general-settings-collapse')" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white collapse-inner ">
                <a class="collapse-item" href="{{route('admin-currency-index')}}">{{__('Currency')}}</a>
                <a class="collapse-item" href="{{route('admin-theme-settings')}}">{{__('Settings')}}</a>
                <a class="collapse-item" href="{{route('admin-localization-settings')}}">{{__('Language')}}</a>
            </div>
        </div>
    </li>


    @elseif($permissions != null and in_array($item->id,$permissions) and $item->slug == 'payment-gateways')
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.payment.index')}}">
            <i class="fa fa-credit-card"></i>
            <span>{{__("Payment Gateways")}}</span>
        </a>
    </li>
    @endif
    @endforeach



    <!--
    | Version Display Area
    -->
    <hr class="sidebar-divider">
    <div class="version" id="version-ruangadmin"></div>
</ul>
