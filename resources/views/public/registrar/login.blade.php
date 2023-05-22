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
										<img src="{{asset('template/assets/img/login-banner.png')}}"
										 class="img-fluid" alt="Doccure Login">	
									</div>
									<div class="col-md-12 col-lg-6 login-right">
										<div class="login-header">
											<h3>Login <span>Doccure</span></h3>
										</div>
										<form action="index.html">
											<div class="form-group form-focus">
												<input type="email" class="form-control floating">
												<label class="focus-label">Email</label>
											</div>
											<div class="form-group form-focus">
												<input type="password" class="form-control floating">
												<label class="focus-label">Password</label>
											</div>
											<div class="text-end">
												<a class="forgot-link" href="forgot-password.html">Forgot Password ?</a>
											</div>
											<button class="btn btn-primary w-100 btn-lg login-btn" type="submit">Login</button>
											<div class="login-or">
												<span class="or-line"></span>
												<span class="span-or">or</span>
											</div>
											<div class="row form-row social-login">
												<div class="col-6">
													<a href="#" class="btn btn-facebook w-100">
														<i class="fab fa-facebook-f me-1">
															</i> Login
														</a>
												</div>
												<div class="col-6">
													<a href="#" class="btn btn-google w-100">
														<i class="fab fa-google me-1">
															</i> Login
														</a>
												</div>
											</div>
											<div class="text-center dont-have">Não tenho conta ainda 
												<a href="{{route('home.register')}}">Registrar</a></div>
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