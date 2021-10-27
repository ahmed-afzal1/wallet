@extends('layouts.front')


@section('content')

  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area" style="background: url({{ $gs->breadcamp ? asset('assets/images/'.$gs->breadcamp):asset('assets/images/noimage.png') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="pagetitle">
            {{ __('Frequently Asked Question')}}
          </h1>

          <ul class="pages">
              <li>
                <a href="{{ route('home') }}">
                  {{__('Home')}}
                </a>
              </li>
              <li>
                <a href="{{ route('front.faq') }}">
                  {{ __('Faq')}}
                </a>
              </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->

  <!-- faq Area Start -->
  <section class="faq-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div id="accordion">

            @foreach($faqs as $fq)
            <h2 class="heading">{{ $fq->title }}</h2>
            <div class="content ">
                <p>{!! $fq->details !!}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- faq Area End-->

@endsection
