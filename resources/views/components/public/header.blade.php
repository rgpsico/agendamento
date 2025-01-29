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
							<a id="mobile_btn" href="{{route('home.index')}}">
								<span class="bar-icon">
									<span></span>
									<span></span>
									<span></span>
								</span>
							</a>
							<a href="{{route('home.index')}}" class="navbar-brand logo">
								<img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" class="img-fluid" alt="Logo">
							</a>
						</div>
						<div class="main-menu-wrapper">
							<div class="menu-header">
								<a href="" class="menu-logo">
									<img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>
							<ul class="main-nav">
								@if(auth()->check()) {{-- Verifica se o usuário está autenticado --}}
									@if(auth()->user()->tipo_usuario == 'Professor')
									<li class="my-3">
										<a href="{{route('cliente.dashboard')}}" class="btn reg-btn">Admin</a>                                        
									</li>
									<li class="my-3">
										<a href="{{route('user.logout')}}" class="btn reg-btn">Sair</a>                                        
									</li>
									@elseif (auth()->user()->tipo_usuario == 'Aluno')    
									<li class="my-3">
										<a href="{{route('alunos.aulas')}}" class="btn reg-btn">Admin</a>                                        
									</li>
									<li class="my-3">
										<a href="{{route('user.logout')}}" class="btn reg-btn">Sair</a>                                        
									</li>
									@endif
								@else
								{{-- Caso não haja usuário autenticado, mostre as opções de login --}}
								<li class="login-link">
									<a href="/login">Login</a>
								</li>
								<li class="register-btn">
									<a href="{{route('home.login')}}" class="btn btn-primary log-btn">
										<i class="feather-lock"></i>Login
									</a>
								</li>
								@endif
							</ul>
							
							
						</div>
					</nav>
				</div>
			</header>