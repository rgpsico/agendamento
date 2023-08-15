<x-public.layout title="HOME">
   
   
			<!-- Page Content -->
			
			<div class="content top-space" style="min-height: 172.906px;">
				<div class="container-fluid">
					
					<div class="row">
						
						<div class="col-md-8 offset-md-2">
							
							<!-- Login Tab Content -->
							<div class="account-content">
								
								<div class="row align-items-center justify-content-center">
									<div class="col-md-7 col-lg-6 login-left">
										<img src="{{asset('admin/img/register.png')}}"
										 class="img-fluid" alt="Login Professor">	
									</div>
									<div class="col-md-12 col-lg-6 login-right">
										<div class="login-header">
											<x-alert/>
											<h3>Login <span>Professor</span></h3>
										</div>
										<form action="{{route('user.login')}}" method="POST">
											@csrf
											<div class="form-group form-focus">
												<input type="email" name="email" class="form-control floating">
												<label class="focus-label">Email</label>
												@error('email')
												<span class="text-danger">{{ $message }}</span>
												@enderror
											</div>
											
											<div class="form-group form-focus">
												<input type="password" name="senha" class="form-control floating">
												<label class="focus-label">Senha</label>
												@error('senha')
												<span class="text-danger">{{ $message }}</span>
												@enderror
											</div>
											
											<div class="text-end">
												<a class="forgot-link" href="l">Esqueceu a Senha ?</a>
											</div>
											<button class="btn btn-primary w-100 btn-lg login-btn" type="submit">Login</button>
											<div class="login-or">
												<span class="or-line"></span>
												<span class="span-or">Ou</span>
											</div>
											<div class="row form-row social-login">
												<div class="col-6">
													<a href="#" class="btn btn-facebook w-100">
														<i class="fab fa-facebook-f me-1">
															</i> Login sss bb
														</a>
												</div>
												<div class="col-6">
													<a href="{{route('login.google')}}" class="btn btn-google w-100">
														<i class="fab fa-google me-1">
															</i> Login
														</a>
												</div>
											</div>
											<div class="text-center dont-have">Não tenho conta ainda 
												<a href="{{route('home.registerProf')}}">Registrar</a></div>
										</form>
									</div>
								</div>
							</div>
							<!-- /Login Tab Content -->
								
						</div>
					</div>

				</div>

			</div>
			<!-- /Page Content -->
			<!-- /Page Content -->
</x-layoutsadmin>