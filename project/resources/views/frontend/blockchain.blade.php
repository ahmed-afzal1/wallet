@extends('layouts.front')

@section('content')

  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area" style="background: url({{ $gs->breadcumb_banner ? asset('assets/images/'.$gs->breadcumb_banner):asset('assets/images/noimage.png') }});">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="pagetitle">
            {{ ('Deposit') }}
          </h1>

          <ul class="pages">
                
              <li>
                <a href="{{ route('home') }}">
                  {{ __('Home') }}
                </a>
              </li>
              <li>

                  {{ __('BlockChain') }}

              </li>
              <li>

                  >> {{__('Deposit')}}

              </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->


<section class="order-details">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="order-details-box">
						<div class="header">
							<h4 class="title">
								{{--{{ $langg->lang31 }}--}}
								Bitcoin Invest Information
							</h4>
						</div>
						<div class="row justify-content-between px-4 py-5">
							<div class="col-lg-12">
								<div class="content">


									{{-- @include('includes.form-error')
									@include('includes.form-success') --}}

									<div class="panel-body text-center verify-success">
										<img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=bitcoin:{{ Session::get('address') }}?amount={{ Session::get('amount') }}">
										<br>
										<br>
										<h3 class="text-center">Address: {{ Session::get('address') }}</h3>
										<p>Please send approximately <b>{{ Session::get('amount') }}</b> BTC to this address. After completing your payment, <b>{{ Session::get('currency_sign') }}{{ Session::get('currency_value') }}</b> invest will be deposited. <br>This Process may take some time for confirmations. Thank you.</p>
										<h4><a href="javascript:history.back();"> Go Back</a></h4>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>




@endsection


@section('scripts')

<script type="text/javascript">

</script>


@endsection