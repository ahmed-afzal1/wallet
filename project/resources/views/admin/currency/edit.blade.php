@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Currency')}}
        <a href="{{ route('admin-currency-index') }}" class="btn back-btn btn-sm btn-info"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
    </h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-currency-index')}}" class="text-muted">{{__('Currency')}}</a></li>
    </ol>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-6">
      <!-- Form Basic -->
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Currency Form') }}</h6>
        </div>
  
        <div class="card-body">
          <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
          <form class="geniusform" action="{{route('admin-currency-update',$currencydata->id)}}" method="POST" enctype="multipart/form-data">
  
              @include('includes.admin.form-both')
  
              {{ csrf_field() }}
  
              <div class="form-group">
                  <label for="inp-name">{{ __('Name') }}</label>
                  <input type="text" class="form-control" id="inp-name" name="name"  placeholder="{{ __('Enter Name') }}" value="{{$currencydata->name}}">
              </div>

              <div class="form-group">
                <label for="inp-name">{{ __('Category') }}</label>
                <select class="form-control mb-3" name="country_id" required>
                    <option disabled selected>-- Select Country --</option>
                    @forelse($countries as $val)
                        <option {{($val->id == $currencydata->country_id)?'selected':''}} value="{{$val->id}}">{{$val->name}}</option>
                    @empty
                    @endforelse
                </select>
              </div>
  
              <div class="form-group">
                  <label for="inp-Code">{{ __('Code') }}</label>
                  <input type="text" class="form-control" id="inp-Code" name="code"  placeholder="{{ __('Enter Code') }}" value="{{$currencydata->code}}" required>
              </div>

              <div class="form-group">
                <label for="inp-Sign">{{ __('Sign') }}</label>
                <input type="text" class="form-control" id="inp-Sign" name="sign"  placeholder="{{ __('Enter Sign') }}" value="{{$currencydata->sign}}" required>
              </div>

              <div class="form-group">
                <label for="inp-Rate">{{ __('Rate') }}</label>
                <input type="text" class="form-control" id="inp-Rate" name="rate"  placeholder="{{ __('Enter Rate') }}" name="rate" value="{{$currencydata->rate}}"  required>
              </div>
  
              <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>
  
          </form>
        </div>
      </div>
    </div>
  </div>






  @endsection
