@extends('layouts.front')
@section('content')


  <!-- Breadcrumb Area Start -->
  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area" style="background: url({{ $gs->breadcamp ? asset('assets/images/'.$gs->breadcamp):asset('assets/images/noimage.png') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="pagetitle">
            {{ __('Project Details')}}
          </h1>

          <ul class="pages">

              <li>
                <a href="{{ route('home') }}">
                  {{__('Home')}}
                </a>
              </li>
              <li>
                <a href="{{ route('home') }}">
                  {{ __('Projects')}}
                </a>
              </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->
  <!-- Breadcrumb Area End -->

<section class="about">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="about-info">
            <img class="projectimg" src="{{ asset('assets/images/portfolio/'.$ppage->photo1) }}">
            <p>
              {!! $ppage->details !!}
            </p>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection
