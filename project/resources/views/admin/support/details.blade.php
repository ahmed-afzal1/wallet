@extends('layouts.admin')

@section('content')

<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Support Ticket')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.support.ticket')}}" class="text-muted">{{__('Support Ticket')}}</a></li>
    </ol>
</div>
</div>

<div class="row justify-content-center mt-3">
  <div class="col-lg-12">
    <div class="card">
            <div class="card-header pt-4">
                <h5 class="d-inline-block m-0">{{ $ticketinfo->subject }}</h5>
                <select name="tstatus"  id="ticketstatus" class="float-right">
                    <option  value="pending" data-href="{{route('admin-support-ticket-status',['tid'=>$ticketinfo->id,'tstatus'=>'pending'])}}" {{($ticketinfo->ticket_status == 'pending')?'selected':''}}>{{__('Pending')}}</option>
                    <option  value="processing"  data-href="{{route('admin-support-ticket-status',['tid'=>$ticketinfo->id,'tstatus'=>'processing'])}}" {{($ticketinfo->ticket_status == 'processing')?'selected':''}}>{{__('Processing')}}</option>
                    <option  value="complete"  data-href="{{route('admin-support-ticket-status',['tid'=>$ticketinfo->id,'tstatus'=>'completed'])}}" {{($ticketinfo->ticket_status == 'completed')?'selected':''}}>{{__('Complete')}}</option>
                </select>
            </div>
            <hr>
            <div class="card-body">
                {{-- <p><strong>{{__("User")}}:</strong>  {{$ticketinfo->user_email}} <strong class="float-right">{{__("Created at")}}: <span class="font-weight-light">{{\Carbon\Carbon::parse($ticketinfo->created_at)->format('d M Y')}}</span></strong></p>
                <p><strong>{{__("Subject")}}:</strong> {{$ticketinfo->subject}}</p>
                <p><strong class="d-block">{{__("Message")}}:</strong>  {{$ticketinfo->message}}</p> --}}

                <div class="order-table-wrap support-ticket-wrapper">
                    <div class="panel panel-primary">
                        <div class="gocover" style="background: url(https://royalscripts.com/product/geniuscart/fashion/assets/images/1564224329loading3.gif) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        <div class="alert alert-success validation" style="display: none;">
                            <button type="button" class="close alert-close"><span>×</span></button>
                            <p class="text-left"></p>
                        </div>

                        <div class="alert alert-danger validation" style="display: none;">
                            <button type="button" class="close alert-close"><span>×</span></button>
                            <ul class="text-left">

                            </ul>
                        </div>

                        <div class="panel-body" id="messages">
                            @foreach($replies as $message)
                              @if($message->user_id != 0)
                              <div class="single-reply-area user">
                                  <div class="row">
                                      <div class="col-lg-12">
                                          <div class="reply-area">
                                              <div class="left">
                                                  <p>{{$message->message}}</p>
                                              </div>
                                              <div class="right">
                                                  <img class="img-circle" src="{{$message->user->photo != null ? asset('assets/images/'.$message->user->photo) : asset('assets/images/noimage.png')}}" alt="">
                                                  <p class="ticket-date">{{$message->user->name}}</p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <br>
                              @else
                              <div class="single-reply-area admin">
                                  <div class="row">
                                      <div class="col-lg-12">
                                          <div class="reply-area">
                                              <div class="left">
                                                  <img class="img-circle" src="{{ asset('assets/images/'.$admin->photo)}}" alt="">
                                                  <p class="ticket-date"></p>
                                              </div>
                                              <div class="right">
                                                  <p>{{$message->message}}</p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <br>
                              @endif
                              @endforeach
                          </div>

                        <div class="panel-footer">
                            <form id="messageform" action="{{route('admin.support.ticket.message')}}" data-href="{{ route('admin-message-load',$ticketinfo->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <input type="hidden" name="supportticket_id" value="{{$ticketinfo->id}}">
                                    <textarea class="form-control" name="message" id="wrong-invoice" rows="5" required="" placeholder="Message"></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="mybtn1">{{__('Add Reply')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>



  </div>
</div>

@endsection

 @section('jquerydatatabescript')
 <script src="{{asset('assets/backend/vendor/jqueryDatatable/datatable.js')}}"></script>
  <script>
      (function ($) {
           "use strict"; // Start of use strict

          // jQuery datatable
          $(document).ready( function () {
              $('#myTable').DataTable();
          });

        $('#ticketstatus').on('change',function(){
            let url = $('#ticketstatus option:selected').attr('data-href');
            let tsvalue = $('#ticketstatus option:selected').val();

            $.get(url,function(response){
                $.notify(response.message,response.type);
                if(tsvalue == 'completed'){
                    $('#ticketstatus').css({'background':'#8989fa','color':'#fff'});

                }else if(tsvalue == 'processing'){
                    $('#ticketstatus').css({'background':'#8989fa','color':'#fff'});

                }else if(tsvalue == 'pending'){
                    $('#ticketstatus').css({'background':'#8989fa','color':'#fff'});

                }

            });

        });



      })(jQuery);

  </script>
@endsection
