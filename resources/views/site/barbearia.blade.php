@php
  $nome       = $site->titulo ?? 'Barbearia';
  $descricao  = $site->descricao ?? '';
  $wa         = preg_replace('/\D/', '', $site->whatsapp ?? '');
  $waText     = rawurlencode('Oi! Quero agendar um horário na '.$nome);
  $corPri     = $site->cores['primaria']   ?? '#c9a24b';
  $corSec     = $site->cores['secundaria'] ?? '#1b1b1b';

  $capaUrl    = $site->capa         ? asset('storage/'.$site->capa)         : null;
  $logoUrl    = $site->logo         ? asset('storage/'.$site->logo)         : null;
  $sobreImg   = $site->sobre_imagem ? asset('storage/'.$site->sobre_imagem) : null;
  $sobreTitulo = $site->sobre_titulo    ?? 'A casa';
  $sobreDesc   = $site->sobre_descricao ?? '';
  $sobreItens  = collect($site->sobre_itens ?? []);

  $servicos   = $site->siteServicos ?? collect();
  $depoimentos = $site->depoimentos ?? collect();
  $avaliacaoGoogle = config('sites.avaliacao_google', [])[$site->id] ?? null;

  $empresa    = $site->empresa ?? null;
  $endRel     = $site->endereco ?? null;
  $telefone   = $empresa->telefone ?? null;
  $endStr     = $endRel ? trim(($endRel->endereco ?? '').(($endRel->cidade ?? null) ? ' — '.$endRel->cidade : '').(($endRel->uf ?? null) ? '/'.$endRel->uf : '')) : null;
  $cidade     = $endRel->cidade ?? null;
  $contatos   = collect($site->contatos ?? []);
  $instagram  = ltrim((string) ($site->instagram ?: optional($contatos->firstWhere('tipo', 'instagram'))->valor), '@');
  $horarios   = $contatos->where('tipo', 'horario')->pluck('valor')->filter()->values();
  $galeria    = $empresa ? $empresa->galeria : collect();
  $bot        = $site->bot_id ? \App\Models\Bot::find($site->bot_id) : null;
  $botAtivo   = $bot && $bot->widget_ativo && $bot->status;
  $preco      = fn ($v) => (float) $v > 0 ? 'R$ '.number_format((float) $v, 2, ',', '.') : null;

  // Fila de check-in: só quando a empresa é de um nicho com a função ligada (a barbearia de verdade)
  $nichoEmpresa = $empresa ? \App\Models\NichoConfiguracao::daEmpresa($empresa) : null;
  $temFila = $empresa && $empresa->status === 'ativo' && $nichoEmpresa && $nichoEmpresa->recurso('fila_checkin');
  $barbeiros = collect();
  $naFilaTotal = 0;
  if ($temFila) {
      $esperando = \App\Models\FilaAtendimento::where('empresa_id', $empresa->id)->where('status', 'aguardando')
          ->selectRaw('professor_id, count(*) as n')->groupBy('professor_id')->pluck('n', 'professor_id');
      $barbeiros = \App\Models\Professor::where('empresa_id', $empresa->id)->where('ativo', true)->with('usuario')->orderBy('id')->get()
          ->each(fn ($b) => $b->esperando = (int) ($esperando[$b->id] ?? 0));
      $naFilaTotal = $barbeiros->sum('esperando');
  }
  $termoBarbeiros = $nichoEmpresa ? $nichoEmpresa->termo('profissionais', 'Barbeiros') : 'Barbeiros';
  $linkFila = $temFila ? route('barbearia.show', $empresa) : null;
  $canonical = 'https://'.request()->getHost();
  $descMeta = \Illuminate\Support\Str::limit(strip_tags($descricao ?: 'Barbearia'.($cidade ? " em {$cidade}" : '').'. Veja os barbeiros, a tabela de preços e entre na fila.'), 155);
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nome }}@if($cidade) · Barbearia em {{ $cidade }}@endif</title>
<meta name="description" content="{{ $descMeta }}">
<link rel="canonical" href="{{ $canonical }}">
@unless($site->ativo)<meta name="robots" content="noindex, nofollow">@endunless
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $nome }}">
<meta property="og:description" content="{{ $descMeta }}">
<meta property="og:url" content="{{ $canonical }}">
@if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'HairSalon',
    'name' => $nome,
    'description' => $descMeta,
    'url' => $canonical,
    'image' => $capaUrl ?: $logoUrl,
    'telephone' => $telefone ?: ($wa ? '+'.$wa : null),
    'address' => $endRel ? array_filter(['@type' => 'PostalAddress', 'streetAddress' => $endRel->endereco, 'addressLocality' => $endRel->cidade, 'addressRegion' => $endRel->uf, 'postalCode' => $endRel->cep, 'addressCountry' => 'BR']) : null,
    'sameAs' => $instagram ? ['https://instagram.com/'.$instagram] : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --pri:{{ $corPri }};--noite:{{ $corSec }};
  --papel:#f4efe6;--tinta:#1b1b1b;--suave:#6e675b;--linha:#e0d8c8;
  --on-pri:#1b1b1b;--claro:#efe9dc;--claro-suave:#b9b2a3;--linha-esc:#34322e;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:76px}
body{margin:0;background:var(--noite);color:var(--claro);font:400 1.0625rem/1.6 'Barlow',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
img{max-width:100%;display:block}
a{color:inherit}
h1,h2,h3{font-family:'Oswald','Arial Narrow',sans-serif;font-weight:600;text-transform:uppercase;letter-spacing:.02em;line-height:1.08;margin:0;text-wrap:balance}
.wrap{width:min(1080px,100% - 32px);margin-inline:auto}
.btn{display:inline-flex;align-items:center;justify-content:center;min-height:50px;padding:12px 26px;border-radius:6px;font:600 1rem 'Oswald',sans-serif;letter-spacing:.06em;text-transform:uppercase;text-decoration:none;border:2px solid transparent;transition:transform .15s}
.btn:hover{transform:translateY(-2px)}
.btn-pri{background:var(--pri);color:var(--on-pri)}
.btn-ghost{border-color:var(--pri);color:var(--pri)}
.eyebrow{font:500 .85rem 'Oswald',sans-serif;letter-spacing:.2em;text-transform:uppercase;color:var(--pri)}
/* listra de poste de barbeiro */
.poste{height:8px;background:repeating-linear-gradient(-45deg,#b3261e 0 14px,#f4efe6 14px 28px,#1d4f91 28px 42px)}
header{position:sticky;top:0;z-index:20;background:color-mix(in srgb,var(--noite) 94%,transparent);backdrop-filter:blur(8px)}
header .wrap{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:64px}
.brand{display:flex;align-items:center;gap:10px;font:700 1.25rem 'Oswald',sans-serif;letter-spacing:.06em;text-transform:uppercase;text-decoration:none}
.brand img{height:36px;width:auto}
nav{display:flex;align-items:center;gap:22px}
nav a{text-decoration:none;font:500 .95rem 'Oswald',sans-serif;letter-spacing:.1em;text-transform:uppercase;color:var(--claro-suave)}
nav a:hover{color:var(--pri)}
@media (max-width:700px){nav a:not(.btn){display:none}}
/* hero */
.hero{position:relative;overflow:hidden;padding-block:64px 72px;background:
  linear-gradient(180deg,color-mix(in srgb,var(--noite) 70%,#000),var(--noite))}
.hero.com-capa{background:linear-gradient(180deg,rgba(0,0,0,.55),var(--noite) 96%),var(--capa) center/cover}
.hero h1{font-size:clamp(2.6rem,9vw,5.2rem);margin-block:14px 18px}
.hero p{max-width:50ch;color:var(--claro-suave);margin:0 0 28px;font-size:1.15rem}
.acts{display:flex;flex-wrap:wrap;gap:12px}
.agora{display:inline-flex;align-items:center;gap:10px;margin-top:26px;padding:10px 16px;border:1px solid var(--linha-esc);border-radius:999px;font-weight:500;color:var(--claro)}
.agora i{width:10px;height:10px;border-radius:50%;background:#58c47f;box-shadow:0 0 0 4px rgba(88,196,127,.2)}
.agora.cheia i{background:#e0a030;box-shadow:0 0 0 4px rgba(224,160,48,.2)}
/* seções */
section{padding-block:72px}
section.papel{background:var(--papel);color:var(--tinta)}
.head{max-width:60ch;margin-bottom:34px}
.head h2{font-size:clamp(1.9rem,5vw,2.8rem);margin-top:8px}
.papel .eyebrow{color:#8a6a14}
/* barbeiros */
.barbeiros{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
.barbeiro{display:flex;align-items:center;gap:16px;padding:18px;border:1px solid var(--linha-esc);border-radius:10px;text-decoration:none;background:#222}
.barbeiro:hover{border-color:var(--pri)}
.barbeiro .ini{width:56px;height:56px;border-radius:50%;background:var(--pri);color:var(--on-pri);display:grid;place-items:center;font:700 1.4rem 'Oswald',sans-serif;flex:none}
.barbeiro strong{display:block;font:600 1.15rem 'Oswald',sans-serif;letter-spacing:.04em;text-transform:uppercase}
.barbeiro span.fila{font-size:.9rem;color:var(--claro-suave)}
.barbeiro span.fila b{color:#e0a030}
/* tabela de preços (quadro de barbearia) */
.tabela{list-style:none;margin:0;padding:0;display:grid;gap:4px 56px}
@media (min-width:860px){.tabela{grid-template-columns:1fr 1fr}}
.tabela li{padding-block:14px;border-bottom:1px solid var(--linha)}
.tabela .linha{display:flex;align-items:baseline;gap:10px}
.tabela .linha h3{font-size:1.2rem}
.tabela .linha .pt{flex:1;border-bottom:2px dotted #b8ad95;transform:translateY(-4px)}
.tabela .linha .valor{font:700 1.25rem 'Oswald',sans-serif;color:#8a6a14;white-space:nowrap}
.tabela p{margin:4px 0 0;color:var(--suave);font-size:.95rem}
.tabela .dur{font-size:.85rem;color:var(--suave)}
/* horários */
.horas{display:grid;gap:10px;max-width:520px}
.horas p{margin:0;padding:12px 16px;border-left:3px solid var(--pri);background:#222}
/* sobre / galeria */
.sobre{display:grid;gap:34px;align-items:center}
@media (min-width:860px){.sobre.com-img{grid-template-columns:1fr 1fr}}
.sobre img{border-radius:10px;width:100%;aspect-ratio:4/3;object-fit:cover}
.sobre p{color:var(--claro-suave);max-width:60ch}
.itens{list-style:none;padding:0;margin:20px 0 0;display:grid;gap:14px}
.itens li{padding-left:16px;border-left:3px solid var(--pri)}
.itens strong{display:block}
.itens span{color:var(--claro-suave)}
.galeria{display:grid;gap:10px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
.galeria img{aspect-ratio:1;width:100%;object-fit:cover;border-radius:8px}
/* avaliações */
.avals{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
.aval{background:#fff;border-radius:10px;padding:20px;border:1px solid var(--linha)}
.aval .est{color:#c9971a;letter-spacing:2px}
.aval p{margin:8px 0;color:var(--tinta)}
.aval small{color:var(--suave)}
.cta-aval{margin-top:26px}
/* contato */
.contato{display:grid;gap:30px}
@media (min-width:860px){.contato{grid-template-columns:1fr 1fr}}
.contato dl{margin:0;display:grid;gap:16px}
.contato dt{font:500 .85rem 'Oswald',sans-serif;letter-spacing:.16em;text-transform:uppercase;color:var(--pri)}
.contato dd{margin:2px 0 0;overflow-wrap:anywhere}
.cta{background:var(--pri);color:var(--on-pri);border-radius:12px;padding:40px 28px;text-align:center}
.cta h2{font-size:clamp(1.7rem,4vw,2.4rem);margin-bottom:12px}
.cta p{margin:0 0 22px}
.cta .btn{background:var(--noite);color:var(--claro)}
footer{padding-block:26px;color:var(--claro-suave);font-size:.92rem}
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
      @if($barbeiros->isNotEmpty())<a href="#barbeiros">{{ $termoBarbeiros }}</a>@endif
      @if($servicos->isNotEmpty())<a href="#precos">Preços</a>@endif
      @if($horarios->isNotEmpty())<a href="#horarios">Horários</a>@endif
      @if($depoimentos->count() || $avaliacaoGoogle)<a href="#avaliacoes">Avaliações</a>@endif
      <a href="#contato">Contato</a>
      @if($linkFila)<a class="btn btn-pri" style="min-height:40px;padding:6px 18px" href="{{ $linkFila }}">Entrar na fila</a>
      @elseif($wa)<a class="btn btn-pri" style="min-height:40px;padding:6px 18px" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Agendar</a>@endif
    </nav>
  </div>
  <div class="poste" aria-hidden="true"></div>
</header>

<main id="topo">
  <section class="hero {{ $capaUrl ? 'com-capa' : '' }}" @if($capaUrl) style="--capa:url('{{ $capaUrl }}')" @endif>
    <div class="wrap">
      <span class="eyebrow">{{ $cidade ? "Barbearia · {$cidade}" : "Barbearia" }}</span>
      <h1>{{ $nome }}</h1>
      @if($descricao)<p>{{ $descricao }}</p>@endif
      <div class="acts">
        @if($linkFila)<a class="btn btn-pri" href="{{ $linkFila }}">Entrar na fila</a>@endif
        @if($wa)<a class="btn {{ $linkFila ? 'btn-ghost' : 'btn-pri' }}" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Agendar pelo WhatsApp</a>@endif
        @if($servicos->isNotEmpty() && ! $linkFila && ! $wa)<a class="btn btn-pri" href="#precos">Ver preços</a>@endif
      </div>
      @if($temFila && $barbeiros->isNotEmpty())
        <div class="agora {{ $naFilaTotal >= 4 ? 'cheia' : '' }}" id="fila-agora"><i></i>
          <span>{{ $naFilaTotal === 0 ? 'Sem fila agora' : $naFilaTotal.' '.($naFilaTotal === 1 ? 'pessoa' : 'pessoas').' na fila agora' }}</span>
        </div>
      @endif
    </div>
  </section>

  @if($barbeiros->isNotEmpty())
  <section id="barbeiros">
    <div class="wrap">
      <div class="head"><span class="eyebrow">A equipe</span><h2>{{ $termoBarbeiros }}</h2></div>
      <div class="barbeiros">
        @foreach($barbeiros as $b)
          <a class="barbeiro" href="{{ route('barbearia.barbeiro', [$empresa, $b]) }}">
            <span class="ini">{{ mb_strtoupper(mb_substr(optional($b->usuario)->nome ?? '?', 0, 1)) }}</span>
            <span><strong>{{ optional($b->usuario)->nome }}</strong>
              <span class="fila">@if($b->esperando)<b>{{ $b->esperando }}</b> na fila @else sem fila agora @endif</span></span>
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @if($servicos->isNotEmpty())
  <section id="precos" class="papel">
    <div class="wrap">
      <div class="head"><span class="eyebrow">Tabela</span><h2>Serviços e preços</h2></div>
      <ul class="tabela">
        @foreach($servicos as $srv)
          <li>
            <div class="linha"><h3>{{ $srv->titulo }}</h3><span class="pt"></span>@if($p = $preco($srv->preco))<span class="valor">{{ $p }}</span>@endif</div>
            @if($srv->descricao)<p>{{ $srv->descricao }}</p>@endif
            @if($srv->duracao)<span class="dur">{{ $srv->duracao }}</span>@endif
          </li>
        @endforeach
      </ul>
    </div>
  </section>
  @endif

  @if($horarios->isNotEmpty())
  <section id="horarios">
    <div class="wrap">
      <div class="head"><span class="eyebrow">Funcionamento</span><h2>Horários</h2></div>
      <div class="horas">@foreach($horarios as $h)<p>{{ $h }}</p>@endforeach</div>
    </div>
  </section>
  @endif

  @if($sobreDesc || $sobreItens->isNotEmpty() || $sobreImg)
  <section id="sobre" class="papel">
    <div class="wrap sobre {{ $sobreImg ? 'com-img' : '' }}">
      <div>
        <span class="eyebrow">Sobre</span>
        <h2 style="font-size:clamp(1.9rem,5vw,2.8rem);margin:8px 0 14px">{{ $sobreTitulo }}</h2>
        @if($sobreDesc)<p style="color:var(--suave)">{{ $sobreDesc }}</p>@endif
        @if($sobreItens->isNotEmpty())
          <ul class="itens">
            @foreach($sobreItens as $it)
              <li><strong>{{ $it['titulo'] ?? '' }}</strong><span style="color:var(--suave)">{{ $it['descricao'] ?? '' }}</span></li>
            @endforeach
          </ul>
        @endif
      </div>
      @if($sobreImg)<img src="{{ $sobreImg }}" alt="{{ $nome }}" loading="lazy">@endif
    </div>
  </section>
  @endif

  @if($galeria->isNotEmpty())
  <section>
    <div class="wrap">
      <div class="head"><span class="eyebrow">Galeria</span><h2>A barbearia</h2></div>
      <div class="galeria">
        @foreach($galeria as $g)@if($g->image)<img src="{{ asset('storage/'.$g->image) }}" alt="{{ $nome }}" loading="lazy">@endif @endforeach
      </div>
    </div>
  </section>
  @endif

  @if($depoimentos->count() || $avaliacaoGoogle)
  <section id="avaliacoes" class="papel">
    <div class="wrap">
      <div class="head"><span class="eyebrow">Clientes</span><h2>O que dizem de nós</h2></div>
      @if($depoimentos->count())
      <div class="avals">
        @foreach($depoimentos as $d)
          <div class="aval"><div class="est">{{ str_repeat('★', max(1, min(5, (int) $d->nota ?: 5))) }}</div><p>{{ $d->comentario }}</p><small>{{ $d->nome }}</small></div>
        @endforeach
      </div>
      @endif
      @if($avaliacaoGoogle)
        <div class="cta-aval"><a class="btn btn-pri" style="background:var(--noite);color:var(--claro)" href="{{ $avaliacaoGoogle }}" target="_blank" rel="noopener">Avalie a gente no Google</a></div>
      @endif
    </div>
  </section>
  @endif

  <section id="contato">
    <div class="wrap contato">
      <div>
        <div class="head" style="margin-bottom:22px"><span class="eyebrow">Contato</span><h2>Venha cortar</h2></div>
        <dl>
          @if($wa)<div><dt>WhatsApp</dt><dd><a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">{{ $telefone ?: '+'.$wa }}</a></dd></div>
          @elseif($telefone)<div><dt>Telefone</dt><dd>{{ $telefone }}</dd></div>@endif
          @if($endStr)<div><dt>Endereço</dt><dd><a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($endStr) }}" target="_blank" rel="noopener">{{ $endStr }}</a></dd></div>@endif
          @if($instagram)<div><dt>Instagram</dt><dd><a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener">&#64;{{ $instagram }}</a></dd></div>@endif
          @if($botAtivo)<div><dt>Chat</dt><dd>Use o botão de chat no canto da tela para tirar dúvidas.</dd></div>@endif
        </dl>
      </div>
      @if($linkFila || $wa)
      <div class="cta">
        <h2>{{ $linkFila ? 'Chegou? Entre na fila' : 'Vamos marcar o seu horário?' }}</h2>
        <p>{{ $linkFila ? 'Escolha o barbeiro e faça o check-in pelo celular.' : 'Chame no WhatsApp e agende.' }}</p>
        @if($linkFila)<a class="btn" href="{{ $linkFila }}">Escolher barbeiro</a>
        @else<a class="btn" href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener">Chamar no WhatsApp</a>@endif
      </div>
      @endif
    </div>
  </section>
</main>

<div class="poste" aria-hidden="true"></div>
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
