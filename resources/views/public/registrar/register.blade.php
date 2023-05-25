<x-public.layout title="HOME">
   
   
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
											<h3>Registrar 
												<a href="">Você é um Professor</a>
											</h3>
										</div>
										
										<!-- Register Form -->
										<form action="{{route('user.store')}}" method="POST">
											@csrf
											<div class="form-group form-focus">
												<input type="text" class="form-control floating" name="nome" value="roger">
												<label class="focus-label">Nome</label>
											</div>
											<div class="form-group form-focus">
												<input type="text" name="email" class="form-control floating" value="rgyr2010@hotmail.com">
												<label class="focus-label">E-mail</label>
											</div>
											<div class="form-group form-focus">
												<input type="password" name="senha" class="form-control floating">
												<label class="focus-label">Senha</label>
											</div>
											<div class="text-end">
												<a class="forgot-link" href="{{route('home.login')}}">Você ja tem uma Conta ?</a>
											</div>
											<button class="btn btn-primary w-100 btn-lg login-btn" type="submit">Entrar</button>
											<div class="login-or">
												<span class="or-line"></span>
												<span class="span-or">Ou</span>
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
			<!-- /Page Content -->
</x-layoutsadmin>