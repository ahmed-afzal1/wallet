

  <!-- Dashbord-content Area Start -->
  <section class="dashbord-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="aside-area ">
            <div class="main-menu" id="sidemenu">
              <div class="current-balance">
                <div class="content">
                  <p class="amount">{{$userinfo->def_acc_balance}}</p>
                    <span class="label">{{__("Current Balance")}}</span>
                    <a href="javasctipt:;" class="text-secondary d-block" data-toggle="modal" data-target="#exampleModalScrollable">
                      <small>{{__('My Accounts')}}</small>
                    </a>
                </div>
              </div>
              <ul class="nav">
                <li class="nav-item">
                  <a href="{{route('user-dashboard')}}" class="nav-link @yield('dashboard')"><i class="material-icons">{{__("home")}}</i>{{__("Dashboard")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-send')}}" class="nav-link  @yield('send')" ><i class="material-icons">{{__("send")}}</i>{{__("Send")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-request-money')}}" class="nav-link  @yield('sendrequest')" ><i class="material-icons">list</i>{{__("Request")}}</a>
				        </li>

                <li class="nav-item">
                  <a href="{{route('user-request-received')}}" class="nav-link  @yield('request')" ><i class="material-icons">list</i>{{__("Received Request")}}</a>
				        </li>

                <li class="nav-item">
                  <a href="{{route('user-deposit-money')}}" class="nav-link @yield('deposit')"><i class="material-icons">collections_bookmark</i>{{__("Deposit")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-money-withdraw-page')}}" class="nav-link @yield('withdraw')"><i class="material-icons">monetization_on</i>{{__("Withdraw")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-alltransaction')}}" class="nav-link  @yield('transaction')"><i class="material-icons">card_travel</i>{{__("Transactions")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user.exchange')}}" class="nav-link  @yield('exchange')"><i class="material-icons">autorenew</i>{{__("Exchange")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user.account.invoicelist')}}" class="nav-link  @yield('invoice')"><i class="material-icons">receipt</i>{{__("Invoice")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-support-ticket-create')}}" class="nav-link  @yield('ticket')"><i class="material-icons">contact_support</i>{{__("Ticket")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-profile')}}" class="nav-link  @yield('account')"><i class="material-icons">person</i>{{__("Account")}}</a>
                </li>

                <li class="nav-item">
                  <a href="{{route('user-bankaccount-create')}}" class="nav-link"><i class="material-icons">credit_card</i>{{__("Cards & Bank Accounts")}}</a>
                </li>
              </ul>


            </div>

            <div class="help-box mt-30">
              <div class="icon-area">
                <div class="icon">
                  <img src="{{asset('assets/user/images/chat-icon.png')}}" alt="">
                </div>
              </div>
              <div class="content">
                <h4 class="title">
                  {{__('Need Help')}}?
                </h4>
                <p class="text">
                  {{__('Have questions or concerns
                    regrading your account?
                    Our experts are here to help')}}!.
                </p>
              </div>
            </div>

          </div>
        </div>

      <!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalScrollableTitle">{{__("Accounts Balance")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
            <table class="table table-hover text-left balance-table">
                <thead>
                  <tr>
                    <th scope="col">{{__("Currency Name")}}</th>
                    <th scope="col">{{__("Currency Code")}}</th>
                    <th scope="col">{{__("Balance")}}</th>
                    <th scope="col">{{__("Rate")}}</th>
                    <th scope="col">{{__("Default")}}</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                    $accountbalanceinfo = App\Models\Accountbalance::where('user_email',$userinfo->email)->get();

                @endphp
                @forelse ($accountbalanceinfo as $item)
                <tr>
                    <td scope="row">
                        <span class="rounded-circle currency-sign-box mr-2">{{$item->currency->sign}}</span>
                        <span>{{__(ucwords($item->currency->name))}}</span>
                    </td>
                    <td>{{__($item->currency->code)}}</td>
                    <td>{{App\Models\Currency::placesign(round($item->balance,2),$item->currency->id)}}</td>
                    <td>{{round($item->currency->rate,2)}}</td>
                    <td>
                        @if ($item->currency_id == $userinfo->default_currency)
                            <span class="btn btn-success btn-sm">{{__("Default")}}</span>
                        @else
                        <a href="{{route('user-set-default-currency',['default_currency'=>$item->currency_id])}}" class="btn btn-warning btn-sm">{{__("Set Default")}}</a>
                        @endif
                    </td>
                  </tr>
                @empty

                @endforelse

                </tbody>
              </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</button>
        </div>
      </div>
    </div>
  </div>
