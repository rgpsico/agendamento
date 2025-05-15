<x-admin.layout title="Configurações">
    <div class="page-wrapper">
        <div class="content container-fluid">
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
                    <a class="nav-link" data-bs-toggle="tab" href="#tipoSistema">Tipo de Sistema</a>
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
                            <label>Tipo de Agendamento:</label>
                            <select name="agendamento_tipo" class="form-control" id="tipoAgendamento">
                                <option value="horarios" {{ old('agendamento_tipo', $tipoAgendamento) == 'horarios' ? 'selected' : '' }}>Com Horário</option>
                                <option value="whatsapp" {{ old('agendamento_tipo', $tipoAgendamento) == 'whatsapp' ? 'selected' : '' }}>Via WhatsApp</option>
                            </select>
                            
                        </div>

                        <div class="form-group" id="whatsappField" style="{{ $tipoAgendamento === 'whatsapp' ? '' : 'display:none;' }}">
                            <label>Número do WhatsApp:</label>
                            <input type="text" name="whatsapp_numero" class="form-control" value="{{ $whatsappNumero }}" placeholder="Ex: 21999998888">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Login & Registro -->
                @if(Auth::user()->isAdmin)
                <div class="tab-pane fade" id="login">
                    <form action="{{ route('configuracoesGeral.salvar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Imagem da Tela de Login:</label>
                            <input type="file" name="login_image" class="form-control">
                            @if($loginImage)
                                <div class="mt-2">
                                    <img src="{{ $loginImage }}" alt="Imagem Login" width="150">
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Imagem da Tela de Registro:</label>
                            <input type="file" name="register_image" class="form-control">
                            @if($registerImage)
                                <div class="mt-2">
                                    <img src="{{ $registerImage }}" alt="Imagem Registro" width="150">
                                </div>
                            @endif
                        </div>
                        

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Página Inicial -->
                <div class="tab-pane fade" id="home">
                    <form action="{{ route('configuracoesGeral.salvar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Modo de Exibição:</label>
                            <select name="home_mode" class="form-control">
                                <option value="carousel" {{ $homeMode === 'carousel' ? 'selected' : '' }}>Carrossel</option>
                                <option value="breadcrumb" {{ $homeMode === 'breadcrumb' ? 'selected' : '' }}>Breadcrumb</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Imagens do Carrossel:</label>
                            <input type="file" name="carousel_images[]" class="form-control" multiple>
                            
                            @if(!empty($carouselImages))
                                <div class="mt-3">
                                    @foreach($carouselImages as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Imagem Carrossel" width="100" class="m-1">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>

                <!-- Aba: Tipo de Sistema -->
                <div class="tab-pane fade" id="tipoSistema">
                    <form action="{{ route('configuracoesGeral.salvar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Selecione o Tipo de Sistema:</label>
                            <select name="sistema_tipo" class="form-control">
                                <option value="passeio" {{ old('sistema_tipo', $sistemaTipo) == 'passeio' ? 'selected' : '' }}>Passeio Turístico</option>
                                <option value="estetica" {{ old('sistema_tipo', $sistemaTipo) == 'estetica' ? 'selected' : '' }}>Área de Estética</option>
                                <option value="manicure" {{ old('sistema_tipo', $sistemaTipo) == 'manicure' ? 'selected' : '' }}>Manicure</option>
                            </select>
                            
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tipoAgendamento = document.getElementById("tipoAgendamento");
            const whatsappField = document.getElementById("whatsappField");

            tipoAgendamento.addEventListener("change", function () {
                whatsappField.style.display = (this.value === "whatsapp") ? "block" : "none";
            });
        });
    </script>
</x-admin.layout>
