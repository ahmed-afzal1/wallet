@extends('layouts.admin')

@section('content')

  <div class="card">
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Transaction') }}</h1>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Transaction') }}</li>
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
                <th>{{__("Date")}}</th>
                <th>{{__("User")}}</th>
                <th>{{__("Type")}}</th>
                <th>{{__("Amount")}}</th>
                <th>{{__("Receiver")}}</th>
                <th>{{__("Status")}}</th>
                <th>{{__("Options")}}</th>
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

{{-- STATUS MODAL ENDS --}}


{{-- DELETE MODAL --}}

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
                ajax: '{{ route('admin.transaction.datatables') }}',
                columns: [
                        { data: 'created_at', name: 'created_at' },
                        { data: 'user', name: 'user' },
                        { data: 'transaction_type', name: 'transaction_type' },
                        { data: 'amount', name: 'amount' },
                        { data: 'receiver', name: 'receiver' },
                        { data: 'status', name: 'status' },
                        { data: 'action', searchable: false, orderable: false }
                    ],
                    language: {
                        processing: '<img src="{{asset('assets/images/loader/'.$gs->admin_loader)}}">',
                    }
            });

    </script>
@endsection



