@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Payment Gateway')}}
        <a href="{{ route('admin.payment.index') }}" class="btn back-btn btn-sm btn-info"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
    </h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.payment.index')}}" class="text-muted">{{__('Payment Gateway')}}</a></li>
    </ol>
</div>


<div class="row mb-3">
    <div class="col-md-1"></div>
    <div class="col-md-10">

        <div class="card">
            <div class="card-body">
              <form class="geniusform" action="{{route('admin.payment.update',$data->id)}}" class="form-content-right" method="POST" enctype="multipart/form-data">
                  @csrf

              @include('includes.admin.form-both')
                    
              @if($data->type == 'automatic')

              <div class="form-group">
                <label for="Category">{{ __('Name') }}</label>
                <input type="text" class="form-control" name="name" value="{{$data->name}}" id="" placeholder="{{ __('Name') }}">
              </div>

              @if($data->information != null)
              @foreach($data->convertAutoData() as $pkey => $pdata)

              @if($pkey == 'sandbox_check')

              <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="customCheck1" name="pkey[{{ __($pkey) }}]" value="1" {{ $pdata == 1 ? "checked":"" }}>
                <label class="custom-control-label" for="customCheck1">{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</label>
              </div>

              @else
              <div class="form-group">
                <label for="">{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</label>
                <input type="text" class="form-control" name="pkey[{{ __($pkey) }}]" placeholder="{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }}" value="{{ $pdata }}" required="">
              </div>
              @endif
              @endforeach

              @endif
              @else
              <div class="form-group">
                <label for="Category">{{ __('Name') }}</label>
              <input type="text" class="form-control" name="title" value="{{$data->title}}" placeholder="{{ __('Name') }}">
              </div>
          
              @if($data->keyword == null || 'Manual')
              <div class="form-group">
                <label for="area1">{{ __('Description') }}</label>
                <textarea id="area1" class="form-control summernote" name="details">{{$data->details}}</textarea>
            </div>

            @endif
            @endif

                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
              </form>
          </div>
      </div>
  </div>
  
</div>




  @endsection
