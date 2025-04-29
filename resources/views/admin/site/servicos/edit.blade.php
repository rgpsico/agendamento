<x-admin.layout title="Editar Serviço">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Editar Serviço</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.site.servicos.index') }}">Serviços</a></li>
                            <li class="breadcrumb-item active">Editar Serviço</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Editar Serviço</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            <form action="{{ route('admin.site.servicos.update', $servico->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $servico->titulo) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea name="descricao" class="form-control" rows="4" required>{{ old('descricao', $servico->descricao) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="preco">Preço</label>
                                    <input type="number" step="0.01" name="preco" class="form-control" value="{{ old('preco', $servico->preco) }}">
                                </div>

                                <div class="form-group">
                                    <label for="imagem">Imagem</label>
                                    <input type="file" name="imagem" class="form-control">
                                    @if($servico->imagem)
                                        <img src="{{ asset('storage/' . $servico->imagem) }}" alt="Imagem atual" width="100" class="mt-2">
                                    @endif
                                </div>

                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Atualizar</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
