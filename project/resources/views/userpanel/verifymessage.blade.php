@extends('layouts.front')

@section('content')
<div class="card pt-4  pb-2 rounded action-form" >
    <div class="card-body">
        @if($message == 0)
        <p>{{__("Your account already verified.")}} <a href="{{route('home')}}" class="text-primary">{{_("Home")}}</a></p>
        @else 
        <p>{{__("Account verification successfull. ")}}<a href="{{route('user-dashboard')}}" class="text-primary">{{_("Dashboard")}}</a></p>
        @endif
        <p class="m-0"><a class="btn btn-sm btn-primary float-right" href="{{route('user.logout')}}">{{__("Logout")}}</a></p>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('/assets/backend/js/login.js')}}"></script>
@endsection