@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Information Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-theme-settings')}}" class="text-muted">{{__('Theme Settings')}}</a></li>
    </ol>
</div>




<div class="row mb-3">
    <div class="col-xl-3">
        <ul id="tabsJustified" class="nav nav-pills flex-column pos-none">
            <li class="nav-item"><a href="javascript:;" data-target="#logo" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase active"><i class="far fa-images"></i> {{__('Logo & Favicon')}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#loader" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase"><i class="far fa-images"></i> {{__('Loader')}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#breadcamp" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase "><i class="far fa-images"></i> {{__('Breadcamp Banner')}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#siteinfo" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase "><i class="fas fa-desktop"></i> {{__("Site Info")}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#transactioninfo" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase "><i class="fas fa-money-check-alt"></i> {{__("Transaction")}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#footer" data-toggle="tab" class="nav-link small  rounded-sm text-uppercase "><i class="fas fa-desktop"></i> {{__("Footer Section")}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#email-settings" data-toggle="tab" class="nav-link small rounded-sm  text-uppercase"><i class="fas fa-envelope"></i> {{__("Email Configuration")}}</a></li>
            <li class="nav-item"><a href="javascript:;" data-target="#social-settings" data-toggle="tab" class="nav-link small rounded-sm  text-uppercase"><i class="fas fa-street-view"></i> {{__("Social Settings")}}</a></li>
        </ul>
    </div>
    <div class="col-xl-9">
            <div class="tab-content border rounded p-3 w-100">
                <div id="logo" class="tab-pane fade active show">
                    <h5>{{__("Update Logo & Favicon")}}</h5>
                    <hr>

                    @includeIf('partials.logoFavicon')
                </div>


                <div id="loader" class="tab-pane fade">
                    <h5>{{__("Update Loader")}}</h5>
                    <hr>
                    @includeIf('partials.loader')
                </div>

            <div id="breadcamp" class="tab-pane fade">
                <h5>{{__("Update Breadcamp Banner")}}</h5>
                <hr>
                @includeIf('partials.breadcumb')
            </div>


            <div id="siteinfo" class="tab-pane fade">
                <h5>{{__("Update Site Info")}}</h5>
                <hr>
                @includeIf('partials.siteInfo')
            </div>


            <div id="transactioninfo" class="tab-pane fade">
                <h5>{{__("Update Transaction Info")}}</h5>
                <hr>
                @includeIf('partials.transactionInfo')
            </div>


                 <div id="footer" class="tab-pane fade">
                    <h5>{{__("Update Footer Info")}}</h5>
                    <hr>
                    @includeIf('partials.footerInfo')
                </div>



                <div id="email-settings" class="tab-pane fade">
                    <h5>{{__("Email Configuration")}}</h5>
                    <hr>
                    @includeIf('partials.emailConfig')
                </div>
                <div id="social-settings" class="tab-pane fade">
                    <h5>{{__("Social Settings")}}</h5>
                    <hr>
                    @includeIf('partials.socialSetting')
                </div>

            </div>

    </div>
</div>
@endsection

@section('scripts')

@endsection
