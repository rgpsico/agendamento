<x-admin.layout title="CRM - Formularios">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <h3 class="page-title">Formularios de Campanha</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">CRM</li>
                    <li class="breadcrumb-item active">Formularios</li>
                </ul>
            </div>

            @include('crm._nav')
            <x-alert-messages />

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Campanha</th>
                                <th>Canal</th>
                                <th>Leads</th>
                                <th>Link publico</th>
                                <th>Status</th>
                                <th class="text-end">Configuracao</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campanhas as $campanha)
                                <tr>
                                    <td>
                                        <strong>{{ $campanha->nome }}</strong>
                                        <div class="text-muted small">{{ $campanha->formulario_titulo_final }}</div>
                                    </td>
                                    <td>{{ $campanha->canal }}</td>
                                    <td>{{ $campanha->leads_count }}</td>
                                    <td>
                                        <div class="input-group input-group-sm" style="min-width: 320px;">
                                            <input type="text" class="form-control" value="{{ $campanha->formulario_url }}" readonly>
                                            <a href="{{ $campanha->formulario_url }}" target="_blank" class="btn btn-outline-primary">Abrir</a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $campanha->formulario_ativo ? 'success' : 'secondary' }}">
                                            {{ $campanha->formulario_ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#form-campanha-{{ $campanha->id }}">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="form-campanha-{{ $campanha->id }}">
                                    <td colspan="6">
                                        <form method="POST" action="{{ route('crm.campanhas.formulario.update', $campanha) }}" class="row g-3">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-md-4">
                                                <label class="form-label">Titulo do formulario</label>
                                                <input name="formulario_titulo" class="form-control" value="{{ old('formulario_titulo', $campanha->formulario_titulo) }}" placeholder="{{ $campanha->nome }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Texto do botao</label>
                                                <input name="formulario_botao" class="form-control" value="{{ old('formulario_botao', $campanha->formulario_botao ?: 'Quero agendar uma aula') }}" required>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="formulario_ativo" value="1" class="form-check-input" @checked($campanha->formulario_ativo)>
                                                    <label class="form-check-label">Formulario ativo</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Descricao</label>
                                                <textarea name="formulario_descricao" class="form-control" rows="3" placeholder="Explique a oferta da campanha.">{{ old('formulario_descricao', $campanha->formulario_descricao) }}</textarea>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button class="btn btn-primary">Salvar formulario</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Nenhuma campanha cadastrada. Crie uma campanha antes de gerar o formulario.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $campanhas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
