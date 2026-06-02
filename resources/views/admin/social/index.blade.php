@extends('layouts.admin')

@section('title', 'Redes Sociais')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Redes Sociais</h4>
            <small class="text-muted">Conecte Facebook e Instagram para publicar artigos automaticamente</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(!config('services.meta.app_id'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Configuração pendente:</strong> As variáveis <code>META_APP_ID</code> e <code>META_APP_SECRET</code>
            não estão configuradas no servidor. Peça ao administrador para adicionar no <code>.env</code>.
        </div>
    @endif

    <div class="row g-4">

        {{-- Card Facebook --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:48px;height:48px;background:#1877f2;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fab fa-facebook-f text-white" style="font-size:1.4rem"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Facebook</h5>
                            <small class="text-muted">Publicar na sua Página</small>
                        </div>
                    </div>

                    @if($connection?->hasFacebook())
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Conectado
                            </span>
                        </div>
                        <div class="bg-light rounded p-3 mb-3">
                            <div class="fw-semibold">{{ $connection->facebook_page_name }}</div>
                            <small class="text-muted">ID: {{ $connection->facebook_page_id }}</small>
                            @if($connection->token_expires_at)
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Token expira: {{ $connection->token_expires_at->format('d/m/Y') }}
                                        @if($connection->isExpired())
                                            <span class="badge bg-danger ms-1">Expirado</span>
                                        @endif
                                    </small>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-3">Nenhuma página conectada.</p>
                    @endif

                    @if(config('services.meta.app_id'))
                        <a href="{{ route('admin.social.connect') }}" class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook me-1"></i>
                            {{ $connection?->hasFacebook() ? 'Reconectar' : 'Conectar Facebook' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card Instagram --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:48px;height:48px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fab fa-instagram text-white" style="font-size:1.4rem"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Instagram</h5>
                            <small class="text-muted">Conta Business vinculada ao Facebook</small>
                        </div>
                    </div>

                    @if($connection?->hasInstagram())
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Conectado
                            </span>
                        </div>
                        <div class="bg-light rounded p-3 mb-3">
                            <div class="fw-semibold">@{{ $connection->instagram_username }}</div>
                            <small class="text-muted">ID: {{ $connection->instagram_account_id }}</small>
                        </div>
                    @else
                        <p class="text-muted mb-3">
                            @if($connection?->hasFacebook())
                                Instagram Business não encontrado. Verifique se sua conta do Instagram está vinculada à Página do Facebook.
                            @else
                                Conecte o Facebook primeiro — o Instagram é detectado automaticamente.
                            @endif
                        </p>
                    @endif

                    @if(config('services.meta.app_id') && !$connection?->hasInstagram())
                        <a href="{{ route('admin.social.connect') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fab fa-instagram me-1"></i> Tentar conectar
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Desconectar --}}
    @if($connection)
        <div class="mt-4">
            <form method="POST" action="{{ route('admin.social.disconnect') }}"
                  onsubmit="return confirm('Desconectar todas as contas?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-unlink me-1"></i> Desconectar todas as contas
                </button>
            </form>
        </div>
    @endif

    {{-- Como usar --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Como funciona</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">1</span>
                        <div><strong>Conecte</strong> sua Página do Facebook acima</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">2</span>
                        <div><strong>Publique</strong> um artigo no blog</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0">3</span>
                        <div><strong>Compartilhe</strong> com 1 clique no Facebook e Instagram</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
