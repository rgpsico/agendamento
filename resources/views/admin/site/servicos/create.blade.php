<x-admin.layout title="Adicionar Serviço">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Adicionar Serviço</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.site.servicos.index') }}">Serviços</a></li>
                            <li class="breadcrumb-item active">Adicionar Serviço</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Novo Serviço</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            <form action="{{ route('admin.site.servicos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input type="text" name="titulo" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea name="descricao" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="preco">Preço (opcional)</label>
                                    <input type="number" step="0.01" name="preco" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="imagem">Imagem (opcional)</label>
                                    <input type="file" name="imagem" class="form-control">
                                </div>

                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
