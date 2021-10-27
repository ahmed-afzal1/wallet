
        
      </div>
    </div>
  </section>
  <!-- Dashbord-content Area End -->
	<!-- Footer Area Start -->
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-4">
					<div class="footer-widget about-widget">
						<div class="footer-logo">
							<a href="{{route('home')}}">
								<img src="{{asset('assets/images/'.$gs->footer_logo)}}" class="w-100" alt="">
							</a>
						</div>
						<div class="text">
							{{$gs->footer_text}}
						</div>
						
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="footer-widget address-widget">
						<h4 class="title">
							{{__("Address")}}
						</h4>
						<ul class="about-info">
							<li>
								<p>
									<i class="fas fa-globe"></i>
									{{$gs->address}}								
								</p>
							</li>
							<li>
								<p>
									<i class="fas fa-phone"></i>
									{{$gs->phone}}
								</p>
							</li>
							<li>
								<p>
									<i class="far fa-envelope"></i>
									{{$gs->email}}
								</p>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
						<div class="footer-widget  footer-newsletter-widget">
							<h4 class="title">
								{{__("Newsletter")}}
							</h4>
							<div class="newsletter-form-area">
								<form id="simpleform" action="{{route('user-subscriber-create')}}" method="POST">
									@csrf
									<input type="email" name="subscriber" placeholder="Your email address...">
									<button type="submit" class="btn">
										<i class="far fa-paper-plane"></i>
									</button>
								</form>
							</div>
							<div class="social-links">
								<h4 class="title">
										{{__("We're social, connect with us")}}:
								</h4>
								<div class="fotter-social-links">

									<ul>
										<li>
											<a href="{{$gs->sociallink != NULL ? $gs->sociallink->facebook : ''}}" target="_blank" class="facebook">
												<i class="fab fa-facebook-f"></i>
											</a>
										</li>

										<li>
											<a href="{{$gs->sociallink != NULL ? $gs->sociallink->twitter : ''}}"  target="_blank" class="twitter">
												<i class="fab fa-twitter"></i>
											</a>
										</li>

										<li>
											<a href="{{$gs->sociallink != NULL ? $gs->sociallink->linkedin : ''}}"  target="_blank" class="linkedin">
												<i class="fab fa-linkedin-in"></i>
											</a>
										</li>
									</ul>
								</div>
							</div>
					
						</div>
				</div>
			</div>
		</div>
		<div class="copy-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="content">
							<div class="content">
								<p>{!!$gs->copyright!!}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer Area End -->
