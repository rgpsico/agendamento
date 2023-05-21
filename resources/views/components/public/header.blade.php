<!DOCTYPE html> 
<html lang="en">
	<head>

		<meta charset="utf-8">
		<title>{{$title}}</title>
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
		
		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/css/bootstrap-datetimepicker.min.css')}}">
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/plugins/select2/css/select2.min.css')}}">
		
		<!-- Fancybox CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/plugins/fancybox/jquery.fancybox.min.css')}}">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="{{asset('template/assets/css/custom.css')}}">
	
	</head>
	<body>

		<!-- Main Wrapper -->
		<div class="main-wrapper)}}">
		
			<!-- Header -->
			<header class="header header-fixed header-one">
				<div class="container">
					<nav class="navbar navbar-expand-lg header-nav">
						<div class="navbar-header">
							<a id="mobile_btn" href="javascript:void(0);">
								<span class="bar-icon">
									<span></span>
									<span></span>
									<span></span>
								</span>
							</a>
							<a href="" class="navbar-brand logo">
								<img src="{{asset('template/assets/img/logo.png')}}" class="img-fluid" alt="Logo">
							</a>
						</div>
						<div class="main-menu-wrapper">
							<div class="menu-header">
								<a href="" class="menu-logo">
									<img src="template/assets/img/logo.png" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>
							<ul class="main-nav">
								<li class="has-submenu ">
									<a href="">Home <i class="fas fa-chevron-down"></i></a>
									
								</li>
								
								<li class="login-link"><a href="login.html">Login / Cadastrar</a></li>
								<li class="register-btn">
									<a href="register.html" class="btn reg-btn"><i class="feather-user"></i>Registrar</a>
								</li>
								<li class="register-btn">
									<a href="login.html" class="btn btn-primary log-btn"><i class="feather-lock"></i>Login</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>