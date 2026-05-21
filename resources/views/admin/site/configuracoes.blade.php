<x-admin.layout title="Configurações do Site">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Gerenciar Site</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Site</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Configurações do Site</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            @if(isset($site))
                                <form action="{{ route('admin.site.configuracoes.update', $site->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                            @else
                            <form action="{{ route('admin.site.configuracoes.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                                @csrf

                                <!-- Seção 1: Informações gerais -->
                                <div class="form-section mb-4">
                                    <h5 class="mb-3">Informações Gerais</h5>
                                    <div class="form-group">
                                        <label for="titulo">Título do Site</label>
                                        <input type="text" name="titulo" value="{{ old('titulo', $site->titulo ?? '') }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $site->descricao ?? '') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <input type="file" name="logo" class="form-control">
                                        @if (!empty($site->logo))
                                            <img src="{{ asset('storage/' . $site->logo) }}" alt="Logo atual" height="60" class="mt-2">
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="capa">Capa</label>
                                        <input type="file" name="capa" class="form-control">
                                        @if (!empty($site->capa))
                                            <img src="{{ asset('storage/' . $site->capa) }}" alt="Capa atual" height="80" class="mt-2">
                                        @endif
                                    </div>
                                </div>

                                <!-- Seção 2: Cores -->
                                <div class="form-section mb-4">
                                    <h5 class="mb-3">Cores do Tema</h5>
                                    <div class="form-group">
                                        <label for="cores[primaria]">Cor Primária</label>
                                        <input type="color" name="cores[primaria]" value="{{ old('cores.primaria', $site->cores['primaria'] ?? '#0ea5e9') }}" class="form-control form-control-color">
                                    </div>

                                    <div class="form-group">
                                        <label for="cores[secundaria]">Cor Secundária</label>
                                        <input type="color" name="cores[secundaria]" value="{{ old('cores.secundaria', $site->cores['secundaria'] ?? '#38b2ac') }}" class="form-control form-control-color">
                                    </div>
                                </div>

                                <!-- Seção 3: Sobre Nós -->
                                <div class="form-section mb-4">
                                    <h5 class="mb-3">Seção "Sobre Nós"</h5>

                                    <div class="form-group">
                                        <label for="sobre_titulo">Título</label>
                                        <input type="text" name="sobre_titulo" value="{{ old('sobre_titulo', $site->sobre_titulo ?? '') }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="sobre_descricao">Descrição</label>
                                        <textarea name="sobre_descricao" class="form-control" style="min-height: 300px;">{{ old('sobre_descricao', $site->sobre_descricao ?? '') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="sobre_imagem">Imagem</label>
                                        <input type="file" name="sobre_imagem" class="form-control">
                                        @if (!empty($site->sobre_imagem))
                                            <img src="{{ asset('storage/' . $site->sobre_imagem) }}" height="100">
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>Itens do Sobre Nós</label>
                                        <div id="itens-container">
                                            @php
                                                $sobreItens = old('sobre_itens', $site->sobre_itens ?? []);
                                            @endphp

                                            @foreach($sobreItens as $index => $item)
                                            <div class="item-bloco border p-3 mb-3 bg-light rounded position-relative">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()">Remover</button>
                                                <input type="text" name="sobre_itens[{{ $index }}][icone]" class="form-control mb-2" placeholder="Classe do Ícone (ex: fas fa-heart)" value="{{ $item['icone'] ?? '' }}">
                                                <input type="text" name="sobre_itens[{{ $index }}][titulo]" class="form-control mb-2" placeholder="Título" value="{{ $item['titulo'] ?? '' }}">
                                                <textarea name="sobre_itens[{{ $index }}][descricao]" class="form-control" placeholder="Descrição">{{ $item['descricao'] ?? '' }}</textarea>
                                            </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-secondary mt-2" onclick="adicionarItem()">+ Adicionar Item</button>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end">
                                    <button class="btn btn-success">
                                        @if(isset($site))
                                            Atualizar
                                        @else
                                            Salvar
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══════════════════════════════════════
                 SEÇÃO: MARKETING & RASTREAMENTO
            ═══════════════════════════════════════ --}}
            @if(isset($site) && $site->id)
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                Marketing &amp; Rastreamento
                            </h4>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formNovoTracking">
                                <i class="fas fa-plus me-1"></i> Adicionar código
                            </button>
                        </div>
                        <div class="card-body">

                            {{-- Atalhos rápidos --}}
                            <div class="mb-4">
                                <p class="text-muted small mb-2">Adicionar rapidamente:</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="preencherTracking('Google Analytics GA4','google_analytics','analytics','')">
                                        <img src="https://www.google.com/favicon.ico" width="14" class="me-1"> Google Analytics
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="preencherTracking('Google Tag Manager','google_tag_manager','other','')">
                                        <img src="https://www.google.com/favicon.ico" width="14" class="me-1"> Tag Manager (GTM)
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="preencherTracking('Meta / Facebook Pixel','facebook_pixel','pixel','')">
                                        <img src="https://www.facebook.com/favicon.ico" width="14" class="me-1"> Meta Pixel
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="preencherTracking('TikTok Pixel','tiktok_pixel','pixel','')">
                                        🎵 TikTok Pixel
                                    </button>
                                    <button class="btn btn-outline-dark btn-sm" onclick="preencherTracking('Script personalizado','custom','other','',true)">
                                        <i class="fas fa-code me-1"></i> JavaScript livre
                                    </button>
                                </div>
                            </div>

                            {{-- Formulário de adição (colapsável) --}}
                            <div class="collapse mb-4" id="formNovoTracking">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="mb-3">Novo código de rastreamento</h6>
                                    <form action="{{ route('tracking.store', $site->id) }}" method="POST" id="formTracking">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Nome <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="t_name" class="form-control form-control-sm"
                                                       placeholder="Ex: Google Analytics" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Plataforma</label>
                                                <select name="provider" id="t_provider" class="form-select form-select-sm" onchange="toggleScriptField()">
                                                    <option value="google_analytics">Google Analytics (GA4)</option>
                                                    <option value="google_tag_manager">Google Tag Manager</option>
                                                    <option value="facebook_pixel">Meta / Facebook Pixel</option>
                                                    <option value="tiktok_pixel">TikTok Pixel</option>
                                                    <option value="custom">JavaScript personalizado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Tipo</label>
                                                <select name="type" id="t_type" class="form-select form-select-sm">
                                                    <option value="analytics">Analytics</option>
                                                    <option value="pixel">Pixel</option>
                                                    <option value="ads">Ads</option>
                                                    <option value="other">Outro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check ms-2 mb-1">
                                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="t_status" checked>
                                                    <label class="form-check-label small" for="t_status">Ativo</label>
                                                </div>
                                            </div>

                                            {{-- ID do código --}}
                                            <div class="col-12" id="wrapCode">
                                                <label class="form-label">
                                                    ID do código <span class="text-danger">*</span>
                                                    <small class="text-muted" id="codeHint">Ex: G-XXXXXXXXXX para GA4</small>
                                                </label>
                                                <input type="text" name="code" id="t_code" class="form-control form-control-sm"
                                                       placeholder="Cole aqui o ID">
                                            </div>

                                            {{-- Script livre (para custom) --}}
                                            <div class="col-12" id="wrapScript" style="display:none">
                                                <label class="form-label">
                                                    Código JavaScript completo
                                                    <small class="text-muted">Cole o script inteiro, incluindo a tag &lt;script&gt;</small>
                                                </label>
                                                <textarea name="script" id="t_script" class="form-control form-control-sm"
                                                          rows="6" style="font-family:monospace;font-size:12px"
                                                          placeholder="&lt;script&gt;...&lt;/script&gt;"></textarea>
                                                {{-- code precisa ter algum valor quando for custom --}}
                                                <input type="hidden" name="code" value="custom" id="t_code_hidden" style="display:none">
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-save me-1"></i> Salvar código
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-toggle="collapse" data-bs-target="#formNovoTracking">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Lista de códigos cadastrados --}}
                            @php $trackings = $site->trackingCodes ?? collect(); @endphp
                            @if($trackings->isEmpty())
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-chart-bar fa-2x mb-2 d-block opacity-25"></i>
                                    Nenhum código cadastrado ainda.
                                    <br><small>Clique em "Adicionar código" ou use os atalhos acima.</small>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Plataforma</th>
                                                <th>ID / Código</th>
                                                <th>Tipo</th>
                                                <th>Status</th>
                                                <th style="width:80px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trackings as $tc)
                                            <tr>
                                                <td class="fw-semibold">{{ $tc->name }}</td>
                                                <td>
                                                    @php
                                                      $icons = [
                                                        'google_analytics'   => '📊',
                                                        'google_tag_manager' => '🏷️',
                                                        'facebook_pixel'     => '🎯',
                                                        'tiktok_pixel'       => '🎵',
                                                        'custom'             => '💻',
                                                      ];
                                                      $labels = [
                                                        'google_analytics'   => 'Google Analytics',
                                                        'google_tag_manager' => 'Tag Manager',
                                                        'facebook_pixel'     => 'Meta Pixel',
                                                        'tiktok_pixel'       => 'TikTok Pixel',
                                                        'custom'             => 'JS Personalizado',
                                                      ];
                                                    @endphp
                                                    {{ $icons[$tc->provider] ?? '🔧' }}
                                                    {{ $labels[$tc->provider] ?? $tc->provider }}
                                                </td>
                                                <td>
                                                    @if($tc->provider === 'custom')
                                                        <span class="text-muted small font-monospace">script inline</span>
                                                    @else
                                                        <code class="text-primary small">{{ $tc->code }}</code>
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $tc->type }}</span></td>
                                                <td>
                                                    @if($tc->status)
                                                        <span class="badge bg-success">Ativo</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inativo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('tracking.destroy', $tc->id) }}" method="POST"
                                                          onsubmit="return confirm('Remover este código de rastreamento?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Remover">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>{{-- card-body --}}
                    </div>{{-- card --}}
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
    // ── Tracking: preenche atalhos rápidos ──
    function preencherTracking(nome, provider, tipo, codigo, isCustom = false) {
        document.getElementById('t_name').value     = nome;
        document.getElementById('t_provider').value = provider;
        document.getElementById('t_type').value     = tipo;
        document.getElementById('t_code').value     = codigo;

        // Garante que hints aparecem corretos
        toggleScriptField();

        // Abre o collapse se estiver fechado
        const el = document.getElementById('formNovoTracking');
        if (!el.classList.contains('show')) {
            new bootstrap.Collapse(el, { toggle: true });
        }
        // Foca no campo ID
        setTimeout(() => {
            isCustom
              ? document.getElementById('t_script').focus()
              : document.getElementById('t_code').focus();
        }, 350);
    }

    // ── Tracking: mostra campo ID ou script dependendo do provider ──
    const providerHints = {
        google_analytics:   'Ex: G-XXXXXXXXXX',
        google_tag_manager: 'Ex: GTM-XXXXXXX',
        facebook_pixel:     'Ex: 1234567890123',
        tiktok_pixel:       'Ex: CXXXXXXXXXXXXXXXX',
        custom:             '',
    };
    function toggleScriptField() {
        const provider = document.getElementById('t_provider').value;
        const isCustom = provider === 'custom';
        document.getElementById('wrapCode').style.display   = isCustom ? 'none' : '';
        document.getElementById('wrapScript').style.display = isCustom ? ''     : 'none';
        // Muda required
        document.getElementById('t_code').required   = !isCustom;
        document.getElementById('t_script').required = isCustom;
        // Atualiza hint
        document.getElementById('codeHint').textContent = providerHints[provider] || '';
    }

        let itemIndex = {{ count($sobreItens) }};
        function adicionarItem() {
            const container = document.getElementById('itens-container');
            const div = document.createElement('div');
            div.classList.add('item-bloco', 'border', 'p-3', 'mb-3', 'bg-light', 'rounded', 'position-relative');
            div.innerHTML = `
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()">Remover</button>
                <input type="text" name="sobre_itens[\${itemIndex}][icone]" class="form-control mb-2" placeholder="Classe do Ícone (ex: fas fa-heart)">
                <input type="text" name="sobre_itens[\${itemIndex}][titulo]" class="form-control mb-2" placeholder="Título">
                <textarea name="sobre_itens[\${itemIndex}][descricao]" class="form-control" placeholder="Descrição"></textarea>
            `;
            container.appendChild(div);
            itemIndex++;
        }
    </script>

    <style>
        .form-section {
            padding: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 2rem;
            background-color: #f9f9f9;
        }
    </style>
</x-admin.layout>