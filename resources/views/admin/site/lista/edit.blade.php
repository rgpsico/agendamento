<x-admin.layout title="Editar Virtual Host">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Editar Virtual Host</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('virtualhosts.index') }}">Virtual Hosts</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $fileName }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('virtualhosts.update', $fileName) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="content">Conteúdo do Virtual Host</label>
                                    <textarea name="content" id="content" rows="20" class="form-control">{{ $content }}</textarea>
                                </div>

                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                    <a href="{{ route('virtualhosts.index') }}" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
