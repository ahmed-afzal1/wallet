@extends('layouts.front')

@section('content')

@if($ps->slider == 1)
    @if(count($sliders))
        @include('includes.slider-style')
    @endif
@endif


@if($ps->slider == 1)
	<section class="hero-area">
		@if(count($sliders))
			<div class="hero-area-slider">
					@foreach($sliders as $data)
						<div class="intro-content {{$data->position}}" style="background-image: url({{asset('assets/images/'.$data->photo)}})">
							<div class="container">
								<div class="row">
									<div class="col-lg-12">
										<div class="slider-content">
											<!-- layer 1 -->
											<div class="layer-1">
												<h4 style="font-size: {{$data->subtitle_size}}px; color: {{$data->subtitle_color}}" class="subtitle subtitle{{$data->id}}" data-animation="animated {{$data->subtitle_anime}}">{{$data->subtitle_text}}</h4>
												<h2 style="font-size: {{$data->title_size}}px; color: {{$data->title_color}}" class="title title{{$data->id}}" data-animation="animated {{$data->title_anime}}">{{$data->title_text}}</h2>
											</div>
											<!-- layer 2 -->
											<div class="layer-2">
												<p style="font-size: {{$data->details_size}}px; color: {{$data->details_color}}"  class="text text{{$data->id}}" data-animation="animated {{$data->details_anime}}">{{$data->details_text}}</p>
											</div>
											<!-- layer 3 -->
											<div class="layer-3">
												<a href="{{$data->link}}" target="_blank" class="mybtn1"><span>Explore More <i class="fas fa-chevron-right"></i></span></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
			</div>
		@endif
	</section>
	<!-- Hero Area End -->
@endif


@if ($ps->featured == 1)
    <!-- Features Area Start-->
    <section class="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="feature-area">
                        <div class="row">
                            @foreach($services as $service)
                            <div class="col-lg-4 col-md-6">
                                <a href="{{ route('front.service',$service->slug) }}" target="_blank" class="single-feature">
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/services/'.$service->photo) }}" alt="">
                                    </div>
                                    <div class="content">
                                        <h4 class="title">
                                            {{ $service->title }}
                                        </h4>
                                        <p class="text">
                                            {{ $service->subtitle }}
                                        </p>
                                        <span class="link">
                                            <i class="fas fa-angle-double-right"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Area End-->
@endif

@if($ps->contact_section == 1)

<!-- Submit Address Area Start -->
<div class="submit-address"  style="background: url({{ asset('assets/images/'.$ps->c_background) }});">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-md-7">
					<h4 class="title">
						{{ $ps->c_title }}
					</h4>
				</div>
				<div class="col-lg-5 col-md-5 d-flex align-self-center j-end">
					<a href="{{ route('front.contact') }}" class="mybtn1">{{ __('Contact Now') }}</a>
				</div>
			</div>
		</div>
	</div>
@endif


{{-- Faq Area End --}}

@if ($ps->project_section == 1)
    <!-- Package Gallery Area Start -->
    <section class="gallery">
        <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-10">
                        <div class="section-heading">
                            <h5 class="sub-title">
                                    {{$ps->portfolio_subtitle}}
                            </h5>
                            <h2 class="title">
                                {{$ps->portfolio_title}}
                            </h2>
                            <p class="text">
                               {{$ps->portfolio_text}}
                            </p>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="gallery-slider">
                        @foreach($portfolios as $data)
                            <div class="item">
                                <a href="{{ route('front.project',$data->slug) }}" class="single-project">
                                    <div class="img">
                                            <img src="{{ asset('assets/images/'.$data->photo) }}" alt="">
                                    </div>
                                    <div class="content">
                                        <p class="sub-title">
                                            {{ $data->title }}
                                        </p>
                                        <h4 class="title">
                                            {{ $data->subtitle }}
                                        </h4>
                                        <span class="link">
                                            <i class="fas fa-angle-double-right"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Package Gallery Area End -->
@endif

@if ($ps->review_section == 1)
    <section class="testimonial" style="background: url({{ asset('assets/images/'.$ps->review_background) }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">
                    <div class="section-heading color-white">
                        <h5 class="sub-title">
                            {{ $ps->review_subtitle }}
                        </h5>
                        <h2 class="title">
                            {{ $ps->review_title }}
                        </h2>
                        <p class="text">
                            {{ $ps->review_text }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="testimonial-slider">
                        @foreach($reviews as $review)
                            <div class="single-testimonial">
                                <div class="review-text">
                                    <p>{{ strip_tags($review->details) }}</p>
                                </div>
                                <div class="people">
                                    <div class="img">
                                            <img src="{{ asset('assets/images/'.$review->photo) }}" alt="">
                                    </div>
                                    <div class="content">
                                        <h4 class="title">{{ $review->title }}</h4>
                                        <p class="designation">{{  $review->subtitle }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif



@if($ps->blog_section == 1)
	<!-- Blog Area Start -->
	<section class="blog">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-7 col-md-10">
					<div class="section-heading">
						<h5 class="sub-title">
							{{ $ps->blog_subtitle }}
						</h5>
						<h2 class="title">
							{{ $ps->blog_title }}
						</h2>
						<p class="text">
							{{ $ps->blog_text }}
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="blog-slider">
                        @foreach($blogs as $blog)
                            <a href="{{ route('front.blogshow',$blog->id) }}" class="single-blog">
                                <div class="img">
                                    <img src="{{ asset('assets/images/'.$blog->photo) }}" alt="">
                                </div>

                                <div class="content">
                                    <h4 class="title">
                                        {{ $blog->title }}
                                    </h4>
                                    <ul class="top-meta">
                                        <li>
                                            <span>
                                                <i class="far fa-calendar"></i> {{  date('d M, Y',strtotime($blog->created_at)) }}
                                            </span>
                                        </li>
                                        <li>
                                            <span>
                                                    <i class="far fa-eye"></i> {{ $blog->views }}
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="text">
                                        <p>
                                            {{substr(strip_tags($blog->details),0,120)}}
                                        </p>
                                    </div>
                                    {{-- <span class="link">{{ $langg->lang55 }} <i class="fas fa-chevron-right"></i></span> --}}
                                </div>
                            </a>
                        @endforeach
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Blog Area End -->
@endif


@endsection
