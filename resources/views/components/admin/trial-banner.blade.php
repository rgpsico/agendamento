@php
    $trial = auth()->check()
        ? \App\Models\LeadQualificacao::where('usuario_id', auth()->id())->where('status', 'trial')->latest()->first()
        : null;
    $dias = $trial?->diasDeTeste();
@endphp
@if($trial)
    <a href="{{ route('assinar') }}" style="position:fixed;right:16px;bottom:16px;z-index:1030;background:#0f172a;color:#fff;padding:10px 16px;border-radius:99px;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.25)">
        ⏳ Teste grátis: {{ $dias }} {{ $dias === 1 ? 'dia restante' : 'dias restantes' }} · <span style="text-decoration:underline">Assinar</span>
    </a>
@endif
