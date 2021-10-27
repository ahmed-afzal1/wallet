<div class="row">
    <div class="card mb-4 mx-auto">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('BreadCamp') }}</h6>
        </div>

        <div class="card-body ">
            <div class="row justify-content-center">
                <form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
                    @include('includes.admin.form-both')

                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="wrapper-image-preview-settings">
                            <div class="box-settings">
                                <div class="back-preview-image" style="background-image: url({{ $gs->breadcamp ? asset('assets/images/'.$gs->breadcamp):asset('assets/images/placeholder.jpg') }});"></div>
                                <div class="upload-options-settings">
                                    <label class="img-upload-label" for="img-upload-6">
                                         <i class="fas fa-camera"></i> {{ __('Upload Picture') }}
                                         <br>
                                         <small class="small-font">{{ __('600 X 600') }}</small>
                                    </label>
                                    <input id="img-upload-6" type="file" class="image-upload" name="breadcamp" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn btn-primary ml-5">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>