<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transaction" aria-expanded="true"
    aria-controls="collapseTable">
    <i class="fas fa-exchange-alt"></i>
    <span>{{  __('Manage Transaction') }}</span>
  </a>
  <div id="transaction" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="{{route('admin-all-transaction')}}">{{__('All Transaction')}}</a>
      <a class="collapse-item" href="{{route('admin-withdraw-money')}}">{{__('Manage Withdraw')}} <span class="badge badge-warning float-right py-1">{{$withwrawtotal}}</span></a>
      <a class="collapse-item" href="{{route('admin.deposit.money')}}">{{__('Manage Deposit')}} <span class="badge badge-warning float-right py-1">{{$deposittotal}}</span></a>
    </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#blog" aria-expanded="true"
    aria-controls="collapseTable">
    <i class="fas fa-fw fa-newspaper"></i>
    <span>{{  __('Manage Blog') }}</span>
  </a>
  <div id="blog" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="{{ route('admin-cblog-index') }}">{{ __('Categories') }}</a>
      <a class="collapse-item" href="{{ route('admin-blog-index') }}">{{ __('Posts') }}</a>
    </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.support.ticket') }}">
    <i class="far fa-fw fa-window-maximize"></i>
    <span>{{ __('Support Ticket') }}<span class="badge badge-warning float-right font-weight-light" style="font-size:12px">{{$tickettotal}}</span></span></a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#activities" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-snowboarding"></i>
      <span>{{__('Activities')}}</span>
  </a>
  <div id="activities" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{route('admin.loginactivities')}}">{{__('Login Activities')}}</a>
          <a class="collapse-item" href="{{route('admin.activities')}}">{{__('System Activities')}}</a>
      </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#bankaccount" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-university"></i>
      <span>{{__('Card & Bank Accounts')}}</span>
  </a>
  <div id="bankaccount" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{route('admin.all.bankaccounts')}}">{{__('Bank Accounts')}}</a>
          <a class="collapse-item" href="{{route('admin.cards.index')}}">{{__('Cards')}}</a>
      </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#generalsettings" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-cogs"></i>
      <span>{{__('General Settings')}}</span>
  </a>
  <div id="generalsettings" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white collapse-inner ">
          <a class="collapse-item" href="{{route('admin-currency-index')}}">{{__('Currency')}}</a>
          <a class="collapse-item" href="{{route('admin-theme-settings')}}">{{__('Settings')}}</a>
          <a class="collapse-item" href="{{route('admin-localization-settings')}}">{{__('Language')}}</a>
      </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#homepage-setting" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-fw fa-home"></i>
      <span>{{__('Home Page Settings')}}</span>
  </a>
  <div id="homepage-setting" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('admin.slider') }}">{{__('Slider')}}</a>
          <a class="collapse-item" href="{{ route('admin-service-index') }}">{{ __('Service Section') }}</a>
          <a class="collapse-item" href="{{ route('admin-portfolio-index') }}">{{ __('Project Section') }}</a>
          <a class="collapse-item" href="{{ route('admin-review-index') }}">{{ __('Review Section') }}</a>
          <a class="collapse-item" href="{{ route('admin-ps-customize') }}">{{__('Home Page Customization')}}</a>
          <a class="collapse-item" href="{{ route('admin-ps-homecontact') }}">{{ __('Contact Section') }}</a>
      </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menupage-setting" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-fw fa-edit"></i>
      <span>{{__('Menu Page Settings')}}</span>
  </a>
  <div id="menupage-setting" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{route('admin-ps-contact')  }}">{{ __('Contact Us Page') }}</a>
          <a class="collapse-item" href="{{ route('admin-page-index') }}">{{ __('Other Pages') }}</a>
          <a class="collapse-item" href="{{ route('admin-faq-index') }}">{{ __('Faq') }}</a>
      </div>
  </div>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{route('admin.payment.index')}}">
      <i class="fa fa-credit-card"></i>
      <span>{{__("Payment Gateways")}}</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.staff.index') }}">
    <i class="fas fa-fw fa-users"></i>
    <span>{{ __('Manage Staff') }}</span></a>
</li>


<li class="nav-item">
  <a class="nav-link" href="{{ route('admin-role-index') }}">
    <i class="fas fa-fw fa-users-cog"></i>
    <span>{{ __('Manage Roles') }}</span></a>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{route('admin.user.index')}}">
      <i class="fas fa-user-secret"></i>
      <span>{{__('Customer List')}}</span>
  </a>
</li>


<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.subscribers.list') }}">
    <i class="fas fa-fw fa-users-cog"></i>
    <span>{{ __('Subscribers') }}</span></a>
</li>


<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sactive" aria-expanded="true" aria-controls="collapseBootstrap">
      <i class="fas fa-cog"></i>
      <span>{{__('System Activation')}}</span>
  </a>
  <div id="sactive" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('admin-activation-form')}}"> {{ __('Activation') }}</a>
        <a class="collapse-item" href="{{route('admin-generate-backup')}}"> {{ __('Generate Backup') }}</a>
      </div>
  </div>
</li>
