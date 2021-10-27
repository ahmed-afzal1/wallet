@extends('layouts.admin')

@section('content')

<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between">
    @if ($data->id != 1)
      <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Staff') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.staff.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
      @else 
      <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Super Admin') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.dashboard')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
    @endif
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
      @if ($data->id != 1)
        <li class="breadcrumb-item"><a href="{{route('admin.staff.index')}}">{{ __('Staffs') }}</a></li>
      @endif

      @if ($data->id != 1)
        <li class="breadcrumb-item"><a href="{{route('admin.staff.edit',$data->id)}}">{{ __('Edit Staff') }}</a></li>
        @else 
        <li class="breadcrumb-item"><a href="{{route('admin.myprofile')}}">{{ __('Profile') }}</a></li>
      @endif
  </ol>
  </div>
</div>

<div class="row justify-content-center mt-3">
  <div class="col-lg-6">
    <!-- Form Basic -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Staff Form') }}</h6>
      </div>

      <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/backend/images/loader/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{ route('admin.staff.update',$data->id) }}" method="POST" enctype="multipart/form-data">

          @include('includes.admin.form-both')

          {{ csrf_field() }}

            <div class="form-group">
              <label for="name">{{ __('Name') }}</label>
              <input type="text" class="form-control" id="name" name="name"  placeholder="{{ __('Enter Name') }}" value="{{ $data->name }}" required>
           </div>

           <div class="form-group">
              <label for="email">{{ __('Email') }}</label>
              <input type="email" class="form-control" id="email" name="email"  placeholder="{{ __('Enter Email') }}" value="{{ $data->email }}" required>
              <small class="d-block"><span class="badge badge-info">{{__('Note')}}</span> {{__('Email must unique.')}}</small>
           </div>

           <div class="form-group">
              <label for="title">{{ __('Phone') }}</label>
              <input type="text" class="form-control" id="phone" name="phone"  placeholder="{{ __('Enter Phone') }}" value="{{ $data->phone }}">
           </div>


           @if ($data->id != 1)
            <div class="form-group">
                <label for="privilige">{{ __('Privilege') }}</label>
                <select class="form-control mb-3" name="role_id" id="privilige">
                    <option value="" selected>{{__('Select Privilege')}}</option>
                    @foreach ($roles as $val)
                        <option value="{{$val->id}}" {{ $data->role_id == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                    @endforeach
                </select>
            </div>
           @endif


           <div class="form-group">
              <label for="address">{{ __('Address') }}</label>
               <textarea class="form-control"  id="address" name="address" required rows="3" placeholder="{{__('Address')}}">{{$data->address}}</textarea>
             
           </div>

           <div class="form-group">
              <label for="about">{{ __('About') }}</label>
              <textarea class="form-control"  id="about" name="about" required rows="3" placeholder="{{__('About')}}">{{$data->about}}</textarea>
          </div>



            <div class="form-group">
                <label>{{ __('Set Picture') }} <small class="small-font">({{ __('Preferred Size 600 X 600') }})</small></label>
                <div class="wrapper-image-preview">
                    <div class="box">
                        <div class="back-preview-image" style="background-image: url({{$data->photo ? asset('assets/images/'.$data->photo) : asset('assets/images/placeholder.jpg') }});"></div>
                        <div class="upload-options">
                            <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                            <input id="img-upload" type="file" class="image-upload" name="photo" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>


            <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>

        </form>
      </div>
    </div>

    <!-- Form Sizing -->

    <!-- Horizontal Form -->

  </div>
</div>

@endsection