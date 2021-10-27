@extends('layouts.admin')

@section('content')


<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between">
  <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Contact Section') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.dashboard')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>

      <li class="breadcrumb-item"><a href="{{route('admin-ps-homecontact')}}">{{ __('Edit Contact Section') }}</a></li>
  </ol>
  </div>
</div>

<div class="row justify-content-center mt-3">
<div class="col-lg-6">
  <!-- Form Basic -->
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Contact Section') }}</h6>
    </div>

    <div class="card-body">
      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
      <form class="geniusform" action="{{route('admin-ps-update')}}" method="POST" enctype="multipart/form-data">

        @include('includes.admin.form-both')

          {{ csrf_field() }}

          <div class="form-group" id="set-picture">
            <label>{{ __('Set Picture') }} <small class="small-font">({{ __('Preferred Size 600 X 600') }})</small></label>
            <div class="wrapper-image-preview">
                <div class="box">
                    <div class="back-preview-image" style="background: url({{ $data->c_background ? asset('assets/images/'.$data->c_background):asset('assets/images/noimage.png') }});"></div>
                    <div class="upload-options">
                        <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                        <input id="img-upload" type="file" class="image-upload" name="c_background" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="inp-name">{{ __('Title') }}</label>
            <input type="text" class="form-control" id="inp-name" name="c_title"  placeholder="{{ __('Title') }}" value="{{$data->c_title}}" required>
        </div>


          <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>

      </form>
    </div>
  </div>
</div>

</div>
<!--Row-->
@endsection




