<link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">

<link rel="stylesheet" href="{{asset('admin/css/bootstrap.css')}}">
		
<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{asset('admin/css/font-awesome.min.css')}}">

<!-- Main CSS -->
<link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
<div class="main-wrapper login-body">
	<div class="login-wrapper">
		<div class="container">
			<div class="loginbox">
				<div class="login-left">
					<img class="img-fluid" src="{{asset('admin/img/logo-white.png')}}" alt="Logo">
				</div>
				<div class="login-right">
					<div class="login-right-wrap">
						<h1>Registrar Escola de surf</h1>
						<p class="account-subtitle">Acessar seu painel administrativo</p>
						
						<!-- Form -->
						<form action="{{route('user.store')}}">
							
							<input type="hidden" name="tipo_usuario" value="professor">
							<x-text-input name="nome" size="30" label="Nome"  />
                           
							<x-text-input name="email" size="30" label="Email"  />
								
						    <x-text-input name="password" size="30" label="Senha"  />
							<x-text-input name="password" size="30" label="Repetir Senha"  />
							<div class="form-group mb-0">
								<button class="btn btn-primary w-100" type="submit">Registrar</button>
							</div>
						</form>
						<!-- /Form -->
						
						<div class="login-or">
							<span class="or-line"></span>
							<span class="span-or">Ou</span>
						</div>
						
						<!-- Social Login -->
						<div class="social-login">
							<span>Registrar Com</span>
							<a href="#" class="facebook">
								<i class="fa fa-facebook"></i></a>
								<a href="#" class="google">
								<i class="fa fa-google"></i>
							</a>
						</div>
						<!-- /Social Login -->
						
						<div class="text-center dont-have">Eu ja tenho uma conta? 
							<a href="{{route('home.login')}}">Login</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>