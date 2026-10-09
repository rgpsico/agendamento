<article class="tour reveal {{ $p->principal ? '' : 'small' }}">
    <div class="ph" data-open="{{ $p->id }}">
        @if($p->img)<img src="{{ $p->img }}" alt="{{ $p->titulo }}" loading="lazy">
        @else<svg aria-hidden="true"><use href="#i-compass"/></svg>@endif
        @if($p->credito)<a class="credit" href="{{ $p->credito_url }}" target="_blank" rel="noopener"><span data-i18n="photo">Foto</span>: {{ $p->credito }}</a>@endif
    </div>
    <div class="bd">
        <h3 data-sv="{{ $p->id }}" data-f="t" data-open="{{ $p->id }}">{{ $p->titulo }}</h3>
        @if($p->descricao)<p data-sv="{{ $p->id }}" data-f="d">{{ $p->descricao }}</p>@endif
        @if($p->duracao_txt)<div class="chips"><span class="chip"><span data-i18n="duration">Duração</span>: {{ $p->duracao_txt }}</span></div>@endif
        @if($p->preco > 0)
            <div class="price"><span data-i18n="from">a partir de</span><strong>R$ {{ number_format($p->preco, 2, ',', '.') }}</strong></div>
        @endif
        <div class="actions">
            <button type="button" class="btn btn--ocean" data-open="{{ $p->id }}" data-i18n="view_exp">Ver experiência</button>
        </div>
    </div>
</article>
