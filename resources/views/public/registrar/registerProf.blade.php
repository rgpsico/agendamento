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
		  <div class="row">
			<div class="col-md-6 login-left">
			  <img class="img-fluid" src="{{asset('admin/img/logo-white.png')}}" alt="Logo">
			</div>
			<div class="col-md-6 login-right">
			  <div class="login-right-wrap">
				<h1>Registrar Escola de surf</h1>
				<p class="account-subtitle">Acessar seu painel administrativo</p>
  
				<!-- Form -->
				<form action="{{route('user.store')}}" method="POST">
				  @csrf
  
				  <input type="hidden" name="tipo_usuario" value="Professor">
				  <x-text-input name="nome"  size="100%" label="Nome" placeholder="Nome: Roger Ne" />
				  <x-text-input name="email" size="30" label="Email" placeholder="email@124.com" />
				  <x-text-input type='password' name="senha" size="30" label="Senha" />
				  <x-text-input type='password' name="senha" size="30" label="Repetir Senha" />
				   
				  <div class="form-group">
					<label for="">Modalidade</label>
						<select name="modalidade_id" class="form-control" id="modalidade_id">
							
					
							@foreach ($modalidade as  $value)
								<option value="{{$value->id}}">{{$value->nome}}</option>
							@endforeach 
										
						</select>
				  </div>
				 

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
				{{-- <div class="social-login">
				  <span>Registrar Com</span>
				  <a href="#" class="facebook">
					<i class="fa fa-facebook"></i>
				  </a>
				  <a href="#" class="google">
					<i class="fa fa-google"></i>
				  </a>
				</div> --}}
				<!-- /Social Login -->
  
				<div class="col-12">
					{{-- <div class="col-12">
						<a href="{{route('prof.login.google')}}" class="btn btn-google w-100">
							<i class="fab fa-google me-1">
								</i> Login com Google
							</a>
							
					</div> --}}
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  