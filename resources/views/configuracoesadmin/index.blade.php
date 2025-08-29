<x-admin.layout title="Configurações">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <h3 class="mb-4">Configurações do Sistema</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Abas de Configuração -->
            <ul class="nav nav-tabs" id="configTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#gerais">Gerais</a>
                </li>
                @if(Auth::user()->isAdmin)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#login">Login & Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#home">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#sistema">Sistema</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#redes">Redes Sociais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#legais">Políticas & Termos</a>
                    </li>
                @endif
            </ul>

            <!-- Conteúdo das Abas -->
            <div class="tab-content mt-3">
                
                <!-- Aba: Configurações Gerais -->
                <div class="tab-pane fade show active" id="gerais">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nome do Sistema:</label>
                            <input type="text" name="nome_sistema" class="form-control" value="{{ $nomeSistema ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Logo Header:</label>
                            <input type="file" name="logo_header" class="form-control">
                            @isset($logoHeader)
                                <img src="{{ asset('storage/' . $logoHeader) }}" width="100" class="mt-2">
                            @endisset
                        </div>

                        <div class="form-group">
                            <label>Logo Footer:</label>
                            <input type="file" name="logo_footer" class="form-control">
                            @isset($logoFooter)
                                <img src="{{ asset('storage/' . $logoFooter) }}" width="100" class="mt-2">
                            @endisset
                        </div>

                        <div class="form-group">
                            <label>Texto do Modal de Boas Vindas:</label>
                            <textarea name="modal_boas_vindas" class="form-control">{{ $modalBoasVindas ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Login & Registro -->
                <div class="tab-pane fade" id="login">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Imagem Tela de Login:</label>
                            <input type="file" name="login_image" class="form-control">
                            @if($loginImage)
                                <img src="{{ asset('storage/' . $loginImage) }}" width="150" class="mt-2">
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Imagem Tela de Registro:</label>
                            <input type="file" name="register_image" class="form-control">
                            @if($registerImage)
                                <img src="{{ asset('storage/' . $registerImage) }}" width="150" class="mt-2">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Página Inicial -->
                <div class="tab-pane fade" id="home">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Modo de Exibição:</label>
                            <select name="home_mode" class="form-control">
                                <option value="slider" {{ $homeMode === 'slider' ? 'selected' : '' }}>Slider</option>
                                <option value="imagem" {{ $homeMode === 'imagem' ? 'selected' : '' }}>Imagem Padrão</option>
                            </select>
                        </div>

                        <div class="form-group" id="sliderImages">
                            <label>Imagens Slider:</label>
                            <input type="file" name="slider_images[]" class="form-control" multiple>
                            @if(!empty($sliderImages))
                                <div class="mt-3">
                                    @foreach($sliderImages as $img)
                                        <img src="{{ asset('storage/' . $img) }}" width="100" class="m-1">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="form-group" id="imagemHome">
                            <label>Imagem Padrão Home:</label>
                            <input type="file" name="home_image" class="form-control">
                            @isset($homeImage)
                                <img src="{{ asset('storage/' . $homeImage) }}" width="150" class="mt-2">
                            @endisset
                        </div>

                        <div class="form-group">
                            <label>Título da Página Inicial:</label>
                            <input type="text" name="home_title" class="form-control" value="{{ $homeTitle  ?? ''}}">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Sistema -->
                <div class="tab-pane fade" id="sistema">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tipo de Sistema:</label>
                            <select name="sistema_tipo" class="form-control">
                                <option value="passeio" {{ $sistemaTipo == 'passeio' ? 'selected' : '' }}>Passeio</option>
                                <option value="estetica" {{ $sistemaTipo == 'estetica' ? 'selected' : '' }}>Estética</option>
                                <option value="manicure" {{ $sistemaTipo == 'manicure' ? 'selected' : '' }}>Manicure</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Redes Sociais -->
                <div class="tab-pane fade" id="redes">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Instagram:</label>
                            <input type="text" name="instagram" class="form-control" value="{{ $instagram  ?? ''}}">
                        </div>
                        <div class="form-group">
                            <label>WhatsApp:</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $whatsapp  ?? ''}}">
                        </div>
                        <div class="form-group">
                            <label>TikTok:</label>
                            <input type="text" name="tiktok" class="form-control" value="{{ $tiktok  ?? ''}}">
                        </div>
                        <div class="form-group">
                            <label>E-mail:</label>
                            <input type="email" name="email" class="form-control" value="{{ $email  ?? ''}}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Políticas & Termos -->
                <div class="tab-pane fade" id="legais">
                    <form action="{{ route('configuracoes.salvar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Política de Privacidade:</label>
                            <textarea name="politica_privacidade" class="form-control">{{ $politicaPrivacidade ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Termos e Condições:</label>
                            <textarea name="termos_condicoes" class="form-control">{{ $termosCondicoes ?? ''}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const homeMode = document.querySelector('select[name="home_mode"]');
            const sliderField = document.getElementById("sliderImages");
            const imagemField = document.getElementById("imagemHome");

            function toggleHomeFields() {
                if(homeMode.value === 'slider') {
                    sliderField.style.display = 'block';
                    imagemField.style.display = 'none';
                } else {
                    sliderField.style.display = 'none';
                    imagemField.style.display = 'block';
                }
            }

            homeMode.addEventListener("change", toggleHomeFields);
            toggleHomeFields();
        });
    </script>
</x-admin.layout>
