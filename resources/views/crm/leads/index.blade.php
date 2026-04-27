<x-admin.layout title="CRM - Leads">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div><h3 class="page-title">Leads</h3><ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Leads</li></ul></div>
                <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">Novo Lead</a>
            </div>
            @include('crm._nav')
            <x-alert-messages />

            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-2" method="GET">
                        <div class="col-md-3"><input name="busca" class="form-control" value="{{ request('busca') }}" placeholder="Buscar por nome, e-mail ou telefone"></div>
                        <div class="col-md-2">
                            <select name="pipeline_status" class="form-control">
                                <option value="">Todos os status</option>
                                @foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(request('pipeline_status') === $key)>{{ $label }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="origem" class="form-control">
                                <option value="">Todas as origens</option>
                                @foreach($origens as $key => $label)<option value="{{ $key }}" @selected(request('origem') === $key)>{{ $label }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="whatsapp" class="form-control">
                                <option value="">WhatsApp - todos</option>
                                <option value="enviado" @selected(request('whatsapp') === 'enviado')>Mensagem enviada</option>
                                <option value="pendente" @selected(request('whatsapp') === 'pendente')>Ainda nao enviado</option>
                            </select>
                        </div>
                        <div class="col-md-2"><button class="btn btn-secondary w-100">Filtrar</button></div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Nome</th><th>Contato</th><th>WhatsApp</th><th>Origem</th><th>Status</th><th>Valor</th><th>Campanha</th><th class="text-end">Acoes</th></tr></thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr>
                                    <td><a href="{{ route('crm.leads.show', $lead) }}"><strong>{{ $lead->nome }}</strong></a><div class="text-muted small">{{ $lead->interesse }}</div></td>
                                    <td>{{ $lead->telefone ?? '-' }}<div class="text-muted small">{{ $lead->email }}</div></td>
                                    <td>
                                        @if($lead->whatsapp_enviado_em)
                                            <span class="badge bg-success">Enviado</span>
                                            <div class="text-muted small">{{ $lead->whatsapp_enviado_em->format('d/m/Y H:i') }}</div>
                                        @else
                                            <span class="badge bg-secondary">Pendente</span>
                                        @endif
                                    </td>
                                    <td>{{ $lead->origem_label }}</td>
                                    <td><span class="badge bg-primary">{{ $lead->pipeline_status_label }}</span></td>
                                    <td>R$ {{ number_format((float) $lead->valor_estimado, 2, ',', '.') }}</td>
                                    <td>{{ $lead->campanha->nome ?? '-' }}</td>
                                    <td class="text-end">
                                        @if($lead->whatsapp_url)
                                            <form method="POST" action="{{ route('crm.leads.whatsapp', $lead) }}" target="_blank" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">WhatsApp</button>
                                            </form>
                                        @endif
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('crm.leads.show', $lead) }}">Ver</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('crm.leads.edit', $lead) }}">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Nenhum lead encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $leads->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
