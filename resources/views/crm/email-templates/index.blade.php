<x-admin.layout title="CRM - Templates de E-mail">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="page-title">Templates de E-mail</h3>
                    <ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Templates de E-mail</li></ul>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoTemplate">Novo Template</button>
            </div>

            @include('crm._nav')
            <x-alert-messages />

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Assunto</th>
                                <th>Status</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                                <tr>
                                    <td><strong>{{ $template->nome }}</strong></td>
                                    <td>{{ $template->assunto }}</td>
                                    <td>
                                        @if($template->ativo)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="previsualizarTemplate({{ $template->id }})">
                                            Visualizar
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarTemplate{{ $template->id }}">
                                            Editar
                                        </button>
                                        <form method="POST" action="{{ route('crm.email-templates.destroy', $template) }}" class="d-inline"
                                            onsubmit="return confirm('Excluir este template?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Modal editar --}}
                                <div class="modal fade" id="modalEditarTemplate{{ $template->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <form method="POST" action="{{ route('crm.email-templates.update', $template) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('crm.email-templates._form', ['template' => $template])
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
                                <tr><td colspan="4" class="text-center text-muted">Nenhum template cadastrado ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal novo template --}}
    <div class="modal fade" id="modalNovoTemplate" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('crm.email-templates.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Template de E-mail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('crm.email-templates._form', ['template' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Criar Template</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal preview --}}
    <div class="modal fade" id="modalPreview" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview: <span id="previewNome"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="bg-light px-3 py-2 border-bottom text-muted small">
                        <strong>Assunto:</strong> <span id="previewAssunto"></span>
                    </div>
                    <iframe id="previewFrame" style="width:100%; height:520px; border:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Dados dos templates para JS --}}
    <script>
        const templates = @json($templates->keyBy('id'));

        function previsualizarTemplate(id) {
            const tpl = templates[id];
            document.getElementById('previewNome').textContent    = tpl.nome;
            document.getElementById('previewAssunto').textContent = tpl.assunto
                .replace(/{nome}/g, 'João Silva')
                .replace(/{email}/g, 'joao@exemplo.com')
                .replace(/{telefone}/g, '(21) 99999-9999')
                .replace(/{empresa}/g, 'Studio Pilates')
                .replace(/{interesse}/g, 'Pilates');

            const corpo = tpl.corpo
                .replace(/{nome}/g, 'João Silva')
                .replace(/{email}/g, 'joao@exemplo.com')
                .replace(/{telefone}/g, '(21) 99999-9999')
                .replace(/{empresa}/g, 'Studio Pilates')
                .replace(/{interesse}/g, 'Pilates');

            const frame = document.getElementById('previewFrame');
            frame.srcdoc = corpo;

            new bootstrap.Modal(document.getElementById('modalPreview')).show();
        }
    </script>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('modalNovoTemplate'));
                modal.show();
            });
        </script>
    @endif
</x-admin.layout>
