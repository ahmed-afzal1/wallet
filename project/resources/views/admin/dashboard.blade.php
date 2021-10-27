@extends('layouts.admin')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __("Dashboard")}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__("Home")}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__("Dashboard")}}</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__('Total Balance')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$new_bal->sign}}  {{number_format($totalbalance,2)}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__("Total Profit")}} </div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$new_bal->sign}}{{number_format($totalprofit,2)}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-danger ">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__("Transaction Total")}}</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$new_bal->sign}}{{number_format($transactionTotal,2)}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-secondary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__("Withdraw Total")}}</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$new_bal->sign}}{{number_format($withdrawTotal,2)}}</div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__('Total Deposit')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$new_bal->sign}}{{number_format($depositetotal,2)}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__("Personal User")}} </div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$user->where('acc_type','personal')->count()}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 bg-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white mb-1">{{__("Business User")}}</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{$user->where('acc_type','business')->count()}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("Monthly Transaction Report")}}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("Monthly Total")}}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-gray-500">{{__("Send")}}
                            <div class="small float-right"><b>${{Arr::exists($tranThisMonth,"send") ? number_format($tranThisMonth['send']) : ''}}</b></div>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-gray-500">{{__("Received")}}
                            <div class="small float-right"><b>${{Arr::exists($tranThisMonth,'received') ? number_format($tranThisMonth['received']) : ''}}</b></div>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-gray-500">{{__("Withdraw")}}
                            <div class="small float-right"><b>${{Arr::exists($tranThisMonth,'withdraw') ? number_format($tranThisMonth['withdraw']) : ''}}</b></div>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-gray-500">{{__("Deposit")}}
                            <div class="small float-right"><b>${{Arr::exists($tranThisMonth,'deposit') ? number_format($tranThisMonth['deposit']) : ''}}</b></div>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Example -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("Transaction")}}</h6>
                    <a class="m-0 float-right btn btn-info btn-sm" href="{{route('admin-all-transaction')}}">{{__("View More ")}}<i class="fas fa-chevron-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>{{__("Transaction ID")}}</th>
                                <th>{{__("Customer")}}</th>
                                <th>{{__("Amount")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Action")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latesttransaction as $value)
                                <tr>
                                    <td><a href="{{route('admin.transaction.details',$value->transaction_id)}}">{{$value->transaction_id}}</a>
                                        <small class="d-block">{{ucwords($value->transanction_type)}}</small>
                                    </td>
                                    <td> {{$value->sender}}  </td>
                                    <td>${{number_format($value->amount,2)}}</td>
                                    <td>
                                        @if($value->status == 'complete')
                                        <span class="badge badge-success">{{__("Completed")}}</span>
                                        @else
                                        <span class="badge badge-warning">{{__("Pending")}}</span>
                                        @endif
                                    </td>
                                    <td><a  href="{{route('admin.transaction.details',$value->transaction_id)}}" class="btn btn-sm btn-primary">{{__("Details")}}</a></td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>

        <!-- Message From Customer-->
        <div class="col-xl-4 col-lg-5 ">
            <div class="card">
                <div class="card-header py-4 bg-primary d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-light">{{__("Message From Customer")}}</h6>
                </div>
                <div>
                    @forelse ($latestticketlist as $value)
                        <div class="customer-message align-items-center">
                            <a class="font-weight-bold" href="{{route('admin.support.ticket.view',['id'=>$value->id])}}">
                                <div class="text-truncate message-title">{{$value->subject}}</div>
                                <div class="small text-gray-500 message-time font-weight-bold">{{$value->user_email}} · {{\Carbon\Carbon::parse($value->created_at)->format('d M Y')}}</div>
                            </a>
                        </div>
                    @empty

                    @endforelse


                    <div class="card-footer text-center">
                        <a class="m-0 small text-primary card-link" href="{{route('admin.support.ticket')}}">{{__("View More")}} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script>
    var $m_jan = "{{$transPerMonth['01']}}",
        $m_feb = "{{$transPerMonth['02']}}",
        $m_mar = "{{$transPerMonth['03']}}",
        $m_apr = "{{$transPerMonth['04']}}",
        $m_may = "{{$transPerMonth['05']}}",
        $m_jun = "{{$transPerMonth['06']}}",
        $m_jul = "{{$transPerMonth['07']}}",
        $m_aug = "{{$transPerMonth['08']}}",
        $m_sep = "{{$transPerMonth['09']}}",
        $m_oct = "{{$transPerMonth['10']}}",
        $m_nov = "{{$transPerMonth['11']}}",
        $m_dec = "{{$transPerMonth['12']}}";
</script>
    <script src="{{asset('assets/admin/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/chart-area-demo.js')}}"></script>
@endsection
