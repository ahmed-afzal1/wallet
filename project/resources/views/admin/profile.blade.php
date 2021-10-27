@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__("My Profile")}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__("Home")}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.profile')}}" class="text-muted">{{__("Myprofile")}}</a></li>
    </ol>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card">
            <img src="{{asset( ($admininfo->photo != null) ? '/assets/backend/images/admins/'.$admininfo->photo : '/assets/backend/images/admins/user.png')}}" class="card-img-top" alt="user image">
            <div class="card-body">
                <div class="card-body  pb-3">
                    <h5 class="card-title m-0"> {{__(ucwords($admininfo->name))}} </h5>
                    <small class="d-block">
                        {{__(($admininfo->role != null)?$admininfo->role->name:'Non Permited')}} 
                        <span class="badge badge{{ ($admininfo->status == 0) ? '-danger':'-success'}}"> 
                            {{($admininfo->status == 1)? 'Active':'Deactive'}}
                        </span>
                    </small>
                    <strong>{{__("Email")}}</strong>: {{$admininfo->email}}
                    <p><strong>{{__("Last Login")}}:</strong> <br> {{App\Models\Admin::loginCheck($admininfo->id,'admin')->login_time}}</p>
                  </div>

            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="text-right w-100 pb-3">
                    <a href="{{route('admin.dashboard')}}" class="btn btn-primary btn-sm">{{__("Go Back")}}</a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updatepass">
                       {{__("Change Password")}}
                    </button>
                </div>

                <ul class="list-group list-group-flush pb-3">
                    @if ($admininfo->role)
                        <li class="list-group-item"><strong>{{__("Privilege")}}</strong>  <span class="badge badge-primary float-right">{{$admininfo->role->name}}</span></li>
                    @endif
                    <li class="list-group-item"><strong>{{__("Email")}}</strong>: {{$admininfo->email}}</li>
                    <li class="list-group-item"><strong>{{__("Phone")}}</strong>: {{$admininfo->phone}}</li>
                    <li class="list-group-item"><strong>{{__("Address")}}</strong>: <br>{{$admininfo->address}}</li>
                    <li class="list-group-item"><strong>{{__("About")}}</strong>: <br>{{$admininfo->about}}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="updatepass" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
  
        <div class="modal-header">
            <h5 class="modal-title">{{ __("Update Password") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>

        <!-- Modal body -->
        <div class="modal-body p-3 ">
            <div class="alert-danger"></div>
             <form class="geniusform" action="{{route('admin-changepassword',$admininfo->id)}}" class="form-content-right" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-3 col-form-label"> {{__("Current Password")}} <span class="text-danger">*</span> </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="currentpassword" id="staticEmail" required placeholder=" {{__("Current Password")}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">{{__("New Password")}} <span class="text-danger">*</span> </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password"  required id="inputPassword" placeholder="{{__("New Password")}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cinputPassword" class="col-sm-3 col-form-label">{{__("Confirm Password")}} <span class="text-danger">*</span> </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password_confirmation"  required id="cinputPassword" placeholder="{{__("Confirm Password")}}">
                    </div>
                </div>


                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">{{__("Update")}}</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">

        </div>

      </div>
    </div>
  </div>
	
					
@endsection