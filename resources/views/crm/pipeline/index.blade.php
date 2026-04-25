<x-admin.layout title="CRM - Pipeline">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <style>
        .crm-board { display: grid; grid-template-columns: repeat(5, minmax(260px, 1fr)); gap: 12px; overflow-x: auto; padding-bottom: 12px; }
        .crm-column { background: #f6f8fb; border: 1px solid #e9ecef; border-radius: 8px; min-height: 620px; }
        .crm-column-header { padding: 14px; border-bottom: 1px solid #e9ecef; font-weight: 700; }
        .crm-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin: 10px; cursor: grab; box-shadow: 0 2px 8px rgba(15,23,42,.05); }
        .crm-card:active { cursor: grabbing; }
    </style>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div><h3 class="page-title">Pipeline Kanban</h3><ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Pipeline</li></ul></div>
                <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">Novo Lead</a>
            </div>
            @include('crm._nav')
            <x-alert-messages />
            <div class="crm-board">
                @foreach($statuses as $status => $label)
                    <div class="crm-column">
                        <div class="crm-column-header d-flex justify-content-between">
                            <span>{{ $label }}</span>
                            <span class="badge bg-secondary">{{ ($leads[$status] ?? collect())->count() }}</span>
                        </div>
                        <div class="crm-dropzone" data-status="{{ $status }}">
                            @foreach(($leads[$status] ?? collect()) as $lead)
                                <div class="crm-card" data-id="{{ $lead->id }}">
                                    <div class="d-flex justify-content-between gap-2">
                                        <a href="{{ route('crm.leads.show', $lead) }}"><strong>{{ $lead->nome }}</strong></a>
                                        <span class="badge bg-{{ $lead->prioridade === 'Alta' ? 'danger' : ($lead->prioridade === 'Baixa' ? 'secondary' : 'warning') }}">{{ $lead->prioridade }}</span>
                                    </div>
                                    <div class="small text-muted mt-2">{{ $lead->interesse ?? 'Sem interesse informado' }}</div>
                                    <div class="small mt-2">Valor: R$ {{ number_format((float) $lead->valor_estimado, 2, ',', '.') }}</div>
                                    <div class="small">Origem: {{ $lead->origem_label }}</div>
                                    <div class="small">No estagio: {{ $lead->tempo_no_estagio }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.crm-dropzone').forEach(zone => {
            new Sortable(zone, {
                group: 'crm',
                animation: 150,
                onAdd: function (event) {
                    const leadId = event.item.dataset.id;
                    const status = event.to.dataset.status;
                    fetch(`/crm/pipeline/${leadId}/mover`, {
                        method: 'PATCH',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
                        body: JSON.stringify({pipeline_status: status})
                    }).then(response => {
                        if (!response.ok) window.location.reload();
                    });
                }
            });
        });
    </script>
</x-admin.layout>
