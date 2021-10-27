<form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
    @include('includes.admin.form-both')

    {{ csrf_field() }}
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="stitle">{{__("Site Title")}}</label>
            <input type="text" name="title"  required  value="{{$gs->title}}" class="form-control" id="stitle">
        </div>

        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">{{__("Email address")}}</label>
            <input type="email" name="email" required  class="form-control" value="{{$gs->email}}" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>

        <div class="form-group col-md-6">
            <label for="exampleInputPassword1">{{__("Phone")}}</label>
            <input type="text" name="phone"  required  class="form-control allowIntiger" value="{{$gs->phone}}"  id="exampleInputPassword1">
        </div>

        <div class="form-group col-md-6">
            <label for="exampleInputPassword1">{{__("Theme Color")}}</label>
            <input type="color" name="colors"  required  class="form-control"  value="{{$gs->colors}}" id="exampleInputPassword1">
        </div>

        <div class="form-group col-md-6">
            <label for="exampleInputPassword1">{{__("Address")}}</label>
            <input type="text" name="address"  required  class="form-control"  value="{{$gs->address}}" id="exampleInputPassword1">
        </div>

        <div class="form-group col-md-5 verify">
            <label for="verify">{{__("Sign Up Verification")}}</label>
            <div class="btn-group mb-1">
                <button type="button" class="btn btn-sm btn-rounded dropdown-toggle btn-{{ $gs->is_verification_email == 1 ? 'success' : 'danger' }}" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    {{ $gs->is_verification_email == 1 ? __('Activated') : __('Deactivated')}}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item drop-change" href="javascript:;" data-status="1" data-val="{{ __('Activated') }}" data-href="{{ route('admin-gs-status',['is_verification_email',1]) }}">{{ __('Activate') }}</a>
                    <a class="dropdown-item drop-change" href="javascript:;" data-status="0" data-val="{{ __('Deactivated') }}" data-href="{{ route('admin-gs-status',['is_verification_email',0]) }}">{{ __('Deactivate') }}</a>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" id="submit-btn" class="btn btn-primary d-block mx-auto mt-4">{{ __('Submit') }}</button>
</form>