<x-admin.layout title="CRM - Widgets de Captura">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="page-title">Widgets de Captura</h3>
                    <ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Widgets</li></ul>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovo">+ Novo Widget</button>
            </div>

            @include('crm._nav')
            <x-alert-messages />

            @forelse($modals as $modal)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold fs-6">{{ $modal->nome }}</span>
                                @if($modal->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </div>
                            <div class="text-muted small mb-2">{{ $modal->titulo }}</div>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($modal->campo_nome)     <span class="badge bg-light text-dark border">Nome</span> @endif
                                @if($modal->campo_email)    <span class="badge bg-light text-dark border">E-mail</span> @endif
                                @if($modal->campo_telefone) <span class="badge bg-light text-dark border">Telefone</span> @endif
                                <span class="badge bg-light text-dark border">{{ $modal->origem_lead }}</span>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small text-muted mb-1">Cole este código no WordPress (antes de <code>&lt;/body&gt;</code>):</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control font-monospace"
                                    value='<script src="{{ $modal->widgetUrl() }}" defer></script>'
                                    id="snippet-{{ $modal->id }}" readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="copiarSnippet('snippet-{{ $modal->id }}', this)">
                                    Copiar
                                </button>
                            </div>
                            <div class="mt-1">
                                <button class="btn btn-sm btn-outline-info"
                                    onclick="previewModal({{ json_encode($modal) }})">
                                    👁 Ver preview
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2 text-end">
                            <button class="btn btn-sm btn-outline-secondary mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar{{ $modal->id }}">
                                Editar
                            </button>
                            <form method="POST" action="{{ route('crm.modal-capturas.destroy', $modal) }}"
                                onsubmit="return confirm('Excluir este widget?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal editar --}}
            <div class="modal fade" id="modalEditar{{ $modal->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('crm.modal-capturas.update', $modal) }}">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Widget</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('crm.modal-capturas._form', ['modal' => $modal, 'campanhas' => $campanhas])
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @empty
                <div class="card"><div class="card-body text-center text-muted py-5">
                    Nenhum widget criado ainda. Crie o primeiro clicando em <strong>+ Novo Widget</strong>.
                </div></div>
            @endforelse
        </div>
    </div>

    {{-- Modal novo --}}
    <div class="modal fade" id="modalNovo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('crm.modal-capturas.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Widget de Captura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('crm.modal-capturas._form', ['modal' => null, 'campanhas' => $campanhas])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Criar Widget</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal preview --}}
    <div class="modal fade" id="modalPreviewWidget" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" id="previewWidgetContent">
            </div>
        </div>
    </div>

    <script>
    function copiarSnippet(id, btn) {
        var el = document.getElementById(id);
        navigator.clipboard.writeText(el.value).then(function() {
            btn.textContent = '✓ Copiado!';
            setTimeout(function() { btn.textContent = 'Copiar'; }, 2000);
        });
    }

    function previewModal(cfg) {
        var fields = '';
        if (cfg.campo_nome)     fields += '<input type="text" class="form-control mb-2" placeholder="Seu nome">';
        if (cfg.campo_email)    fields += '<input type="email" class="form-control mb-2" placeholder="Seu e-mail">';
        if (cfg.campo_telefone) fields += '<input type="tel" class="form-control mb-2" placeholder="Seu telefone">';

        document.getElementById('previewWidgetContent').innerHTML =
            '<div class="modal-header" style="background:' + cfg.cor_primaria + '; color:#fff;">'
            + '<h5 class="modal-title" style="color:#fff">' + cfg.titulo + '</h5>'
            + '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>'
            + '</div>'
            + '<div class="modal-body">'
            + (cfg.descricao ? '<p class="text-muted">' + cfg.descricao + '</p>' : '')
            + fields
            + '<button class="btn w-100 mt-2" style="background:' + cfg.cor_primaria + '; color:#fff">' + cfg.botao_texto + '</button>'
            + '</div>';

        new bootstrap.Modal(document.getElementById('modalPreviewWidget')).show();
    }
    </script>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('modalNovo')).show();
        });
    </script>
    @endif
</x-admin.layout>
