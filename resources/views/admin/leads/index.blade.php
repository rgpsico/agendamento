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
                    <div class="col-sm-6 text-end">
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
</x-admin.layout>
