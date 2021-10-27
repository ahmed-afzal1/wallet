@extends('layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Language Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-localization-settings')}}" class="text-muted">{{__('Language')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;" class="text-muted">{{$language->language_code}}</a></li>
    </ol>
</div>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="d-inline-block">{{__("Language Edit")}} <small class=" ml-3 btn btn-warning btn-sm">{{__("Total Field")}}: <span class="badge badge-info p-1" id="totallangfield">0</span> </small></h5>
                <input type="text"  value="" placeholder="Search a specific key" id="datasearch" onkeyup="searchdata()" class="float-right rounded p-1">
            </div>
            <div class="card-body" id="contentbox">
                <form class="geniusform" action="{{route("admin-language-content-update")}}" method="post">
                    @include('includes.admin.form-both')

                    @csrf 
                    <input type="hidden" name="language_code" value="{{$language->language_code}}">
                    <div class="form-row language-form-data">
                        @forelse ($langkey as $key => $value)
                            <div class="form-group col-sm-6 addLangNew item">
                                <label for="inputEmail4" class="w-100">{{$key}} 
                                    <span class="text-danger">*</span> <span class="keytaken text-danger pl-2"></span>
                                    @if($language->language_code == 'en')
                                        
                                    @endif
                                </label>
                                <input type="text" name="language[]" required class="form-control languageinput" value="{{(array_key_exists($key,$langList))?$langList[$key]:$key}}">
                            </div>
                        @empty
                            @if ($language->language_code == 'en')
                                <div class="form-group col-sm-6 addLangNew item">
                                    <label for="inputEmail4" class="w-100">
                                        <span class="newfieldcontent"> Enter value </span> 
                                        <span class="text-danger">*</span>
                                        <span class="keytaken text-danger pl-2"></span>
                                        <span class="btn btn-danger btn-sm float-right cross-box">&#10006;</span>
                                    </label> 
                                    <input type="text" name="language[]" required class="form-control addnewlanginput languageinput" placeholder="Enter value"/>
                                </div>
                            @endif
                        @endforelse
                    </div>
                    <div class="form-row">
                        @if($language->language_code == 'en')
                        @endif
                        <div>
                            <button type="submit"   id="update-lang-content" class="btn btn-primary btn-sm"> {{__("Submit")}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
    <script>
        'use strict';
        $(document).on('keyup','.languageinput',function(){
            let formData = {};
            let exalert = 0;
            let fieldval = null;
            $("input[name^='language[']").each(function() {
                fieldval = $(this).val().trim();
                if(formData.hasOwnProperty(fieldval)){
                    exalert = 1;
                }else{
                    $(this).parent().find('.keytaken').html('');
                    formData[fieldval] = fieldval;
                }
            });
            if(exalert === 1){
                $(this).parent().find('.keytaken').html('This key allready taken.');
            }else{
                $(this).parent().find('.keytaken').html('');
            }
        });


        function searchdata() {
            let input, filter, item, contenttable, fieldValue, i;
            input = document.getElementById("datasearch");
            filter = input.value.toUpperCase();
            contenttable = document.getElementById("contentbox");
            item = contenttable.getElementsByClassName("item");
                for (i = 0; i < item.length; i++) {
                    fieldValue = item[i].getElementsByTagName("input")[0].value;
                    fieldValue = fieldValue.trim();
                    if (fieldValue.toUpperCase().indexOf(filter) > -1) {
                        item[i].style.display = "";
                    } else {
                        item[i].style.display = "none";
                    }
                }
            }

            let contenttable = document.getElementById("contentbox");
            let item = contenttable.getElementsByClassName("item");
            let totallangfield = item.length;
            $("#totallangfield").html(totallangfield);  
    </script>
@endsection