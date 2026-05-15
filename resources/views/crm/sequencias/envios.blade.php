<x-admin.layout title="Histórico de Envios — {{ $sequencia->nome }}">
<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">📋 Histórico: {{ $sequencia->nome }}</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">CRM</li>
                <li class="breadcrumb-item"><a href="{{ route('crm.sequencias.index') }}">Automação</a></li>
                <li class="breadcrumb-item active">Histórico</li>
            </ul>
        </div>
        <a href="{{ route('crm.sequencias.index') }}" class="btn btn-outline-secondary">← Voltar</a>
    </div>

    @include('crm._nav')
    <x-alert-messages />

    {{-- Resumo --}}
    <div class="row g-3 mb-4">
        @php
            $totalEnviados  = $envios->where('status', 'enviado')->count();
            $totalPendentes = $envios->where('status', 'pendente')->count();
            $totalFalhou    = $envios->where('status', 'falhou')->count();
        @endphp
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-success">{{ $envios->where('status', 'enviado')->count() }}</div>
                    <div class="text-muted small">Enviados</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-warning">{{ $envios->where('status', 'pendente')->count() }}</div>
                    <div class="text-muted small">Pendentes / Agendados</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-danger">{{ $envios->where('status', 'falhou')->count() }}</div>
                    <div class="text-muted small">Falharam</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-secondary">{{ $envios->where('status', 'cancelado')->count() }}</div>
                    <div class="text-muted small">Cancelados</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Lead</th>
                            <th>Etapa</th>
                            <th>Canal</th>
                            <th>Status</th>
                            <th>Agendado para</th>
                            <th>Enviado em</th>
                            <th>Mensagem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($envios as $envio)
                        <tr>
                            <td>
                                <a href="{{ route('crm.leads.show', $envio->lead) }}" class="fw-semibold text-decoration-none">
                                    {{ $envio->lead?->nome ?? '—' }}
                                </a>
                                @if($envio->lead?->telefone)
                                    <div class="text-muted small">{{ $envio->lead->telefone }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    Etapa {{ $envio->etapa?->ordem ?? '?' }}
                                </span>
                            </td>
                            <td>
                                @if($envio->canal === 'whatsapp') 📱 WhatsApp
                                @elseif($envio->canal === 'email') 📧 E-mail
                                @else {{ $envio->canal }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $envio->status_color }}">
                                    {{ $envio->status_label }}
                                </span>
                                @if($envio->erro)
                                    <div class="text-danger small mt-1" title="{{ $envio->erro }}">
                                        ⚠ {{ Str::limit($envio->erro, 40) }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $envio->agendado_para?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="text-muted small">
                                {{ $envio->enviado_em?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td>
                                @if($envio->mensagem_enviada)
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="verMensagem(this)"
                                        data-msg="{{ e($envio->mensagem_enviada) }}">
                                        Ver
                                    </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Nenhum envio registrado ainda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($envios->hasPages())
        <div class="card-footer">
            {{ $envios->links() }}
        </div>
        @endif
    </div>

</div>
</div>

{{-- Modal ver mensagem --}}
<div class="modal fade" id="modalMensagem" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Mensagem Enviada</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <pre id="txtMensagem" style="white-space:pre-wrap;word-break:break-word;font-family:inherit"></pre>
    </div>
</div>
</div>
</div>

<script>
function verMensagem(btn) {
    document.getElementById('txtMensagem').textContent = btn.dataset.msg;
    new bootstrap.Modal(document.getElementById('modalMensagem')).show();
}
</script>

</x-admin.layout>
