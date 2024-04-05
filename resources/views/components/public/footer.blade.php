<footer class="footer footer-one">
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-4">
					<div class="footer-widget footer-about">
						<div class="footer-logo">
							<a href="">
							<img src="{{asset('template/assets/img/logo.png')}}" alt="logo">
						</a>
						</div>
						<div class="footer-about-content">
							<p> Estamos comprometidos em promover a paixão pelos esportes ao ar livre, facilitando o acesso, o engajamento e o sucesso dos atletas e comunidades costeiras em todo o mundo</p>
						</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-4">
					<div class="footer-widget footer-menu">
						<h2 class="footer-title">Alunos</h2>
						<ul>
							<li><a href="search.html">Buscar Professores</a></li>
							<li><a href="{{route('home.login')}}">Login</a></li>
							<li><a href="{{route('home.registerAluno')}}">Registrar</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-4">
					<div class="footer-widget footer-menu">
						<h2 class="footer-title">Professores</h2>
						<ul>
							<li><a href="{{route('home.registerProf')}}">Cadastrar</a></li>
							<li><a href="">Whatssap</a></li>
							<li><a href="">Login</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-5">
					<div class="footer-widget footer-contact">
						<h2 class="footer-title">Ligue pra gente</h2>
						<div class="footer-contact-info">
							<div class="footer-address">
								<p><i class="feather-map-pin"></i> Rua saint roman 200, Brasil , RJ</p>
							</div>
							<div class="footer-address">
								<p><i class="feather-phone-call"></i> +21 990271287</p>
							</div>
							<div class="footer-address mb-0">
								<p><i class="feather-mail"></i> rjpasseios@gamil.com</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-7">
					<div class="footer-widget">
						<h2 class="footer-title">Envie um e-mail</h2>
						<div class="subscribe-form">
							<form action="#">
								<input type="email" class="form-control" placeholder="Email">
								<button type="submit" class="btn">Enviar</button>
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
							<p class="mb-0"> Copyright © 2023 <a href="https://rogerneves.com.br" target="_blank">Roger Neves.</a> All Rights Reserved</p>
						</div>
					</div>
					<div class="col-md-6 col-lg-6">
					
						<!-- Copyright Menu -->
						<div class="copyright-menu">
							<ul class="policy-menu">
								<li><a href="">Politica de Privacidade</a></li>
								<li><a href="">Termos e Condições</a></li>
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
{{-- <script src="https://clever-chat.ai/chatbot/1.0.0/index.js"></script>
<clever-chatbot version=1.0.0 chatbotId="f52ac7b85b8"></clever-chatbot> --}}
  
<script src="{{asset('template/assets/js/jquery-3.6.4.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('template/assets/js/bootstrap.bundle.min.js')}}"></script>

<!-- Sticky Sidebar JS -->
<script src="{{asset('template/assets/plugins/theia-sticky-sidebar/ResizeSensor.js')}}"></script>
<script src="{{asset('template/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}"></script>

<!-- Select2 JS -->
<script src="{{asset('template/assets/plugins/select2/js/select2.min.js')}}"></script>

<!-- Datetimepicker JS -->
<script src="{{asset('template/assets/js/moment.min.js')}}"></script>
<script src="{{asset('template/assets/js/bootstrap-datetimepicker.min.js')}}"></script>

<!-- Fancybox JS -->
<script src="{{asset('template/assets/plugins/fancybox/jquery.fancybox.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{asset('template/assets/js/script.js')}}"></script>


</body>
</html>