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