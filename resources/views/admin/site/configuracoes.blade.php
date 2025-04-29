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
                                    <input type="color" name="cores[primaria]" value="{{ $site->cores['primaria'] ?? '#0ea5e9' }}" class="form-control form-control-color">
                                </div>

                                <div class="form-group">
                                    <label for="cores[secundaria]">Cor Secundária</label>
                                    <input type="color" name="cores[secundaria]" value="{{ $site->cores['secundaria'] ?? '#38b2ac' }}" class="form-control form-control-color">
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
