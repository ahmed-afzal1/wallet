@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__("Customer")}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__("Home")}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#" class="text-muted">{{__('Customer Profile')}}</a></li>
    </ol>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card">
            <img class="card-img-top" src="{{($userdata->photo)?asset($userdata->photo):asset('assets/backend/images/users/user.jpg')}}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title m-0">{{$userdata->name}}</h5>
              <strong class="d-block">{{__("Account Balance")}} <span class="btn btn-dark btn-sm float-right">${{number_format($useraccbaltotal,2)}}</span></strong>
              <strong>{{__("Email")}}</strong>: {{$userdata->email}}
              <p>
                @if (isset($userlogin))
                    <p>IP: <strong>{{$userlogin->ip}}</strong></p>
                    <p>Location : <strong>{{$userlogin->location}}</strong></p>
                    <p>Browser : <strong>{{$userlogin->browser}}</strong></p>
                    <p>Operating System : <strong>{{$userlogin->os}}</strong></p>
                @else
                    <strong>{{__("Last Login")}}: {{__('No Login History')}}</strong>  
                @endif

                @if ($alluserlogins)
                    <button type="button" class="btn btn-primary userData" data-toggle="modal" data-target="#staticBackdrop">
                    Details
                    </button>
                @endif
            </p>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>{{__("Account Type")}}</strong>  <span class="badge badge-primary float-right">{{$userdata->acc_type}}</span></li>
              <li class="list-group-item"><strong>{{__("About")}}</strong>: <br>{{$userdata->about}}</li>
              <li class="list-group-item"><strong>{{__("Alternative Email")}}</strong>: {{$userdata->alt_email}}</li>
              <li class="list-group-item"><strong>{{__("Phone")}}</strong>: {{$userdata->phone}}</li>
              <li class="list-group-item"><strong>{{__("Bussiness Name")}}</strong>: {{$userdata->business_name}}</li>
              <li class="list-group-item"><strong>{{__("Website")}}</strong>: {{$userdata->website}}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4  pb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('Total Transaction') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($sendmoney,2)}}</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4  pb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">{{__("Total Deposit")}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($depositmoney,2)}}</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <div class="col-md-4  pb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">{{__("Total Withdraw")}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($withdrawmoney,2)}}</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            
            
        
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__("Account Balance")}}</strong>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hovered table-striped">
                                <thead>
                                    <tr>
                                        <th width="35%">{{__("Country Code")}}</th>
                                        <th width="35%">{{__("Balance")}}</th>
                                        <th width="30%">{{__("Actions")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($useraccbal as $value)

                                    <tr>
                                        <td>{{$value->currency->code}} 
                                            @if($userdata->default_currency == $value->currency_id)
                                                <span class="badge badge-primary">Default</span>
                                            @endif
                                        </td>
                                        <td>{{App\Models\Currency::convertwithcurrencyrate($value->balance,$value->currency_id,true)}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" id="increment" data-toggle="modal" data-target="#incrementModal" data-id="{{ $value->id }}">{{__('Increment')}}</button>
                                            <button class="btn btn-sm btn-danger" id="decrement" data-toggle="modal" data-target="#decrementModal" data-id="{{ $value->id }}">{{__('Decrement')}}</button>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">
                                                <h5 class="text-center text-muted">{{__('No record found!')}}</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>        
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <strong>{{__("Last Complete Transaction")}}</strong>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hovered table-striped">
                            <thead>
                                <tr>
                                    <th>{{__("Transaction ID")}}</th>
                                    <th>{{__("Balance")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usertrancbal as $value)
                                <tr>
                                    <td>ID: {{$value->transaction_id}} 
                                        <br> Type: {{ucwords($value->transaction_type)}}
                                        <br> Date: {{\Carbon\Carbon::parse($value->created_at)->format('d M Y')}}
                                    </td>
                                    <td  class="text-center">{{App\Models\Currency::where('id',$value->transaction_currency)->first()->sign }} {{$value->amount}}
                                        <a class="action-path btn btn-primary btn-sm" href="{{route('admin.transaction.details',[$value->transaction_id])}}">{{__('Details')}}</a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">
                                            <h5 class="text-center text-muted">No record found!</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- Area Chart -->
        {{-- <div class="row">
            <div class="col-md-12 pt-4">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('Monthly Recap Report')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">{{__('Login Details')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                @if (count($alluserlogins)>0)
                    @foreach ($alluserlogins as $alluserlogin)
                        <p>IP: <strong>{{$alluserlogin->ip}}</strong></p>
                        <p>Location : <strong>{{$alluserlogin->location}}</strong></p>
                        <p>Browser : <strong>{{$alluserlogin->browser}}</strong></p>
                        <p>Operating System : <strong>{{$alluserlogin->os}}</strong></p>
                    <hr>
                    @endforeach
                @else 
                    <p class="text-center">{{__('No Login History Found')}}</p>
                @endif
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>


    <div class="modal fade" id="incrementModal" tabindex="-1" role="dialog" aria-labelledby="incrementModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">{{__('Increment User Balance')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="incrementModalForm" action="{{route('admin.user.incrementBalance')}}" method="post">
              @csrf
              <input type="hidden" name="id" id="input_id" value="">
              <input type="hidden" name="modal_type" value="increment">
              <div class="modal-body">
                <p id="available_balance"> </p>
                <input type="number" id="input_price" name="amount" value="" class="form-control" min="1">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submitIncrement">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <div class="modal fade" id="decrementModal" tabindex="-1" role="dialog" aria-labelledby="decrementModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">{{__('Decrement User Balance')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="decrementModalForm" action="{{route('admin.user.decrementBalance')}}" method="post">
              @csrf
              <input type="hidden" name="id" id="input_did" value="">
              <input type="hidden" name="modal_type" value="decrement">
              <div class="modal-body">
                <p id="available_dbalance"> </p>
                <input type="number" id="input_dprice" name="amount" value="" class="form-control" min="1">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
</div>
@endsection

@section('scripts')
    <script>
        var $m_jan = 1546,
            $m_feb = 461,
            $m_mar = 846,
            $m_apr = 855,
            $m_may = 960,
            $m_jun = 135,
            $m_aug = 654,
            $m_sep = 244,
            $m_oct = 541,
            $m_nov = 820,
            $m_dec = 786;
    </script>

    <script src="{{asset('assets/backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('assets/backend/js/chart-area-demo.js')}}"></script>
    <script>

      $(document).ready(function () {
      

      
      $('body').on('click', '#decrement', function (event) {
            event.preventDefault();
            var id = $(this).data('id');

            $.get('user/increment/' + id, function (data) {

                $('#available_dbalance').text(`Available Balance ${data.balance}`);
                $('#input_did').val(data.id);

            })
        });

        $('body').on('click', '#increment', function (event) {
            event.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.get('user/increment/' + id, function (data) {

                $('#available_balance').text(`Available Balance ${data.balance}`);
                $('#input_id').val(data.id);

                console.log(data.balance);
            })
        });
      
      }); 
      </script>
@endsection