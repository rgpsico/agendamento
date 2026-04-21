<x-admin.layout title="Detalhes do Lead">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="page-title">{{ $lead->nome }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">Leads</a></li>
                            <li class="breadcrumb-item active">Detalhes</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.leads.edit', $lead) }}" class="btn btn-primary">
                            <i class="fe fe-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.leads.resetar', $lead) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Resetar lead ao início do funil? Isso apaga e-mail enviado, temperatura e acesso trial.');">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fe fe-rotate-ccw"></i> Resetar
                            </button>
                        </form>
                        <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Deseja excluir este lead?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fe fe-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informações do Lead</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Nome</label>
                                    <p class="mb-0 fw-semibold">{{ $lead->nome }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Empresa</label>
                                    <p class="mb-0">{{ $lead->empresa ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">E-mail</label>
                                    <p class="mb-0">
                                        @if($lead->email)
                                            <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Telefone</label>
                                    <p class="mb-0">{{ $lead->telefone ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Origem</label>
                                    <p class="mb-0">{{ $lead->origem_label }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Interesse</label>
                                    <p class="mb-0">{{ $lead->interesse ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Responsável</label>
                                    <p class="mb-0">{{ $lead->responsavel?->nome ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Cadastrado em</label>
                                    <p class="mb-0">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($lead->observacoes)
                                    <div class="col-12">
                                        <label class="text-muted small">Observações</label>
                                        <p class="mb-0">{{ $lead->observacoes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">Status atual</p>
                            <span class="badge bg-{{ $lead->status_color }} fs-6 px-3 py-2">
                                {{ $lead->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Atualizar Status</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.leads.update', $lead) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nome" value="{{ $lead->nome }}">
                                <input type="hidden" name="origem" value="{{ $lead->origem }}">
                                <select name="status" class="form-control mb-2" onchange="this.form.submit()">
                                    @foreach(\App\Models\Lead::$statusList as $key => $label)
                                        <option value="{{ $key }}" {{ $lead->status === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
