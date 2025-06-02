<style>
    /* ===== FOOTER STYLING ===== */

    /* ===== FOOTER STYLING ===== */

    .footer-one {
        background: linear-gradient(135deg, #1a365d 0%, #2d5a87 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .footer-one::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="wave" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M0,50 Q25,30 50,50 T100,50 V100 H0 Z" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23wave)"/></svg>');
        pointer-events: none;
    }

    .footer-top {
        padding: 60px 0 40px;
        position: relative;
        z-index: 2;
    }

    .footer-widget {
        margin-bottom: 30px;
    }

    /* Logo e About Section */
    .footer-about .footer-logo {
        margin-bottom: 25px;
    }

    .footer-about .footer-logo img {
        max-height: 60px;
        width: auto;
        filter: brightness(1.1);
        transition: transform 0.3s ease;
    }

    .footer-about .footer-logo:hover img {
        transform: scale(1.05);
    }

    .footer-about-content p {
        font-size: 14px;
        line-height: 1.7;
        color: #e2e8f0;
        margin: 0;
    }

    /* Footer Titles */
    .footer-title {
        font-size: 18px;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #4299e1, #63b3ed);
        border-radius: 2px;
    }

    /* Menu Lists */
    .footer-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-menu ul li {
        margin-bottom: 12px;
        padding-left: 0;
    }

    .footer-menu ul li a {
        color: #cbd5e0;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        padding-left: 20px;
    }

    .footer-menu ul li a::before {
        content: '→';
        position: absolute;
        left: 0;
        color: #4299e1;
        transition: transform 0.3s ease;
    }

    .footer-menu ul li a:hover {
        color: #ffffff;
        padding-left: 25px;
    }

    .footer-menu ul li a:hover::before {
        transform: translateX(3px);
    }

    /* Contact Info */
    .footer-contact-info p {
        font-size: 14px;
        color: #e2e8f0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        line-height: 1.6;
    }

    .footer-contact-info p i {
        margin-right: 12px;
        font-size: 16px;
        color: #4299e1;
        width: 20px;
        flex-shrink: 0;
    }

    /* Newsletter Form */
    .subscribe-form {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .subscribe-form .form-control {
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 12px 20px;
        flex: 1;
        font-size: 14px;
        border-radius: 25px;
    }

    .subscribe-form .form-control::placeholder {
        color: #cbd5e0;
    }

    .subscribe-form .form-control:focus {
        outline: none;
        box-shadow: none;
        background: rgba(255, 255, 255, 0.1);
    }

    .subscribe-form .btn {
        background: linear-gradient(45deg, #4299e1, #3182ce);
        border: none;
        color: white;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .subscribe-form .btn:hover {
        background: linear-gradient(45deg, #3182ce, #2b77cb);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3);
    }

    /* Social Icons */
    .social-icon ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 15px;
    }

    .social-icon ul li a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        color: #cbd5e0;
        border-radius: 50%;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .social-icon ul li a:hover {
        background: #4299e1;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(66, 153, 225, 0.4);
    }

    .social-icon ul li a i {
        font-size: 18px;
    }

    /* Footer Bottom */
    .footer-bottom {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 2;
    }

    .footer-bottom p {
        color: #cbd5e0;
        margin: 0;
        font-size: 14px;
    }

    .footer-bottom a {
        color: #4299e1;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-bottom a:hover {
        color: #63b3ed;
    }

    .policy-menu {
        margin: 0;
        padding: 0;
    }

    .policy-menu .list-inline-item {
        margin-left: 25px;
    }

    .policy-menu .list-inline-item a {
        color: #cbd5e0;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
        position: relative;
    }

    .policy-menu .list-inline-item a::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 0;
        height: 2px;
        background: #4299e1;
        transition: width 0.3s ease;
    }

    .policy-menu .list-inline-item a:hover {
        color: #ffffff;
    }

    .policy-menu .list-inline-item a:hover::after {
        width: 100%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-top {
            padding: 40px 0 30px;
        }

        .footer-widget {
            margin-bottom: 40px;
            text-align: center;
        }

        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-menu ul li a {
            justify-content: center;
            padding-left: 0;
        }

        .footer-menu ul li a::before {
            display: none;
        }

        .subscribe-form {
            flex-direction: column;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 15px;
        }

        .subscribe-form .form-control,
        .subscribe-form .btn {
            border-radius: 8px;
        }

        .social-icon ul {
            justify-content: center;
        }

        .footer-bottom .row {
            text-align: center;
        }

        .footer-bottom .col-md-6:last-child {
            margin-top: 15px;
        }

        .policy-menu .list-inline-item {
            margin: 0 10px;
        }
    }

    @media (max-width: 576px) {
        .social-icon ul li a {
            width: 40px;
            height: 40px;
        }

        .social-icon ul li a i {
            font-size: 16px;
        }

        .footer-contact-info p {
            font-size: 13px;
        }

        .footer-about-content p {
            font-size: 13px;
        }
    }
</style>
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
