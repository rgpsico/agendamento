<!DOCTYPE html> 
<html lang="en">
	<head>
		
		<meta charset="utf-8">
		<title>Doccure</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		
		<!-- Favicons -->
		<link href="{{asset('template/assets/img/favicon.png')}}" rel="icon">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/css/bootstrap.min.css')}}">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
		<link rel="stylesheet" href="{{asset('template/assets/plugins/fontawesome/css/all.min.css')}}">

		<!-- Feathericon CSS -->
    	<link rel="stylesheet" href="{{asset('template/assets/css/feather.css')}}">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/css/custom.css')}}">
	
	</head>
	<body class="account-page">

		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<header class="header header-fixed header-one">
				<div class="container">
					<nav class="navbar navbar-expand-lg header-nav">
						<div class="navbar-header">
							<a id="mobile_btn" href="{{route('home.index')}}">
								<span class="bar-icon">
									<span></span>
									<span></span>
									<span></span>
								</span>
							</a>
							<a href="index.html" class="navbar-brand logo">
								<img src="{{ asset('https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png')}}" class="img-fluid" alt="Logo">
							</a>
						</div>
						<div class="main-menu-wrapper">
							<div class="menu-header">
								<a href="index.html" class="menu-logo">
									<img src="{{asset('https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png')}}" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>
							<ul class="main-nav">
								<li class="has-submenu">
									<a href="">Home <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li ><a href="index.html">Home</a></li>
										<li><a href="index-2.html">Home 2</a></li>
										<li><a href="index-3.html">Home 3</a></li>
										<li><a href="index-4.html">Home 4</a></li>
										<li><a href="index-5.html">Home 5</a></li>
										<li><a href="index-6.html">Home 6</a></li>
										<li><a href="index-7.html">Home 7</a></li>
										<li><a href="index-8.html">Home 8</a></li>
										<li><a href="index-9.html">Home 9</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="javascript:void(0);">Doctors <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="doctor-dashboard.html">Doctor Dashboard</a></li>
										<li><a href="appointments.html">Appointments</a></li>
										<li><a href="schedule-timings.html">Schedule Timing</a></li>
										<li><a href="my-patients.html">Patients List</a></li>
										<li><a href="patient-profile.html">Patients Profile</a></li>
										<li><a href="chat-doctor.html">Chat</a></li>
										<li><a href="invoices.html">Invoices</a></li>
										<li><a href="doctor-profile-settings.html">Profile Settings</a></li>
										<li><a href="reviews.html">Reviews</a></li>
										<li><a href="doctor-register.html">Doctor Register</a></li>
										<li class="has-submenu">
											<a href="doctor-blog.html">Blog</a>
											<ul class="submenu">
												<li><a href="doctor-blog.html">Blog</a></li>
												<li><a href="blog-details.html">Blog view</a></li>
												<li><a href="doctor-add-blog.html">Add Blog</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="javascript:void(0);">Patients <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li class="has-submenu">
											<a href="javascript:void(0);">Doctors</a>
											<ul class="submenu inner-submenu">
												<li><a href="map-grid.html">Map Grid</a></li>
												<li><a href="map-list.html">Map List</a></li>
											</ul>
										</li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Search Doctor</a>
											<ul class="submenu inner-submenu">
												<li><a href="search.html">Search Doctor 1</a></li>
												<li><a href="search-2.html">Search Doctor 2</a></li>
											</ul>
										</li>
										<li><a href="doctor-profile.html">Doctor Profile</a></li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Booking</a>
											<ul class="submenu inner-submenu">
												<li><a href="booking.html">Booking 1</a></li>
												<li><a href="booking-2.html">Booking 2</a></li>
											</ul>
										</li>
										<li><a href="checkout.html">Checkout</a></li>
										<li><a href="booking-success.html">Booking Success</a></li>
										<li><a href="patient-dashboard.html">Patient Dashboard</a></li>
										<li><a href="favourites.html">Favourites</a></li>
										<li><a href="chat.html">Chat</a></li>
										<li><a href="profile-settings.html">Profile Settings</a></li>
										<li><a href="change-password.html">Change Password</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="">Pharmacy <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="pharmacy-index.html">Pharmacy</a></li>
										<li><a href="pharmacy-details.html">Pharmacy Details</a></li>
										<li><a href="pharmacy-search.html">Pharmacy Search</a></li>
										<li><a href="product-all.html">Product</a></li>
										<li><a href="product-description.html">Product Description</a></li>
										<li><a href="cart.html">Cart</a></li>
										<li><a href="product-checkout.html">Product Checkout</a></li>
										<li><a href="payment-success.html">Payment Success</a></li>
										<li><a href="pharmacy-register.html">Pharmacy Register</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="javascript:void(0);">Pages <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="about-us.html">About Us</a></li>
										<li><a href="contact-us.html">Contact Us</a></li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Call</a>
											<ul class="submenu inner-submenu">
												<li><a href="voice-call.html">Voice Call</a></li>
												<li><a href="video-call.html">Video Call</a></li>
											</ul>
										</li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Invoices</a>
											<ul class="submenu inner-submenu">
												<li><a href="invoices.html">Invoices</a></li>
												<li><a href="invoice-view.html">Invoice View</a></li>
											</ul>
										</li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Authentication</a>
											<ul class="submenu inner-submenu">
												<li><a href="login-email.html">Login Email</a></li>
												<li><a href="login-phone.html">Login Phone</a></li>
												<li><a href="doctor-signup.html">Doctor Signup</a></li>
												<li><a href="patient-signup.html">Patient Signup</a></li>
												<li><a href="forgot-password.html">Forgot Password 1</a></li>
												<li><a href="forgot-password2.html">Forgot Password 2</a></li>
												<li><a href="login-email-otp.html">Email OTP</a></li>
												<li><a href="login-phone-otp.html">Phone OTP</a></li>
											</ul>
										</li>
										<li class="has-submenu">
											<a href="javascript:void(0);">Error Pages</a>
											<ul class="submenu inner-submenu">
												<li><a href="error-404.html">404 Error</a></li>
												<li><a href="error-500.html">500 Error</a></li>
											</ul>
										</li>
										<li><a href="blank-page.html">Starter Page</a></li>
										<li><a href="pricing.html">Pricing Plan</a></li>
										<li><a href="faq.html">FAQ</a></li>
										<li><a href="maintenance.html">Maintenance</a></li>
										<li><a href="coming-soon.html">Coming Soon</a></li>
										<li><a href="terms-condition.html">Terms & Condition</a></li>
										<li><a href="privacy-policy.html">Privacy Policy</a></li>
										<li><a href="components.html">Components</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="#">Blog <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="blog-list.html">Blog List</a></li>
										<li><a href="blog-grid.html">Blog Grid</a></li>
										<li><a href="blog-details.html">Blog Details</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="#">Admin <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="admin/index.html" target="_blank">Admin</a></li>
										<li><a href="pharmacy/index.html" target="_blank">Pharmacy Admin</a></li>
									</ul>
								</li>
								<li class="searchbar">
									<a href="javascript:void(0);"><i class="feather-search"></i></a>
									<div class="togglesearch" style="display: none;">
										<form action="search.html">
											<div class="input-group">
												<input type="text" class="form-control">
												<button type="submit" class="btn">Search</button>
											</div>
										</form>
									</div>
								</li>
								<li class="login-link"><a href="login.html">Login / Signup</a></li>
								<li class="register-btn">
									<a href="register.html" class="btn reg-btn"><i class="feather-user"></i>Register</a>
								</li>
								<li class="register-btn">
									<a href="login.html" class="btn btn-primary log-btn"><i class="feather-lock"></i>Login</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>
			<!-- /Header -->	
			
			<!-- Page Content -->
			<div class="content top-space">
				<div class="container">
					
					<div class="row">
						<div class="col-md-8 offset-md-2">
								
							<!-- Register Content -->
							<div class="account-content">
								<div class="row align-items-center justify-content-center">
									<div class="col-md-7 col-lg-6 login-left">
										<img src="{{asset('template/assets/img/login-banner.png')}}" class="img-fluid" alt="Doccure Register">	
									</div>
									<div class="col-md-12 col-lg-6 login-right">
										<div class="login-header">
											<h3>Patient Register <a href="doctor-register.html">Are you a Doctor?</a></h3>
										</div>
										
										<!-- Register Form -->
										<form action="patient-register-step1.html">
											<div class="form-group form-focus">
												<input type="text" class="form-control floating">
												<label class="focus-label">Name</label>
											</div>
											<div class="form-group form-focus">
												<input type="text" class="form-control floating">
												<label class="focus-label">Mobile Number</label>
											</div>
											<div class="form-group form-focus">
												<input type="password" class="form-control floating">
												<label class="focus-label">Create Password</label>
											</div>
											<div class="text-end">
												<a class="forgot-link" href="login.html">Already have an account?</a>
											</div>
											<button class="btn btn-primary w-100 btn-lg login-btn" type="submit">Signup</button>
											<div class="login-or">
												<span class="or-line"></span>
												<span class="span-or">or</span>
											</div>
											<div class="row form-row social-login">
												<div class="col-6">
													<a href="#" class="btn btn-facebook w-100"><i class="fab fa-facebook-f me-1"></i> Login</a>
												</div>
												<div class="col-6">
													<a href="#" class="btn btn-google w-100"><i class="fab fa-google me-1"></i> Login</a>
												</div>
											</div>
										</form>
										<!-- /Register Form -->
										
									</div>
								</div>
							</div>
							<!-- /Register Content -->
								
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
   
			<!-- Footer -->
			<footer class="footer footer-one">
				<div class="footer-top">
					<div class="container">
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="footer-widget footer-about">
									<div class="footer-logo">
										<img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" alt="logo">
									</div>
									<div class="footer-about-content">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-4">
								<div class="footer-widget footer-menu">
									<h2 class="footer-title">For Patients</h2>
									<ul>
										<li><a href="search.html">Search for Doctors</a></li>
										<li><a href="login.html">Login</a></li>
										<li><a href="register.html">Register</a></li>
									</ul>
								</div>
							</div>
							<div class="col-lg-2 col-md-4">
								<div class="footer-widget footer-menu">
									<h2 class="footer-title">For Doctors</h2>
									<ul>
										<li><a href="appointments.html">Appointments</a></li>
										<li><a href="chat.html">Chat</a></li>
										<li><a href="login.html">Login</a></li>
									</ul>
								</div>
							</div>
							<div class="col-lg-2 col-md-5">
								<div class="footer-widget footer-contact">
									<h2 class="footer-title">Contact Us</h2>
									<div class="footer-contact-info">
										<div class="footer-address">
											<p><i class="feather-map-pin"></i> 3556 Beech Street, USA</p>
										</div>
										<div class="footer-address">
											<p><i class="feather-phone-call"></i> +1 315 369 5943</p>
										</div>
										<div class="footer-address mb-0">
											<p><i class="feather-mail"></i> doccure@example.com</p>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-7">
								<div class="footer-widget">
									<h2 class="footer-title">Join Our Newsletter</h2>
									<div class="subscribe-form">
										<form action="#">
		                                    <input type="email" class="form-control" placeholder="Enter Email">
		                                    <button type="submit" class="btn">Submit</button>
	                                    </form>
	                                </div>
									<div class="social-icon">
										<ul>
											<li>
												<a href="javascript:;" target="_blank"><i class="fab fa-facebook"></i> </a>
											</li>
											<li>
												<a href="javascript:;" target="_blank"><i class="fab fa-instagram"></i></a>
											</li>
											<li>
												<a href="javascript:;" target="_blank"><i class="fab fa-twitter"></i> </a>
											</li>
											<li>
												<a href="javascript:;" target="_blank"><i class="fab fa-linkedin-in"></i></a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-bottom">
					<div class="container">
						<!-- Copyright -->
						<div class="copyright">
							<div class="row">
								<div class="col-md-6 col-lg-6">
									<div class="copyright-text">
										<p class="mb-0"> Copyright © 2023 <a href="https://themeforest.net/user/dreamguys/portfolio" target="_blank">Dreamguys.</a> All Rights Reserved</p>
									</div>
								</div>
								<div class="col-md-6 col-lg-6">
								
									<!-- Copyright Menu -->
									<div class="copyright-menu">
										<ul class="policy-menu">
											<li><a href="privacy-policy.html">Privacy Policy</a></li>
											<li><a href="terms-condition.html">Terms and Conditions</a></li>
										</ul>
									</div>
									<!-- /Copyright Menu -->
									
								</div>
							</div>
						</div>
						<!-- /Copyright -->					
					</div>
				</div>
			</footer>
			<!-- /Footer -->
		   
		</div>
		<!-- /Main Wrapper -->
	  
		<!-- jQuery -->
		<script src="{{asset('template/assets/js/jquery-3.6.4.min.js')}}"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="{{asset('template/assets/js/bootstrap.bundle.min.js')}}"></script>
		
		<!-- Custom JS -->
		<script src="{{asset('template/assets/js/script.js') }}"></script>
		
	</body>
</html>