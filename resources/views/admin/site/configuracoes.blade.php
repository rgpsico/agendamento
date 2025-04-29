<x-admin.layout title="Configurações do Site">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title"></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active"></li>
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

                                <div class="form-group">
                                    <label for="cores[primaria]">Cor Primária</label>
                                    <input type="color" name="cores[primaria]" value="{{ old('cores.primaria', $site->cores['primaria'] ?? '#0ea5e9') }}" class="form-control form-control-color">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cores[secundaria]">Cor Secundária</label>
                                    <input type="color" name="cores[secundaria]" value="{{ old('cores.secundaria', $site->cores['secundaria'] ?? '#38b2ac') }}" class="form-control form-control-color">
                                </div>

                                <div class="form-group">
                                    <label for="sobre_titulo">Título da seção "Sobre Nós"</label>
                                    <input type="text" name="sobre_titulo" value="{{ old('sobre_titulo', $site->sobre_titulo ?? '') }}" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="sobre_descricao">Descrição da seção</label>
                                    <textarea name="sobre_descricao" rows="4" class="form-control">{{ old('sobre_descricao', $site->sobre_descricao ?? '') }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="sobre_imagem">Imagem</label>
                                    <input type="file" name="sobre_imagem" class="form-control">
                                    @if (!empty($site->sobre_imagem))
                                        <img src="{{ asset('storage/' . $site->sobre_imagem) }}" height="100">
                                    @endif
                                </div>
                                
                                {{-- Itens dinâmicos (você pode melhorar com Vue ou Livewire depois) --}}
                                <div class="form-group">
                                    <label for="sobre_itens">Itens (JSON manual por enquanto)</label>
                                    <textarea name="sobre_itens" rows="4" class="form-control">{{ json_encode($site->sobre_itens ?? [], JSON_PRETTY_PRINT) }}</textarea>
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
</x-admin.layout>
