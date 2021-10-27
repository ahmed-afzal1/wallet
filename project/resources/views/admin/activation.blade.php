@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('System Purchase Activation') }} </h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin-activation-form')}}">{{ __('System Activation') }}</a></li>
    </ol>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-6">
      <!-- Form Basic -->
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('System Activation') }}</h6>
        </div>
  
        <div class="card-body">
          <div class="gocover" style="background: url({{asset('assets/backend/images/loader/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

		  @if($activation_data != "")
				<div class="row">
					<div class="col-lg-12 text-center" style="color:darkgreen">

						{!! $activation_data !!}

					</div>
				</div>
			@else
          <form id="geniusformdata" action="{{ route('admin-activate-purchase') }}" method="POST" enctype="multipart/form-data">
  
              @include('includes.admin.form-both')
  
              {{ csrf_field() }}
  
              <div class="form-group">
                <label for="name">{{ __('Purchase Code') }} <small><a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">{{ __('How to get purchase code?') }}</a></small></label>
				<input class="form-control" name="pcode" id="admin_name" placeholder="{{ __('Enter Purchase Code') }}" required="" value="" type="text">
             </div>
  
              <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Activate') }}</button>
  
          </form>
		  @endif
        </div>
      </div>
      <!-- Form Sizing -->
  
      <!-- Horizontal Form -->
    </div>
  </div>


@endsection