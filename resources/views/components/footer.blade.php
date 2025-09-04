<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<footer class="footer footer-one">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <!-- Logo e Descrição -->
                <div class="col-lg-3 col-md-4">
                    <div class="footer-widget footer-about">
                        <div class="footer-logo">
                            <a href="/">
                                <img src="{{ $config && $config->logo_footer ? asset('storage/'.$config->logo_footer) : 'admin/img/surfbread2.png' }}" 
                                     class="img-fluid" alt="Logo">
                            </a>
                        </div>
                        <div class="footer-about-content footer-descricao" style="color:#fff; text-align: left;">
                            <p style="color:#fff;">
                                {{  $config->footer_descricao ?? 
                                ' 
                                Estamos comprometidos em promover a paixão pelos esportes ao ar livre, facilitando o
                                acesso, o engajamento e o sucesso dos atletas e comunidades costeiras em todo o mundo.
                                ' }}
                               
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu Alunos -->
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title light" style="color:#fff;">Alunos</h2>
                        <ul>
                            <li><a style="color:#fff;" href="">Buscar Professores</a></li>
                            <li><a style="color:#fff;" href="{{ route('home.login') }}">Login</a></li>
                            <li><a style="color:#fff;" href="{{ route('home.registerAluno') }}">Registrar</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Menu Professores -->
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title" style="color:#fff;">Professores</h2>
                        <ul>
                            <li><a style="color:#fff;" href="{{ route('home.registerProf') }}">Cadastrar</a></li>
                            <li><a style="color:#fff;" href="https://wa.me/5521990271287" target="_blank" rel="noopener">WhatsApp</a></li>
                            <li><a style="color:#fff;" href="{{ route('home.login') }}">Login</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contato -->
                <div class="col-lg-2 col-md-5">
                    <div class="footer-widget footer-contact">
                        <h2 class="footer-title" style="color:#fff;">Ligue pra gente</h2>
                        <div class="footer-contact-info">
                            <p><i class="feather-map-pin"></i> Rua Saint Roman, 200 – Rio de Janeiro, Brasil</p>
                            <p><i class="feather-phone-call"></i> +55 21 99027-1287</p>
                            <p><i class="feather-mail"></i> {{ $config->email ?? 'rjpasseios@gmail.com' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Newsletter e Social -->
                <div class="col-lg-3 col-md-7">
                    <div class="footer-widget">
                        <h2 class="footer-title" style="color:#fff;">Envie um e-mail</h2>
                        <form action="#" class="subscribe-form">
                            <input type="email" class="form-control" placeholder="Digite seu e-mail">
                            <button type="submit" class="btn">Enviar</button>
                        </form>
                        <div class="social-icon mt-3">
                            <ul>
                                @if($config && $config->facebook)
                                    <li><a href="{{ $config->facebook }}" target="_blank"><i class="fab fa-facebook" style="color:#fff;"></i></a></li>
                                @endif
                                @if($config && $config->instagram)
                                    <li><a href="{{ $config->instagram }}" target="_blank"><i class="fab fa-instagram" style="color:#fff;"></i></a></li>
                                @endif
                                @if($config && $config->twitter)
                                    <li><a href="{{ $config->twitter }}" target="_blank"><i class="fab fa-twitter" style="color:#fff;"></i></a></li>
                                @endif
                                @if($config && $config->linkedin)
                                    <li><a href="{{ $config->linkedin }}" target="_blank"><i class="fab fa-linkedin-in" style="color:#fff;"></i></a></li>
                                @endif
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
                    <p class="mb-0">© {{ date('Y') }} <a href="https://rogerneves.com.br" target="_blank">Roger Neves</a>. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="policy-menu list-inline mb-0">
                        @if($config && $config->politica_privacidade)
                            <li class="list-inline-item"><a href="{{ $config->politica_privacidade }}">Política de Privacidade</a></li>
                        @endif
                        @if($config && $config->termos_condicoes)
                            <li class="list-inline-item"><a href="{{ $config->termos_condicoes }}">Termos e Condições</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
