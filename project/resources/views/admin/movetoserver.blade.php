@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('System Purchase Activation') }} </h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin-generate-backup')}}">{{ __('Generate BackUp') }}</a></li>
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
		  	@include('includes.admin.form-both')

		  <div style="padding: 10px;" class="text-center">
			@if($bkuplink == "")
				<span id="bkupData">{{ __('No Backup File Generated.') }}</span>
			@else
				<span id="bkupData"><a href="{{$bkuplink}}">{{$chk}}</a><a href="{{route('admin-clear-backup')}}"> <i class="fa fa-times-circle"></i></a></span>
			@endif
		</div>
		<hr/>
		<div class="add-product-footer text-center">
			<button name="addProduct_btn" id="generateBkup" type="button" class="btn btn-primary">{{ __('Generate Backup') }}</button>
		</div>

        </div>
      </div>
      <!-- Form Sizing -->
  
      <!-- Horizontal Form -->
    </div>
  </div>


@endsection

@section('scripts')
	<script type="text/javascript">

        $("#generateBkup").click(function(){
            $('#bkupData').html('<img style="height:100px;" src="{{asset("assets/backend/images/loader/".$gs->admin_loader)}}"><br>{{ __('Generating Backup... Please wait....') }}');
            $.ajax({
                url: "{{url('admin/check/movescript')}}",
                success: function(result){
                    if(result.status == 'success'){
                        $("#bkupData").html('<a href="'+result.backupfile+'">'+result.filename+'</a>');
                    }
                }
            });
        });

	</script>

@endsection