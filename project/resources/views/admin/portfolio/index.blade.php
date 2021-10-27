@extends('layouts.admin')

@section('content')

<div class="card">
	<div class="d-sm-flex align-items-center justify-content-between">
	<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Project Section') }}</h5>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
		<li class="breadcrumb-item"><a href="{{ route('admin-portfolio-index') }}">{{ __('Project Section') }}</a></li>
	</ol>
	</div>
</div>
<div class="row mt-3 mx-auto">
    <!-- Datatables -->
    <div class="col-lg-12">

      <div class="card mb-4 pt-5">
        <form class="geniusform" action="{{route('admin-ps-update')}}" method="POST">
            @include('includes.admin.form-both')
			@csrf
            <div class="form-row">
                <div class="form-group ml-5 col-md-5 ">
                    <label for="stitle">{{__("Subtitle")}}</label>
                    <input type="text" name="portfolio_subtitle"  required  value="{{$ps->portfolio_subtitle}}" class="form-control" id="stitle">
                </div>
                <div class="form-group ml-5  col-md-5">
                    <label for="title">{{__("Title")}}</label>
                    <input type="text" name="portfolio_title" required  class="form-control" value="{{$ps->portfolio_title}}" id="title" >
                </div>
                <div class="form-group mx-auto">
                    <label for="details">{{ __('Text ') }}</label>
                    <textarea class="form-control summernote"  id="details" name="portfolio_text" required rows="3" placeholder="{{__('Enter Text')}}">{{ $ps->portfolio_text }}</textarea>
                </div>
            </div>
            <button type="submit" id="submit-btn" class="btn btn-primary d-block mx-auto mb-4">{{ __('Submit') }}</button>
        </form>
      </div>
    </div>
    <!-- DataTable with Hover -->

  </div>

<!-- Row -->
<div class="row mt-3">
  <!-- Datatables -->
  <div class="col-lg-12">
	<div class="card mb-4">
	  <div class="table-responsive p-3">
		<table class="table align-items-center table-flush" id="geniustable">
		  <thead class="thead-light">
			<tr>
			  <th>{{ __('Featured Image') }}</th>
			  <th>{{ __('Title') }}</th>
			  <th>{{ __('Actions') }}</th>
			</tr>
		  </thead>
		</table>
	  </div>
	</div>
  </div>
  <!-- DataTable with Hover -->

</div>
<!--Row-->

{{-- STATUS MODAL --}}

<div class="modal fade confirm-modal" id="statusModal" tabindex="-1" role="dialog"
	aria-labelledby="statusModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
	<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">{{ __("Update Status") }}</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		</div>
		<div class="modal-body">
			<p class="text-center">{{ __("You are about to change the status.") }}</p>
			<p class="text-center">{{ __("Do you want to proceed?") }}</p>
		</div>
		<div class="modal-footer">
		<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
		<a href="javascript:;" class="btn btn-success btn-ok">{{ __("Update") }}</a>
		</div>
	</div>
	</div>
</div>

{{-- STATUS MODAL ENDS --}}


{{-- DELETE MODAL --}}

<div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog"
aria-labelledby="deleteModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">{{ __("Confirm Delete") }}</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
	<p class="text-center">{{__("You are about to delete this Service. Every informtation under this service will be deleted.")}}</p>
	<p class="text-center">{{ __("Do you want to proceed?") }}</p>
</div>
<div class="modal-footer">
	<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
	<a href="javascript:;" class="btn btn-danger btn-ok">{{ __("Delete") }}</a>
</div>
</div>
</div>
</div>

@endsection



@section('scripts')

    <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('admin-portfolio-datatables') }}',
               columns: [
                        { data: 'photo', name: 'photo' , searchable: false, orderable: false},
                        { data: 'title', name: 'title' },
            			{ data: 'action', searchable: false, orderable: false }

                     ],
                language : {
                	processing: '<img src="{{asset('assets/backend/images/loader/'.$gs->admin_loader)}}">'
                }
            });

			$(function() {
            $(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">'+
                '<a class="btn btn-primary" href="{{route('admin-portfolio-create')}}">'+
            '<i class="fas fa-plus"></i> Add New Portfolio'+
            '</a>'+
            '</div>');
        });


</script>


@endsection
