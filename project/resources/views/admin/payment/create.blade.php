@extends('layouts.admin')

@section('content')

<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Payment') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.payment.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.payment.index')}}">{{ __('Payment') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.payment.create')}}">{{ __('Add New Payment') }}</a></li>
    </ol>
  </div>
</div>

  <div class="row justify-content-center mt-3">
    <div class="col-lg-6">
      <!-- Form Basic -->
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Add New Payment Form') }}</h6>
        </div>
  
        <div class="card-body">
          <div class="gocover" style="background: url({{asset('assets/backend/images/loader/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
          <form class="geniusform" action="{{route('admin.payment.store')}}" method="POST" enctype="multipart/form-data">
  
            @include('includes.admin.form-both')
  
              {{ csrf_field() }}
  
              <div class="form-group">
                <label for="Category">{{ __('Name') }}</label>
              <input type="text" class="form-control" name="title" value="" placeholder="{{ __('Name') }}">
              </div>
      
              <div class="form-group">
                <label for="area1">{{ __('Description') }}</label>
                 <textarea id="area1" class="form-control summernote" name="details" placeholder="{{__('Description')}}"></textarea>
            </div>

  
              <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>
  
          </form>
        </div>
      </div>
  
    </div>
  </div>


@endsection

@section('scripts')

@endsection