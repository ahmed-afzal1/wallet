<form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
    @include('includes.admin.form-both')

    {{ csrf_field() }}

    <div class="form-row">
        <div class="form-group">
            <label for="copyright">{{ __('Copyright Text ') }}</label>
            <textarea class="form-control summernote"  id="copyright" required name="copyright" rows="3" placeholder="{{__('Copyright')}}">{{$gs->copyright}}</textarea>
        </div>


        <div class="form-group">
            <label for="footer_text">{{ __('Footer Text ') }}</label>
            <textarea class="form-control summernote"  id="footer_text" required name="footer_text"  rows="3" placeholder="{{__('Footer_Text')}}">{{$gs->footer_text}}</textarea>
        </div>
    </div>
    <button type="submit" id="submit-btn" class="btn btn-primary d-block mx-auto mt-2">{{ __('Submit') }}</button>
</form>