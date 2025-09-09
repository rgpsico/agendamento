<x-admin.layout title="Configurações do Site">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <div class="page-header modern-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-content">
                            <h3 class="page-title">
                                <i class="fas fa-cog me-2"></i>
                                Gerenciar Site
                            </h3>
                            <ul class="breadcrumb modern-breadcrumb">
                                <li class="breadcrumb-item"><a href=""><i class="fas fa-home me-1"></i>Admin</a></li>
                                <li class="breadcrumb-item active">Configurações do Site</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card modern-card">
                        <div class="card-header modern-card-header">
                            <h4 class="card-title">
                                <i class="fas fa-palette me-2"></i>
                                Configurações do Site
                            </h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                           <form action="{{ route('admin.site.configuracoes.update', $site->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')                      
                                @csrf

                            <!-- Template Selection -->
                            <div class="form-group mb-4">
                                <label for="template_id" class="form-label">
                                    <i class="fas fa-layout me-2"></i>Template do Site
                                </label>
                                <select name="template_id" id="template_id" class="form-control modern-select">
                                    <option value="">-- Selecione um template --</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" {{ old('template_id', $site->template_id) == $template->id ? 'selected' : '' }}>
                                            {{ $template->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                                <!-- Seção 1: Informações gerais -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-info-circle section-icon"></i>
                                            Informações Gerais
                                        </h5>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="titulo" class="form-label">
                                                    <i class="fas fa-heading me-1"></i>Título do Site
                                                </label>
                                                <input type="text" name="titulo" value="{{ old('titulo', $site->titulo ?? '') }}" class="form-control modern-input">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="whatsapp" class="form-label">
                                                    <i class="fab fa-whatsapp me-1"></i>Número do WhatsApp
                                                </label>
                                                <input type="tel" name="whatsapp" value="{{ old('whatsapp', $site->whatsapp ?? '') }}" class="form-control modern-input" placeholder="+55 21 99999-9999">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="descricao" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>Descrição
                                        </label>
                                        <textarea name="descricao" class="form-control modern-textarea" rows="4">{{ old('descricao', $site->descricao ?? '') }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group upload-group">
                                                <label for="logo" class="form-label">
                                                    <i class="fas fa-image me-1"></i>Logo
                                                </label>
                                                <div class="upload-wrapper">
                                                    <input type="file" name="logo" class="form-control modern-file">
                                                    @if (!empty($site->logo))
                                                        <div class="current-image">
                                                            <img src="{{ asset('storage/' . $site->logo) }}" alt="Logo atual" class="preview-img">
                                                            <span class="image-label">Logo atual</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group upload-group">
                                                <label for="capa" class="form-label">
                                                    <i class="fas fa-panorama me-1"></i>Capa
                                                </label>
                                                <div class="upload-wrapper">
                                                    <input type="file" name="capa" class="form-control modern-file">
                                                    @if (!empty($site->capa))
                                                        <div class="current-image">
                                                            <img src="{{ asset('storage/' . $site->capa) }}" alt="Capa atual" class="preview-img">
                                                            <span class="image-label">Capa atual</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NOVO: Autoatendimento com IA -->
                                    <div class="form-group">
                                        <div class="modern-checkbox">
                                            <input type="checkbox" name="autoatendimento_ia" id="autoatendimento_ia" class="form-check-input" 
                                            {{ old('autoatendimento_ia', $site->autoatendimento_ia ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="autoatendimento_ia">
                                                <i class="fas fa-robot me-2"></i>
                                                Ativar Autoatendimento com IA
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seção 2: Cores -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-palette section-icon"></i>
                                            Cores do Tema
                                        </h5>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group color-group">
                                                <label for="cores[primaria]" class="form-label">
                                                    <i class="fas fa-circle me-1" style="color: {{ old('cores.primaria', $site->cores['primaria'] ?? '#0ea5e9') }}"></i>
                                                    Cor Primária
                                                </label>
                                                <div class="color-wrapper">
                                                    <input type="color" name="cores[primaria]" value="{{ old('cores.primaria', $site->cores['primaria'] ?? '#0ea5e9') }}" class="form-control form-control-color modern-color">
                                                    <span class="color-value">{{ old('cores.primaria', $site->cores['primaria'] ?? '#0ea5e9') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group color-group">
                                                <label for="cores[secundaria]" class="form-label">
                                                    <i class="fas fa-circle me-1" style="color: {{ old('cores.secundaria', $site->cores['secundaria'] ?? '#38b2ac') }}"></i>
                                                    Cor Secundária
                                                </label>
                                                <div class="color-wrapper">
                                                    <input type="color" name="cores[secundaria]" value="{{ old('cores.secundaria', $site->cores['secundaria'] ?? '#38b2ac') }}" class="form-control form-control-color modern-color">
                                                    <span class="color-value">{{ old('cores.secundaria', $site->cores['secundaria'] ?? '#38b2ac') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seção 3: Sobre Nós -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-users section-icon"></i>
                                            Seção "Sobre Nós"
                                        </h5>
                                    </div>

                                    <div class="form-group">
                                        <label for="sobre_titulo" class="form-label">
                                            <i class="fas fa-heading me-1"></i>Título
                                        </label>
                                        <input type="text" name="sobre_titulo" value="{{ old('sobre_titulo', $site->sobre_titulo ?? '') }}" class="form-control modern-input">
                                    </div>

                                    <div class="form-group">
                                        <label for="sobre_descricao" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>Descrição
                                        </label>
                                        <textarea name="sobre_descricao" class="form-control modern-textarea" style="min-height: 300px;">{{ old('sobre_descricao', $site->sobre_descricao ?? '') }}</textarea>
                                    </div>

                                    <div class="form-group upload-group">
                                        <label for="sobre_imagem" class="form-label">
                                            <i class="fas fa-image me-1"></i>Imagem
                                        </label>
                                        <div class="upload-wrapper">
                                            <input type="file" name="sobre_imagem" class="form-control modern-file">
                                            @if (!empty($site->sobre_imagem))
                                                <div class="current-image">
                                                    <img src="{{ asset('storage/' . $site->sobre_imagem) }}" class="preview-img">
                                                    <span class="image-label">Imagem atual</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-list me-1"></i>Itens do Sobre Nós
                                        </label>
                                        <div id="itens-container" class="dynamic-container">
                                            @php
                                                $sobreItens = old('sobre_itens', $site->sobre_itens ?? []);
                                            @endphp

                                            @foreach($sobreItens as $index => $item)
                                            <div class="dynamic-item">
                                                <div class="item-header">
                                                    <span class="item-number">{{ $index + 1 }}</span>
                                                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.parentElement.remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="sobre_itens[{{ $index }}][icone]" class="form-control mb-2 modern-input" placeholder="Classe do Ícone (ex: fas fa-heart)" value="{{ $item['icone'] ?? '' }}">
                                                <input type="text" name="sobre_itens[{{ $index }}][titulo]" class="form-control mb-2 modern-input" placeholder="Título" value="{{ $item['titulo'] ?? '' }}">
                                                <textarea name="sobre_itens[{{ $index }}][descricao]" class="form-control modern-textarea" placeholder="Descrição">{{ $item['descricao'] ?? '' }}</textarea>
                                            </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-outline-primary add-btn" onclick="adicionarItem()">
                                            <i class="fas fa-plus me-1"></i>Adicionar Item
                                        </button>
                                    </div>
                                </div>

                                <!-- Seção 4: Serviços -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-concierge-bell section-icon"></i>
                                            Serviços
                                        </h5>
                                    </div>

                                    <div id="servicos-container" class="dynamic-container">
                                        @php
                                            $servicos = old('servicos', $site->siteServicos ?? []);
                                        @endphp

                                        @foreach($servicos as $index => $servico)
                                            <div class="dynamic-item">
                                                <input type="hidden" name="servicos[{{ $index }}][id]" value="{{ is_object($servico) ? $servico->id : ($servico['id'] ?? '') }}">
                                                <div class="item-header">
                                                    <span class="item-number">{{ $index + 1 }}</span>
                                                    <button type="button" class="btn btn-danger btn-sm remove-btn" data-idservico="{{ is_object($servico) ? $servico->id : ($servico['id'] ?? '') }}" id="servicoRemover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" name="servicos[{{ $index }}][titulo]" class="form-control mb-2 modern-input" placeholder="Título do Serviço" value="{{ is_object($servico) ? $servico->titulo : ($servico['titulo'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <span class="input-group-text">R$</span>
                                                            <input type="number" step="0.01" name="servicos[{{ $index }}][preco]" class="form-control modern-input" placeholder="Preço" value="{{ is_object($servico) ? $servico->preco : ($servico['preco'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <textarea name="servicos[{{ $index }}][descricao]" class="form-control mb-2 modern-textarea" placeholder="Descrição do Serviço">{{ is_object($servico) ? $servico->descricao : ($servico['descricao'] ?? '') }}</textarea>
                                                <div class="upload-wrapper">
                                                    <input type="file" name="servicos[{{ $index }}][imagem]" class="form-control modern-file">
                                                    @if(!empty($servico['imagem']) && is_array($servico))
                                                        <div class="current-image">
                                                            <img src="{{ asset('storage/' . $servico['imagem']) }}" class="preview-img-small">
                                                        </div>
                                                    @elseif(is_object($servico) && !empty($servico->imagem))
                                                        <div class="current-image">
                                                            <img src="{{ asset('storage/' . $servico->imagem) }}" class="preview-img-small">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-outline-primary add-btn" onclick="adicionarServico()">
                                        <i class="fas fa-plus me-1"></i>Adicionar Serviço
                                    </button>
                                </div>

                                <!-- Seção 5: Depoimentos -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-comments section-icon"></i>
                                            Depoimentos
                                        </h5>
                                    </div>

                                    <div id="depoimentos-container" class="dynamic-container">
                                        @php
                                            $depoimentos = old('depoimentos', $site->depoimentos ?? []);
                                        @endphp

                                        @foreach($depoimentos as $index => $depoimento)
                                        <div class="dynamic-item">
                                             <input type="hidden" name="depoimentos[{{ $index }}][id]" value="{{ $depoimento->id }}">
                                            <div class="item-header">
                                                <span class="item-number">{{ $index + 1 }}</span>
                                                <button type="button" class="btn btn-danger btn-sm remove-btn" id="depoimentoremover" data-iddepoimento="{{ $depoimento->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" name="depoimentos[{{ $index }}][nome]" class="form-control mb-2 modern-input" placeholder="Nome" value="{{ $depoimento['nome'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="rating-input">
                                                        <label class="small-label">Nota (0 a 5)</label>
                                                        <input type="number" name="depoimentos[{{ $index }}][nota]" class="form-control modern-input" placeholder="Nota" min="0" max="5" value="{{ $depoimento['nota'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="depoimentos[{{ $index }}][comentario]" class="form-control mb-2 modern-textarea" placeholder="Comentário">{{ $depoimento['comentario'] ?? '' }}</textarea>
                                            <div class="upload-wrapper">
                                                <input type="file" name="depoimentos[{{ $index }}][foto]" class="form-control modern-file">
                                                @if(!empty($depoimento['foto']))
                                                    <div class="current-image">
                                                        <img src="{{ asset('storage/' . $depoimento['foto']) }}" class="preview-img-small rounded-circle">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-outline-primary add-btn" onclick="adicionarDepoimento()">
                                        <i class="fas fa-plus me-1"></i>Adicionar Depoimento
                                    </button>
                                </div>

                                <!-- Seção 6: Códigos de Rastreamento -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-chart-line section-icon"></i>
                                            Códigos de Rastreamento
                                        </h5>
                                    </div>

                                    <div id="tracking-container" class="dynamic-container">
                                        @php
                                            $trackingCodes = old('tracking_codes', $site->trackingCodes ?? []);
                                        @endphp

                                        @foreach($trackingCodes as $index => $tracking)
                                        <div class="dynamic-item">
                                            <input type="hidden" name="tracking_codes[{{ $index }}][id]" value="{{ $tracking['id'] ?? '' }}">
                                            <div class="item-header">
                                                <span class="item-number">{{ $index + 1 }}</span>
                                                <button type="button" class="btn btn-danger btn-sm remove-btn" data-idtracking="{{ $tracking['id'] ?? '' }}" id="trackingRemover">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Nome</label>
                                                    <input type="text" name="tracking_codes[{{ $index }}][name]" class="form-control modern-input" value="{{ $tracking['name'] ?? '' }}" placeholder="Ex: Google Analytics">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Provedor</label>
                                                    <input type="text" name="tracking_codes[{{ $index }}][provider]" class="form-control modern-input" value="{{ $tracking['provider'] ?? '' }}" placeholder="Ex: Google, Meta">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Código</label>
                                                    <input type="text" name="tracking_codes[{{ $index }}][code]" class="form-control modern-input" value="{{ $tracking['code'] ?? '' }}" placeholder="Ex: G-XXXXXX">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Tipo</label>
                                                    <select name="tracking_codes[{{ $index }}][type]" class="form-control modern-select">
                                                        <option value="analytics" {{ ($tracking['type'] ?? '') == 'analytics' ? 'selected' : '' }}>Analytics</option>
                                                        <option value="ads" {{ ($tracking['type'] ?? '') == 'ads' ? 'selected' : '' }}>Anúncios</option>
                                                        <option value="pixel" {{ ($tracking['type'] ?? '') == 'pixel' ? 'selected' : '' }}>Pixel</option>
                                                        <option value="other" {{ ($tracking['type'] ?? '') == 'other' ? 'selected' : '' }}>Outro</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label">Script (opcional)</label>
                                                <textarea name="tracking_codes[{{ $index }}][script]" class="form-control modern-textarea" rows="3" placeholder="Cole aqui o script de rastreamento se necessário">{{ $tracking['script'] ?? '' }}</textarea>
                                            </div>

                                            <div class="modern-checkbox">
                                                <input type="checkbox" name="tracking_codes[{{ $index }}][status]" class="form-check-input" value="1" {{ !empty($tracking['status']) ? 'checked' : '' }}>
                                                <label class="form-check-label">Ativo</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-outline-primary add-btn" onclick="adicionarTracking()">
                                        <i class="fas fa-plus me-1"></i>Adicionar Código
                                    </button>
                                </div>

                                <!-- Seção Domínio e Virtual Host -->
                                <div class="form-section modern-section">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-globe section-icon"></i>
                                            Domínio Personalizado e Virtual Host
                                        </h5>
                                    </div>

                                    <div class="form-group">
                                        <label for="dominio_personalizado" class="form-label">
                                            <i class="fas fa-link me-1"></i>
                                            Domínio Personalizado
                                            <button type="button" class="btn btn-link p-0 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Como configurar domínio" onclick="toggleDominioHelp()">
                                                <i class="fas fa-question-circle text-info"></i>
                                            </button>
                                        </label>
                                        <input type="text" name="dominio_personalizado" value="{{ old('dominio_personalizado', $site->dominio_personalizado ?? '') }}" class="form-control modern-input" placeholder="dominio.com.br">
                                        
                                        <!-- Help Box -->
                                        <div id="dominio-help" class="help-box mt-3" style="display: none;">
                                            <div class="help-content">
                                                <h6><i class="fas fa-info-circle me-2"></i>Como configurar seu domínio:</h6>
                                                <div class="help-steps">
                                                    <div class="help-step">
                                                        <span class="step-number">1</span>
                                                        <div class="step-content">
                                                            <strong>Registre seu domínio</strong>
                                                            <p>Registre seu domínio em sites como <a href="https://registro.br" target="_blank">Registro.br</a>, GoDaddy, Hostgator, ou outro registrador de sua preferência.</p>
                                                        </div>
                                                    </div>
                                                    <div class="help-step">
                                                        <span class="step-number">2</span>
                                                        <div class="step-content">
                                                            <strong>Configure o DNS</strong>
                                                            <p>No painel do seu registrador, aponte seu domínio para nossos servidores:</p>
                                                            <div class="dns-info">
                                                                <code>Tipo A: {{ request()->getHost() }}</code><br>
                                                                <code>CNAME: www → {{ request()->getHost() }}</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="help-step">
                                                        <span class="step-number">3</span>
                                                        <div class="step-content">
                                                            <strong>Digite seu domínio</strong>
                                                            <p>Após configurar o DNS (pode levar até 24h), digite seu domínio no campo acima e marque a opção "Gerar Virtual Host".</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="help-note">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>Importante:</strong> O SSL será gerado automaticamente após a configuração do DNS.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modern-checkbox">
                                        <input type="checkbox" name="gerar_vhost" id="gerar_vhost" class="form-check-input"
                                        {{ old('gerar_vhost', $site->gerar_vhost ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gerar_vhost">
                                            <i class="fas fa-server me-2"></i>
                                            Gerar Virtual Host para este site
                                        </label>
                                    </div>

                                    @if(!empty($site->dominio_personalizado))
                                        <div class="domain-status mt-3">
                                            <div class="status-item">
                                                <i class="fas fa-globe text-primary me-2"></i>
                                                <strong>Domínio atual:</strong> {{ $site->dominio_personalizado }}
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Mensagem caso o VHost já esteja criado --}}
                                    @if(!empty($site->dominio_personalizado) && $site->vhost_criado)
                                        <div class="alert alert-success modern-alert mt-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Virtual Host já configurado para este domínio.
                                        </div>
                                    @endif
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success btn-lg modern-btn">
                                        <i class="fas fa-save me-2"></i>
                                        @if(isset($site))
                                            Atualizar Configurações
                                        @else
                                            Salvar Configurações
                                        @endif
                                    </button>
                                </div>
                            </form>

                            @isset($dnsStatus, $sslStatus)
                            <div class="ssl-status-card mt-4">
                                <div class="card modern-card">
                                    <div class="card-header modern-card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-lock me-2"></i>Status do SSL
                                        </h5>
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        {{-- Exibe status atual --}}
                                        @if($sslStatus === true)
                                            <div class="alert alert-success modern-alert mb-0 flex-grow-1 me-3">
                                                <i class="fas fa-check-circle me-2"></i>
                                                SSL Ativo para <strong>{{ $site->dominio_personalizado }}</strong>
                                            </div>
                                        @else
                                            <div class="alert alert-danger modern-alert mb-0 flex-grow-1 me-3">
                                                <i class="fas fa-times-circle me-2"></i>
                                                SSL Não Ativo para <strong>{{ $site->dominio_personalizado }}</strong>
                                            </div>
                                        @endif

                                        {{-- Botão de gerar SSL aparece só se DNS ok e SSL ainda não criado --}}
                                        @if($dnsStatus === true && $sslStatus === false)
                                            <form action="{{ route('admin.gerarSSL') }}" method="POST" class="ms-3">
                                                @csrf
                                                <input type="hidden" name="site_id" value="{{ $site->id }}">
                                                <button type="submit" class="btn btn-success modern-btn">
                                                    <i class="fas fa-lock me-1"></i> Gerar SSL
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // Remove depoimento ao clicar no botão
            $(document).on('click', '#depoimentoremover', function() {
                const id = $(this).data('iddepoimento');

                if (!id) {
                    alert('ID do depoimento não encontrado!');
                    return;
                }

                if (confirm('Tem certeza que deseja remover este depoimento?')) {
                    $.ajax({
                        url: '/admin/site/depoimentos/' + id + '/destroy',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert('Depoimento removido com sucesso!');
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Erro ao remover depoimento: ' + xhr.status);
                        }
                    });
                }
            });

            $(document).on('click', '#servicoRemover', function() {
                const id = $(this).data('idservico');

                if (!id) {
                    alert('ID do serviço não encontrado!');
                    return;
                }

                if (confirm('Tem certeza que deseja remover este serviço?')) {
                    $.ajax({
                        url: '/admin/site/servicos/' + id + '/destroy',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert('Serviço removido com sucesso!');
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Erro ao remover serviço: ' + xhr.status);
                        }
                    });
                }
            });

            $(document).on('click', '#trackingRemover', function() {
                const id = $(this).data('idtracking');
                if (!id) return alert('ID do tracking não encontrado!');

                if (confirm('Tem certeza que deseja remover este código?')) {
                    $.ajax({
                        url: '/admin/tracking/' + id + '/destroy',
                        type: 'POST',
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            alert(response.message);
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Erro ao remover: ' + xhr.status);
                        }
                    });
                }
            });
        });

        // Toggle help box for domain configuration
        function toggleDominioHelp() {
            const helpBox = document.getElementById('dominio-help');
            if (helpBox.style.display === 'none') {
                helpBox.style.display = 'block';
            } else {
                helpBox.style.display = 'none';
            }
        }

        let depoimentoIndex = {{ count($depoimentos) }};
        function adicionarDepoimento() {
            const container = document.getElementById('depoimentos-container');
            const div = document.createElement('div');
            div.classList.add('dynamic-item');
            div.innerHTML = `
                <div class="item-header">
                    <span class="item-number">${depoimentoIndex + 1}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="depoimentos[${depoimentoIndex}][nome]" class="form-control mb-2 modern-input" placeholder="Nome">
                    </div>
                    <div class="col-md-4">
                        <div class="rating-input">
                            <label class="small-label">Nota (0 a 5)</label>
                            <input type="number" name="depoimentos[${depoimentoIndex}][nota]" class="form-control modern-input" placeholder="Nota" min="0" max="5">
                        </div>
                    </div>
                </div>
                <textarea name="depoimentos[${depoimentoIndex}][comentario]" class="form-control mb-2 modern-textarea" placeholder="Comentário"></textarea>
                <div class="upload-wrapper">
                    <input type="file" name="depoimentos[${depoimentoIndex}][foto]" class="form-control modern-file">
                </div>
            `;
            container.appendChild(div);
            depoimentoIndex++;
        }

        let servicoIndex = {{ count($servicos) }};
        function adicionarServico() {
            const container = document.getElementById('servicos-container');
            const div = document.createElement('div');
            div.classList.add('dynamic-item');
            div.innerHTML = `
                <div class="item-header">
                    <span class="item-number">${servicoIndex + 1}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="servicos[${servicoIndex}][titulo]" class="form-control mb-2 modern-input" placeholder="Título do Serviço">
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" step="0.01" name="servicos[${servicoIndex}][preco]" class="form-control modern-input" placeholder="Preço">
                        </div>
                    </div>
                </div>
                <textarea name="servicos[${servicoIndex}][descricao]" class="form-control mb-2 modern-textarea" placeholder="Descrição do Serviço"></textarea>
                <div class="upload-wrapper">
                    <input type="file" name="servicos[${servicoIndex}][imagem]" class="form-control modern-file">
                </div>
            `;
            container.appendChild(div);
            servicoIndex++;
        }

        let trackingIndex = {{ count($trackingCodes) }};
        function adicionarTracking() {
            const container = document.getElementById('tracking-container');
            const div = document.createElement('div');
            div.classList.add('dynamic-item');
            div.innerHTML = `
                <div class="item-header">
                    <span class="item-number">${trackingIndex + 1}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nome</label>
                        <input type="text" name="tracking_codes[${trackingIndex}][name]" class="form-control modern-input" placeholder="Ex: Google Analytics">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Provedor</label>
                        <input type="text" name="tracking_codes[${trackingIndex}][provider]" class="form-control modern-input" placeholder="Ex: Google, Meta">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Código</label>
                        <input type="text" name="tracking_codes[${trackingIndex}][code]" class="form-control modern-input" placeholder="Ex: G-XXXXXX">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Tipo</label>
                        <select name="tracking_codes[${trackingIndex}][type]" class="form-control modern-select">
                            <option value="analytics">Analytics</option>
                            <option value="ads">Anúncios</option>
                            <option value="pixel">Pixel</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Script (opcional)</label>
                    <textarea name="tracking_codes[${trackingIndex}][script]" class="form-control modern-textarea" rows="3" placeholder="Cole aqui o script de rastreamento se necessário"></textarea>
                </div>
                <div class="modern-checkbox">
                    <input type="checkbox" name="tracking_codes[${trackingIndex}][status]" class="form-check-input" value="1">
                    <label class="form-check-label">Ativo</label>
                </div>
            `;
            container.appendChild(div);
            trackingIndex++;
        }

        let itemIndex = {{ count($sobreItens) }};
        function adicionarItem() {
            const container = document.getElementById('itens-container');
            const div = document.createElement('div');
            div.classList.add('dynamic-item');
            div.innerHTML = `
                <div class="item-header">
                    <span class="item-number">${itemIndex + 1}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="text" name="sobre_itens[${itemIndex}][icone]" class="form-control mb-2 modern-input" placeholder="Classe do Ícone (ex: fas fa-heart)">
                <input type="text" name="sobre_itens[${itemIndex}][titulo]" class="form-control mb-2 modern-input" placeholder="Título">
                <textarea name="sobre_itens[${itemIndex}][descricao]" class="form-control modern-textarea" placeholder="Descrição"></textarea>
            `;
            container.appendChild(div);
            itemIndex++;
        }

        // Update color value display
        document.addEventListener('DOMContentLoaded', function() {
            const colorInputs = document.querySelectorAll('.modern-color');
            colorInputs.forEach(input => {
                const valueSpan = input.parentNode.querySelector('.color-value');
                if (valueSpan) {
                    input.addEventListener('input', function() {
                        valueSpan.textContent = this.value;
                        const icon = input.parentNode.parentNode.querySelector('label i');
                        if (icon) {
                            icon.style.color = this.value;
                        }
                    });
                }
            });
        });
    </script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --card-shadow: 0 10px 25px rgba(0,0,0,0.1);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .modern-header {
            background: var(--primary-gradient);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .header-content {
            padding: 1.5rem 0;
        }

        .page-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .modern-breadcrumb {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }

        .modern-breadcrumb a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .modern-breadcrumb a:hover {
            color: white;
        }

        .modern-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .modern-card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .modern-card-header .card-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modern-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .modern-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .section-header {
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .section-title {
            color: #374151;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .section-icon {
            color: #667eea;
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .form-label {
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .modern-input, .modern-select, .modern-textarea, .modern-file {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .modern-input:focus, .modern-select:focus, .modern-textarea:focus, .modern-file:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .modern-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modern-checkbox {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            transition: var(--transition);
        }

        .modern-checkbox:hover {
            background: #f3f4f6;
        }

        .modern-checkbox .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            margin-top: 0.1rem;
        }

        .modern-checkbox .form-check-label {
            font-weight: 500;
            color: #374151;
            margin-left: 0.5rem;
        }

        .color-group {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1rem;
        }

        .color-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modern-color {
            width: 60px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .color-value {
            font-family: 'Courier New', monospace;
            background: #374151;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .upload-group {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 1.5rem;
            transition: var(--transition);
        }

        .upload-group:hover {
            border-color: #667eea;
            background: #f3f4f6;
        }

        .current-image {
            margin-top: 1rem;
            text-align: center;
        }

        .preview-img {
            max-height: 80px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .preview-img-small {
            max-height: 60px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .image-label {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dynamic-container {
            border: 1px solid #e5e7eb;
            border-radius: var(--border-radius);
            padding: 1rem;
            background: #f9fafb;
        }

        .dynamic-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            transition: var(--transition);
        }

        .dynamic-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .item-number {
            background: var(--primary-gradient);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .remove-btn {
            background: var(--danger-gradient);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            color: white;
            transition: var(--transition);
        }

        .remove-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .add-btn {
            background: var(--success-gradient);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.3);
            color: white;
        }

        .modern-btn {
            background: var(--primary-gradient);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            padding: 0.75rem 2rem;
            transition: var(--transition);
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .form-actions {
            text-align: center;
            padding: 2rem 0;
            border-top: 2px solid #f3f4f6;
            margin-top: 2rem;
        }

        .modern-alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        .modern-alert.alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .modern-alert.alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .help-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .help-content h6 {
            color: #1565c0;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .help-steps {
            margin-bottom: 1rem;
        }

        .help-step {
            display: flex;
            margin-bottom: 1rem;
            align-items: flex-start;
        }

        .step-number {
            background: #2196f3;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 1rem;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-content strong {
            color: #1565c0;
            display: block;
            margin-bottom: 0.5rem;
        }

        .step-content p {
            margin: 0;
            color: #424242;
            font-size: 0.9rem;
        }

        .dns-info {
            background: #263238;
            color: #4fc3f7;
            padding: 0.75rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }

        .help-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 0.75rem;
            color: #856404;
            font-size: 0.9rem;
        }

        .domain-status {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            color: #495057;
        }

        .rating-input label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .small-label {
            font-size: 0.8rem !important;
            color: #6c757d !important;
        }

        .input-group-text {
            background: #f8f9fa;
            border-color: #e5e7eb;
            color: #495057;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .modern-section {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .help-step {
                flex-direction: column;
            }

            .step-number {
                margin-bottom: 0.5rem;
                margin-right: 0;
            }
        }
    </style>
</x-admin.layout>