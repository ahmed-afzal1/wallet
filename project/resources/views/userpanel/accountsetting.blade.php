@extends('layouts.user') 

@section('content')

        <div class="account-box">
            <div class="header-area">
                <h4 class="title">
                    {{__("Account Setting")}} 
                </h4>
              </div>
            <p><span class="text-danger">*</span> <small>{{__('Marked are Required')}}</small></p>
            <form id="formdata2" action="{{route('user-account-setting')}}"  method="POST" >
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Language')}}<span class="text-danger">*</span></label>
                            <select name="language" class="form-control" required>
                                @if($language != null)
                                    @foreach ($language as $value)
                                        <option value="{{$value->id}}" {{($userinfo->language == $value->id)?'selected':''}}>{{__($value->languageInfo->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Password')}}<span class="text-danger">*</span></label>
                            <input type="password" class="form-control"  name="password" required  id="exampleInputEmail1">
                        </div>
                    </div>
                </div>
                <div class="submit-btn form-group">
                    <button type="submit" class="btn btn-primary btn-sm">{{__("Change")}}</button>
                </div>
              </form>
        </div>



@endsection