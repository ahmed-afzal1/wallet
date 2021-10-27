<div class="row mx-auto">
    <div class="col-md-4 col-lg-4">
        <form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
                <label for="logo" class="">{{__("Logo")}} <span class="text-danger">*</span> </label>
                <div class="wrapper-image-preview-settings">
                    <div class="box-settings">
                        <div class="back-preview-image" style="background-image: url({{ $gs->logo ? asset('assets/images/'.$gs->logo):asset('assets/images/placeholder.jpg') }});"></div>
                        <div class="upload-options-settings">
                            <label class="img-upload-label" for="img-upload">
                                 <i class="fas fa-camera"></i> {{ __('Upload Picture') }}
                                 <br>
                                 <small class="small-font">{{ __('600 X 600') }}</small>
                            </label>
                            <input id="img-upload" type="file" class="image-upload" name="logo" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary ml-5">{{ __('Submit') }}</button>
        </form>
    </div>

    <div class="col-md-4 col-lg-4">
        <form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
                <p><label for="footer_logo" class="">{{__("Footer Logo")}} <span class="text-danger">*</span> </label></p>
                <div class="wrapper-image-preview-settings">
                    <div class="box-settings">
                        <div class="back-preview-image" style="background-image: url({{ $gs->footer_logo ? asset('assets/images/'.$gs->footer_logo):asset('assets/images/placeholder.jpg') }});"></div>
                        <div class="upload-options-settings">
                            <label class="img-upload-label" for="img-upload-2">
                                 <i class="fas fa-camera"></i> {{ __('Upload Picture') }}
                                 <br>
                                 <small class="small-font">{{ __('600 X 600') }}</small>
                            </label>
                            <input id="img-upload-2" type="file" class="image-upload" name="footer_logo" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary ml-5">{{ __('Submit') }}</button>
        </form>
    </div>

    <div class="col-md-4 col-lg-4">
        <form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
                <p><label for="footer_logo" class="">{{__("Favicon")}} <span class="text-danger">*</span> </label></p>
                <div class="wrapper-image-preview-settings">
                    <div class="box-settings">
                        <div class="back-preview-image" style="background-image: url({{ $gs->favicon ? asset('assets/images/'.$gs->favicon):asset('assets/images/placeholder.jpg') }});"></div>
                        <div class="upload-options-settings">
                            <label class="img-upload-label" for="img-upload-3">
                                 <i class="fas fa-camera"></i> {{ __('Upload Picture') }}
                                 <br>
                                 <small class="small-font">{{ __('600 X 600') }}</small>
                            </label>
                            <input id="img-upload-3" type="file" class="image-upload" name="favicon" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary ml-5">{{ __('Submit') }}</button>
        </form>
    </div>
</div>