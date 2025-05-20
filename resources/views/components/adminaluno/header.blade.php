<!DOCTYPE html>
<html lang="en">
    <head>
		
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>{{$title}}</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/img/favicon.png')}}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('admin/css/bootstrap.css')}}">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{asset('admin/css/font-awesome.min.css')}}">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="{{asset('admin/css/feathericon.min.css')}}">
		
		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{asset('admin/css/bootstrap-datetimepicker.min.css')}}">
		
		<!-- Full Calander CSS -->
        <link rel="stylesheet" href="{{asset('admin/plugins/fullcalendar/fullcalendar.min.css')}}">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">

		

		
		{{-- @vite(['resources/css/app.css']) --}}

    </head>
    <body>

	
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
				@php 
					$user = Auth::user() ?? ''; // pega o usuário autenticado
					$empresa = $user->empresa ?? '';
					$avatar = $empresa->avatar ?? '';
				@endphp
                <div class="header-left">
                    <a href="{{route('home.index')}}" class="logo">
						<img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" width="150" height="150" alt="Logo Rjpasseios">		</a>
					<a href="{{route('home.index')}}" class="logo logo-small">
						<img src="{{asset('admin/img/logo-small.png')}}" alt="Logo" width="30" height="30">
					</a>
                </div>
				<!-- /Logo -->
				
				<a href="javascript:void(0);" id="toggle_btn">
					<i class="fe fe-text-align-left"></i>
				</a>
				
				<div class="top-nav-search">
					<form>
						<input type="text" class="form-control" placeholder="Buscar">
						<button class="btn" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</form>
				</div>
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fa fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
				
				<!-- Header Right Menu -->
				<ul class="nav user-menu">
					
					<!-- Notifications -->
					<li class="nav-item dropdown noti-dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
							<i class="fe fe-bell"></i> <span class="badge rounded-pill">3</span>
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Notificação</span>
								<a href="javascript:void(0)" class="clear-noti"> Limpar </a>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
									<li class="notification-message">
										<a href="#">
											<div class="media d-flex">
												<span class="avatar avatar-sm flex-shrink-0">
													<img class="avatar-img rounded-circle" alt="User Image" src="{{asset('admin/img/doctors/doctor-thumb-01.jpg')}}">
												</span> 404 
												<div class="media-body flex-grow-1">
													<p class="noti-details">
														<span class="noti-title">Dr. Ruby Perrin</span> Schedule 
														<span class="noti-title">her appointment</span>
													</p>
													<p class="noti-time">
														<span class="notification-time">4 mins ago</span>
													</p>
												</div>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="topnav-dropdown-footer">
								<a href="#">Notificação</a>
							</div>
						</div>
					</li>
					<!-- /Notifications -->
					
					<!-- User Menu -->
					<li class="nav-item dropdown has-arrow">
						<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
							<span class="user-img"><img class="rounded-circle" src="{{asset('admin/img/profiles/avatar-01.jpg')}}" width="31" alt="Ryan Taylor"></span>
						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
									<img src="{{asset('admin/img/profiles/avatar-01.jpg')}}" alt="User Image" class="avatar-img rounded-circle">
								</div>
								<div class="user-text">
									<p class="text-muted mb-0">Administrator</p>
									<a class="dropdown-item" href="{{route('user.logout')}}">Logout</a>		</div>
			
								</div>
							</div>
							{{-- <a class="dropdown-item" href="{{route('home.show', ['id' => Auth::user()->empresa->id])}}">Perfil</a>
							<a class="dropdown-item" href="{{route('empresa.configuracao')}}">Configurações</a>
							<a class="dropdown-item" href="{{route('user.logout')}}">Logout</a>
						</div> --}}
					</li>
					<!-- /User Menu -->
					
				</ul>
			 <x-adminaluno.sidebarescola/>
			<!-- /Sidebar -->

	<!-- jQuery -->

	 <!-- Scripts -->
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
 
	 <!-- Bootstrap JS -->
	 <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
 
	 <!-- Plugins -->
	 <script src="{{ asset('admin/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
	 <script src="{{ asset('admin/js/moment.min.js') }}"></script>
	 <script src="{{ asset('admin/js/bootstrap-datetimepicker.min.js') }}"></script>
	 <script src="{{ asset('admin/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
	 <script src="{{ asset('admin/plugins/fullcalendar/jquery.fullcalendar.js') }}"></script>
 
	 <!-- Feather Icons -->
	 <script src="{{ asset('template/assets/js/feather.min.js') }}"></script>
 
	 <!-- Mobile Input -->
	 <script src="{{ asset('template/assets/plugins/intltelinput/js/intlTelInput.js') }}"></script>
 
	 <!-- Custom JS -->
	 <script src="{{ asset('admin/js/script.js') }}"></script>
 
<!-- Custom JS -->


			
