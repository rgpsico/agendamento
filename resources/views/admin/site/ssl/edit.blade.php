<x-admin.layout title="Domínio e SSL">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Configurar Domínio Personalizado</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Domínio & SSL</li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Domínio Personalizado</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.site.dominios.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="dominio_personalizado">Domínio (ex: seusite.com.br)</label>
                            <input type="text" name="dominio_personalizado" value="{{ old('dominio_personalizado', $site->dominio_personalizado ?? '') }}" class="form-control" placeholder="dominio.com.br">
                        </div>

                        <div class="form-group">
                            <label>Status de apontamento DNS:</label>
                            @if($dnsStatus === true)
                                <div class="alert alert-success">
                                    ✅ Domínio apontando corretamente para o servidor.
                                </div>
                            @elseif($dnsStatus === false)
                                <div class="alert alert-danger">
                                    ❌ Domínio ainda não está apontando para o IP <strong>{{ $ipServidor }}</strong>.
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Status do Certificado SSL:</label>
                            @if($sslStatus === true)
                                <div class="alert alert-success">
                                    🔒 Certificado SSL válido e ativo.
                                </div>
                            @elseif($sslStatus === false)
                                <div class="alert alert-warning">
                                    ⚠️ Certificado ainda não instalado.
                                </div>
                            @endif
                        </div>

                        <div class="form-group d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Salvar Domínio</button>

                            

                            <a href="{{ route('admin.site.dominios') }}" class="btn btn-secondary">
                                Atualizar Status
                            </a>
                        </div>

                        <div class="mt-3 text-muted small">
                            Após salvar o domínio, a geração do SSL será feita automaticamente se o domínio estiver corretamente apontado para <strong>{{ $ipServidor }}</strong>.
                        </div>
                    </form>
                </form>
                    @if($dnsStatus === true && $sslStatus === false)
                    <form action="{{ route('gerarSSL') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('POST')
                        <input type="submit" class="btn btn-success" value="Gerar SSL">
                    </form>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
