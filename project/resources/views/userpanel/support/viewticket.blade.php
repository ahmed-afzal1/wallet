@extends('layouts.user') 

@section('content')

    <div class="user-profile-details">
        <div class="order-history">
            <div class="header-area">
                <h4 class="title">
                {{__('Subject')}}: {{$data->subject}} <a class="mybtn1" href="{{route('user-support-ticket-create')}}"> <i class="fas fa-arrow-left"></i> Back</a>
                </h4>
            </div>
            @include('flashmessage')

            <div class="support-ticket-wrapper ">
                <div class="panel panel-primary">
                    <div class="gocover" style="background: url(https://royalscripts.com/product/geniuscart/fashion/assets/images/1564224328loading3.gif) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
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
                                            <img class="img-circle" src="{{$message->user->photo != null ? asset('assets/images/users/'.$message->user->photo) : asset('assets/images/noimage.png')}}" alt="">
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
                                            <img class="img-circle" src="{{ asset('assets/backend/images/admins/'.$admin->photo)}}" alt="">
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
                        <form action="{{route('user.support.ticket.reply')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="supportticket_id" value="{{$data->id}}">
                                <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                <textarea class="form-control" name="message" id="wrong-invoice" rows="5" style="resize: vertical;" required="" placeholder="Message"></textarea>
                            </div>

                            <div class="form-group">
                                <button class="mybtn1">{{__('Add Reply')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

@endsection

@section('script')
    <script src="{{asset('assets/userpanel/js/transaction.js')}}"></script>
@endsection


