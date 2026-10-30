@php
  $nomeEscola = $site->titulo ?? 'Escola de Surf';
  $descricao  = $site->descricao ?? 'Aulas de surf para todos os níveis.';
  $wa         = preg_replace('/\D/', '', $site->whatsapp ?? '');
  $waText     = rawurlencode('Oi! Quero agendar uma aula de surf na '.$nomeEscola);
  $corPri     = $site->cores['primaria']   ?? '#0a5c8a';
  $corSec     = $site->cores['secundaria'] ?? '#0e86c8';

  $capaUrl    = $site->capa         ? asset('storage/'.$site->capa)         : null;
  $logoUrl    = $site->logo         ? asset('storage/'.$site->logo)         : null;
  $sobreImg   = $site->sobre_imagem ? asset('storage/'.$site->sobre_imagem) : null;

  $sobreTitulo = $site->sobre_titulo    ?? 'Surf para todos, do primeiro remo à primeira onda.';
  $sobreDesc   = $site->sobre_descricao ?? $descricao;
  $sobreItens  = collect($site->sobre_itens ?? []);

  $depoimentos = $site->depoimentos ?? collect();
  $servicos    = $site->siteServicos ?? collect();
  $artigos     = $site->artigos ?? collect();

  $empresa    = $site->empresa ?? null;
  $endRel     = $site->endereco ?? null;
  $telefone   = $empresa->telefone ?? null;
  $emailSite  = $empresa->email    ?? null;
  $endStr     = $endRel ? trim(($endRel->endereco ?? '').(($endRel->cidade ?? null) ? ' — '.$endRel->cidade : '')) : null;
  $instagram  = ltrim((string) ($site->instagram ?? ''), '@');
  $galeria    = $empresa ? $empresa->galeria : collect();
  $galeriaUrl = $site->galeria_url ?? null;
  $bot        = $site->bot_id ? \App\Models\Bot::find($site->bot_id) : null;
  $botAtivo   = $bot && $bot->widget_ativo && $bot->status;
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nomeEscola }} — Escola de Surf em Ipanema</title>
<meta name="description" content="{{ $descricao }}">
<meta name="robots" content="index, follow">
<meta name="google-site-verification" content="PENdq3tzLGfpwMXRvoiqISI5uM6cRsIe0Wvm77kL6K0">
<link rel="canonical" href="{{ str_replace('http://', 'https://', request()->url()) }}">

{{-- Open Graph --}}
<meta property="og:type"        content="website">
<meta property="og:title"       content="{{ $nomeEscola }} — Escola de Surf">
<meta property="og:description" content="{{ $descricao }}">
<meta property="og:url"         content="{{ request()->url() }}">
@if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
<meta property="og:locale"      content="pt_BR">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $nomeEscola }} — Escola de Surf">
<meta name="twitter:description" content="{{ $descricao }}">
@if($capaUrl)<meta name="twitter:image" content="{{ $capaUrl }}">@endif

{{-- Schema.org LocalBusiness --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsActivityLocation",
  "name": "{{ $nomeEscola }}",
  "description": "{{ $descricao }}"
  @if($logoUrl),"logo": "{{ $logoUrl }}"@endif
  @if($capaUrl),"image": "{{ $capaUrl }}"@endif
  @if($wa),"telephone": "+{{ $wa }}"@endif
  @if($emailSite),"email": "{{ $emailSite }}"@endif
  @if($endStr),"address": {"@type":"PostalAddress","streetAddress":"{{ $endStr }}"}@endif
  ,"url": "{{ request()->getSchemeAndHttpHost() }}"
  ,"sameAs": []
}
</script>

<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --sea:      {{ $corPri }};
  --sea-deep: color-mix(in srgb, {{ $corPri }} 70%, #000);
  --sea-soft: color-mix(in srgb, {{ $corPri }} 12%, #fff);
  --coral:    oklch(0.72 0.13 35);
  --coral-soft: oklch(0.94 0.04 40);
  --bg:       #f9f8f6;
  --bg-2:     #f2f1ee;
  --ink:      #1e1c18;
  --ink-soft: #5a5650;
  --line:     #e4e1db;
  --wa:       #25D366;
  --wa-deep:  #128C7E;
  --radius-m: 14px; --radius-l: 22px; --radius-xl: 36px;
  --display: "Bricolage Grotesque", ui-sans-serif, system-ui, sans-serif;
  --sans:    "DM Sans", ui-sans-serif, system-ui, sans-serif;
  --mono:    "JetBrains Mono", ui-monospace, monospace;
}
*{box-sizing:border-box;margin:0;padding:0}
html{width:100%;overflow-x:hidden;scroll-behavior:smooth}
body{width:100%;min-height:100vh;font-family:var(--sans);color:var(--ink);background:var(--bg);
  -webkit-font-smoothing:antialiased;line-height:1.55;font-size:16px;overflow-x:hidden}
img,svg{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
button{font:inherit;cursor:pointer;border:0;background:none;color:inherit}
::selection{background:var(--sea);color:#fff}

/* ── Layout ── */
.wrap{width:100%;max-width:min(1560px,94vw);margin-inline:auto;padding-inline:clamp(16px,3vw,48px)}
section{display:block;width:100%;padding:clamp(56px,8vw,112px) 0}

/* ── Nav ── */
.nav{position:sticky;top:0;left:0;z-index:50;width:100%;display:block;
  backdrop-filter:blur(14px);
  background:color-mix(in srgb,var(--bg) 92%,transparent);
  border-bottom:1px solid var(--line)}
.nav-inner{display:flex;flex-direction:column;width:100%}
.nav-row{display:flex;align-items:center;gap:20px;padding:10px 0 8px}

.brand{display:flex;align-items:center;gap:10px;font-family:var(--display);
  font-weight:700;font-size:21px;letter-spacing:-.01em;flex-shrink:0;line-height:1}
.brand img{height:34px;width:auto;object-fit:contain}
.brand .mark{width:30px;height:30px;border-radius:50%;flex-shrink:0;
  background:conic-gradient(from 200deg,var(--sea-deep),var(--sea) 35%,var(--sea-soft) 60%,var(--sea) 100%)}

.nav-links{display:flex;gap:24px;margin-left:8px}
.nav-links a{font-size:14px;color:var(--ink-soft);transition:color .2s}
.nav-links a:hover{color:var(--ink)}
.nav-cta{margin-left:auto;display:flex;align-items:center;gap:10px}
.btn{display:inline-flex;align-items:center;gap:7px;font-weight:600;font-size:14px;
  padding:10px 20px;border-radius:999px;transition:transform .15s,background .2s,color .2s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:var(--sea);color:#fff}
.btn-primary:hover{background:var(--sea-deep)}
.btn-ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}
.btn-ghost:hover{border-color:var(--ink)}

/* ── Hero ── */
.hero{position:relative;overflow:hidden;width:100%;
  padding:clamp(48px,7vw,100px) 0 clamp(56px,8vw,112px);
  background:linear-gradient(135deg,color-mix(in srgb,var(--sea) 9%,#fff) 0%,var(--bg) 65%)}
.hero-grid{display:grid;grid-template-columns:1.1fr 0.9fr;gap:clamp(24px,4vw,64px);align-items:center}
.eyebrow{font-family:var(--mono);font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--sea-deep);
  display:inline-flex;align-items:center;gap:10px;margin-bottom:20px}
.eyebrow::before{content:"";width:26px;height:1px;background:var(--sea-deep)}
h1.headline{font-family:var(--display);font-weight:700;font-size:clamp(44px,6.5vw,96px);
  line-height:.96;letter-spacing:-.035em;margin:0 0 22px;text-wrap:balance}
h1.headline em{font-style:italic;font-weight:500;color:var(--sea-deep)}
.sub{font-size:clamp(15px,1.3vw,18px);color:var(--ink-soft);max-width:52ch;margin:0 0 30px;text-wrap:pretty}
.hero-ctas{display:flex;gap:10px;flex-wrap:wrap}
.hero-stats{display:flex;gap:clamp(18px,3vw,40px);margin-top:clamp(32px,5vw,56px);
  padding-top:22px;border-top:1px solid var(--line)}
.hero-stats .s strong{font-family:var(--display);font-weight:600;font-size:clamp(26px,2.8vw,38px);
  display:block;letter-spacing:-.02em}
.hero-stats .s span{font-size:12px;color:var(--ink-soft)}
.hero-art{position:relative;aspect-ratio:4/5;border-radius:var(--radius-xl);overflow:hidden;
  background:var(--sea-soft);width:100%}
.hero-art img{width:100%;height:100%;object-fit:cover}
.hero-art .ph-block{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  background:repeating-linear-gradient(135deg,color-mix(in srgb,var(--sea) 15%,#fff) 0 14px,var(--sea-soft) 14px 28px);
  font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--sea-deep)}
.hero-art .ph-block span{background:rgba(255,255,255,.8);padding:6px 12px;border-radius:6px}
.badge-float{position:absolute;bottom:16px;left:16px;background:#fff;border-radius:var(--radius-m);
  padding:12px 16px;display:flex;align-items:center;gap:10px;
  box-shadow:0 16px 48px -16px color-mix(in srgb,var(--sea-deep) 30%,transparent);max-width:80%}
.badge-float .dot{width:9px;height:9px;border-radius:50%;background:var(--coral);
  box-shadow:0 0 0 4px color-mix(in srgb,var(--coral) 22%,transparent);flex-shrink:0}
.badge-float small{display:block;color:var(--ink-soft);font-size:11px;font-family:var(--mono);
  letter-spacing:.06em;text-transform:uppercase}
.badge-float strong{font-family:var(--display);font-weight:600;font-size:14px}

/* ── Sections ── */
.section-head{display:grid;grid-template-columns:1fr 1.4fr;gap:clamp(20px,4vw,60px);
  align-items:end;margin-bottom:clamp(36px,5vw,64px)}
h2.title{font-family:var(--display);font-weight:600;font-size:clamp(30px,4vw,58px);
  line-height:1.02;letter-spacing:-.025em;margin:10px 0 0;text-wrap:balance}
h2.title em{font-style:italic;font-weight:500;color:var(--sea-deep)}
.section-lead{font-size:16px;color:var(--ink-soft);max-width:54ch;text-wrap:pretty}
.kicker{font-family:var(--mono);font-size:11px;letter-spacing:.2em;text-transform:uppercase;
  color:var(--coral);display:inline-flex;align-items:center;gap:8px}
.kicker::before{content:"●"}

/* ── Values ── */
.values{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.value{border:1px solid var(--line);border-radius:var(--radius-l);padding:26px;background:var(--bg);
  transition:transform .25s,border-color .25s,background .25s}
.value:hover{transform:translateY(-3px);border-color:var(--sea);background:var(--sea-soft)}
.value .ico{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;
  background:var(--sea-soft);color:var(--sea-deep);margin-bottom:16px}
.value h3{font-family:var(--display);font-size:20px;font-weight:600;margin:0 0 6px;letter-spacing:-.01em}
.value p{margin:0;color:var(--ink-soft);font-size:14.5px}

/* ── Classes / Cards ── */
.classes{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.class-card{border-radius:var(--radius-l);overflow:hidden;background:var(--bg-2);border:1px solid var(--line);
  display:flex;flex-direction:column;transition:transform .3s}
.class-card:hover{transform:translateY(-4px)}
.class-card.featured{border-color:var(--sea);background:color-mix(in srgb,var(--sea) 4%,var(--bg-2))}
.class-card.featured .tag{background:var(--sea);color:#fff}
.class-card .media{aspect-ratio:16/9;overflow:hidden;position:relative}
.class-card .media img{width:100%;height:100%;object-fit:cover}
.class-card .media .ph-block{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  background:repeating-linear-gradient(135deg,color-mix(in srgb,var(--sea) 12%,#fff) 0 14px,var(--sea-soft) 14px 28px);
  font-family:var(--mono);font-size:11px;text-transform:uppercase;color:var(--sea-deep)}
.class-card .media .ph-block span{background:rgba(255,255,255,.8);padding:5px 10px;border-radius:5px}
.class-card .body{padding:22px 24px 26px;display:flex;flex-direction:column;gap:8px}
.class-card .tag{display:inline-flex;align-self:flex-start;font-family:var(--mono);font-size:10px;
  letter-spacing:.14em;text-transform:uppercase;color:var(--sea-deep);background:var(--sea-soft);
  padding:4px 9px;border-radius:6px}
.class-card h3{font-family:var(--display);font-size:24px;font-weight:600;margin:2px 0 4px;letter-spacing:-.02em}
.class-card p{margin:0;color:var(--ink-soft);font-size:14.5px}
.class-card .meta{margin-top:auto;padding-top:14px;display:flex;gap:14px;flex-wrap:wrap;
  font-size:12px;color:var(--ink-soft)}
.class-card .meta b{color:var(--ink);font-weight:600}

/* ── About split ── */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:clamp(24px,5vw,64px);align-items:center}
.about-img{border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:4/5}
.about-img img{width:100%;height:100%;object-fit:cover}
.about-img .ph-block{width:100%;height:100%;display:flex;align-items:center;justify-content:center;
  background:repeating-linear-gradient(135deg,color-mix(in srgb,var(--sea) 12%,#fff) 0 14px,var(--sea-soft) 14px 28px);
  font-family:var(--mono);font-size:11px;text-transform:uppercase;color:var(--sea-deep)}
.about-img .ph-block span{background:rgba(255,255,255,.8);padding:5px 10px;border-radius:5px}

/* ── Testimonials ── */
.testimonials{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.quote{border:1px solid var(--line);border-radius:var(--radius-l);padding:26px;background:var(--bg);
  display:flex;flex-direction:column;gap:16px}
.quote .stars{color:var(--coral);letter-spacing:2px}
.quote blockquote{font-family:var(--display);font-size:17px;line-height:1.45;margin:0;
  letter-spacing:-.005em;text-wrap:pretty;color:var(--ink)}
.quote .who{display:flex;align-items:center;gap:10px;margin-top:auto}
.quote .avatar{width:40px;height:40px;border-radius:50%;overflow:hidden;flex-shrink:0;
  background:conic-gradient(from 0deg,var(--coral),var(--sea),var(--sea-deep),var(--coral));
  filter:saturate(.7)}
.quote .avatar img{width:100%;height:100%;object-fit:cover}
.quote .who b{font-weight:600;display:block;font-size:14px}
.quote .who span{font-size:12px;color:var(--ink-soft)}

/* ── Pricing ── */
.pricing{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.plan{border:1px solid var(--line);border-radius:var(--radius-l);padding:30px 26px;background:var(--bg);
  display:flex;flex-direction:column;gap:16px}
.plan.featured{background:var(--ink);color:#f9f8f6;border-color:var(--ink)}
.plan.featured .feat li{color:rgba(249,248,246,.75)}
.plan.featured .feat li svg{stroke:var(--coral)}
.plan.featured .pill{background:var(--coral);color:#fff}
.plan.featured .btn-primary{background:#f9f8f6;color:var(--ink)}
.plan.featured .btn-primary:hover{background:var(--coral);color:#fff}
.plan .pill{align-self:flex-start;font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;
  padding:4px 9px;border-radius:6px;background:var(--sea-soft);color:var(--sea-deep)}
.plan h3{font-family:var(--display);font-size:23px;margin:0;font-weight:600;letter-spacing:-.015em}
.plan .price{font-family:var(--display);font-size:48px;font-weight:600;letter-spacing:-.03em;line-height:1}
.plan .price sup{font-size:16px;font-weight:500;vertical-align:top;margin-right:4px;opacity:.7}
.plan .price small{font-size:13px;font-weight:500;margin-left:5px;opacity:.7;font-family:var(--sans)}
.feat{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px}
.feat li{display:flex;gap:9px;align-items:flex-start;font-size:14px;color:var(--ink-soft)}
.feat li svg{flex-shrink:0;stroke:var(--sea-deep);margin-top:2px}
.plan .btn{justify-content:center;margin-top:4px}

/* ── FAQ ── */
.faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 40px}
details.faq{border-bottom:1px solid var(--line);padding:16px 0}
details.faq summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:10px;
  font-family:var(--display);font-weight:600;font-size:17px;letter-spacing:-.01em}
details.faq summary::-webkit-details-marker{display:none}
details.faq summary::after{content:"+";margin-left:auto;font-weight:400;font-size:24px;
  color:var(--ink-soft);transition:transform .2s}
details.faq[open] summary::after{content:"−"}
details.faq p{margin:10px 0 0;color:var(--ink-soft);font-size:14.5px;max-width:58ch}

/* ── CTA Band ── */
.cta-band{background:var(--ink);color:#f9f8f6;border-radius:var(--radius-xl);
  padding:clamp(36px,5vw,72px);display:grid;grid-template-columns:1.2fr 1fr;
  gap:clamp(20px,4vw,52px);align-items:center;position:relative;overflow:hidden}
.cta-band::before{content:"";position:absolute;right:-100px;top:-100px;width:380px;height:380px;
  border-radius:50%;background:radial-gradient(circle,var(--sea) 0%,transparent 70%);opacity:.5}
.cta-band h2{font-family:var(--display);font-size:clamp(28px,4vw,54px);margin:0;
  font-weight:600;letter-spacing:-.025em;line-height:1.02}
.cta-band h2 em{font-style:italic;color:var(--coral);font-weight:500}
.cta-band p{color:rgba(249,248,246,.7);margin:14px 0 24px;font-size:16px;max-width:44ch}
.cta-band .btns{display:flex;gap:10px;flex-wrap:wrap;position:relative;z-index:1}
.cta-band .btn-primary{background:#f9f8f6;color:var(--ink)}
.cta-band .btn-primary:hover{background:var(--coral);color:#fff}
.cta-band .btn-ghost{border-color:rgba(249,248,246,.25);color:#f9f8f6}
.cta-band .info-card{background:rgba(249,248,246,.07);border:1px solid rgba(249,248,246,.14);
  border-radius:var(--radius-l);padding:26px;display:flex;flex-direction:column;gap:14px;
  position:relative;z-index:1;backdrop-filter:blur(20px)}
.cta-band .info-card .row{display:flex;gap:12px;align-items:flex-start}
.cta-band .info-card .row svg{flex-shrink:0;margin-top:2px;opacity:.75}
.cta-band .info-card small{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;
  color:rgba(249,248,246,.55);display:block}
.cta-band .info-card strong{font-weight:500;font-size:14.5px}

/* ── Contact form ── */
.contact-grid{display:grid;grid-template-columns:.9fr 1.1fr;gap:clamp(24px,5vw,60px);align-items:start}
.contact-side h3{font-family:var(--display);font-weight:600;font-size:21px;letter-spacing:-.01em;margin:0 0 6px}
.contact-side p{color:var(--ink-soft);font-size:14.5px;margin:0 0 26px;max-width:38ch}
.contact-side .info-row{display:flex;align-items:flex-start;gap:12px;padding:16px 0;border-top:1px solid var(--line)}
.contact-side .info-row:last-of-type{border-bottom:1px solid var(--line)}
.contact-side .info-row svg{flex-shrink:0;color:var(--sea-deep);margin-top:2px}
.contact-side .info-row small{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;
  color:var(--ink-soft);display:block;margin-bottom:2px}
.contact-side .info-row strong{font-weight:500;font-size:14.5px}
form.contact-form{background:var(--bg);border:1px solid var(--line);border-radius:var(--radius-l);
  padding:clamp(22px,3vw,34px);display:grid;grid-template-columns:1fr 1fr;gap:14px}
.field{display:flex;flex-direction:column;gap:5px}
.field.full{grid-column:span 2}
.field label{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-soft)}
.field label .req{color:var(--coral);margin-left:2px}
.field input,.field select,.field textarea{width:100%;font:inherit;color:var(--ink);background:var(--bg-2);
  border:1px solid var(--line);border-radius:10px;padding:11px 13px;
  transition:border-color .2s,background .2s,box-shadow .2s;appearance:none}
.field textarea{min-height:120px;resize:vertical;line-height:1.5}
.field select{background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' stroke='%23555' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/></svg>");
  background-repeat:no-repeat;background-position:right 13px center;padding-right:36px}
.field input:focus,.field select:focus,.field textarea:focus{outline:none;background:#fff;
  border-color:var(--sea);box-shadow:0 0 0 3px color-mix(in srgb,var(--sea) 14%,transparent)}
.field input::placeholder,.field textarea::placeholder{color:color-mix(in srgb,var(--ink-soft) 60%,transparent)}
.check-row{display:flex;align-items:flex-start;gap:9px;font-size:13px;color:var(--ink-soft)}
.check-row input{margin-top:2px;width:15px;height:15px;accent-color:var(--sea-deep);flex-shrink:0}
.form-foot{grid-column:span 2;display:flex;justify-content:space-between;align-items:center;
  gap:14px;flex-wrap:wrap;margin-top:2px}
.form-foot .submit{display:flex;gap:9px;flex-wrap:wrap}
.form-foot .note{font-size:12px;color:var(--ink-soft);font-family:var(--mono)}
.form-success{grid-column:span 2;display:none;background:color-mix(in srgb,var(--sea-soft) 60%,#fff);
  border:1px solid color-mix(in srgb,var(--sea) 28%,var(--line));border-radius:10px;
  padding:16px 18px;font-size:14px;color:var(--sea-deep);align-items:center;gap:10px}
.form-success.show{display:flex}

/* ── Blog / Artigos ── */
#blog{background:var(--bg-2)}
.blog-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.blog-card{background:var(--bg);border:1px solid var(--line);border-radius:var(--radius-l);
  overflow:hidden;display:flex;flex-direction:column;
  transition:transform .3s,box-shadow .3s}
.blog-card:hover{transform:translateY(-4px);
  box-shadow:0 16px 40px -16px color-mix(in srgb,var(--ink) 18%,transparent)}
.blog-card .thumb{aspect-ratio:16/9;overflow:hidden;position:relative;background:var(--sea-soft)}
.blog-card .thumb img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.blog-card:hover .thumb img{transform:scale(1.05)}
.blog-card .thumb .ph-block{position:absolute;inset:0;display:flex;align-items:center;
  justify-content:center;
  background:repeating-linear-gradient(135deg,color-mix(in srgb,var(--sea) 10%,#fff) 0 14px,var(--sea-soft) 14px 28px)}
.blog-card .thumb .ph-block span{background:rgba(255,255,255,.8);padding:5px 10px;
  border-radius:5px;font-family:var(--mono);font-size:11px;text-transform:uppercase;color:var(--sea-deep)}
.blog-card .body{padding:20px 22px 24px;display:flex;flex-direction:column;gap:8px;flex:1}
.blog-card .meta-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.blog-card .cat{font-family:var(--mono);font-size:10px;letter-spacing:.14em;
  text-transform:uppercase;color:var(--sea-deep);background:var(--sea-soft);
  padding:3px 8px;border-radius:5px}
.blog-card .date{font-family:var(--mono);font-size:10px;color:var(--ink-soft)}
.blog-card h3{font-family:var(--display);font-size:19px;font-weight:600;
  line-height:1.25;letter-spacing:-.01em;margin:0;text-wrap:balance}
.blog-card h3 a{color:var(--ink);transition:color .2s}
.blog-card h3 a:hover{color:var(--sea-deep)}
.blog-card p{margin:0;color:var(--ink-soft);font-size:14px;
  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.blog-card .read{margin-top:auto;padding-top:14px;
  font-family:var(--mono);font-size:11px;letter-spacing:.08em;text-transform:uppercase;
  color:var(--sea-deep);display:inline-flex;align-items:center;gap:6px;
  transition:gap .2s}
.blog-card:hover .read{gap:10px}
.blog-card--featured{grid-column:span 2}
.blog-card--featured .thumb{aspect-ratio:21/9}
.blog-card--featured h3{font-size:24px}
.blog-empty{text-align:center;padding:40px 0;color:var(--ink-soft);font-size:15px}
.blog-empty a{color:var(--sea-deep);font-weight:600}
@media(max-width:980px){
  .blog-grid{grid-template-columns:1fr 1fr}
  .blog-card--featured{grid-column:span 2}
  .blog-card--featured .thumb{aspect-ratio:16/9}
}
@media(max-width:640px){
  .blog-grid{grid-template-columns:1fr}
  .blog-card--featured{grid-column:span 1}
}

/* ── Footer ── */
footer{width:100%;margin-top:32px;padding:48px 0 28px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:36px}
.foot-grid h5{font-family:var(--mono);font-size:10px;letter-spacing:.16em;text-transform:uppercase;
  color:var(--ink-soft);margin:0 0 12px}
.foot-grid ul{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:7px}
.foot-grid a{font-size:14px;color:var(--ink)}
.foot-grid a:hover{color:var(--sea-deep)}
.foot-grid p{color:var(--ink-soft);font-size:14px;margin:6px 0 0;max-width:34ch}
.foot-bottom{margin-top:40px;padding-top:20px;border-top:1px solid var(--line);
  display:flex;justify-content:space-between;align-items:center;font-size:12px;
  color:var(--ink-soft);flex-wrap:wrap;gap:10px}
.foot-socials{display:flex;gap:8px}
.foot-socials a{width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;
  justify-content:center;border:1px solid var(--line);transition:background .2s,color .2s,border-color .2s}
.foot-socials a:hover{background:var(--ink);color:var(--bg);border-color:var(--ink)}

/* ── Google Translate / bandeiras ── */
#google_translate_element{display:none}
.goog-te-banner-frame,.skiptranslate{display:none!important}
body{top:0!important}

/* Nav vira duas linhas para acomodar bandeiras sob o logo */
.nav-inner{display:flex;flex-direction:column;width:100%}
.nav-row{padding:14px 0 10px}  /* linha principal: logo + links + cta */

/* Faixa das bandeiras — fica grudada sob o logo */
.lang-bar{
  display:flex;align-items:center;gap:2px;
  padding:4px 0 6px;
  border-top:1px solid var(--line);
}
.lang-btn{
  width:36px;height:26px;border-radius:5px;
  border:2px solid transparent;
  display:inline-flex;align-items:center;justify-content:center;
  cursor:pointer;background:transparent;padding:2px;
  transition:border-color .2s,transform .15s,box-shadow .15s;
  overflow:hidden;
}
.lang-btn img{
  width:100%;height:100%;object-fit:cover;
  border-radius:3px;display:block;
}
.lang-btn:hover{transform:scale(1.15);border-color:var(--line)}
.lang-btn.active{
  border-color:var(--sea);
  box-shadow:0 0 0 2px color-mix(in srgb,var(--sea) 25%,transparent);
}

/* ── Seções com background próprio ── */
#aulas{background:var(--bg-2)}
#precos{background:color-mix(in srgb,var(--sea) 5%,var(--bg))}
#depoimentos{background:var(--bg)}
#agendar{background:var(--ink);padding:clamp(48px,7vw,96px) 0}
#agendar .wrap{max-width:100%;padding-inline:clamp(20px,5vw,60px)}
/* Remove borda arredondada do CTA band quando seção já tem background */
#agendar .cta-band{background:transparent;border-radius:0;padding:0;box-shadow:none}
#agendar .cta-band h2{color:#f9f8f6}
#agendar .cta-band p{color:rgba(249,248,246,.7)}
footer{background:var(--bg)}

/* ── WhatsApp flutuante ── */
.whats{position:fixed;right:clamp(14px,3vw,26px);bottom:clamp(14px,3vw,26px);z-index:100;
  background:var(--wa);color:#fff;width:62px;height:62px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 12px 28px -8px color-mix(in srgb,var(--wa-deep) 65%,transparent);
  transition:transform .2s,width .25s;overflow:hidden;cursor:pointer}
.whats:hover{transform:scale(1.05);width:210px;border-radius:999px}
.whats svg{flex-shrink:0}
.whats .label{font-weight:600;font-size:13px;white-space:nowrap;max-width:0;opacity:0;
  transition:max-width .3s,opacity .2s .05s,margin .3s;margin-left:0}
.whats:hover .label{max-width:180px;opacity:1;margin-left:9px}
.whats::before{content:"";position:absolute;inset:0;border-radius:50%;background:var(--wa);
  opacity:.4;animation:pulse 2.2s ease-out infinite;z-index:-1}
@keyframes pulse{0%{transform:scale(1);opacity:.4}100%{transform:scale(1.7);opacity:0}}

/* ── Responsive ── */
@media(max-width:980px){
  .hero-grid,.about-grid,.cta-band,.contact-grid{grid-template-columns:1fr}
  .hero-art{aspect-ratio:5/4}
  .section-head{grid-template-columns:1fr}
  .values{grid-template-columns:1fr 1fr}
  .classes{grid-template-columns:1fr}
  .testimonials{grid-template-columns:1fr}
  .pricing{grid-template-columns:1fr}
  .faq-grid{grid-template-columns:1fr}
  .foot-grid{grid-template-columns:1fr 1fr}
  .nav-links{display:none}
  form.contact-form{grid-template-columns:1fr}
  .field.full,.form-foot,.form-success{grid-column:span 1}
  /* No mobile o botão CTA some, bandeiras ficam --*/
  .nav-cta .btn{display:none}
}
@media(max-width:560px){
  .values{grid-template-columns:1fr}
  h1.headline{font-size:42px}
  .nav-row{gap:12px;padding:8px 0 6px}
  .lang-bar{gap:3px}
  .lang-btn{width:30px;height:22px}
  .brand{font-size:18px}
}

/* ── Galeria (masonry por colunas, sem cortar as fotos) ── */
.gal{column-count:3;column-gap:14px}
.gal-item{display:block;break-inside:avoid;margin:0 0 14px;border-radius:var(--radius-m);overflow:hidden;
  background:var(--sea-soft);cursor:zoom-in;position:relative}
.gal-item img{width:100%;height:auto;transition:transform .35s}
.gal-item:hover img{transform:scale(1.03)}
.lb{position:fixed;inset:0;z-index:200;background:rgba(10,14,12,.92);display:flex;align-items:center;justify-content:center;padding:24px}
.lb[hidden]{display:none}
.lb img{max-width:min(94vw,1100px);max-height:88vh;width:auto;height:auto;object-fit:contain;border-radius:8px}
.lb-x,.lb-nav{position:absolute;color:#fff;background:rgba(255,255,255,.14);border-radius:50%;width:46px;height:46px;
  font-size:30px;line-height:1;display:flex;align-items:center;justify-content:center}
.lb-x{top:16px;right:16px}
.lb-prev{left:14px;top:50%;transform:translateY(-50%)}
.lb-next{right:14px;top:50%;transform:translateY(-50%)}
.lb-x:hover,.lb-nav:hover{background:rgba(255,255,255,.28)}
@media(max-width:980px){.gal{column-count:2}}
@media(max-width:560px){.gal{column-gap:10px}.gal-item{margin-bottom:10px}.lb-nav{width:40px;height:40px}}
</style>

  {{-- ── Códigos de rastreamento / marketing ── --}}
  @include('site._partials.tracking')
</head>
<body>

{{-- ═══ NAV ═══ --}}
<header class="nav">
  <div class="wrap nav-inner">

    {{-- Linha 1: logo · links · cta --}}
    <div class="nav-row">
      <div style="display:flex;flex-direction:column;gap:0">
        <a href="#" class="brand">
          @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $nomeEscola }}">
          @else
            <span class="mark"></span>
            <span>{{ $nomeEscola }}<span style="color:var(--coral)">.</span></span>
          @endif
        </a>
        {{-- Bandeiras — ficam sempre visíveis, abaixo do logo --}}
        @php $flagBase = request()->getSchemeAndHttpHost(); @endphp
        <div class="lang-bar" id="langSwitch">
          <button class="lang-btn" onclick="gtTo('pt')" title="Português" data-lang="pt">
            <img src="{{ $flagBase }}/admin/img/country/brasil.png" alt="PT">
          </button>
          <button class="lang-btn" onclick="gtTo('en')" title="English" data-lang="en">
            <img src="{{ $flagBase }}/admin/img/country/usa.png" alt="EN">
          </button>
          <button class="lang-btn" onclick="gtTo('es')" title="Español" data-lang="es">
            <img src="{{ $flagBase }}/admin/img/country/espanha.png" alt="ES">
          </button>
          <button class="lang-btn" onclick="gtTo('fr')" title="Français" data-lang="fr">
            <img src="{{ $flagBase }}/admin/img/country/franca.png" alt="FR">
          </button>
        </div>
      </div>

      <nav class="nav-links">
        @if($sobreItens->isNotEmpty() || $sobreDesc)<a href="#sobre">Sobre</a>@endif
        @if($servicos->isNotEmpty())<a href="#aulas">Aulas</a>@endif
        @if($galeria->isNotEmpty())<a href="#galeria">Galeria</a>@endif
        @if($depoimentos->isNotEmpty())<a href="#depoimentos">Depoimentos</a>@endif
        @if($artigos->isNotEmpty())<a href="#blog">Blog</a>@endif
        <a href="#contato">Contato</a>
      </nav>

      <div class="nav-cta">
        <a href="#contato" class="btn btn-primary">Agendar aula</a>
      </div>
    </div>

  </div>
</header>

{{-- Google Translate element oculto --}}
<div id="google_translate_element"></div>

{{-- ═══ HERO ═══ --}}
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow">Escola de Surf · Ipanema</span>
      <h1 class="headline">
        Aprenda a surfar<br/>
        <em>em Ipanema</em><br/>
        com a {{ $nomeEscola }}
      </h1>
      <p class="sub">{{ $descricao }}</p>
      <div class="hero-ctas">
        @if($wa)
        <a href="https://wa.me/{{ $wa }}?text={{ $waText }}" class="btn btn-primary" target="_blank" rel="noopener">Agendar pelo WhatsApp →</a>
        @endif
        @if($servicos->isNotEmpty())<a href="#aulas" class="btn btn-ghost">Ver aulas</a>@endif
        @if($telefone)
        <a href="tel:+55{{ preg_replace('/\D/','',$telefone) }}" class="btn btn-ghost">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.6 3.41 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.55a16 16 0 0 0 5.55 5.55l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          Ligue agora
        </a>
        @endif
      </div>
      <div class="hero-stats">
        <div class="s"><strong>Em grupo</strong><span>ou individual, você escolhe</span></div>
        <div class="s"><strong>Ipanema</strong><span>Rio de Janeiro · RJ</span></div>
      </div>
    </div>
    <div class="hero-art">
      @if($capaUrl)
        <img src="{{ $capaUrl }}" alt="Aula de surf da {{ $nomeEscola }} em Ipanema">
      @else
        <div class="ph-block"><span>Imagem principal</span></div>
      @endif
      @if($wa)
      <div class="badge-float">
        <span class="dot"></span>
        <div>
          <small>Fale com a gente</small>
          <strong>Agende sua aula pelo WhatsApp</strong>
        </div>
      </div>
      @endif
    </div>
  </div>
</section>

{{-- ═══ SOBRE ═══ --}}
<section id="sobre" style="background:var(--bg-2)">
  <div class="wrap">
    <div class="about-grid">
      <div class="about-img">
        @if($sobreImg)
          <img src="{{ $sobreImg }}" alt="Sobre a {{ $nomeEscola }}" loading="lazy">
        @else
          <div class="ph-block"><span>Foto da escola</span></div>
        @endif
      </div>
      <div>
        <span class="kicker">Sobre nós</span>
        <h2 class="title" style="margin-top:14px">{{ $sobreTitulo }}</h2>
        <p style="color:var(--ink-soft);font-size:16px;margin:18px 0 32px;text-wrap:pretty">{{ $sobreDesc }}</p>
        @if($sobreItens->isNotEmpty())
        <div class="values" style="grid-template-columns:1fr;gap:14px">
          @foreach($sobreItens as $item)
          <div class="value">
            <div class="ico">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/><path d="M2 20c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/></svg>
            </div>
            <h3>{{ $item['titulo'] ?? '' }}</h3>
            <p>{{ $item['descricao'] ?? '' }}</p>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══ AULAS ═══ --}}
@if($servicos->isNotEmpty())
<section id="aulas">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Nossas aulas</span>
        <h2 class="title">Para cada pé <em>na areia.</em></h2>
      </div>
      <p class="section-lead">Escolha o formato que combina com você e chame a gente no WhatsApp para combinar dia, horário e valores.</p>
    </div>
    <div class="classes">
      @foreach($servicos as $srv)
      <article class="class-card{{ $srv->destaque ? ' featured' : '' }}">
        <div class="media">
          @if(!empty($srv->imagem))
            <img src="{{ asset('storage/'.$srv->imagem) }}" alt="{{ $srv->titulo }}" loading="lazy">
          @else
            <div class="ph-block"><span>{{ $srv->titulo }}</span></div>
          @endif
        </div>
        <div class="body">
          @if(!empty($srv->nivel))<span class="tag">{{ $srv->nivel }}</span>@endif
          <h3>{{ $srv->titulo }}</h3>
          <p>{{ $srv->descricao }}</p>
          @php
            $meta = array_filter([
              $srv->duracao    ? '⏱ <b>'.e($srv->duracao).'</b>' : null,
              $srv->capacidade ? '👥 <b>'.e($srv->capacidade).'</b>' : null,
              $srv->info_extra ? '📌 <b>'.e($srv->info_extra).'</b>' : null,
              ($srv->preco > 0) ? '💰 <b>R$ '.number_format($srv->preco,2,',','.').'</b>' : '💬 <b>Valores pelo WhatsApp</b>',
            ]);
          @endphp
          <div class="meta">
            @foreach($meta as $m)<span>{!! $m !!}</span>@endforeach
          </div>
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══ GALERIA ═══ --}}
@if($galeria->isNotEmpty())
<section id="galeria" style="background:var(--bg-2)">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Galeria</span>
        <h2 class="title">Momentos <em>na areia e no mar.</em></h2>
      </div>
      <div>
        <p class="section-lead">Um pouco do dia a dia das aulas da {{ $nomeEscola }}.</p>
        @if($galeriaUrl)
        <a href="{{ $galeriaUrl }}" class="btn btn-primary" target="_blank" rel="noopener" style="margin-top:18px;display:inline-flex">
          Ver galeria completa
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:6px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        @endif
      </div>
    </div>
    <div class="gal">
      @foreach($galeria as $foto)
      <a href="{{ asset('storage/'.$foto->image) }}" class="gal-item" data-lightbox>
        <img src="{{ asset('storage/'.$foto->image) }}" alt="Foto da {{ $nomeEscola }} #{{ $loop->iteration }}" loading="lazy">
      </a>
      @endforeach
    </div>
  </div>
</section>
<div class="lb" id="lb" hidden role="dialog" aria-label="Foto ampliada">
  <button type="button" class="lb-x" aria-label="Fechar">×</button>
  <button type="button" class="lb-nav lb-prev" aria-label="Foto anterior">‹</button>
  <img id="lbImg" alt="">
  <button type="button" class="lb-nav lb-next" aria-label="Próxima foto">›</button>
</div>
@endif

{{-- ═══ DEPOIMENTOS (só quando há depoimentos reais cadastrados) ═══ --}}
@if($depoimentos->isNotEmpty())
<section id="depoimentos">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Depoimentos</span>
        <h2 class="title"><em>Quem já remou</em> com a gente.</h2>
      </div>
      <p class="section-lead">O que os alunos dizem sobre as aulas.</p>
    </div>
    <div class="testimonials">
      @foreach($depoimentos->take(3) as $dep)
      <div class="quote">
        <div class="stars">★★★★★</div>
        <blockquote>"{{ $dep->depoimento }}"</blockquote>
        <div class="who">
          <div class="avatar">
            @if(!empty($dep->foto))<img src="{{ asset('storage/'.$dep->foto) }}" alt="{{ $dep->nome }}">@endif
          </div>
          <div><b>{{ $dep->nome }}</b><span>{{ $dep->cargo ?? 'Aluno' }}</span></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══ BLOG / ÚLTIMOS ARTIGOS ═══ --}}
@if($artigos->isNotEmpty())
@php $artigos = $site->artigos ?? collect(); @endphp
<section id="blog">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Educação &amp; Surf</span>
        <h2 class="title">Aprender nunca <em>sai de moda.</em></h2>
      </div>
      <div>
        <p class="section-lead">
          Técnica, segurança, condições do mar, destinos e histórias de quem vive o surf de verdade.
          Conteúdo novo toda semana.
        </p>
        <a href="{{ route('site.blog.index') }}"
           class="btn btn-ghost" style="margin-top:18px;display:inline-flex">
          Ver todos os artigos
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:6px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

    @if($artigos->isNotEmpty())
      {{-- Schema.org BlogPosting para SEO --}}
      <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Blog",
        "name": "{{ $nomeEscola }} — Blog",
        "url": "{{ request()->getSchemeAndHttpHost() }}/blog",
        "blogPost": [
          @foreach($artigos as $i => $a)
          {
            "@type": "BlogPosting",
            "headline": "{{ addslashes($a->titulo) }}",
            "description": "{{ addslashes($a->resumo ?? '') }}",
            "url": "{{ request()->getSchemeAndHttpHost() }}/blog/{{ $a->slug }}",
            "datePublished": "{{ $a->publicado_em?->toIso8601String() }}",
            "dateModified":  "{{ $a->updated_at->toIso8601String() }}",
            "author": {"@type":"Organization","name":"{{ $nomeEscola }}"},
            "publisher": {
              "@type": "Organization",
              "name": "{{ $nomeEscola }}"
              @if($logoUrl),"logo":{"@type":"ImageObject","url":"{{ $logoUrl }}"}@endif
            }
            @if($a->imagem_capa),"image":"{{ request()->getSchemeAndHttpHost() }}/storage/{{ $a->imagem_capa }}"@endif
          }{{ !$loop->last ? ',' : '' }}
          @endforeach
        ]
      }
      </script>

      <div class="blog-grid">
        @foreach($artigos as $artigo)
        <article class="blog-card {{ $loop->first ? 'blog-card--featured' : '' }}"
                 itemscope itemtype="https://schema.org/BlogPosting">
          <a href="{{ route('site.blog.show', $artigo->slug) }}" class="thumb" aria-label="{{ $artigo->titulo }}">
            @if($artigo->imagem_capa)
              <img src="{{ request()->getSchemeAndHttpHost() }}/storage/{{ $artigo->imagem_capa }}"
                   alt="{{ $artigo->titulo }}" itemprop="image" loading="lazy">
            @else
              <div class="ph-block"><span>Surf &amp; Mar</span></div>
            @endif
          </a>
          <div class="body">
            <div class="meta-row">
              <span class="cat">{{ $loop->first ? '⭐ Destaque' : 'Surf' }}</span>
              @if($artigo->publicado_em)
                <time class="date" itemprop="datePublished" datetime="{{ $artigo->publicado_em->toIso8601String() }}">
                  {{ $artigo->publicado_em->translatedFormat('d \d\e M \d\e Y') }}
                </time>
              @endif
            </div>
            <h3 itemprop="headline">
              <a href="{{ route('site.blog.show', $artigo->slug) }}">{{ $artigo->titulo }}</a>
            </h3>
            @if($artigo->resumo)
              <p itemprop="description">{{ $artigo->resumo }}</p>
            @endif
            <a href="{{ route('site.blog.show', $artigo->slug) }}" class="read">
              Ler artigo
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </article>
        @endforeach
      </div>

    @else
      {{-- Sem artigos: exibe CTA para criar --}}
      <div class="blog-empty">
        <p>Em breve novos artigos sobre surf, técnica e segurança no mar.</p>
        <p style="margin-top:8px"><a href="{{ route('site.blog.index') }}">Acompanhe o blog →</a></p>
      </div>
    @endif
  </div>
</section>

@endif

{{-- ═══ CONTATO ═══ --}}
<section id="contato">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Fale conosco</span>
        <h2 class="title">Mande sua dúvida. <em>A gente responde.</em></h2>
      </div>
      <p class="section-lead">Preencha o formulário e entraremos em contato. Para falar agora, chame no WhatsApp ou converse com o nosso assistente no canto da tela.</p>
    </div>
    <div class="contact-grid">
      <aside class="contact-side">
        <h3>Outras formas de falar com a gente</h3>
        @if($endStr)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small>Onde estamos</small><strong>{{ $endStr }}</strong></div>
        </div>
        @endif
        @if($wa)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small>WhatsApp</small><strong>{{ $telefone ?: '+'.$wa }}</strong></div>
        </div>
        @endif
        @if($instagram)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          <div><small>Instagram</small><strong><a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener">&#64;{{ $instagram }}</a></strong></div>
        </div>
        @endif
        @if($emailSite)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><polyline points="22,6 12,13 2,6"/></svg>
          <div><small>E-mail</small><strong>{{ $emailSite }}</strong></div>
        </div>
        @endif
      </aside>

      <form class="contact-form" id="ondaForm" novalidate>
        @csrf
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;opacity:0;height:0;width:0">
        <div class="field">
          <label>Nome <span class="req">*</span></label>
          <input id="f-name" name="nome" type="text" required maxlength="120" placeholder="Como podemos te chamar?"/>
        </div>
        <div class="field">
          <label>E-mail <span class="req">*</span></label>
          <input id="f-email" name="email" type="email" required maxlength="160" placeholder="voce@email.com"/>
        </div>
        <div class="field">
          <label>Telefone / WhatsApp</label>
          <input id="f-phone" name="whatsapp" type="tel" maxlength="30" placeholder="(21) 99999-9999"/>
        </div>
        <div class="field">
          <label>Tipo de aula</label>
          <select name="passeio">
            @foreach($servicos as $srv)<option value="{{ $srv->titulo }}">{{ $srv->titulo }}</option>@endforeach
            <option value="Ainda não sei / tenho dúvida">Ainda não sei / tenho dúvida</option>
          </select>
        </div>
        <div class="field full">
          <label>Mensagem</label>
          <textarea name="mensagem" maxlength="2000" placeholder="Conte um pouco sobre você, datas pretendidas e o que espera da aula."></textarea>
        </div>
        <div class="field full check-row">
          <input id="f-consent" type="checkbox" required/>
          <label for="f-consent">Concordo em receber contato por e-mail ou WhatsApp.</label>
        </div>
        <div class="form-foot">
          <span class="note">Seus dados são usados só para responder a este contato.</span>
          <div class="submit">
            <button type="submit" class="btn btn-primary">Enviar mensagem →</button>
            @if($wa)<a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-ghost">Ou pelo WhatsApp</a>@endif
          </div>
        </div>
        <div class="form-success" id="ondaSuccess" role="status">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
          <span>Recebemos sua mensagem! Vamos responder em breve.</span>
        </div>
      </form>
    </div>
  </div>
</section>

{{-- ═══ CTA BAND ═══ --}}
<section id="agendar">
  <div class="wrap">
    <div class="cta-band">
      <div>
        <h2>Pronto pra remar?<br/><em>Vem surfar com a gente.</em></h2>
        <p>Chame no WhatsApp e escolha entre aula em grupo ou individual.</p>
        <div class="btns">
          @if($wa)
          <a href="https://wa.me/{{ $wa }}?text={{ $waText }}" class="btn btn-primary" target="_blank" rel="noopener">
            <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.003 3.2c-7.067 0-12.8 5.733-12.8 12.8 0 2.258.59 4.453 1.713 6.394L3.2 28.8l6.582-1.726a12.78 12.78 0 0 0 6.221 1.585h.006c7.067 0 12.8-5.733 12.803-12.8 0-3.42-1.331-6.635-3.749-9.054A12.71 12.71 0 0 0 16.003 3.2Z"/></svg>
            Agendar pelo WhatsApp
          </a>
          @endif
          <a href="#contato" class="btn btn-ghost">Preencher formulário</a>
        </div>
      </div>
      <div class="info-card">
        @if($endStr)
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small>Onde estamos</small><strong>{{ $endStr }}</strong></div>
        </div>
        @endif
        @if($wa)
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small>WhatsApp</small><strong>{{ $telefone ?: '+'.$wa }}</strong></div>
        </div>
        @endif
        @if($instagram)
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          <div><small>Instagram</small><strong>&#64;{{ $instagram }}</strong></div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <a href="#" class="brand" style="font-family:var(--display);font-weight:700;font-size:20px;letter-spacing:-.01em;display:inline-flex;align-items:center;gap:8px">
          @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $nomeEscola }}" style="height:30px;width:auto">
          @else<span class="mark" style="width:28px;height:28px;border-radius:50%;background:conic-gradient(from 200deg,var(--sea-deep),var(--sea) 35%,var(--sea-soft) 60%,var(--sea) 100%);flex-shrink:0"></span>
          <span>{{ $nomeEscola }}<span style="color:var(--coral)">.</span></span>
          @endif
        </a>
        <p>Escola de surf em Ipanema, Rio de Janeiro. Aulas em grupo e individuais.</p>
      </div>
      <div>
        <h5>Escola</h5>
        <ul>
          <li><a href="#sobre">Sobre</a></li>
          @if($galeria->isNotEmpty())<li><a href="#galeria">Galeria</a></li>@endif
          <li><a href="#contato">Contato</a></li>
        </ul>
      </div>
      @if($servicos->isNotEmpty())
      <div>
        <h5>Aulas</h5>
        <ul>
          @foreach($servicos as $srv)<li><a href="#aulas">{{ $srv->titulo }}</a></li>@endforeach
        </ul>
      </div>
      @endif
      <div>
        <h5>Contato</h5>
        <ul>
          @if($wa)<li><a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">WhatsApp</a></li>@endif
          @if($instagram)<li><a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener">Instagram</a></li>@endif
          @if($emailSite)<li><a href="mailto:{{ $emailSite }}">{{ $emailSite }}</a></li>@endif
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} {{ $nomeEscola }} · Todos os direitos reservados</span>
      @if($instagram)
      <div class="foot-socials">
        <a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
      </div>
      @endif
    </div>
  </div>
</footer>

{{-- ═══ WhatsApp Flutuante ═══ --}}
@if($wa)
<a href="https://wa.me/{{ $wa }}?text={{ $waText }}" class="whats" aria-label="WhatsApp" target="_blank" rel="noopener">
  <svg width="30" height="30" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
    <path d="M16.003 3.2c-7.067 0-12.8 5.733-12.8 12.8 0 2.258.59 4.453 1.713 6.394L3.2 28.8l6.582-1.726a12.78 12.78 0 0 0 6.221 1.585h.006c7.067 0 12.8-5.733 12.803-12.8 0-3.42-1.331-6.635-3.749-9.054A12.71 12.71 0 0 0 16.003 3.2Zm0 23.466h-.005a10.61 10.61 0 0 1-5.408-1.481l-.388-.23-4.014 1.053 1.07-3.913-.252-.402a10.62 10.62 0 0 1-1.627-5.683c0-5.873 4.78-10.654 10.66-10.654a10.59 10.59 0 0 1 7.534 3.124 10.59 10.59 0 0 1 3.12 7.537c-.003 5.876-4.783 10.65-10.69 10.65Zm5.838-7.974c-.32-.16-1.893-.934-2.187-1.04-.293-.107-.507-.16-.72.16-.214.32-.827 1.04-1.014 1.254-.187.213-.373.24-.693.08-.32-.16-1.35-.498-2.572-1.587-.95-.848-1.59-1.895-1.777-2.214-.187-.32-.02-.493.14-.652.144-.143.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.053-.4-.027-.56-.08-.16-.72-1.733-.986-2.374-.26-.624-.524-.539-.72-.55-.187-.008-.4-.01-.613-.01a1.18 1.18 0 0 0-.854.4c-.293.32-1.12 1.094-1.12 2.667 0 1.573 1.147 3.094 1.307 3.307.16.213 2.253 3.44 5.46 4.825.763.33 1.358.527 1.822.674.766.243 1.464.209 2.014.127.615-.092 1.893-.774 2.16-1.52.267-.747.267-1.387.187-1.52-.08-.134-.293-.214-.613-.374Z"/>
  </svg>
  <span class="label">Fale conosco</span>
</a>
@endif

<script>
// Formulário de contato → vira lead da escola (rota pública com honeypot e limite de envios)
const ondaForm = document.getElementById('ondaForm');
if (ondaForm) {
  ondaForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!ondaForm.checkValidity()) { ondaForm.reportValidity(); return; }
    const btn = ondaForm.querySelector('button[type=submit]');
    const ok  = document.getElementById('ondaSuccess');
    btn.disabled = true;
    try {
      const body = Object.fromEntries(new FormData(ondaForm).entries());
      const r = await fetch('{{ route("site.contato", $site->slug) }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
        body: JSON.stringify(body)
      });
      if (!r.ok) throw new Error('HTTP ' + r.status);
      ok.querySelector('span').textContent = 'Recebemos sua mensagem! Vamos responder em breve.';
      ok.classList.add('show');
      ondaForm.querySelectorAll('input:not([type=checkbox]),textarea').forEach(f => f.value = '');
      document.getElementById('f-consent').checked = false;
    } catch (err) {
      ok.querySelector('span').textContent = 'Não foi possível enviar agora. Chame a gente no WhatsApp.';
      ok.classList.add('show');
    } finally {
      btn.disabled = false;
      setTimeout(() => ok.classList.remove('show'), 7000);
    }
  });
}

// Galeria: visualização ampliada com setas e teclado
(function () {
  const lb = document.getElementById('lb'); if (!lb) return;
  const items = [...document.querySelectorAll('[data-lightbox]')];
  const img = document.getElementById('lbImg'); let i = 0;
  const show = (n) => { i = (n + items.length) % items.length; img.src = items[i].href; img.alt = items[i].querySelector('img').alt; };
  const open = (n) => { show(n); lb.hidden = false; document.body.style.overflow = 'hidden'; };
  const close = () => { lb.hidden = true; img.src = ''; document.body.style.overflow = ''; };
  items.forEach((a, n) => a.addEventListener('click', (e) => { e.preventDefault(); open(n); }));
  lb.querySelector('.lb-x').onclick = close;
  lb.querySelector('.lb-prev').onclick = () => show(i - 1);
  lb.querySelector('.lb-next').onclick = () => show(i + 1);
  lb.addEventListener('click', (e) => { if (e.target === lb) close(); });
  document.addEventListener('keydown', (e) => {
    if (lb.hidden) return;
    if (e.key === 'Escape') close(); else if (e.key === 'ArrowLeft') show(i - 1); else if (e.key === 'ArrowRight') show(i + 1);
  });
})();
</script>

{{-- ═══ GOOGLE TRANSLATE COM BANDEIRAS ═══ --}}
<script>
// ── Inicializa o widget oculto do Google Translate ──
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'pt',
    includedLanguages: 'pt,en,es,fr',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    autoDisplay: false
  }, 'google_translate_element');
}

// ── Detecta idioma ativo pelo cookie googtrans ──
function getLangFromCookie() {
  const match = document.cookie.match(/googtrans=\/pt\/([a-z]{2})/);
  return match ? match[1] : 'pt';
}

// ── Muda idioma via cookie + reload ──
function gtTo(lang) {
  if (lang === 'pt') {
    // Remove cookie → volta ao original
    const domains = ['', '.' + location.hostname];
    domains.forEach(d => {
      document.cookie = 'googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; domain=' + d;
    });
  } else {
    const domains = ['', '.' + location.hostname];
    domains.forEach(d => {
      document.cookie = 'googtrans=/pt/' + lang + '; path=/; domain=' + d;
    });
  }
  location.reload();
}

// ── Marca a bandeira do idioma ativo ──
document.addEventListener('DOMContentLoaded', function () {
  const current = getLangFromCookie();
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === current);
  });
});
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>

@if($botAtivo){!! $bot->widgetSnippet() !!}@endif
</body>
</html>
