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

                           
                                <form action="{{ route('admin.site.store') }}" method="POST" enctype="multipart/form-data">
                      
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

        </div>
    </div>

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