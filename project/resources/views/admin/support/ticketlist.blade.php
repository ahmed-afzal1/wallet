@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 p-2">{{ __('Support Ticket') }}</h1>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Support Ticket') }}</li>
        </ol>
    </div>
</div>



<div class="row mt-3">
  <!-- Datatables -->
  <div class="col-lg-12">

	@include('flashmessage')

	<div class="card mb-4">
	  <div class="table-responsive p-3">
		<table class="table align-items-center table-flush" id="geniustable">
		  <thead class="thead-light">
			<tr>
                <th>{{__("Ticket ID")}}</th>
                <th>{{__("User")}}</th>
                <th>{{__("Subject")}}</th>
                <th>{{__("Message")}}</th>
                <th>{{__("Status")}}</th>
                <th>{{__("Action")}}</th>
			</tr>
		  </thead>
		</table>
	  </div>
	</div>
  </div>

</div>


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
                <p class="text-center">{{__("You are about to delete this Ticket.")}}</p>
                <p class="text-center">{{ __("Do you want to proceed?") }}</p>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
                <a href="javascript:;" class="btn btn-danger btn-ok">{{ __("Delete") }}</a>
            </div>
        </div>
    </div>
</div>


<div class="sub-categori">
  <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="vendorformLabel">{{ __('Send Message') }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="container-fluid p-0">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="contact-form">
                                  <form id="emailreply1">
                                      {{csrf_field()}}
                                      <ul>
                                          <li>
                                              <input type="email" class="input-field eml-val" id="eml1" name="to" placeholder="{{ __('Email') }} *" value="" required="">
                                          </li>
                                          <li>
                                              <input type="text" class="input-field" id="subj1" name="subject" placeholder="{{ __('Subject') }} *" required="">
                                          </li>
                                          <li>
                                              <textarea class="input-field textarea" name="message" id="msg1" placeholder="{{ __('Your Message') }} *" required=""></textarea>
                                          </li>
                                          <input type="hidden" name="type" value="Ticket">
                                      </ul>
                                      <button class="submit-btn" id="emlsub1" type="submit">{{ __('Send Message') }}</button>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

{{-- MESSAGE MODAL ENDS --}}

@endsection

@section('scripts')
    <script>
        var table = $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.support.datatable') }}',
                columns: [
                        { data: 'ticket_id', name: 'ticket_id' },
                        { data: 'user_email', name: 'user_email' },
                        { data: 'subject', name: 'subject' },
                        { data: 'message', name: 'message' },
                        { data: 'ticket_status', name: 'ticket_status' },
                        { data: 'action', name: 'action' },
                    ],
                    language: {
                        processing: '<img src="{{asset('assets/images/loader/'.$gs->admin_loader)}}">',
                    }
            });

    </script>
@endsection
