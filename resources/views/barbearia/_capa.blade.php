{{-- Capa da barbearia. Sem foto cadastrada, mostra a listra de poste de barbeiro (nunca um buraco). Espera $empresa. --}}
@if($empresa->capaUrl())
    <img class="capa {{ $classe ?? '' }}" src="{{ $empresa->capaUrl() }}" alt="Capa da {{ $empresa->nome }}" loading="lazy">
@else
    <div class="capa vazia {{ $classe ?? '' }}" role="img" aria-label="{{ $empresa->nome }}"></div>
@endif
