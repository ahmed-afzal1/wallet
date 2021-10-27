@extends('layouts.user') 

@section('content')

    <div class="money-form">
        <h4 class="title">
             {{__("Request Received")}}
        </h4>
        <div class="table-responsive">
            @include('flashmessage')
            @if (count($requestreceived) == 0)
            <h5 class="text-danger text-center pb-3 pt-5 font-italic">{{__("No received request avialable")}}.</h5>
            @else
                <table  id="requesttable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th colspan="2">{{__("Request From")}}</th>
                            <th>{{__("Amount ")}}</th>
                            <th>{{__("Transaction Cost")}}</th>
                            <th>{{__("Reference")}}</th>
                            <th>{{__("Status")}}</th>
                            <th>{{__("Action")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requestreceived as $item)
                            @php
                                $requestUser = App\User::where('email',$item->request_from)->first();
                            @endphp
                            
                            <tr>
                                <td class="px-1"><img width="40" height="40" class="rounded-circle" src="{{($item->sender->photo != null)? asset($item->sender->photo):asset('assets/backend/images/users/user.png')}}" alt="..."></td>
                                <td class="pl-0">
                                    @if($requestUser)
                                        <span class="d-block">{{$requestUser->name}}</span>
                                    @endif
                                    <small>{{$item->request_from}}</small>
                                    <small  class="d-block text-warning">{{Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</small>
                                </td>
                                <td>{{App\Models\Currency::convertwithcurrencyrate(round($item->amount,2),$item->currency_id,true)}}</td>
                                <td>{{App\Models\Currency::convertwithcurrencyrate(round($item->transaction_cost,2),$item->currency_id,true)}}</td>
                                <td>
                                    @php
                                        if(strlen($item->referance) > 50){
                                        echo substr($item->referance,0,50).'...';
                                        }else{
                                            echo substr($item->referance,0,50);
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @if ($item->status == 'pending')
                                        <span class="btn btn-sm btn-warning m-0">{{__("Pending")}}</span>
                                    @elseif($item->status == 'completed') 
                                        <span class="btn btn-sm btn-success m-0">{{__("Completed")}}</span>
                                    @endif    
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary m-0 btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{__("Action")}}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item action-path" href="{{route('user-money-request-show',[$item->request_id])}}">{{__("View")}}</a>
                                        @if($item->status != 'completed') 
                                        <a class="dropdown-item action-path confirm-delete" href="javascript:;" data-href="{{route('user-money-request-delete',[$item->request_id])}}" data-toggle="modal" data-target="#confirm-delete">{{__("Delete")}}</a>
                                        @endif 
                                        </div>
                                    </div>
                                    </div>


                                </td>
                            </tr>
                        @empty
                        @endforelse
                    
                    </tbody>
                </table>
            @endif
        </div>
    </div>




{{-- DELETE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
  
      <div class="modal-header">
          <h5 class="modal-title">{{ __("Confirm Delete") }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
      </div>
  
        <!-- Modal body -->
        <div class="modal-body">
              <p>{{ __("You are want to delete this request.") }}</p>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
              <a class="btn btn-danger text-white data-delete">{{ __("Delete") }}</a>
        </div>
  
      </div>
    </div>
  </div>
  
  {{-- DELETE MODAL ENDS --}}


@endsection

@section('script')
<script src="{{asset('assets/backend/vendor/jqueryDatatable/datatable.js')}}"></script>
<script>
    (function ($) {
         "use strict"; // Start of use strict

        // jQuery datatable 
        $(document).ready( function () {
            $('#requesttable').DataTable();
        } );
        
    })(jQuery);

</script>
@endsection

