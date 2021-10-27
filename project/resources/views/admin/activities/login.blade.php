@extends('layouts.admin')

@section('content')

<div class="card">
	<div class="d-sm-flex align-items-center justify-content-between mb-4 ml-2">
    <h1 class="h3 mb-0 text-gray-800">{{ __('Login Activities') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ __('Dashboard') }}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ __('Login Activities') }}</li>
    </ol>
  </div>
</div>



<div class="row mt-3">
  <div class="col-lg-12">
    @include('flashmessage')

    <div class="card mb-4">
      <div class="table-responsive p-3">
      <table class="table align-items-center table-flush" id="geniustable">
        <thead class="thead-light">
        <tr>
          <th>{{__("Name")}}</th>
          <th>{{__("Email")}}</th>
          <th>{{__("Location")}}</th>
          <th>{{__("Agent/Browser")}}</th>
          <th>{{__("OS")}}</th>
          <th>{{__("Login Time")}}</th>
          <th>{{__("Logout Time")}}</th>
        </tr>
        </thead>
      </table>
      </div>
    </div>
  </div>
</div>


{{-- STATUS MODAL --}}

<div class="modal fade confirm-modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle" aria-hidden="true">
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

<div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
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
    <script>
        var table = $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.loginactivities.datatables') }}',
                columns: [
                        { data: 'user_id', name: 'user_id' },
                        { data: 'email', name: 'email' },
                        { data: 'location', name: 'location' },
                        { data: 'browser', name: 'browser' },
                        { data: 'os', name: 'os' },
                        { data: 'login_time', name: 'login_time' },
                        { data: 'logout_time', name: 'logout_time' },
                    ],
                    language: {
                        processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">',
                    }
            });

    </script>
@endsection


