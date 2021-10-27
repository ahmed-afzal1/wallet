@extends('layouts.admin')

@section('content')


<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ __('Slider') }}
        <a href="{{ route('admin.slider') }}" class="btn back-btn btn-sm btn-info"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
      </h1>
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Add New Slider') }}</li>
      </ol>
    </div>


    <!-- Row -->
    <div class="row">
      <!-- Datatables -->
      <div class="col-lg-12">
        <div class="card mb-4 p-2">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          </div>
          <div class="row">
              <div class="col-lg-12">
                <form class="geniusform" action="{{route('admin-sl-store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}

                    @include('includes.admin.form-both')


                        <div class="panel panel-default slider-panel">
                                  <div class="panel-heading text-center"><h3>Sub Title</h3></div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label class="control-label" for="subtitle_text">Text*</label>

                                        <textarea class="form-control" name="subtitle_text" id="subtitle_text" rows="5"  placeholder="Enter Title Text"></textarea>
                                      </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <div class="row">
                                            <div class="col-sm-4">
                                            <label class="control-label" for="subtitle_size">Font Size *<span> (px)</span></label>
                                            <input class="form-control" type="number" name="subtitle_size" value="" min="1">
                                          </div>
                                          <div class="form-group col-sm-4">
                                              <label for="exampleInputPassword1">{{__("Font Color")}}</label>
                                              <input type="color" name="subtitle_color"  required  class="form-control"  value="{{$gs->color}}" id="exampleInputPassword1">
                                          </div>
                                          <div class="col-sm-4">
                                              <label class="control-label" for="subtitle_anime">Animation *</label>
                                                <select class="form-control" id="subtitle_anime" name="subtitle_anime">
                                                      <option value="fadeIn">fadeIn</option>
                                                      <option value="fadeInDown">fadeInDown</option>
                                                      <option value="fadeInLeft">fadeInLeft</option>
                                                      <option value="fadeInRight">fadeInRight</option>
                                                      <option value="fadeInUp">fadeInUp</option>
                                                      <option value="flip">flip</option>
                                                      <option value="flipInX">flipInX</option>
                                                      <option value="flipInY">flipInY</option>
                                                      <option value="slideInUp">slideInUp</option>
                                                      <option value="slideInDown">slideInDown</option>
                                                      <option value="slideInLeft">slideInLeft</option>
                                                      <option value="slideInRight">slideInRight</option>
                                                      <option value="rollIn">rollIn</option>
                                                </select>
                                          </div>
                                          </div>

                                      </div>
                                    </div>
                                  </div>
                          </div>
                        <br>


                                  <div class="panel panel-default slider-panel">
                                            <div class="panel-heading text-center"><h3>Title</h3></div>
                                            <div class="panel-body">
                                          <div class="form-group">
                                              <div class="col-sm-12">
                                                <label class="control-label" for="title_text">Text*</label>

                                              <textarea class="form-control" name="title_text" id="title_text" rows="5"  placeholder="Enter Title Text"></textarea>
                                            </div>
                                          </div>


                                          <div class="form-group">
                                              <div class="col-sm-12">
                                               <div class="row">
                                                  <div class="col-sm-4">
                                                  <label class="control-label" for="title_size">Font Size *<span> (px)</span></label>
                                                  <input class="form-control" type="number" name="title_size" value="" min="1">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="exampleInputPassword1">{{__("Font Color")}}</label>
                                                    <input type="color" name="title_color"  required  class="form-control"  value="{{$gs->color}}" id="exampleInputPassword1">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="title_anime">Animation *</label>
                                                      <select class="form-control" id="title_anime" name="title_anime">
                                                            <option value="fadeIn">fadeIn</option>
                                                            <option value="fadeInDown">fadeInDown</option>
                                                            <option value="fadeInLeft">fadeInLeft</option>
                                                            <option value="fadeInRight">fadeInRight</option>
                                                            <option value="fadeInUp">fadeInUp</option>
                                                            <option value="flip">flip</option>
                                                            <option value="flipInX">flipInX</option>
                                                            <option value="flipInY">flipInY</option>
                                                            <option value="slideInUp">slideInUp</option>
                                                            <option value="slideInDown">slideInDown</option>
                                                            <option value="slideInLeft">slideInLeft</option>
                                                            <option value="slideInRight">slideInRight</option>
                                                            <option value="rollIn">rollIn</option>
                                                      </select>
                                                </div>
                                               </div>

                                            </div>
                                          </div>
                                    </div>
                                    </div>
                                  <br>


                                  <div class="panel panel-default slider-panel">
                                            <div class="panel-heading text-center"><h3>Description</h3></div>
                                            <div class="panel-body">
                                          <div class="form-group">
                                              <div class="col-sm-12">
                                                <label class="control-label" for="details_text">Text*</label>

                                              <textarea class="form-control" name="details_text" id="details_text" rows="5"  placeholder="Enter Title Text"></textarea>
                                            </div>
                                          </div>


                                          <div class="form-group">
                                              <div class="col-sm-12">
                                               <div class="row">
                                                  <div class="col-sm-4">
                                                  <label class="control-label" for="details_size">Font Size *<span> (px)</span></label>
                                                  <input class="form-control" type="number" name="details_size" value="" min="1">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="exampleInputPassword1">{{__("Font Color")}}</label>
                                                    <input type="color" name="details_color"  required  class="form-control"  value="{{$gs->color}}" id="exampleInputPassword1">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="details_anime">Animation *</label>
                                                      <select class="form-control" id="details_anime" name="details_anime">
                                                            <option value="fadeIn">fadeIn</option>
                                                            <option value="fadeInDown">fadeInDown</option>
                                                            <option value="fadeInLeft">fadeInLeft</option>
                                                            <option value="fadeInRight">fadeInRight</option>
                                                            <option value="fadeInUp">fadeInUp</option>
                                                            <option value="flip">flip</option>
                                                            <option value="flipInX">flipInX</option>
                                                            <option value="flipInY">flipInY</option>
                                                            <option value="slideInUp">slideInUp</option>
                                                            <option value="slideInDown">slideInDown</option>
                                                            <option value="slideInLeft">slideInLeft</option>
                                                            <option value="slideInRight">slideInRight</option>
                                                            <option value="rollIn">rollIn</option>
                                                      </select>
                                                </div>
                                               </div>

                                            </div>
                                          </div>
                                    </div>
                                    </div>

                              <br> <br>   {{-- Title Section Ends--}}



                              <div class="form-group " id="set-picture">
                                <div class="col-md-2 mx-auto">
                                  <label>{{ __('Set Picture') }} <small class="small-font">({{ __('Preferred Size 600 X 600') }})</small></label>
                                  <div class="wrapper-image-preview">
                                      <div class="box">
                                          <div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
                                          <div class="upload-options">
                                              <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                                              <input id="img-upload" type="file" class="image-upload" name="photo" accept="image/*">
                                          </div>
                                      </div>
                                  </div>
                                </div>
                            </div>

                  <br><br>

                  <div class="form-group row">
                      <label  class="col-sm-2 text-center">{{ __("Link") }} <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                          <input type="text" class="input-field form-control " name="link" placeholder="link" required="" value="">
                      </div>
                  </div>

                    <div class="form-group row">
                        <label  class="col-sm-2 text-center">{{ __("Link") }} <span class="text-danger">*</span></label>
                      <div class="col-lg-9">
                          <select  name="position" required="" class="form-control">
                              <option value="">Select Position</option>
                              <option value="slide-one">Left</option>
                              <option value="slide-two">Center</option>
                              <option value="slide-three">Right</option>
                            </select>
                      </div>
                    </div>
                    <br>

                    <div class="row">
                      <div class="col-lg-4">
                        <div class="left-area">

                        </div>
                      </div>
                      <div class="col-lg-7">
                        <button class="addProductSubmit-btn btn btn-primary" type="submit">Create Slider</button>
                      </div>
                    </div>
                    <br>
                </form>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @endsection

@section('script')

@endsection
