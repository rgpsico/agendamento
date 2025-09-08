<x-admin.layout title="Editar Virtual Host">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title">Editar Virtual Host</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('virtualhosts.index') }}">Virtual Hosts</a></li>
                            <li class="breadcrumb-item active">Editar</li>
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

                                <div class="mb-3">
                                    <label for="servername" class="form-label">ServerName</label>
                                    <input type="text" class="form-control" id="servername" name="servername" value="{{ $vhost['servername'] ?? '' }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="serveralias" class="form-label">ServerAlias</label>
                                    <input type="text" class="form-control" id="serveralias" name="serveralias" value="{{ $vhost['serveralias'] ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="documentroot" class="form-label">DocumentRoot</label>
                                    <input type="text" class="form-control" id="documentroot" name="documentroot" value="{{ $vhost['documentroot'] ?? '' }}" required>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_ssl" name="enable_ssl" 
                                        {{ !empty($vhost['enable_ssl']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_ssl">Habilitar SSL</label>
                                </div>

                                <button type="submit" class="btn btn-primary">Atualizar</button>
                                <a href="{{ route('virtualhosts.index') }}" class="btn btn-secondary">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
