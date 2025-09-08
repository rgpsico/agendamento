<x-admin.layout title="Criar Virtual Host">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title">Criar Virtual Host</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('virtualhosts.index') }}">Virtual Hosts</a></li>
                            <li class="breadcrumb-item active">Criar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('virtualhosts.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="servername" class="form-label">ServerName</label>
                                    <input type="text" class="form-control" id="servername" name="servername" placeholder="ex: meuapp.com.br" required>
                                </div>

                                <div class="mb-3">
                                    <label for="serveralias" class="form-label">ServerAlias</label>
                                    <input type="text" class="form-control" id="serveralias" name="serveralias" placeholder="ex: www.meuapp.com.br">
                                </div>

                                <div class="mb-3">
                                    <label for="documentroot" class="form-label">DocumentRoot</label>
                                    <input type="text" class="form-control" id="documentroot" name="documentroot" placeholder="/var/www/meuapp/public" required>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="enable_ssl" name="enable_ssl">
                                    <label class="form-check-label" for="enable_ssl">Habilitar SSL</label>
                                </div>

                                <button type="submit" class="btn btn-success">Salvar</button>
                                <a href="{{ route('virtualhosts.index') }}" class="btn btn-secondary">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
