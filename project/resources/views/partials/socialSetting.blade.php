    <form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
        @include('includes.admin.form-both')

        {{ csrf_field() }}
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleInputPassword1">{{__("Facebook")}}</label>
                <input type="text" name="sociallink[facebook]" class="form-control"  value="{{$gs->sociallink != NULL ? $gs->sociallink->facebook : ''}}"  id="exampleInputPassword1">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputPassword1">{{__("Twitter")}}</label>
                <input type="text" name="sociallink[twitter]" class="form-control"  value="{{$gs->sociallink != NULL ? $gs->sociallink->twitter : ''}}"  id="exampleInputPassword1">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputPassword1">{{__("Linkedin")}}</label>
                <input type="text" name="sociallink[linkedin]" class="form-control"   value="{{$gs->sociallink != NULL ? $gs->sociallink->linkedin : ''}}"  id="exampleInputPassword1">
            </div>
        </div>
        <button type="submit" id="submit-btn" class="btn btn-primary ml-5">{{ __('Submit') }}</button>
    </form>