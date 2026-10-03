@extends('super_admin.validador.layout')
@section('titulo', 'Validador de leads')

@section('content')
@php use App\Models\LeadQualificacao as LQ; @endphp
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0 fw-bold">Validador de leads</h4>
        <small class="text-muted">Envie o formulário pro lead e veja as respostas aqui</small>
    </div>
    <form method="POST" action="{{ route('super.admin.validador.store') }}">
        @csrf
        <button class="btn btn-primary btn-sm"><i class="fas fa-link me-1"></i> Novo link avulso</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat-chip"><div class="lbl">Entradas</div><div class="num">{{ $totais['total'] }}</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-chip"><div class="lbl">Responderam</div><div class="num">{{ $totais['respondido'] }}</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-chip"><div class="lbl">Abriram teste</div><div class="num">{{ $totais['trial'] }}</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-chip"><div class="lbl">Clientes</div><div class="num">{{ $totais['cliente'] }}</div></div></div>
</div>

@if($campanhas->isNotEmpty())
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold border-0 pt-3">Funil por campanha</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light"><tr><th>Campanha (utm_campaign)</th><th class="text-end">Entradas</th><th class="text-end">Responderam</th><th class="text-end">Teste</th><th class="text-end">Clientes</th><th class="text-end">Conversão</th></tr></thead>
            <tbody>
            @foreach($campanhas as $c)
                <tr>
                    <td>{{ $c->campanha }}</td>
                    <td class="text-end">{{ $c->entradas }}</td>
                    <td class="text-end">{{ $c->respondidos }}</td>
                    <td class="text-end">{{ $c->testes }}</td>
                    <td class="text-end fw-bold">{{ $c->clientes }}</td>
                    <td class="text-end text-muted">{{ $c->entradas ? round($c->clientes / $c->entradas * 100, 1) : 0 }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<form method="GET" class="card border-0 shadow-sm p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" name="busca" class="form-control form-control-sm" placeholder="Nome, cidade ou WhatsApp" value="{{ request('busca') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos os status</option>
                @foreach(LQ::STATUS as $k => $r)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="nicho" class="form-select form-select-sm">
                <option value="">Todos os nichos</option>
                @foreach(LQ::NICHOS as $k => $n)
                    <option value="{{ $k }}" @selected(request('nicho') === $k)>{{ $n['emoji'] }} {{ $n['rotulo'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="nivel" class="form-select form-select-sm">
                <option value="">Nível digital</option>
                @foreach(LQ::NIVEL as $k => $r)
                    <option value="{{ $k }}" @selected(request('nivel') === $k)>{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i>Filtrar</button>
            <a href="{{ route('super.admin.validador.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th>Lead</th><th>De onde</th><th>Nicho</th><th>Google</th><th>Campanha</th><th>Digital</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($itens as $q)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $q->nome ?: ($q->lead->nome ?? '—') }}</div>
                        <small class="text-muted">{{ $q->whatsapp ?: ($q->lead->telefone ?? '') }}</small>
                    </td>
                    <td>{{ $q->cidade ?: '—' }}</td>
                    <td>{!! $q->nicho ? e($q->nichoEmoji() . ' ' . $q->nichoRotulo()) : '—' !!}</td>
                    <td><small>{{ LQ::GOOGLE[$q->tem_google_meu_negocio] ?? '—' }}</small></td>
                    <td><small>{{ LQ::CAMPANHA[$q->ja_fez_campanha] ?? '—' }}</small></td>
                    <td>@if($q->nivel_digital)<span class="pill pill-{{ $q->nivel_digital }}">{{ LQ::NIVEL[$q->nivel_digital] }}</span>@else — @endif</td>
                    <td>
                        <span class="pill pill-{{ $q->status }}">{{ LQ::STATUS[$q->status] ?? $q->status }}</span>
                        @if($q->concluiuFormulario() && $q->precisaOnboarding())<div><span class="badge text-bg-warning" style="font-size:.65rem">Onboarding</span></div>@endif
                        <div><small class="text-muted">{{ ($q->respondido_em ?? $q->created_at)->format('d/m H:i') }}</small>@if($q->utm_campaign)<div><small class="text-muted">{{ $q->utm_campaign }}</small></div>@endif</div>
                    </td>
                    <td class="text-end"><a href="{{ route('super.admin.validador.show', $q) }}" class="btn btn-sm btn-outline-primary">Abrir</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5">Nenhum registro ainda. Crie um link e envie pro lead.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($itens->hasPages())
        <div class="card-footer bg-white border-top-0 py-3 px-3">{{ $itens->links() }}</div>
    @endif
</div>
@endsection
