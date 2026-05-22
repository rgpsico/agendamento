@php
  $nomeEscola = $site->titulo           ?? 'ONDA';
  $descricao  = $site->descricao        ?? 'Aulas guiadas por instrutores certificados, em uma praia segura, com pranchas, lycras e seguro inclusos.';
  $wa         = preg_replace('/\D/', '', $site->whatsapp ?? '5511999999999');
  $waText     = 'Oi%21%20Quero%20agendar%20uma%20aula%20de%20surf';
  $corPri     = $site->cores['primaria']   ?? '#0a5c8a';
  $corSec     = $site->cores['secundaria'] ?? '#0e86c8';

  $capaUrl    = $site->capa        ? asset('storage/'.$site->capa)        : null;
  $logoUrl    = $site->logo        ? asset('storage/'.$site->logo)        : null;
  $sobreImg   = $site->sobre_imagem? asset('storage/'.$site->sobre_imagem): null;

  $sobreTitulo = $site->sobre_titulo    ?? 'Surf não é só esporte. É um jeito de ler o mar.';
  $sobreDesc   = $site->sobre_descricao ?? 'Somos uma escola com anos de experiência, comprometida em oferecer a melhor experiência possível para quem quer aprender ou evoluir no surf.';

  $depoimentos = $site->depoimentos ?? collect();
  $servicos    = $site->siteServicos ?? collect();

  // Empresa / endereço
  $empresa    = $site->empresa ?? null;
  $endRel     = $site->endereco ?? null;
  $telefone   = $empresa->telefone ?? $wa;
  $emailSite  = $empresa->email    ?? null;
  $endStr     = $endRel ? trim(($endRel->logradouro ?? '').' '.($endRel->numero ?? '').', '.($endRel->bairro ?? '').' — '.($endRel->cidade ?? '')) : null;
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nomeEscola }} — Escola de Surf</title>
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
.wrap{width:100%;max-width:1280px;margin-inline:auto;padding-inline:clamp(20px,5vw,60px)}
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
.nav-row{height:60px}  /* linha principal: logo + links + cta */

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
        <div class="lang-bar" id="langSwitch">
          <button class="lang-btn" onclick="gtTo('pt')" title="Português" data-lang="pt">
            <img src="{{ asset('admin/img/country/brasil.png') }}" alt="PT">
          </button>
          <button class="lang-btn" onclick="gtTo('en')" title="English" data-lang="en">
            <img src="{{ asset('admin/img/country/usa.png') }}" alt="EN">
          </button>
          <button class="lang-btn" onclick="gtTo('es')" title="Español" data-lang="es">
            <img src="{{ asset('admin/img/country/espanha.png') }}" alt="ES">
          </button>
        </div>
      </div>

      <nav class="nav-links">
        <a href="#sobre">Sobre</a>
        <a href="#aulas">Aulas</a>
        <a href="#precos">Preços</a>
        <a href="#depoimentos">Depoimentos</a>
        <a href="#faq">FAQ</a>
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
      <span class="eyebrow">Escola de Surf</span>
      <h1 class="headline">
        Aprenda a surfar<br/>
        <em>a primeira onda</em><br/>
        em um único dia.
      </h1>
      <p class="sub">{{ $descricao }}</p>
      <div class="hero-ctas">
        <a href="#aulas" class="btn btn-primary">Ver aulas disponíveis →</a>
        <a href="#contato" class="btn btn-ghost">Falar com a equipe</a>
      </div>
      <div class="hero-stats">
        <div class="s"><strong>12k+</strong><span>alunos formados</span></div>
        <div class="s"><strong>4.9★</strong><span>avaliações</span></div>
        <div class="s"><strong>13</strong><span>anos na praia</span></div>
      </div>
    </div>
    <div class="hero-art">
      @if($capaUrl)
        <img src="{{ $capaUrl }}" alt="{{ $nomeEscola }}">
      @else
        <div class="ph-block"><span>Imagem principal · substituir</span></div>
      @endif
      <div class="badge-float">
        <span class="dot"></span>
        <div>
          <small>Turma de hoje</small>
          <strong>Vagas abertas · confira os horários</strong>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ SOBRE ═══ --}}
<section id="sobre" style="background:var(--bg-2)">
  <div class="wrap">
    <div class="about-grid">
      <div class="about-img">
        @if($sobreImg)
          <img src="{{ $sobreImg }}" alt="Sobre {{ $nomeEscola }}">
        @else
          <div class="ph-block"><span>Foto da escola · substituir</span></div>
        @endif
      </div>
      <div>
        <span class="kicker">Sobre nós</span>
        <h2 class="title" style="margin-top:14px">{{ $sobreTitulo }}</h2>
        <p style="color:var(--ink-soft);font-size:16px;margin:18px 0 32px;text-wrap:pretty">{{ $sobreDesc }}</p>
        <div class="values" style="grid-template-columns:1fr;gap:14px">
          <div class="value">
            <div class="ico">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/><path d="M2 20c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/></svg>
            </div>
            <h3>Pequenas turmas</h3>
            <p>No máximo 4 alunos por instrutor. Correção individual em cada onda.</p>
          </div>
          <div class="value">
            <div class="ico">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 7v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-5Z"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <h3>Segurança em 1º lugar</h3>
            <p>Certificação ISA, salva-vidas em escala e equipamento revisado a cada aula.</p>
          </div>
          <div class="value">
            <div class="ico">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-12V5l-8-3-8 3v5c0 8 8 12 8 12Z"/></svg>
            </div>
            <h3>Tudo incluso</h3>
            <p>Prancha, leash, lycra UV, parafina, vestiário e seguro. Só traga sunga ou maiô.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ AULAS (serviços cadastrados ou cards padrão) ═══ --}}
<section id="aulas">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Nossas aulas</span>
        <h2 class="title">Para cada pé <em>na areia.</em></h2>
      </div>
      <p class="section-lead">Da primeira remada à manobra. Escolha o formato que combina com o seu nível, ritmo e curiosidade.</p>
    </div>
    <div class="classes">
      @if($servicos->isNotEmpty())
        @foreach($servicos as $srv)
        <article class="class-card{{ $srv->destaque ? ' featured' : '' }}">
          <div class="media">
            @if(!empty($srv->imagem))
              <img src="{{ asset('storage/'.$srv->imagem) }}" alt="{{ $srv->titulo }}">
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
                $srv->duracao   ? '⏱ <b>'.$srv->duracao.'</b>' : null,
                $srv->capacidade? '👥 <b>'.$srv->capacidade.'</b>' : null,
                $srv->info_extra? '📌 <b>'.$srv->info_extra.'</b>' : null,
                $srv->preco     ? '💰 <b>R$ '.number_format($srv->preco,2,',','.').'</b>' : null,
              ]);
            @endphp
            @if($meta)
            <div class="meta">
              @foreach($meta as $m)<span>{!! $m !!}</span>@endforeach
            </div>
            @endif
          </div>
        </article>
        @endforeach
      @else
        {{-- Cards padrão quando não há serviços cadastrados --}}
        <article class="class-card">
          <div class="media"><div class="ph-block"><span>Aula em grupo · foto</span></div></div>
          <div class="body">
            <span class="tag">Iniciantes</span>
            <h3>Primeira Onda</h3>
            <p>Aula em grupo de 2h: teoria na areia, segurança, remada e a primeira onda em pé.</p>
            <div class="meta"><span>⏱ <b>2h</b></span><span>👥 <b>até 4 alunos</b></span></div>
          </div>
        </article>
        <article class="class-card">
          <div class="media"><div class="ph-block"><span>Aula particular · foto</span></div></div>
          <div class="body">
            <span class="tag">Privativa</span>
            <h3>Aula Particular</h3>
            <p>1 instrutor, 1 aluno. Foco em correções de postura, remada e leitura da série.</p>
            <div class="meta"><span>⏱ <b>1h30</b></span><span>👤 <b>1 aluno</b></span><span>🎥 <b>vídeo análise</b></span></div>
          </div>
        </article>
        <article class="class-card">
          <div class="media"><div class="ph-block"><span>Kids surf · foto</span></div></div>
          <div class="body">
            <span class="tag">Kids · 6-12 anos</span>
            <h3>Onda Mirim</h3>
            <p>Programa lúdico semanal para crianças. Brincadeiras na areia, equilíbrio e respeito ao mar.</p>
            <div class="meta"><span>⏱ <b>1h30</b></span><span>👶 <b>até 6 crianças</b></span></div>
          </div>
        </article>
        <article class="class-card">
          <div class="media"><div class="ph-block"><span>Coaching avançado · foto</span></div></div>
          <div class="body">
            <span class="tag">Intermediário+</span>
            <h3>Coaching Avançado</h3>
            <p>Pacote de 8 aulas para evoluir em manobras com plano semanal e revisão em vídeo.</p>
            <div class="meta"><span>⏱ <b>8 sessões</b></span><span>📈 <b>plano personalizado</b></span></div>
          </div>
        </article>
      @endif
    </div>
  </div>
</section>

{{-- ═══ PREÇOS ═══ --}}
<section id="precos" style="background:var(--bg-2)">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Pacotes</span>
        <h2 class="title">Preços honestos. <em>Sem letras miúdas.</em></h2>
      </div>
      <p class="section-lead">Todos os pacotes incluem prancha, leash, lycra UV, parafina, vestiário com chuveiro e seguro do aluno.</p>
    </div>
    <div class="pricing">
      <div class="plan">
        <span class="pill">Avulsa</span>
        <h3>Aula Única</h3>
        <div class="price"><sup>R$</sup>180<small>/aula</small></div>
        <ul class="feat">
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>2 horas de aula em grupo</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Todo o equipamento incluso</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Fotos da sessão por WhatsApp</li>
        </ul>
        <a href="#contato" class="btn btn-ghost">Agendar</a>
      </div>
      <div class="plan featured">
        <span class="pill">Mais escolhido</span>
        <h3>Pacote 5 Aulas</h3>
        <div class="price"><sup>R$</sup>780<small>/total</small></div>
        <ul class="feat">
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>5 aulas, válidas por 60 dias</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Plano de evolução individual</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>1 análise em vídeo grátis</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Acesso ao app de previsão</li>
        </ul>
        <a href="#contato" class="btn btn-primary">Agendar</a>
      </div>
      <div class="plan">
        <span class="pill">Particular</span>
        <h3>Aula Privativa</h3>
        <div class="price"><sup>R$</sup>340<small>/aula</small></div>
        <ul class="feat">
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>1h30 com instrutor exclusivo</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Análise em vídeo no mesmo dia</li>
          <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Horários flexíveis (até 18h)</li>
        </ul>
        <a href="#contato" class="btn btn-ghost">Agendar</a>
      </div>
    </div>
  </div>
</section>

{{-- ═══ DEPOIMENTOS ═══ --}}
<section id="depoimentos">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Depoimentos</span>
        <h2 class="title"><em>Quem já remou</em> com a gente.</h2>
      </div>
      <p class="section-lead">Avaliações reais de alunos que passaram pela nossa escola.</p>
    </div>
    <div class="testimonials">
      @if($depoimentos->isNotEmpty())
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
      @else
        <div class="quote">
          <div class="stars">★★★★★</div>
          <blockquote>"Cheguei achando que ia engolir litros de água. Em duas horas já estava em pé. A equipe explica de um jeito que destrava o medo."</blockquote>
          <div class="who"><div class="avatar"></div><div><b>Camila R.</b><span>Iniciante · São Paulo</span></div></div>
        </div>
        <div class="quote">
          <div class="stars">★★★★★</div>
          <blockquote>"Trouxe meu filho de 7 anos. Ele saiu da água pedindo pra voltar no dia seguinte. Equipe atenciosa e séria com segurança."</blockquote>
          <div class="who"><div class="avatar"></div><div><b>Rafael M.</b><span>Pai · Florianópolis</span></div></div>
        </div>
        <div class="quote">
          <div class="stars">★★★★★</div>
          <blockquote>"Surfo há 4 anos e travei na evolução. Em 8 aulas finalmente entendi o bottom turn. Recomendo demais."</blockquote>
          <div class="who"><div class="avatar"></div><div><b>Diego A.</b><span>Intermediário · Buenos Aires</span></div></div>
        </div>
      @endif
    </div>
  </div>
</section>

{{-- ═══ FAQ ═══ --}}
<section id="faq" style="background:var(--bg-2)">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">FAQ</span>
        <h2 class="title">Perguntas <em>frequentes.</em></h2>
      </div>
      <p class="section-lead">Se a sua não está aqui, manda direto no WhatsApp — respondemos em até 1 hora.</p>
    </div>
    <div class="faq-grid">
      <details class="faq" open><summary>Preciso saber nadar?</summary><p>Sim. Você precisa se sentir confortável em águas até a altura do peito. Não exigimos natação avançada, mas é importante não ter pânico.</p></details>
      <details class="faq"><summary>E se chover ou o mar estiver ruim?</summary><p>Aulas só são canceladas em caso de risco real. Se acontecer, remarcamos sem custo para qualquer dia em até 90 dias.</p></details>
      <details class="faq"><summary>Qual a idade mínima e máxima?</summary><p>Atendemos a partir de 6 anos (turma Kids) e não temos idade máxima. Já tivemos alunos de 72 anos pegando a primeira onda.</p></details>
      <details class="faq"><summary>O que devo levar?</summary><p>Sunga ou maiô, toalha, protetor solar mineral e uma garrafa d'água. Todo o resto fica por nossa conta.</p></details>
      <details class="faq"><summary>Vocês falam inglês e espanhol?</summary><p>Sim. Nossa equipe é fluente em português, inglês e espanhol. Avise no agendamento o idioma de preferência.</p></details>
      <details class="faq"><summary>Posso comprar a aula como presente?</summary><p>Sim! Temos vale-presente digital, válido por 12 meses. Fale com a gente no WhatsApp que enviamos o link de pagamento.</p></details>
    </div>
  </div>
</section>

{{-- ═══ CONTATO ═══ --}}
<section id="contato">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker">Fale conosco</span>
        <h2 class="title">Mande sua dúvida. <em>Respondemos no mesmo dia.</em></h2>
      </div>
      <p class="section-lead">Preencha o formulário e nossa equipe entra em contato pelo canal de sua preferência. Para confirmação imediata, prefira o WhatsApp.</p>
    </div>
    <div class="contact-grid">
      <aside class="contact-side">
        <h3>Outras formas de falar com a gente</h3>
        <p>Estamos disponíveis todos os dias. Em períodos de chuva forte, atendimento apenas online.</p>
        @if($endStr)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small>Endereço</small><strong>{{ $endStr }}</strong></div>
        </div>
        @endif
        @if($wa)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small>WhatsApp</small><strong>+{{ $wa }}</strong></div>
        </div>
        @endif
        @if($emailSite)
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><polyline points="22,6 12,13 2,6"/></svg>
          <div><small>E-mail</small><strong>{{ $emailSite }}</strong></div>
        </div>
        @endif
        <div class="info-row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><small>Horário</small><strong>Todos os dias · 7h às 18h</strong></div>
        </div>
      </aside>

      <form class="contact-form" id="ondaForm" novalidate>
        @csrf
        <div class="field">
          <label>Nome <span class="req">*</span></label>
          <input id="f-name" name="name" type="text" required placeholder="Como podemos te chamar?"/>
        </div>
        <div class="field">
          <label>E-mail <span class="req">*</span></label>
          <input id="f-email" name="email" type="email" required placeholder="voce@email.com"/>
        </div>
        <div class="field">
          <label>Telefone / WhatsApp <span class="req">*</span></label>
          <input id="f-phone" name="phone" type="tel" required placeholder="+55 (11) 99999-9999"/>
        </div>
        <div class="field">
          <label>Tipo de aula</label>
          <select name="class">
            <option value="first">Primeira Onda (iniciantes)</option>
            <option value="private">Aula particular</option>
            <option value="kids">Onda Mirim (kids)</option>
            <option value="advanced">Coaching avançado</option>
            <option value="other">Outro / Tenho dúvida</option>
          </select>
        </div>
        <div class="field full">
          <label>Mensagem</label>
          <textarea name="message" placeholder="Conte um pouco sobre o seu nível, datas pretendidas e o que você espera da aula."></textarea>
        </div>
        <div class="field full check-row">
          <input id="f-consent" type="checkbox" required/>
          <label for="f-consent">Concordo em receber contato por e-mail ou WhatsApp e li a Política de Privacidade.</label>
        </div>
        <div class="form-foot">
          <span class="note">Resposta em até 1h durante o horário comercial.</span>
          <div class="submit">
            <button type="submit" class="btn btn-primary">Enviar mensagem →</button>
            <a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-ghost">Ou pelo WhatsApp</a>
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
        <h2>Pronto pra remar?<br/><em>A primeira onda é por nossa conta.</em></h2>
        <p>Agende sua aula em menos de 1 minuto. Confirmação imediata por WhatsApp.</p>
        <div class="btns">
          <a href="https://wa.me/{{ $wa }}" class="btn btn-primary" target="_blank" rel="noopener">Agendar pelo WhatsApp</a>
          <a href="#contato" class="btn btn-ghost">Preencher formulário</a>
        </div>
      </div>
      <div class="info-card">
        @if($endStr)
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small>Localização</small><strong>{{ $endStr }}</strong></div>
        </div>
        @endif
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><small>Horário</small><strong>Todos os dias · 7h às 18h</strong></div>
        </div>
        @if($wa)
        <div class="row">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small>WhatsApp</small><strong>+{{ $wa }}</strong></div>
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
        <p>Escola de surf comprometida em levar você à primeira onda — e além.</p>
      </div>
      <div>
        <h5>Escola</h5>
        <ul>
          <li><a href="#sobre">Sobre</a></li>
          <li><a href="#aulas">Instrutores</a></li>
        </ul>
      </div>
      <div>
        <h5>Aulas</h5>
        <ul>
          <li><a href="#aulas">Iniciantes</a></li>
          <li><a href="#aulas">Particular</a></li>
          <li><a href="#aulas">Kids</a></li>
          <li><a href="#aulas">Coaching</a></li>
        </ul>
      </div>
      <div>
        <h5>Contato</h5>
        <ul>
          @if($emailSite)<li><a href="mailto:{{ $emailSite }}">{{ $emailSite }}</a></li>@endif
          @if($wa)<li><a href="https://wa.me/{{ $wa }}">WhatsApp</a></li>@endif
          <li><a href="#contato">Como chegar</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} {{ $nomeEscola }} · Todos os direitos reservados</span>
      <div class="foot-socials">
        <a href="#" aria-label="Instagram"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
        <a href="#" aria-label="YouTube"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33Z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg></a>
        <a href="#" aria-label="TikTok"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg></a>
      </div>
    </div>
  </div>
</footer>

{{-- ═══ WhatsApp Flutuante ═══ --}}
<a href="https://wa.me/{{ $wa }}?text={{ $waText }}" class="whats" aria-label="WhatsApp" target="_blank" rel="noopener">
  <svg width="30" height="30" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
    <path d="M16.003 3.2c-7.067 0-12.8 5.733-12.8 12.8 0 2.258.59 4.453 1.713 6.394L3.2 28.8l6.582-1.726a12.78 12.78 0 0 0 6.221 1.585h.006c7.067 0 12.8-5.733 12.803-12.8 0-3.42-1.331-6.635-3.749-9.054A12.71 12.71 0 0 0 16.003 3.2Zm0 23.466h-.005a10.61 10.61 0 0 1-5.408-1.481l-.388-.23-4.014 1.053 1.07-3.913-.252-.402a10.62 10.62 0 0 1-1.627-5.683c0-5.873 4.78-10.654 10.66-10.654a10.59 10.59 0 0 1 7.534 3.124 10.59 10.59 0 0 1 3.12 7.537c-.003 5.876-4.783 10.65-10.69 10.65Zm5.838-7.974c-.32-.16-1.893-.934-2.187-1.04-.293-.107-.507-.16-.72.16-.214.32-.827 1.04-1.014 1.254-.187.213-.373.24-.693.08-.32-.16-1.35-.498-2.572-1.587-.95-.848-1.59-1.895-1.777-2.214-.187-.32-.02-.493.14-.652.144-.143.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.053-.4-.027-.56-.08-.16-.72-1.733-.986-2.374-.26-.624-.524-.539-.72-.55-.187-.008-.4-.01-.613-.01a1.18 1.18 0 0 0-.854.4c-.293.32-1.12 1.094-1.12 2.667 0 1.573 1.147 3.094 1.307 3.307.16.213 2.253 3.44 5.46 4.825.763.33 1.358.527 1.822.674.766.243 1.464.209 2.014.127.615-.092 1.893-.774 2.16-1.52.267-.747.267-1.387.187-1.52-.08-.134-.293-.214-.613-.374Z"/>
  </svg>
  <span class="label">Fale conosco</span>
</a>

<script>
const ondaForm = document.getElementById('ondaForm');
if(ondaForm){
  ondaForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    if(!ondaForm.checkValidity()){ ondaForm.reportValidity(); return; }
    const nome     = document.getElementById('f-name').value.trim();
    const email    = document.getElementById('f-email').value.trim();
    const whatsapp = document.getElementById('f-phone').value.trim();
    try {
      await fetch('{{ route("site.landing.lead") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
        body: JSON.stringify({ nome, email, whatsapp, origem: 'surf' })
      });
      const ok = document.getElementById('ondaSuccess');
      ok.classList.add('show');
      ondaForm.querySelectorAll('input:not([type=radio]):not([type=checkbox]),textarea,select').forEach(f=>f.value='');
      document.getElementById('f-consent').checked = false;
      setTimeout(()=>ok.classList.remove('show'), 7000);
    } catch(err){ console.error(err); }
  });
}
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

</body>
</html>
