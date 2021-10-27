@extends('layouts.admin')

@section('content')

<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between">
  <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Home Page Customization') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin-ps-customize') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin-ps-customize') }}">{{ __('Home Page Customization') }}</a></li>

  </ol>
  </div>
</div>

<div class="row justify-content-center mt-3">
<div class="col-lg-6">
  <!-- Form Basic -->
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('Home page setting') }}</h6>
    </div>

    <div class="card-body">
      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
      <form class="geniusform" action="{{ route('admin-ps-homeupdate') }}" method="POST" enctype="multipart/form-data">

        @include('includes.admin.form-both')

        {{ csrf_field() }}

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="slider" value="1"{{$data->slider==1?"checked":""}} class="custom-control-input" id="manage_slider">
                <label class="custom-control-label" for="manage_slider">{{__('Sliders *')}}</label>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="featured" value="1"{{$data->featured==1?"checked":""}} class="custom-control-input" id="manage_feature">
                <label class="custom-control-label" for="manage_feature">{{__('Featuerd *')}}</label>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="contact_section" value="1" {{$data->contact_section==1?"checked":""}} class="custom-control-input" id="contact">
                <label class="custom-control-label" for="contact">{{__('Contact Section *')}}</label>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="project_section" value="1" {{$data->project_section==1?"checked":""}} class="custom-control-input" id="manage_project">
                <label class="custom-control-label" for="manage_project">{{__('Project Section *')}}</label>
                </div>
            </div>
          </div>

          <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="review_section" value="1" {{$data->review_section==1?"checked":""}} class="custom-control-input" id="manage_review">
                  <label class="custom-control-label" for="manage_review">{{__('Review Section *')}}</label>
                  </div>
              </div>
            </div>

          <div class="col-md-6">
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="blog_section" value="1" {{$data->blog_section==1?"checked":""}}  class="custom-control-input" id="manage_blog">
                <label class="custom-control-label" for="manage_blog">{{__('Blog Section *')}}</label>
                </div>
            </div>
          </div>
          


        </div>
        <hr>

        <button type="submit" id="submit-btn" class="btn btn-primary">{{ __('Submit') }}</button>

    </form>
    </div>
  </div>

  <!-- Form Sizing -->

  <!-- Horizontal Form -->

</div>

</div>
<!--Row-->
@endsection























{{-- @extends('backend.includes.admin.app')

@section('content')

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading">Home Page Customization</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard </a>
                      </li>
                      <li>
                        <a href="javascript:;">Home Page Settings</a>
                      </li>
                      <li>
                        <a href="{{ route('admin-ps-customize') }}">Home Page Customization</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="social-links-area">
                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                        <form id="geniusform" action="{{ route('admin-ps-homeupdate') }}" method="POST" enctype="multipart/form-data">
                          {{ csrf_field() }}

                        @include('includes.admin.form-both')


                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="slider">Sliders *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="slider" value="1" {{$data->slider==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="service">Featured Services *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="service" value="1" {{$data->service==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>

                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="featured">Welcome Informations *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="featured" value="1" {{$data->featured==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="small_banner">Experience Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="small_banner" value="1" {{$data->small_banner==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>

                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="best">Feature Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="best" value="1" {{$data->best==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="contact_section">Contact Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="contact_section" value="1" {{$data->contact_section == 1 ? "checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
              </div>

                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="pricing_plan">Pricing Plan *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="pricing_plan" value="1" {{$data->pricing_plan == 1 ? "checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="top_rated">Project Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="top_rated" value="1" {{$data->top_rated==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                </div>
                </div>

                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="large_banner">Video Presentation Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="large_banner" value="1" {{$data->large_banner==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="big">Team Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="big" value="1" {{$data->big==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>

                <div class="row justify-content-center">
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="hot_sale">Review section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="hot_sale" value="1" {{$data->hot_sale==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="review_blog">Latest Blog Section *</label>
                    <label class="switch float-right">
                      <input type="checkbox" name="review_blog" value="1" {{$data->review_blog==1?"checked":""}}>
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>

                <br>

                <div class="row">
                  <div class="col-12 text-center">
                    <button type="submit" class="submit-btn">Submit</button>
                  </div>
                </div>

                     </form>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection --}}
