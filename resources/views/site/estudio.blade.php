@php
  $nome       = $site->titulo ?? 'Estúdio';
  $descricao  = $site->descricao ?? '';
  $wa         = preg_replace('/\D/', '', $site->whatsapp ?? '');
  $waText     = rawurlencode('Oi! Quero agendar um horário no '.$nome);
  $corPri     = $site->cores['primaria']   ?? '#b5651d';
  $corSec     = $site->cores['secundaria'] ?? '#f3d9b1';

  $capaUrl    = $site->capa         ? asset('storage/'.$site->capa)         : null;
  $logoUrl    = $site->logo         ? asset('storage/'.$site->logo)         : null;
  $sobreImg   = $site->sobre_imagem ? asset('storage/'.$site->sobre_imagem) : null;

  $sobreTitulo = $site->sobre_titulo    ?? 'Sobre o estúdio';
  $sobreDesc   = $site->sobre_descricao ?? $descricao;
  $sobreItens  = collect($site->sobre_itens ?? []);
  $servicos    = $site->siteServicos ?? collect();

  $empresa    = $site->empresa ?? null;
  $endRel     = $site->endereco ?? null;
  $telefone   = $empresa->telefone ?? null;
  $endStr     = $endRel ? trim(($endRel->endereco ?? '').(($endRel->cidade ?? null) ? ' — '.$endRel->cidade : '').(($endRel->uf ?? null) ? '/'.$endRel->uf : '')) : null;
  $instagram  = ltrim((string) ($site->instagram ?? ''), '@');
  $galeria    = $empresa ? $empresa->galeria : collect();
  $bot        = $site->bot_id ? \App\Models\Bot::find($site->bot_id) : null;
  $botAtivo   = $bot && $bot->widget_ativo && $bot->status;
  $temContato = $wa || $telefone || $endStr || $instagram;
  $preco      = fn ($v) => (float) $v > 0 ? 'R$ '.number_format((float) $v, 2, ',', '.') : null;
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nome }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($descricao), 155) }}">
<meta property="og:title" content="{{ $nome }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($descricao), 155) }}">
@if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --pri:{{ $corPri }};--sec:{{ $corSec }};
  --ink:#2b1d12;--muted:#6f5a48;--bg:#fffaf3;--bg-2:#f7ecdc;--card:#ffffff;--line:#ead9c1;
  --on-pri:#ffffff;
}
@media (prefers-color-scheme: dark){
  :root{--ink:#f6e8d6;--muted:#c4ac91;--bg:#1d140d;--bg-2:#271b12;--card:#2f2218;--line:#473625}
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--bg);color:var(--ink);font:400 1.0625rem/1.6 'Figtree',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
img{max-width:100%;display:block}
a{color:inherit}
h1,h2,h3{font-family:'Fraunces',Georgia,serif;line-height:1.12;margin:0;text-wrap:balance}
.wrap{width:min(1080px,100% - 32px);margin-inline:auto}
.btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:12px 26px;border-radius:999px;font-weight:600;text-decoration:none;border:2px solid transparent;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-2px)}
.btn-pri{background:var(--pri);color:var(--on-pri)}
.btn-ghost{border-color:var(--pri);color:var(--pri)}
.eyebrow{font-size:.8rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--pri)}
/* topo */
header{position:sticky;top:0;z-index:20;background:color-mix(in srgb,var(--bg) 92%,transparent);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
header .wrap{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:64px}
.brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-weight:700;font-size:1.15rem;text-decoration:none}
.brand img{height:36px;width:auto}
nav{display:flex;align-items:center;gap:22px}
nav a{text-decoration:none;font-weight:500;color:var(--muted)}
nav a:hover{color:var(--pri)}
@media (max-width:640px){nav a:not(.btn){display:none}}
/* hero */
.hero{position:relative;overflow:hidden;padding-block:72px 80px;background:
  radial-gradient(900px 420px at 85% -10%,color-mix(in srgb,var(--sec) 75%,transparent),transparent 70%),
  linear-gradient(180deg,var(--bg-2),var(--bg))}
.hero .grid{display:grid;gap:40px;align-items:center}
@media (min-width:860px){.hero .grid{grid-template-columns:1.1fr .9fr}}
.hero h1{font-size:clamp(2.2rem,6vw,3.7rem);margin-block:14px 18px}
.hero p{max-width:52ch;color:var(--muted);margin:0 0 28px;font-size:1.15rem}
.hero .acts{display:flex;flex-wrap:wrap;gap:12px}
.hero figure{margin:0;border-radius:28px;overflow:hidden;aspect-ratio:4/5;max-height:520px;box-shadow:0 24px 60px color-mix(in srgb,var(--pri) 30%,transparent)}
.hero figure img{width:100%;height:100%;object-fit:cover}
.sun{width:100%;aspect-ratio:1;max-width:340px;margin-inline:auto;border-radius:50%;background:radial-gradient(circle at 35% 30%,var(--sec),var(--pri) 75%);box-shadow:0 0 0 18px color-mix(in srgb,var(--sec) 35%,transparent),0 0 0 44px color-mix(in srgb,var(--sec) 18%,transparent)}
/* seções */
section{padding-block:72px}
section.alt{background:var(--bg-2)}
.head{max-width:60ch;margin-bottom:36px}
.head h2{font-size:clamp(1.7rem,4vw,2.4rem);margin-top:8px}
.cards{display:grid;gap:20px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
.card{background:var(--card);border:1px solid var(--line);border-radius:20px;overflow:hidden;display:flex;flex-direction:column}
.card img{aspect-ratio:16/10;width:100%;object-fit:cover}
.card .bd{padding:22px;display:flex;flex-direction:column;gap:10px;flex:1}
.card h3{font-size:1.35rem}
.card p{margin:0;color:var(--muted)}
.card .meta{display:flex;flex-wrap:wrap;gap:8px;margin-top:auto;padding-top:6px}
.tag{font-size:.82rem;font-weight:600;padding:4px 12px;border-radius:999px;background:var(--bg-2);color:var(--pri)}
.sobre{display:grid;gap:36px;align-items:center}
@media (min-width:860px){.sobre.com-img{grid-template-columns:1fr 1fr}}
.sobre img{border-radius:24px;width:100%;aspect-ratio:4/3;object-fit:cover}
.sobre p{color:var(--muted);max-width:60ch}
.itens{list-style:none;padding:0;margin:22px 0 0;display:grid;gap:14px}
.itens li{padding-left:18px;border-left:3px solid var(--pri)}
.itens strong{display:block}
.itens span{color:var(--muted)}
.galeria{display:grid;gap:12px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
.galeria img{aspect-ratio:1;width:100%;object-fit:cover;border-radius:14px}
/* contato */
.contato{display:grid;gap:28px}
@media (min-width:860px){.contato{grid-template-columns:1fr 1fr}}
.contato dl{margin:0;display:grid;gap:16px}
.contato dt{font-size:.8rem;letter-spacing:.12em;text-transform:uppercase;color:var(--pri);font-weight:600}
.contato dd{margin:2px 0 0;overflow-wrap:anywhere}
.cta{background:var(--pri);color:var(--on-pri);border-radius:28px;padding:40px 28px;text-align:center}
.cta h2{font-size:clamp(1.6rem,4vw,2.2rem);margin-bottom:12px}
.cta p{margin:0 0 22px;opacity:.9}
.cta .btn{background:var(--on-pri);color:var(--pri)}
footer{padding-block:28px;border-top:1px solid var(--line);color:var(--muted);font-size:.92rem}
footer .wrap{display:flex;flex-wrap:wrap;justify-content:space-between;gap:8px 24px}
@media (prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
</style>
    @include('site._partials.tracking')
    @include('site._partials.verificacao')
</head>
<body>

<header>
  <div class="wrap">
    <a class="brand" href="#topo">@if($logoUrl)<img src="{{ $logoUrl }}" alt="">@endif{{ $nome }}</a>
    <nav>
      @if($servicos->isNotEmpty())<a href="#servicos">Serviços</a>@endif
      <a href="#sobre">Sobre</a>
      @if($temContato || $botAtivo)<a href="#contato">Contato</a>@endif
      @if($wa)<a class="btn btn-pri" style="min-height:40px;padding:8px 20px" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Agendar</a>@endif
    </nav>
  </div>
</header>

<main id="topo">
  <section class="hero">
    <div class="wrap grid">
      <div>
        <span class="eyebrow">{{ $empresa->nome ?? $nome }}@if($endRel && $endRel->cidade) · {{ $endRel->cidade }}@endif</span>
        <h1>{{ $nome }}</h1>
        @if($descricao)<p>{{ $descricao }}</p>@endif
        <div class="acts">
          @if($wa)<a class="btn btn-pri" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Agendar pelo WhatsApp</a>@endif
          @if($servicos->isNotEmpty())<a class="btn btn-ghost" href="#servicos">Ver serviços</a>@endif
        </div>
      </div>
      @if($capaUrl)
        <figure><img src="{{ $capaUrl }}" alt="{{ $nome }}"></figure>
      @else
        <div class="sun" role="img" aria-label="Sol"></div>
      @endif
    </div>
  </section>

  @if($servicos->isNotEmpty())
  <section id="servicos">
    <div class="wrap">
      <div class="head"><span class="eyebrow">Serviços</span><h2>O que você encontra por aqui</h2></div>
      <div class="cards">
        @foreach($servicos as $srv)
          <article class="card">
            @if($srv->imagem)<img src="{{ asset('storage/'.$srv->imagem) }}" alt="{{ $srv->titulo }}" loading="lazy">@endif
            <div class="bd">
              <h3>{{ $srv->titulo }}</h3>
              <p>{{ $srv->descricao }}</p>
              <div class="meta">
                @if($p = $preco($srv->preco))<span class="tag">{{ $p }}</span>@endif
                @if($srv->duracao)<span class="tag">{{ $srv->duracao }}</span>@endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <section id="sobre" class="alt">
    <div class="wrap sobre {{ $sobreImg ? 'com-img' : '' }}">
      <div>
        <span class="eyebrow">Sobre</span>
        <h2 style="font-size:clamp(1.7rem,4vw,2.4rem);margin:8px 0 14px">{{ $sobreTitulo }}</h2>
        @if($sobreDesc)<p>{{ $sobreDesc }}</p>@endif
        @if($sobreItens->isNotEmpty())
          <ul class="itens">
            @foreach($sobreItens as $it)
              <li><strong>{{ $it['titulo'] ?? '' }}</strong><span>{{ $it['descricao'] ?? '' }}</span></li>
            @endforeach
          </ul>
        @endif
      </div>
      @if($sobreImg)<img src="{{ $sobreImg }}" alt="{{ $nome }}" loading="lazy">@endif
    </div>
  </section>

  @if($galeria->isNotEmpty())
  <section>
    <div class="wrap">
      <div class="head"><span class="eyebrow">Galeria</span><h2>O estúdio</h2></div>
      <div class="galeria">
        @foreach($galeria as $g)<img src="{{ asset('storage/'.($g->imagem ?? $g->caminho ?? '')) }}" alt="{{ $nome }}" loading="lazy">@endforeach
      </div>
    </div>
  </section>
  @endif

  @if($temContato || $botAtivo)
  <section id="contato">
    <div class="wrap contato">
      <div>
        <div class="head" style="margin-bottom:22px"><span class="eyebrow">Contato</span><h2>Fale com a gente</h2></div>
        <dl>
          @if($wa)<div><dt>WhatsApp</dt><dd><a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">{{ $telefone ?: '+'.$wa }}</a></dd></div>
          @elseif($telefone)<div><dt>Telefone</dt><dd>{{ $telefone }}</dd></div>@endif
          @if($endStr)<div><dt>Endereço</dt><dd>{{ $endStr }}</dd></div>@endif
          @if($instagram)<div><dt>Instagram</dt><dd><a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener">&#64;{{ $instagram }}</a></dd></div>@endif
          @if($botAtivo)<div><dt>Chat</dt><dd>Use o botão de chat no canto da tela para tirar dúvidas e agendar.</dd></div>@endif
        </dl>
      </div>
      @if($wa)
      <div class="cta">
        <h2>Vamos marcar o seu horário?</h2>
        <p>Chame no WhatsApp e agende.</p>
        <a class="btn" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Chamar no WhatsApp</a>
      </div>
      @endif
    </div>
  </section>
  @endif
</main>

<footer>
  <div class="wrap"><span>© {{ date('Y') }} {{ $nome }}</span>@if($endStr)<span>{{ $endStr }}</span>@endif</div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/site-metrics.js') }}"></script>
<script>
    window.SITE_ID = {{ $site->id ?? 1 }};
    window.WHATSAPP = '{{ $wa }}';
</script>
@if($botAtivo){!! $bot->widgetSnippet() !!}@endif
</body>
</html>
