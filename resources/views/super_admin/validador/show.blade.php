@extends('super_admin.validador.layout')
@section('titulo', 'Validador — respostas')

@section('content')
@php use App\Models\LeadQualificacao as LQ; @endphp
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('super.admin.validador.index') }}" class="text-decoration-none small"><i class="fas fa-arrow-left me-1"></i>Voltar</a>
        <h4 class="mb-0 fw-bold mt-1">{{ $q->nome ?: ($q->lead->nome ?? 'Link avulso') }}</h4>
        <span class="pill pill-{{ $q->status }}">{{ $q->respondido() ? 'Respondido em ' . $q->respondido_em->format('d/m/Y H:i') : 'Aguardando resposta' }}</span>
        @if($q->lead)<span class="ms-2 small text-muted">Lead do CRM: {{ $q->lead->nome }}</span>@endif
    </div>
</div>

<div class="card border-0 shadow-sm p-3 mb-4">
    <label class="small text-muted mb-1">Link do formulário (manda pro lead)</label>
    <div class="input-group">
        <input type="text" class="form-control" id="link" value="{{ $link }}" readonly>
        <button class="btn btn-outline-secondary" type="button" id="copiar"><i class="fas fa-copy me-1"></i>Copiar</button>
        @if($whatsapp)
            <a class="btn btn-success" href="{{ $whatsapp }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp me-1"></i>Enviar no WhatsApp</a>
        @endif
    </div>
    @unless($whatsapp)<small class="text-muted mt-2">Sem telefone cadastrado: copie o link e envie manualmente.</small>@endunless
</div>

@if($q->respondido())
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h6 class="fw-bold mb-3">Respostas</h6>
            <dl class="row mb-0">
                <dt class="col-sm-5 text-muted fw-normal">Nome</dt><dd class="col-sm-7">{{ $q->nome }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">De onde é</dt><dd class="col-sm-7">{{ $q->cidade }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">Nicho</dt><dd class="col-sm-7">{{ $q->nichoEmoji() }} {{ $q->nichoRotulo() }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">Google Meu Negócio</dt><dd class="col-sm-7">{{ LQ::GOOGLE[$q->tem_google_meu_negocio] ?? '—' }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">Já fez campanha</dt><dd class="col-sm-7">{{ LQ::CAMPANHA[$q->ja_fez_campanha] ?? '—' }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">Nível digital</dt><dd class="col-sm-7"><span class="pill pill-{{ $q->nivel_digital }}">{{ LQ::NIVEL[$q->nivel_digital] ?? '—' }}</span></dd>
                <dt class="col-sm-5 text-muted fw-normal">WhatsApp</dt><dd class="col-sm-7">{{ $q->whatsapp ?: '—' }}</dd>
                <dt class="col-sm-5 text-muted fw-normal">Observação</dt><dd class="col-sm-7">{!! nl2br(e($q->observacao ?: '—')) !!}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h6 class="fw-bold mb-3">Leitura rápida</h6>
            <ul class="list-unstyled mb-0">
                @foreach($q->leitura() as [$tom, $texto])
                    <li class="mb-2 leitura-{{ $tom }}"><i class="fas {{ $tom === 'ok' ? 'fa-circle-check' : ($tom === 'alerta' ? 'fa-triangle-exclamation' : 'fa-circle-info') }} me-2"></i>{{ $texto }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<form method="POST" action="{{ route('super.admin.validador.destroy', $q) }}" class="mt-4" onsubmit="return confirm('Remover este registro?')">
    @csrf @method('DELETE')
    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash me-1"></i>Remover</button>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('copiar').addEventListener('click', async function () {
    const el = document.getElementById('link');
    try { await navigator.clipboard.writeText(el.value); } catch (e) { el.select(); document.execCommand('copy'); }
    this.innerHTML = '<i class="fas fa-check me-1"></i>Copiado';
    setTimeout(() => this.innerHTML = '<i class="fas fa-copy me-1"></i>Copiar', 1500);
});
</script>
@endpush
