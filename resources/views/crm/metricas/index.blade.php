<x-admin.layout title="CRM - Métricas de Sites">
<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">📊 Métricas de Sites</h3>
            <ul class="breadcrumb"><li class="breadcrumb-item">CRM</li><li class="breadcrumb-item active">Métricas</li></ul>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoSite">+ Novo Site</button>
    </div>

    @include('crm._nav')
    <x-alert-messages />

    @if($sites->isEmpty())
        <div class="card"><div class="card-body text-center py-5 text-muted">
            <p class="fs-5 mb-3">Nenhum site cadastrado ainda.</p>
            <p>Crie um site, cole o snippet no WordPress e acompanhe as métricas aqui.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoSite">Criar primeiro site</button>
        </div></div>
    @else

    {{-- ── Seletor de site + período ──────────────────────────────────────── --}}
    <form method="GET" class="row g-2 mb-4 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Site</label>
            <select name="site_id" class="form-select" onchange="this.form.submit()">
                @foreach($sites as $s)
                    <option value="{{ $s->id }}" @selected($site?->id == $s->id)>
                        {{ $s->nome }} {{ $s->dominio ? '('.$s->dominio.')' : '' }}
                        {{ $s->ativo ? '' : '— Inativo' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Período</label>
            <select name="dias" class="form-select" onchange="this.form.submit()">
                <option value="7"  @selected($dias==7)>Últimos 7 dias</option>
                <option value="30" @selected($dias==30)>Últimos 30 dias</option>
                <option value="90" @selected($dias==90)>Últimos 90 dias</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            @if($site)
            <div class="input-group input-group-sm">
                <input type="text" class="form-control font-monospace"
                    id="snippet-track" readonly
                    value='<script src="{{ $site->trackUrl() }}" defer></script>'>
                <button class="btn btn-outline-secondary" type="button"
                    onclick="copiarSnippet()">Copiar snippet</button>
            </div>
            @endif
        </div>
    </form>

    @if($site && $dados)
    {{-- ── KPI Cards ────────────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-1 fw-bold text-primary">{{ number_format($dados['visitantes']) }}</div>
                    <div class="text-muted small">Visitantes únicos</div>
                    <div class="text-muted" style="font-size:11px">{{ number_format($dados['totalVisitas']) }} visualizações</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-1 fw-bold text-success">{{ number_format($dados['whatsappClicks']) }}</div>
                    <div class="text-muted small">Cliques no WhatsApp</div>
                    @php $taxaWa = $dados['visitantes'] > 0 ? round($dados['whatsappClicks']/$dados['visitantes']*100,1) : 0; @endphp
                    <div class="text-muted" style="font-size:11px">{{ $taxaWa }}% dos visitantes</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    @php $min = floor($dados['tempoMedio']/60); $seg = $dados['tempoMedio']%60; @endphp
                    <div class="fs-1 fw-bold text-info">{{ $min }}m{{ $seg }}s</div>
                    <div class="text-muted small">Tempo médio no site</div>
                    <div class="text-muted" style="font-size:11px">por sessão</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-1 fw-bold text-warning">{{ $dados['taxaConversao'] }}%</div>
                    <div class="text-muted small">Taxa de conversão modal</div>
                    <div class="text-muted" style="font-size:11px">{{ $dados['modalConverts'] }} / {{ $dados['modalViews'] }} exibições</div>
                </div>
            </div>
        </div>
        @if($dados['botAberturas'] > 0)
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-1 fw-bold text-secondary">{{ number_format($dados['botAberturas']) }}</div>
                    <div class="text-muted small">Aberturas do Bot</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Gráfico de visitantes ────────────────────────────────────────────── --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Visitantes únicos por dia</div>
        <div class="card-body">
            <canvas id="graficoVisitantes" height="80"></canvas>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- ── Top páginas ─────────────────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Top Páginas</div>
                <div class="card-body p-0">
                    @if($dados['topPaginas']->isEmpty())
                        <p class="text-muted text-center py-4">Sem dados ainda</p>
                    @else
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr>
                            <th>Página</th>
                            <th class="text-end">Visitantes</th>
                            <th class="text-end">Views</th>
                        </tr></thead>
                        <tbody>
                        @foreach($dados['topPaginas'] as $p)
                            <tr>
                                <td class="text-truncate" style="max-width:220px" title="{{ $p->pagina }}">
                                    {{ parse_url($p->pagina, PHP_URL_PATH) ?: '/' }}
                                </td>
                                <td class="text-end fw-semibold">{{ $p->visitantes }}</td>
                                <td class="text-end text-muted">{{ $p->visualizacoes }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Dispositivos + Referrers ─────────────────────────────────────── --}}
        <div class="col-md-6 d-flex flex-column gap-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Dispositivos</div>
                <div class="card-body py-2">
                    @php
                        $totalDev = array_sum($dados['dispositivos']) ?: 1;
                        $devIcons = ['desktop'=>'🖥️','mobile'=>'📱','tablet'=>'📟'];
                    @endphp
                    @foreach(['desktop','mobile','tablet'] as $d)
                        @php $n = $dados['dispositivos'][$d] ?? 0; @endphp
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span style="width:24px">{{ $devIcons[$d] }}</span>
                            <span class="text-capitalize" style="width:70px">{{ $d }}</span>
                            <div class="flex-grow-1 bg-light rounded" style="height:10px;overflow:hidden">
                                <div class="bg-primary rounded" style="height:100%;width:{{ round($n/$totalDev*100) }}%"></div>
                            </div>
                            <span class="text-muted small" style="width:50px;text-align:right">{{ $n }} ({{ round($n/$totalDev*100) }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card border-0 shadow-sm flex-grow-1">
                <div class="card-header bg-white fw-semibold">Origens de tráfego</div>
                <div class="card-body p-0">
                    @if($dados['topReferrers']->isEmpty())
                        <p class="text-muted text-center py-3">Sem dados de referrers</p>
                    @else
                    <table class="table table-sm mb-0">
                        <tbody>
                        @foreach($dados['topReferrers'] as $r)
                            <tr>
                                <td class="text-truncate" style="max-width:200px">
                                    {{ parse_url($r->referrer, PHP_URL_HOST) ?: $r->referrer }}
                                </td>
                                <td class="text-end fw-semibold">{{ $r->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif {{-- $site && $dados --}}

    {{-- ── Lista de sites ──────────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Sites cadastrados</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr>
                    <th>Nome</th><th>Domínio</th><th>Seletor WA</th><th>Status</th><th>Snippet</th><th></th>
                </tr></thead>
                <tbody>
                @foreach($sites as $s)
                <tr>
                    <td>{{ $s->nome }}</td>
                    <td class="text-muted">{{ $s->dominio ?: '—' }}</td>
                    <td><code class="text-muted small">{{ $s->whatsapp_selector ?: '—' }}</code></td>
                    <td>
                        @if($s->ativo) <span class="badge bg-success">Ativo</span>
                        @else          <span class="badge bg-secondary">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm font-monospace"
                            id="snp-{{ $s->id }}" readonly
                            value='<script src="{{ $s->trackUrl() }}" defer></script>'
                            style="max-width:320px">
                    </td>
                    <td class="text-end d-flex gap-1 justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm"
                            onclick="copiarSnippetId('snp-{{ $s->id }}', this)">Copiar</button>
                        <button class="btn btn-outline-primary btn-sm"
                            onclick="abrirEditar({{ json_encode(['id'=>$s->id,'nome'=>$s->nome,'dominio'=>$s->dominio,'whatsapp_selector'=>$s->whatsapp_selector,'ativo'=>$s->ativo]) }})">
                            Editar
                        </button>
                        <form method="POST" action="{{ route('crm.metricas.sites.destroy', $s) }}"
                            class="d-inline" onsubmit="return confirm('Excluir site e todos os dados?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endif {{-- sites não vazios --}}
</div>
</div>

{{-- ── Modal Novo Site ─────────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalNovoSite" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('crm.metricas.sites.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Site para Rastreamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Nome interno <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control" required
                            placeholder="Ex: Site Surf Club">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Domínio <span class="text-muted">(informativo)</span></label>
                        <input type="text" name="dominio" class="form-control"
                            placeholder="surfclub.com.br">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Seletor CSS do botão WhatsApp <span class="text-muted">(opcional)</span></label>
                        <input type="text" name="whatsapp_selector" class="form-control font-monospace"
                            placeholder="Ex: #ht-ctc-chat  ou  .ht-ctc-chat  ou  .ctc-analytics">
                        <div class="form-text">
                            Inspecione o botão do WhatsApp no site (F12 → clique no elemento) e cole aqui o <code>#id</code> ou <code>.classe</code>.
                            Assim capturamos cliques em botões que <strong>não</strong> usam link <code>&lt;a href="wa.me/..."&gt;</code>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar site</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal Editar Site ───────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalEditarSite" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditarSite">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Nome interno <span class="text-danger">*</span></label>
                        <input type="text" name="nome" id="edit_nome" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Domínio <span class="text-muted">(informativo)</span></label>
                        <input type="text" name="dominio" id="edit_dominio" class="form-control"
                            placeholder="surfclub.com.br">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Seletor CSS do botão WhatsApp <span class="text-muted">(opcional)</span></label>
                        <input type="text" name="whatsapp_selector" id="edit_wa_selector"
                            class="form-control font-monospace"
                            placeholder="#ht-ctc-chat  ou  .meu-botao-wa">
                        <div class="form-text">ID ou classe CSS do botão WhatsApp que não usa link <code>wa.me</code>.</div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="ativo" id="edit_ativo"
                                class="form-check-input" value="1">
                            <label class="form-check-label" for="edit_ativo">Site ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($site && $dados)
(function() {
    var ctx = document.getElementById('graficoVisitantes');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dados['graficoLabels']),
            datasets: [{
                label: 'Visitantes únicos',
                data:  @json($dados['graficoData']),
                borderColor: '#2a5298',
                backgroundColor: 'rgba(42,82,152,.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2a5298',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
})();
@endif

function abrirEditar(s) {
    document.getElementById('edit_nome').value        = s.nome || '';
    document.getElementById('edit_dominio').value     = s.dominio || '';
    document.getElementById('edit_wa_selector').value = s.whatsapp_selector || '';
    document.getElementById('edit_ativo').checked     = !!s.ativo;
    document.getElementById('formEditarSite').action  = '/crm/metricas/sites/' + s.id;
    new bootstrap.Modal(document.getElementById('modalEditarSite')).show();
}

function copiarSnippet() {
    copiarSnippetId('snippet-track', document.querySelector('[onclick="copiarSnippet()"]'));
}

function copiarSnippetId(id, btn) {
    var el = document.getElementById(id);
    navigator.clipboard.writeText(el.value).then(function() {
        var orig = btn.textContent;
        btn.textContent = '✓ Copiado!';
        setTimeout(function() { btn.textContent = orig; }, 2000);
    });
}
</script>
</x-admin.layout>
