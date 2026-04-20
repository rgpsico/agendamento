<x-admin.layout title="CRM - Leads">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="page-title">Gerenciar Leads</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item">CRM</li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.leads.template') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-download"></i> Template CSV
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImport">
                            <i class="fe fe-upload"></i> Importar CSV
                        </button>
                        <a href="{{ route('admin.leads.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Novo Lead
                        </a>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            {{-- Filtros --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="busca" class="form-control" placeholder="Nome, e-mail ou telefone..."
                                   value="{{ request('busca') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                @foreach($statusList as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Origem</label>
                            <select name="origem" class="form-control">
                                <option value="">Todas</option>
                                @foreach($origens as $key => $label)
                                    <option value="{{ $key }}" {{ request('origem') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fe fe-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">
                                <i class="fe fe-x"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Contato</th>
                                            <th>Origem</th>
                                            <th>Status</th>
                                            <th>Responsável</th>
                                            <th>Cadastro</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($leads as $lead)
                                            <tr>
                                                <td>{{ $lead->id }}</td>
                                                <td>
                                                    <a href="{{ route('admin.leads.show', $lead) }}">
                                                        {{ $lead->nome }}
                                                    </a>
                                                    @if($lead->empresa)
                                                        <br><small class="text-muted">{{ $lead->empresa }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $lead->email ?? '-' }}
                                                    @if($lead->telefone)
                                                        <br><small>{{ $lead->telefone }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $lead->origem_label }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $lead->status_color }}">
                                                        {{ $lead->status_label }}
                                                    </span>
                                                </td>
                                                <td>{{ $lead->responsavel?->nome ?? '-' }}</td>
                                                <td>{{ $lead->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                                           class="btn btn-sm bg-success-light" title="Ver">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.leads.edit', $lead) }}"
                                                           class="btn btn-sm bg-info-light" title="Editar">
                                                            <i class="fe fe-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('admin.leads.destroy', $lead) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Deseja excluir este lead?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm bg-danger-light" title="Excluir">
                                                                <i class="fe fe-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Nenhum lead encontrado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $leads->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">
                        <i class="fe fe-upload"></i> Importar Leads via CSV
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.leads.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            O arquivo CSV deve ter as colunas: <strong>nome, telefone, email, interesse, origem</strong>.<br>
                            Baixe o <a href="{{ route('admin.leads.template') }}">template CSV</a> para usar como modelo.
                        </p>
                        <div class="mb-3">
                            <label class="form-label">Arquivo CSV <span class="text-danger">*</span></label>
                            <input type="file" name="arquivo" class="form-control" accept=".csv,.txt" required>
                        </div>
                        <div class="alert alert-info small mb-0">
                            <strong>Dica:</strong> Se tiver o PDF de prospecção, abra no Excel/Sheets, copie a tabela e salve como CSV.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fe fe-upload"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin.layout>
