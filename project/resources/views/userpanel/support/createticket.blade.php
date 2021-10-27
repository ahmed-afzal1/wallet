@extends('layouts.user')

@section('styles')
    <style>
        .top-card{
            background:#f44 !important;
        }
    </style>
@endsection
@section('content')

    <div class="money-form">
        <h4 class="title">
             {{__("Support Ticket")}} <a href="#" class="btn btn-primary btn-sm float-right m-0"  data-toggle="modal" data-target="#staticBackdrop">{{__("Create Ticket")}}</a>
        </h4>
        <div class="heading-menu pt-4">
            <div class="card-group p-2 bg-white">
                <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                    {{__("Ticket ID")}}
                </div>
                <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                    {{__("Subject")}}
                </div>
                <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                    {{__("Status")}}
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionTransactions">

            @forelse ($ticketlist as $key => $value)


            <div class="accordion" id="accordionExample">
                <div class="card">
                  <div class="card-header" id="headingOne{{$key}}" data-toggle="collapse" data-target="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne{{$key}}">
                        <div class="card-group">
                            <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                                {{$value->ticket_id}}
                            </div>
                            <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                                {{$value->subject}}
                            </div>
                            <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                                @if ($value->ticket_status == 'pending')
                                   <span class=""> {{ucwords($value->ticket_status)}}</span>
                                @elseif($value->ticket_status == 'processing')
                                   <span class=""> {{ucwords($value->ticket_status)}}</span>
                                @elseif($value->ticket_status == 'completed')
                                    <span class=""> {{ucwords($value->ticket_status)}}</span>                                    
                                @endif
                            </div>

                            <div class="card top-card" style="background:transparent !important;box-shadow:none !important">
                                <div class="col-md-2 remove-sm d-flex">
                                    <div class="box">
                                        <i class="material-icons minus">
                                              remove
                                            </i>
                                    </div>
                                  </div>
                            </div>
                        </div>
                  </div>
              
                  <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="headingOne{{$key}}" data-parent="#accordionExample">
                    <div class="card-body">
                        <h5>{{__("Message")}}<small class="text-dark float-right font-size-12">{{__("Created at")}}: {{\Carbon\Carbon::parse($value->created_at)->format('d M Y')}}</small> </h5>
                        
                        {{$value->message}}
						<p><a class="btn btn-warning btn-sm" href="{{route('user.support.ticket.view',$value->id)}}">{{__("Details")}} </a></p>

                    </div>
                  </div>
                </div>
              </div>


            @empty
                <h5 class="text-danger text-center pb-3 pt-5 font-italic">{{__("No transaction record avialable")}}.</h5>
            @endforelse
          </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formdata2" action="{{route('user-support-ticket-submit')}}" method="POST">
                @csrf
                @include('flashmessage')
               
                <div class="form-group">
                    <label for="exampleInputEmail1">{{__("Subject")}}</label>
                    <input type="text" name="subject" class="form-control" id="exampleInputEmail1">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">{{__("Message")}} </label>
                    <textarea name="message" class="form-control"></textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{__("Submit")}} </button>
                </div>
            </form>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>


@endsection
@section('script')
<script src="{{asset('assets/user/js/transaction.js')}}"></script>
@endsection


