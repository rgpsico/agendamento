<footer class="footer footer-one">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <!-- Logo e Descrição -->
                <div class="col-lg-3 col-md-4">
                    <div class="footer-widget footer-about">
                        <div class="footer-logo">
                            <a href="/">
                                <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png"
                                    alt="Logo RJ Passeios">
                            </a>
                        </div>
                        <div class="footer-about-content">
                            <p>Estamos comprometidos em promover a paixão pelos esportes ao ar livre, facilitando o
                                acesso, o engajamento e o sucesso dos atletas e comunidades costeiras em todo o mundo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu Alunos -->
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title">Alunos</h2>
                        <ul>
                            <li><a href="">Buscar Professores</a></li>
                            <li><a href="{{ route('home.login') }}">Login</a></li>
                            <li><a href="{{ route('home.registerAluno') }}">Registrar</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Menu Professores -->
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title">Professores</h2>
                        <ul>
                            <li><a href="{{ route('home.registerProf') }}">Cadastrar</a></li>
                            <li><a href="https://wa.me/5521990271287" target="_blank" rel="noopener">WhatsApp</a></li>
                            <li><a href="{{ route('home.login') }}">Login</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contato -->
                <div class="col-lg-2 col-md-5">
                    <div class="footer-widget footer-contact">
                        <h2 class="footer-title">Ligue pra gente</h2>
                        <div class="footer-contact-info">
                            <p><i class="feather-map-pin"></i> Rua Saint Roman, 200 – Rio de Janeiro, Brasil</p>
                            <p><i class="feather-phone-call"></i> +55 21 99027-1287</p>
                            <p><i class="feather-mail"></i> rjpasseios@gmail.com</p>
                        </div>
                    </div>
                </div>

                <!-- Newsletter e Social -->
                <div class="col-lg-3 col-md-7">
                    <div class="footer-widget">
                        <h2 class="footer-title">Envie um e-mail</h2>
                        <form action="#" class="subscribe-form">
                            <input type="email" class="form-control" placeholder="Digite seu e-mail">
                            <button type="submit" class="btn">Enviar</button>
                        </form>
                        <div class="social-icon mt-3">
                            <ul>
                                <li><a href="https://facebook.com" target="_blank" rel="noopener"><i
                                            class="fab fa-facebook"></i></a></li>
                                <li><a href="https://instagram.com" target="_blank" rel="noopener"><i
                                            class="fab fa-instagram"></i></a></li>
                                <li><a href="https://twitter.com" target="_blank" rel="noopener"><i
                                            class="fab fa-twitter"></i></a></li>
                                <li><a href="https://linkedin.com" target="_blank" rel="noopener"><i
                                            class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- footer-top -->

    <!-- Rodapé -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">© 2023 <a href="https://rogerneves.com.br" target="_blank" rel="noopener">Roger
                            Neves</a>. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="policy-menu list-inline mb-0">
                        <li class="list-inline-item"><a href="#">Política de Privacidade</a></li>
                        <li class="list-inline-item"><a href="#">Termos e Condições</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- /Footer -->

</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
{{-- <script src="https://clever-chat.ai/chatbot/1.0.0/index.js"></script>
<clever-chatbot version=1.0.0 chatbotId="f52ac7b85b8"></clever-chatbot> --}}

<script src="{{ asset('template/assets/js/jquery-3.6.4.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Sticky Sidebar JS -->
<script src="{{ asset('template/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ asset('template/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('template/assets/plugins/select2/js/select2.min.js') }}"></script>

<!-- Datetimepicker JS -->
<script src="{{ asset('template/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('template/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- Fancybox JS -->
<script src="{{ asset('template/assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('template/assets/js/script.js') }}"></script>


</body>

</html>
