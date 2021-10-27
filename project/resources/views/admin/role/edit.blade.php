@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Role') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin-role-index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">{{ __('Roles') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin-role-index') }}">{{ __('Manage Roles') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin-role-edit',$data->id)}}">{{ __('Edit Role') }}</a></li>
    </ol>
    </div>
</div>

<div class="row justify-content-center mt-3">
  <div class="col-lg-6">
    <!-- Form Basic -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Role Form') }}</h6>
      </div>

      <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin-role-update',$data->id)}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
              <label for="inp-name">{{ __('Role Name') }}</label>
              <input type="text" class="form-control" id="inp-name" name="name"  placeholder="{{ __('Enter Role Name') }}" value="{{$data->name}}" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Manage Transaction" {{ $data->sectionCheck('Manage Transaction') ? 'checked' : '' }} class="custom-control-input" id="manage_transaction">
                  <label class="custom-control-label" for="manage_transaction">{{__('Manage Transaction')}}</label>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Manage Blog" {{ $data->sectionCheck('Manage Blog') ? 'checked' : '' }} class="custom-control-input" id="manage_blog">
                  <label class="custom-control-label" for="manage_blog">{{__('Manage Blog')}}</label>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Support Ticket" {{ $data->sectionCheck('Support Ticket') ? 'checked' : '' }} class="custom-control-input" id="support_ticket">
                  <label class="custom-control-label" for="support_ticket">{{__('Support Ticket')}}</label>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Activities" {{ $data->sectionCheck('Activities') ? 'checked' : '' }} class="custom-control-input" id="activity">
                    <label class="custom-control-label" for="activity">{{__('Activities')}}</label>
                    </div>
                </div>
              </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Card Bank Accounts" {{ $data->sectionCheck('Card Bank Accounts') ? 'checked' : '' }} class="custom-control-input" id="card_bank">
                  <label class="custom-control-label" for="card_bank">{{__('Card & Bank Accounts')}}</label>
                  </div>
              </div>
            </div>

              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="General Settings" {{ $data->sectionCheck('General Settings') ? 'checked' : '' }} class="custom-control-input" id="general_setting">
                    <label class="custom-control-label" for="general_setting">{{__('General Settings')}}</label>
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Home Page Settings" {{ $data->sectionCheck('Home Page Settings') ? 'checked' : '' }} class="custom-control-input" id="home_page_setting">
                    <label class="custom-control-label" for="home_page_setting">{{__('Home Page Settings')}}</label>
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Menu Page Settings" {{ $data->sectionCheck('Menu Page Settings') ? 'checked' : '' }} class="custom-control-input" id="menu_page_setting">
                    <label class="custom-control-label" for="menu_page_setting">{{__('Menu Page Settings')}}</label>
                    </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Payment Settings" {{ $data->sectionCheck('Payment Settings') ? 'checked' : '' }} class="custom-control-input" id="payment_setting">
                    <label class="custom-control-label" for="payment_setting">{{__('Payment Settings')}}</label>
                    </div>
                </div>
              </div>


              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Manage Roles" {{ $data->sectionCheck('Manage Roles') ? 'checked' : '' }} class="custom-control-input" id="roles">
                    <label class="custom-control-label" for="roles">{{__('Manage Roles')}}</label>
                    </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Customer List" {{ $data->sectionCheck('Customer List') ? 'checked' : '' }} class="custom-control-input" id="customer_list">
                    <label class="custom-control-label" for="customer_list">{{__('Customer List')}}</label>
                    </div>
                </div>
             </div>

              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Manage Staff" {{ $data->sectionCheck('Manage Staff') ? 'checked' : '' }} class="custom-control-input" id="staff">
                    <label class="custom-control-label" for="staff">{{__('Manage Staff')}}</label>
                    </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Subscribers" {{ $data->sectionCheck('Subscribers') ? 'checked' : '' }} class="custom-control-input" id="subscribers">
                    <label class="custom-control-label" for="subscribers">{{__('Subscribers')}}</label>
                    </div>
                </div>
              </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>

        </form>
      </div>
    </div>
  </div>

</div>
<!--Row-->
@endsection

@section('scripts')


@endsection 