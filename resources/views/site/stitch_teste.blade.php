{{-- Modelo "Stitch Teste" — feito a partir do DESIGN.md em docs/sites/stitch-teste-DESIGN.md
     (método da skill taste-design do google-labs-code/stitch-skills). Só mostra dados reais do site. --}}
@php
  $nome       = $site->titulo ?? 'Escola de Surf';
  $descricao  = $site->descricao ?? '';
  $wa         = preg_replace('/\D/', '', $site->whatsapp ?? '');
  $waText     = rawurlencode('Oi! Vi o site e quero marcar uma aula na '.$nome);
  $acento     = $site->cores['primaria'] ?? '#1F6F78';

  $capaUrl    = $site->capa         ? asset('storage/'.$site->capa)         : null;
  $logoUrl    = $site->logo         ? asset('storage/'.$site->logo)         : null;
  $sobreImg   = $site->sobre_imagem ? asset('storage/'.$site->sobre_imagem) : null;

  $sobreTitulo = $site->sobre_titulo    ?? 'Sobre a escola';
  $sobreDesc   = $site->sobre_descricao ?? $descricao;
  $sobreItens  = collect($site->sobre_itens ?? []);
  $servicos    = $site->siteServicos ?? collect();

  $empresa    = $site->empresa ?? null;
  $endRel     = $site->endereco ?? null;
  $telefone   = $empresa->telefone ?? null;
  $endStr     = $endRel ? trim(($endRel->endereco ?? '').(($endRel->cidade ?? null) ? ' — '.$endRel->cidade : '').(($endRel->uf ?? null) ? '/'.$endRel->uf : '')) : null;
  $instagram  = ltrim((string) ($site->instagram ?? ''), '@');
  $bot        = $site->bot_id ? \App\Models\Bot::find($site->bot_id) : null;
  $botAtivo   = $bot && $bot->widget_ativo && $bot->status;

  $preco      = fn ($v) => (float) $v > 0 ? 'R$ '.number_format((float) $v, 2, ',', '.') : null;

  // Título do hero: a última palavra vira o destaque em itálico, com a miniatura da capa antes dela.
  $palavras   = preg_split('/\s+/', trim(\Illuminate\Support\Str::before($nome, ' - ')));
  $ultima     = count($palavras) > 1 ? array_pop($palavras) : null;
  $inicio     = implode(' ', $palavras);
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $nome }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($descricao), 155) }}">
<meta property="og:title" content="{{ $nome }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($descricao), 155) }}">
@if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600&family=Fraunces:ital,opsz,wght@1,9..144,500&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
:root{
  --areia:#F4F1EA; --sup:#FBFAF6; --tinta:#1C1B19; --pedra:#6B675F; --linha:#E2DDD2;
  --acento:{{ $acento }};
  --display:'Outfit',system-ui,sans-serif; --serif:'Fraunces',ui-serif,serif; --mono:'JetBrains Mono',ui-monospace,monospace;
  --ease:cubic-bezier(.2,.8,.2,1);
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--areia);color:var(--tinta);font:400 1.0625rem/1.65 var(--display);-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{color:inherit}
.wrap{max-width:1240px;margin:0 auto;padding-inline:clamp(16px,4vw,40px)}

/* topo */
header{position:sticky;top:0;z-index:30;background:color-mix(in srgb,var(--areia) 88%,transparent);backdrop-filter:blur(10px);border-bottom:1px solid var(--linha)}
.nav{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:64px}
.marca{display:flex;align-items:center;gap:10px;font-weight:600;letter-spacing:-.02em;text-decoration:none}
.marca img{height:36px;width:auto}
.nav ul{display:flex;gap:28px;list-style:none}
.nav ul a{text-decoration:none;color:var(--pedra);font-size:.95rem}
.nav ul a:hover{color:var(--tinta)}

/* botões */
.btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 24px;border-radius:999px;font:500 1rem var(--display);text-decoration:none;border:1px solid transparent;cursor:pointer;transition:transform .2s var(--ease),background .2s}
.btn:active{transform:translateY(1px)}
.btn-pri{background:var(--acento);color:#fff}
.btn-pri:hover{background:color-mix(in srgb,var(--acento) 86%,#000)}
.btn-linha{border-color:var(--tinta);background:transparent}
.btn-linha:hover{background:var(--tinta);color:var(--areia)}

/* hero */
.hero{min-height:calc(100dvh - 64px);display:grid;grid-template-columns:1.1fr .9fr;gap:clamp(24px,5vw,72px);align-items:center;padding-block:clamp(40px,7vw,96px)}
.hero h1{font-weight:600;font-size:clamp(2.6rem,6.4vw,5.4rem);line-height:1.02;letter-spacing:-.035em}
.hero h1 em{font-family:var(--serif);font-weight:500;font-style:italic;letter-spacing:-.02em;color:var(--acento)}
.mini{display:inline-block;width:1.6em;height:.82em;border-radius:999px;overflow:hidden;vertical-align:-.04em;margin:0 .12em}
.mini img{width:100%;height:100%;object-fit:cover}
.hero p{margin-top:24px;max-width:52ch;color:var(--pedra);font-size:1.15rem}
.hero .acoes{margin-top:36px;display:flex;flex-wrap:wrap;gap:12px}
.hero figure{border-radius:28px;overflow:hidden;aspect-ratio:4/5;background:var(--linha)}
.hero figure img{width:100%;height:100%;object-fit:cover}

/* seções */
section{padding-block:clamp(56px,9vw,120px)}
.rotulo{font:500 .8rem var(--mono);text-transform:uppercase;letter-spacing:.12em;color:var(--acento)}
h2{font-weight:600;font-size:clamp(2rem,4.2vw,3.2rem);line-height:1.08;letter-spacing:-.03em;margin-top:12px;max-width:20ch}
.intro{max-width:62ch;color:var(--pedra);margin-top:16px}

/* serviços em zig-zag */
.servicos{margin-top:clamp(36px,6vw,72px);display:grid;gap:clamp(40px,7vw,96px)}
.srv{display:grid;grid-template-columns:1fr 1fr;gap:clamp(24px,5vw,64px);align-items:center}
.srv:nth-child(even) .srv-img{order:2}
.srv-img{border-radius:24px;overflow:hidden;aspect-ratio:5/4;background:var(--linha)}
.srv-img img{width:100%;height:100%;object-fit:cover;transition:transform .8s var(--ease)}
.srv:hover .srv-img img{transform:scale(1.03)}
.srv h3{font-weight:600;font-size:clamp(1.6rem,2.8vw,2.2rem);letter-spacing:-.025em;line-height:1.1}
.srv p{color:var(--pedra);margin-top:14px;max-width:52ch}
.chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:20px}
.chip{font:500 .8rem var(--mono);padding:6px 12px;border:1px solid var(--linha);border-radius:999px;background:var(--sup)}
.chip.preco{border-color:var(--acento);color:var(--acento)}
.srv .btn{margin-top:24px}

/* sobre */
.sobre{display:grid;grid-template-columns:.9fr 1.1fr;gap:clamp(24px,6vw,80px);align-items:start}
.sobre figure{border-radius:24px;overflow:hidden;aspect-ratio:3/4;position:sticky;top:96px}
.sobre figure img{width:100%;height:100%;object-fit:cover}
.sobre .texto{white-space:pre-line;color:var(--pedra);margin-top:20px;max-width:60ch}
.itens{list-style:none;margin-top:32px;border-top:1px solid var(--linha)}
.itens li{padding:18px 0;border-bottom:1px solid var(--linha);display:grid;grid-template-columns:auto 1fr;gap:16px}
.itens li span{font:500 .85rem var(--mono);color:var(--acento);padding-top:3px}

/* contato */
.contato{background:var(--tinta);color:var(--areia);border-radius:32px;padding:clamp(32px,6vw,72px);display:grid;grid-template-columns:1fr 1fr;gap:clamp(32px,6vw,72px)}
.contato h2{color:var(--areia)}
.contato .intro{color:#B9B4A9}
.contato dl{margin-top:28px;display:grid;gap:16px}
.contato dt{font:500 .75rem var(--mono);text-transform:uppercase;letter-spacing:.12em;color:#8F8A80}
.contato dd a{text-decoration:none;border-bottom:1px solid #5a564f}
form{display:grid;gap:16px}
label{display:grid;gap:6px;font-size:.9rem;color:#D8D3C8}
input,textarea{font:inherit;color:var(--tinta);background:var(--sup);border:1px solid transparent;border-radius:14px;padding:12px 14px;min-height:48px}
input:focus,textarea:focus{outline:2px solid var(--acento);outline-offset:1px}
.oculto{position:absolute;left:-9999px}
.msg{font-size:.95rem;min-height:1.4em}
.msg.erro{color:#F2A3A3}

footer{padding-block:40px;color:var(--pedra);font-size:.9rem;border-top:1px solid var(--linha);margin-top:clamp(56px,9vw,120px)}
footer .wrap{display:flex;flex-wrap:wrap;justify-content:space-between;gap:12px}

/* movimento */
.rev{opacity:0;transform:translateY(18px);transition:opacity .9s var(--ease),transform .9s var(--ease)}
.rev.on{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.rev{opacity:1;transform:none;transition:none}html{scroll-behavior:auto}}

@media (max-width:767px){
  .nav ul{display:none}
  .hero,.srv,.sobre,.contato{grid-template-columns:1fr}
  .hero{min-height:auto}
  .hero figure{aspect-ratio:4/3}
  .srv:nth-child(even) .srv-img{order:0}
  .sobre figure{position:static;aspect-ratio:4/3}
  .mini{display:none}
}
</style>
@include('site._partials.tracking')
</head>
<body>

<header>
  <div class="wrap nav">
    <a class="marca" href="#">
      @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $nome }}">@endif
      <span>{{ \Illuminate\Support\Str::before($nome, ' - ') }}</span>
    </a>
    <ul>
      @if($servicos->isNotEmpty())<li><a href="#aulas">Aulas</a></li>@endif
      @if($sobreDesc)<li><a href="#sobre">Sobre</a></li>@endif
      <li><a href="#contato">Contato</a></li>
    </ul>
    @if($wa)<a class="btn btn-pri" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" data-whatsapp>WhatsApp</a>@endif
  </div>
</header>

<main>
  <div class="wrap hero">
    <div>
      <p class="rotulo rev">Escola de surf</p>
      <h1 class="rev">{{ $inicio }} @if($ultima)@if($capaUrl)<span class="mini"><img src="{{ $capaUrl }}" alt=""></span> @endif<em>{{ $ultima }}</em>@endif</h1>
      @if($descricao)<p class="rev">{{ $descricao }}</p>@endif
      <div class="acoes rev">
        @if($wa)
          <a class="btn btn-pri" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" data-whatsapp>Marcar uma aula</a>
        @else
          <a class="btn btn-pri" href="#contato">Marcar uma aula</a>
        @endif
      </div>
    </div>
    @if($capaUrl)<figure class="rev"><img src="{{ $capaUrl }}" alt="{{ $nome }}"></figure>@endif
  </div>

  @if($servicos->isNotEmpty())
  <section id="aulas">
    <div class="wrap">
      <p class="rotulo rev">Aulas</p>
      <h2 class="rev">Escolha por onde começar</h2>
      <div class="servicos">
        @foreach($servicos as $srv)
          <article class="srv rev">
            <div class="srv-img">
              @if($srv->imagem)<img src="{{ asset('storage/'.$srv->imagem) }}" alt="{{ $srv->titulo }}" loading="lazy">@endif
            </div>
            <div>
              <h3>{{ $srv->titulo }}</h3>
              @if($srv->descricao)<p>{{ $srv->descricao }}</p>@endif
              <div class="chips">
                @if($p = $preco($srv->preco))<span class="chip preco">{{ $p }}</span>@endif
                @if($srv->nivel)<span class="chip">{{ $srv->nivel }}</span>@endif
                @if($srv->duracao)<span class="chip">{{ $srv->duracao }}</span>@endif
                @if($srv->capacidade)<span class="chip">{{ $srv->capacidade }}</span>@endif
              </div>
              @if($wa)<a class="btn btn-linha" href="https://wa.me/{{ $wa }}?text={{ rawurlencode('Oi! Quero saber da aula '.$srv->titulo) }}" target="_blank" rel="noopener" data-whatsapp>Quero essa</a>@endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @if($sobreDesc)
  <section id="sobre">
    <div class="wrap sobre">
      @if($sobreImg)<figure class="rev"><img src="{{ $sobreImg }}" alt="{{ $sobreTitulo }}" loading="lazy"></figure>@endif
      <div>
        <p class="rotulo rev">Sobre</p>
        <h2 class="rev">{{ $sobreTitulo }}</h2>
        <p class="texto rev">{{ $sobreDesc }}</p>
        @if($sobreItens->isNotEmpty())
          <ul class="itens">
            @foreach($sobreItens as $i => $it)
              <li class="rev"><span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><div>{{ is_array($it) ? ($it['titulo'] ?? $it['texto'] ?? '') : $it }}</div></li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </section>
  @endif

  <section id="contato">
    <div class="wrap">
      <div class="contato rev">
        <div>
          <p class="rotulo">Contato</p>
          <h2>Bora pra água?</h2>
          <p class="intro">Mande uma mensagem e a gente responde com os horários disponíveis.</p>
          <dl>
            @if($wa)<div><dt>WhatsApp</dt><dd><a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" data-whatsapp>{{ $telefone ?: '+'.$wa }}</a></dd></div>@endif
            @if($instagram)<div><dt>Instagram</dt><dd><a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener">{{ '@'.$instagram }}</a></dd></div>@endif
            @if($endStr)<div><dt>Endereço</dt><dd>{{ $endStr }}</dd></div>@endif
          </dl>
        </div>
        <form id="form-contato" novalidate>
          <input class="oculto" type="text" name="website" tabindex="-1" autocomplete="off">
          <label>Nome<input name="nome" required maxlength="120" autocomplete="name"></label>
          <label>E-mail<input name="email" type="email" required maxlength="160" autocomplete="email"></label>
          <label>WhatsApp<input name="whatsapp" maxlength="30" autocomplete="tel"></label>
          <label>Mensagem<textarea name="mensagem" rows="4" maxlength="2000"></textarea></label>
          <button class="btn btn-pri" type="submit">Enviar</button>
          <p class="msg" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <span>{{ \Illuminate\Support\Str::before($nome, ' - ') }}</span>
    <span>{{ date('Y') }}</span>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/site-metrics.js') }}"></script>
<script>
  window.SITE_ID = {{ $site->id }};
  window.WHATSAPP = '{{ $wa }}';

  // revelação em cascata
  (function () {
    var els = document.querySelectorAll('.rev');
    if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('on'); }); return; }
    var io = new IntersectionObserver(function (entradas) {
      var atraso = 0;
      entradas.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.style.transitionDelay = (atraso++ * 80) + 'ms';
        e.target.classList.add('on');
        io.unobserve(e.target);
      });
    }, { threshold: .12 });
    els.forEach(function (e) { io.observe(e); });
  })();

  // formulário de contato -> lead no CRM
  document.getElementById('form-contato').addEventListener('submit', function (ev) {
    ev.preventDefault();
    var f = ev.target, msg = f.querySelector('.msg'), btn = f.querySelector('button');
    if (!f.nome.value.trim() || !f.email.value.trim()) { msg.className = 'msg erro'; msg.textContent = 'Preencha nome e e-mail.'; return; }
    btn.disabled = true; msg.className = 'msg'; msg.textContent = 'Enviando...';
    fetch('{{ route('site.contato', $site->slug) }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
      body: new FormData(f)
    }).then(function (r) {
      if (!r.ok) throw r;
      f.reset(); msg.textContent = 'Recebemos sua mensagem. Já já a gente responde.';
    }).catch(function () {
      msg.className = 'msg erro'; msg.textContent = 'Não deu para enviar agora. Tente de novo ou chame no WhatsApp.';
    }).finally(function () { btn.disabled = false; });
  });
</script>
@if($botAtivo){!! $bot->widgetSnippet() !!}@endif
</body>
</html>
