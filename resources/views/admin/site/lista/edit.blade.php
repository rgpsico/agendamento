<x-admin.layout title="Configurações do Site">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Gerenciar Site</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Configurações do Site</li>
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

                           
                           <form action="{{ route('admin.site.configuracoes.update', $site->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')                      
                                @csrf

                            <div class="form-group mb-3">
                                <label for="template_id">Template do Site</label>
                                <select name="template_id" id="template_id" class="form-control">
                                    <option value="">-- Selecione um template --</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" {{ old('template_id', $site->template_id) == $template->id ? 'selected' : '' }}>
                                            {{ $template->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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
                                        <label for="whatsapp">Número do WhatsApp</label>
                                        <input type="tel" name="whatsapp" value="{{ old('whatsapp', $site->whatsapp ?? '') }}" class="form-control" placeholder="+55 21 99999-9999">
                                    </div>

                                    <!-- NOVO: Autoatendimento com IA -->
                                    <div class="form-group form-check mt-4 my-5">
                                        <input type="checkbox" name="autoatendimento_ia" id="autoatendimento_ia" class="form-check-input" 
                                        {{ old('autoatendimento_ia', $site->autoatendimento_ia ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoatendimento_ia">Ativar Autoatendimento com IA</label>
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


                                <!-- Seção 4: Serviços -->
                            <div class="form-section mb-4">
                                <h5 class="mb-3">Serviços</h5>

                                <div id="servicos-container">
                                    @php
                                        $servicos = old('servicos', $site->siteServicos ?? []);
                                    @endphp

                                    @foreach($servicos as $index => $servico)
                                    <div class="servico-bloco border p-3 mb-3 bg-light rounded position-relative">
                                          <input type="hidden" name="servicos[{{ $index }}][id]" value="{{ $servico->id }}">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" data-idservico="{{ $servico->id }}" id="servicoRemover" >Remover</button>
                                        <input type="text" name="servicos[{{ $index }}][titulo]" class="form-control mb-2" placeholder="Título do Serviço" value="{{ $servico['titulo'] ?? '' }}">
                                        <textarea name="servicos[{{ $index }}][descricao]" class="form-control mb-2" placeholder="Descrição do Serviço">{{ $servico['descricao'] ?? '' }}</textarea>
                                        <input type="number" step="0.01" name="servicos[{{ $index }}][preco]" class="form-control mb-2" placeholder="Preço" value="{{ $servico['preco'] ?? '' }}">
                                        <input type="file" name="servicos[{{ $index }}][imagem]" class="form-control">
                                        @if(!empty($servico['imagem']))
                                            <img src="{{ asset('storage/' . $servico['imagem']) }}" height="80" class="mt-2">
                                        @endif
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-secondary mt-2" onclick="adicionarServico()">+ Adicionar Serviço</button>
                            </div>

                            <!-- Seção 5: Depoimentos -->
                            <div class="form-section mb-4">
                                <h5 class="mb-3">Depoimentos</h5>

                                <div id="depoimentos-container">
                                    @php
                                        $depoimentos = old('depoimentos', $site->depoimentos ?? []);
                                    @endphp

                                    @foreach($depoimentos as $index => $depoimento)
                                    <div class="depoimento-bloco border p-3 mb-3 bg-light rounded position-relative">
                                         <input type="hidden" name="depoimentos[{{ $index }}][id]" value="{{ $depoimento->id }}">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" id="depoimentoremover" data-iddepoimento="{{ $depoimento->id }}" >Remover</button>
                                        <input type="text" name="depoimentos[{{ $index }}][nome]" class="form-control mb-2" placeholder="Nome" value="{{ $depoimento['nome'] ?? '' }}">
                                        <input type="number" name="depoimentos[{{ $index }}][nota]" class="form-control mb-2" placeholder="Nota (0 a 5)" min="0" max="5" value="{{ $depoimento['nota'] ?? '' }}">
                                        <textarea name="depoimentos[{{ $index }}][comentario]" class="form-control mb-2" placeholder="Comentário">{{ $depoimento['comentario'] ?? '' }}</textarea>
                                        <input type="file" name="depoimentos[{{ $index }}][foto]" class="form-control">
                                        @if(!empty($depoimento['foto']))
                                            <img src="{{ asset('storage/' . $depoimento['foto']) }}" height="80" class="mt-2">
                                        @endif
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-secondary mt-2" onclick="adicionarDepoimento()">+ Adicionar Depoimento</button>
                            </div>

                         <div class="form-section mb-4">
    <h5 class="mb-3">Códigos de Rastreamento</h5>

    <div id="tracking-container">
        @php
            $trackingCodes = old('tracking_codes', $site->trackingCodes ?? []);
        @endphp

        @foreach($trackingCodes as $index => $tracking)
        <div class="tracking-bloco border p-3 mb-3 bg-light rounded position-relative">
            <input type="hidden" name="tracking_codes[{{ $index }}][id]" value="{{ $tracking['id'] ?? '' }}">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" data-idtracking="{{ $tracking['id'] ?? '' }}" id="trackingRemover">Remover</button>
            
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Nome</label>
                    <input type="text" name="tracking_codes[{{ $index }}][name]" class="form-control" value="{{ $tracking['name'] ?? '' }}" placeholder="Ex: Google Analytics">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Provedor</label>
                    <input type="text" name="tracking_codes[{{ $index }}][provider]" class="form-control" value="{{ $tracking['provider'] ?? '' }}" placeholder="Ex: Google, Meta">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Código</label>
                    <input type="text" name="tracking_codes[{{ $index }}][code]" class="form-control" value="{{ $tracking['code'] ?? '' }}" placeholder="Ex: G-XXXXXX">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Tipo</label>
                    <select name="tracking_codes[{{ $index }}][type]" class="form-control">
                        <option value="analytics" {{ ($tracking['type'] ?? '') == 'analytics' ? 'selected' : '' }}>Analytics</option>
                        <option value="ads" {{ ($tracking['type'] ?? '') == 'ads' ? 'selected' : '' }}>Anúncios</option>
                        <option value="pixel" {{ ($tracking['type'] ?? '') == 'pixel' ? 'selected' : '' }}>Pixel</option>
                        <option value="other" {{ ($tracking['type'] ?? '') == 'other' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label>Script (opcional)</label>
                <textarea name="tracking_codes[{{ $index }}][script]" class="form-control" rows="3" placeholder="Cole aqui o script de rastreamento se necessário">{{ $tracking['script'] ?? '' }}</textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" name="tracking_codes[{{ $index }}][status]" class="form-check-input" value="1" {{ !empty($tracking['status']) ? 'checked' : '' }}>
                <label class="form-check-label">Ativo</label>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-secondary mt-2" onclick="adicionarTracking()">+ Adicionar Código</button>
</div>







                                <!-- Seção Domínio e Virtual Host -->
                               <div class="form-section mb-4">
                                <h5 class="mb-3">Domínio Personalizado e Virtual Host</h5>

                                <div class="form-group">
                                    <label for="dominio_personalizado">Domínio Personalizado (ex: meusite.com.br)</label>
                                    <input type="text" name="dominio_personalizado" value="{{ old('dominio_personalizado', $site->dominio_personalizado ?? '') }}" class="form-control" placeholder="dominio.com.br">
                                </div>

                                <div class="form-group form-check mt-3">
                                    <input type="checkbox" name="gerar_vhost" id="gerar_vhost" class="form-check-input"
                                    {{ old('gerar_vhost', $site->gerar_vhost ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gerar_vhost">Gerar Virtual Host para este site</label>
                                </div>

                                @if(!empty($site->dominio_personalizado))
                                    <div class="mt-2">
                                        <small>Domínio atual: <strong>{{ $site->dominio_personalizado }}</strong></small>
                                    </div>
                                @endif

                                {{-- Mensagem caso o VHost já esteja criado --}}
                                @if(!empty($site->dominio_personalizado) && $site->vhost_criado)
                                    <div class="alert alert-success mt-3">
                                        ✅ Virtual Host já configurado para este domínio.
                                    </div>
                                @endif
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


                     @isset($dnsStatus, $sslStatus)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Status do SSL</h5>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between">

                                {{-- Exibe status atual --}}
                                @if($sslStatus === true)
                                    <div class="alert alert-success mb-0 flex-grow-1 me-3">
                                        ✅ SSL Ativo para <strong>{{ $site->dominio_personalizado }}</strong>
                                    </div>
                                @else
                                    <div class="alert alert-danger mb-0 flex-grow-1 me-3">
                                        ❌ SSL Não Ativo para <strong>{{ $site->dominio_personalizado }}</strong>
                                    </div>
                                @endif

                                {{-- Botão de gerar SSL aparece só se DNS ok e SSL ainda não criado --}}
                                @if($dnsStatus === true && $sslStatus === false)
                                    <form action="{{ route('admin.gerarSSL') }}" method="POST" class="ms-3">
                                        @csrf
                                        <input type="hidden" name="site_id" value="{{ $site->id }}">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-lock-fill me-1"></i> Gerar SSL
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    @endisset


                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <script>
        $(document).ready(function() {
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
                            _token: $('meta[name="csrf-token"]').attr('content') // usa o CSRF do blade
                        },
                        success: function(response) {
                            alert('Depoimento removido com sucesso!');
                            location.reload(); // ou remove o bloco da tela sem reload
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
                    location.reload(); // ou remover só o bloco do DOM
                },
                error: function(xhr) {
                    alert('Erro ao remover serviço: ' + xhr.status);
                }
            });
        }
    });

        });

        


    let depoimentoIndex = {{ count($depoimentos) }};
    function adicionarDepoimento() {
        const container = document.getElementById('depoimentos-container');
        const div = document.createElement('div');
        div.classList.add('depoimento-bloco', 'border', 'p-3', 'mb-3', 'bg-light', 'rounded', 'position-relative');
        div.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()">Remover</button>
            <input type="text" name="depoimentos[\${depoimentoIndex}][nome]" class="form-control mb-2" placeholder="Nome">
            <input type="number" name="depoimentos[\${depoimentoIndex}][nota]" class="form-control mb-2" placeholder="Nota (0 a 5)" min="0" max="5">
            <textarea name="depoimentos[\${depoimentoIndex}][comentario]" class="form-control mb-2" placeholder="Comentário"></textarea>
            <input type="file" name="depoimentos[\${depoimentoIndex}][foto]" class="form-control">
        `;
        container.appendChild(div);
        depoimentoIndex++;
    }
</script>



    <script>
    let servicoIndex = {{ count($servicos) }};
    function adicionarServico() {
        const container = document.getElementById('servicos-container');
        const div = document.createElement('div');
        div.classList.add('servico-bloco', 'border', 'p-3', 'mb-3', 'bg-light', 'rounded', 'position-relative');
        div.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()">Remover</button>
            <input type="text" name="servicos[\${servicoIndex}][titulo]" class="form-control mb-2" placeholder="Título do Serviço">
            <textarea name="servicos[\${servicoIndex}][descricao]" class="form-control mb-2" placeholder="Descrição do Serviço"></textarea>
            <input type="number" step="0.01" name="servicos[\${servicoIndex}][preco]" class="form-control mb-2" placeholder="Preço">
            <input type="file" name="servicos[\${servicoIndex}][imagem]" class="form-control">
        `;
        container.appendChild(div);
        servicoIndex++;
    }
</script>



<script>
    let trackingIndex = {{ count($trackingCodes) }};
    function adicionarTracking() {
        const container = document.getElementById('tracking-container');
        const div = document.createElement('div');
        div.classList.add('tracking-bloco', 'border', 'p-3', 'mb-3', 'bg-light', 'rounded', 'position-relative');
        div.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()">Remover</button>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Nome</label>
                    <input type="text" name="tracking_codes[${trackingIndex}][name]" class="form-control" placeholder="Ex: Google Analytics">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Provedor</label>
                    <input type="text" name="tracking_codes[${trackingIndex}][provider]" class="form-control" placeholder="Ex: Google, Meta">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Código</label>
                    <input type="text" name="tracking_codes[${trackingIndex}][code]" class="form-control" placeholder="Ex: G-XXXXXX">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Tipo</label>
                    <select name="tracking_codes[${trackingIndex}][type]" class="form-control">
                        <option value="analytics">Analytics</option>
                        <option value="ads">Anúncios</option>
                        <option value="pixel">Pixel</option>
                        <option value="other">Outro</option>
                    </select>
                </div>
            </div>
            <div class="mb-2">
                <label>Script (opcional)</label>
                <textarea name="tracking_codes[${trackingIndex}][script]" class="form-control" rows="3" placeholder="Cole aqui o script de rastreamento se necessário"></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" name="tracking_codes[${trackingIndex}][status]" class="form-check-input" value="1">
                <label class="form-check-label">Ativo</label>
            </div>
        `;
        container.appendChild(div);
        trackingIndex++;
    }

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
                location.reload(); // ou remover o bloco do DOM
            },
            error: function(xhr) {
                alert('Erro ao remover: ' + xhr.status);
            }
        });
    }
});

</script>

    <script>
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