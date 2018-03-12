@extends('layouts.app')

@section('content')

<link href="{{ asset('css/login_page.css') }}" rel="stylesheet">

<div class="site-wrapper">

		<div class="site-wrapper-inner">
			<div class="cover-container">
				<div class="container">
				<div class="masthead clearfix">
					<div class="inner">
						<div class="row text-center">
							<div class="col-md-12">
								<h1 class="header">Movie Recommendation Site</h1>
							</div>
						</div>
					</div>
				</div>
				
				<div class="inner content cover">   
					<div class="row">

						<!-- LOGIN FORM -->
						<div class="col-md-6 col">
							<div class="col-md-12 form-div">

								<form class="loginForm" method="POST" action="{{ route('login') }}">
									{{ csrf_field() }}

									<!-- LOGIN LABEL -->
									<div class="row">
										<div class="col-md-12">
											<h2 class="text-center"> Log In </h2>
										</div>
									</div>

									<!-- NAME GROUP -->
									<div class="row">
										<div class="col-md-12 form-group {{ $errors->has('name') ? ' has-error' : '' }}">

											<input type="text" id="name"  class="box form-control" name="name"
														 value="{{ old('name') }}" placeholder="Username" required autofocus>

											@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										  @endif
					  					  <!-- <div class="col-md-4 red" id="userNameError"></div> -->
										</div>
									</div>

				  					<!-- PASSWORD GROUP -->
									<div class="row">   
										<div class="col-md-12 form-group {{ $errors->has('password') ? ' has-error' : '' }}">  

											<input type="password" name="password" id="password"
							 							 placeholder="Password" class="box form-control" required>

											@if ($errors->has('password'))
												<span class="help-block">
													<strong>{{ $errors->first('password') }}</strong>
												</span>
											@endif
										</div>
										<!-- <div class="col-md-8 red" id="passwordError"></div> -->
									</div>

				  					<!-- LOGIN BUTTON -->
									<div class="row">
										<div class="form-group col-md-12">  
											<button type="submit" class="btn btn-primary btn-default btn-lg">Submit</button>
					  						<!-- FORGOT PASSWORD LINK -->
											<a class="btn btn-link forgotPassword" href="{{ route('password.request') }}">Forgot Password</a>
										</div>
									</div>

								</form>
							</div>
						</div><!-- end login form -->


						<!-- REGISTRATION FORM -->
						<div class="col-md-6 col">
							<div class="col-md-12 form-div">
								<form class="registerForm" method="POST" action="{{ route('register') }}">
									{{ csrf_field() }}

									<!-- REGISTER LABEL -->
									<div class="row">
										<div class="col-md-12 middle">
											<h2 class="text-center"> Register </h2>
										</div>
									</div>


									<!-- NAME (REGISTRATION) -->
									<div class="row">
										<div class="form-group col-md-12 {{ $errors->has('name') ? ' has-error' : '' }}">

											<input type="text" name="name" placeholder="Username" id="name"
														 class="box form-control" value="{{ old('name') }}" required autofocus>

											@if ($errors->has('name'))
												<span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<!-- EMAIL (REGISTRATION) -->
									<div class="row">
										<div class="form-group col-md-12">

											<input type="email" placeholder="Email" name="email" id="email"
														 class="box form-control" value="{{ old('email') }}" required>

											@if ($errors->has('email'))
												<span class="help-block">
													<strong>{{ $errors->first('email') }}</strong>
												</span>
											@endif
											<!-- <div class="col-md-8 red" id="emailError"></div> -->
										</div>
									</div>

									
									<!-- PASSWORD (REGISTRATION) -->
									<div class="row">
										<div class="form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">

											<input type="password" name="password" placeholder="Password"
														 id="password" class="box form-control" required>

											@if ($errors->has('password'))
												<span class="help-block">
													<strong>{{ $errors->first('password') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<!-- CONFIRM PASSWORD (REGISTRATION) -->
									<div class="row">
										<div class="form-group col-md-12">

											<input type="password" name="password_confirmation" placeholder="Confirm Password"
														 id="password-confirm" class="box form-control" required>
										</div>
									</div>
										
									<!-- DOB (REGISTRATION) -->
									<div class="row">
										<div class="form-group col-md-12 {{ $errors->has('dob') ? ' has-error' : '' }}">      
											<input type="date" name="dob" id="dob" placeholder="Date of Birth"
														 class="box form-control" max="2017-12-08" value="{{ old('dob') }}">

											@if ($errors->has('dob'))
												<span class="help-block">
													<strong>{{ $errors->first('dob') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<!-- REGISTER BUTTON -->
									<div class="row">
										<div class="form-group col-md-12">  
											<button type="submit" class="btn btn-primary btn-default btn-lg">Register</button>
										</div>
									</div>

								</form>
							</div>
						</div><!-- end registration form -->

					</div>
				</div><!-- end form container -->

				<!-- FOOTER -->
				<div class="mastfoot">
					<div class="inner">
							<div class="container">
								<h3 class="footerText"> Andrew Dunne & Vitalijs Komarovs - 2018 </h3>
							</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

@endsection
