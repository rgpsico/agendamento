@php
  $wa       = $wa       ?? '5511999999999';
  $telefone = $telefone ?? '+55 11 99999-9999';
  $emailContato = $emailContato ?? 'ola@ondasurf.com';
  $endereco = $endereco ?? 'Av. Beira-Mar, 1200 · Praia Norte';
  $cep      = $cep      ?? '88000-000';
  $horario  = $horario  ?? 'Todos os dias · 7h às 18h';
  $nomeEscola = $nomeEscola ?? 'ONDA';
  $origemLead = $origemLead ?? 'surf';
  $waText   = $waText   ?? 'Oi%21%20Quero%20agendar%20uma%20aula%20de%20surf';
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nomeEscola }} — Escola de Surf</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
<style>
  :root{
    --bg:          oklch(0.972 0.012 80);
    --bg-2:        oklch(0.945 0.018 78);
    --ink:         oklch(0.20 0.015 60);
    --ink-soft:    oklch(0.40 0.018 60);
    --line:        oklch(0.88 0.015 75);
    --sea:         oklch(0.42 0.08 220);
    --sea-deep:    oklch(0.30 0.07 225);
    --sea-soft:    oklch(0.92 0.025 215);
    --coral:       oklch(0.72 0.13 35);
    --coral-soft:  oklch(0.94 0.04 40);
    --wa:          #25D366;
    --wa-deep:     #128C7E;
    --radius-s: 8px; --radius-m: 14px; --radius-l: 22px; --radius-xl: 36px;
    --maxw: 1240px;
    --pad: clamp(16px, 4vw, 48px);
    --display: "Bricolage Grotesque", ui-sans-serif, system-ui, sans-serif;
    --sans:    "DM Sans", ui-sans-serif, system-ui, sans-serif;
    --mono:    "JetBrains Mono", ui-monospace, monospace;
  }
  *{box-sizing:border-box} html,body{margin:0;padding:0} html{scroll-behavior:smooth}
  body{ font-family:var(--sans); color:var(--ink); background:var(--bg); -webkit-font-smoothing:antialiased; line-height:1.55; font-size:16px; }
  img,svg{display:block;max-width:100%} a{color:inherit;text-decoration:none} button{font:inherit;cursor:pointer;border:0;background:none;color:inherit}
  ::selection{background:var(--sea);color:var(--bg)}
  .wrap{ max-width:var(--maxw); margin:0 auto; padding-inline:var(--pad); }

  /* NAV */
  .nav{ position:sticky; top:0; z-index:50; backdrop-filter:blur(14px); background:color-mix(in oklab,var(--bg) 78%,transparent); border-bottom:1px solid color-mix(in oklab,var(--line) 70%,transparent); }
  .nav-row{ display:flex; align-items:center; gap:24px; height:72px; }
  .brand{ display:flex; align-items:center; gap:10px; font-family:var(--display); font-weight:700; font-size:22px; letter-spacing:-0.01em;}
  .brand .mark{ width:34px;height:34px;border-radius:50%; background:radial-gradient(circle at 30% 30%,var(--coral) 0 22%,transparent 23%),conic-gradient(from 200deg,var(--sea-deep),var(--sea) 35%,var(--sea-soft) 60%,var(--sea) 100%); box-shadow:inset 0 0 0 1px color-mix(in oklab,var(--ink) 8%,transparent); }
  .nav-links{ display:flex; gap:28px; margin-left:16px;}
  .nav-links a{ font-size:14.5px; color:var(--ink-soft); transition:color .2s;}
  .nav-links a:hover{ color:var(--ink); }
  .nav-cta{ margin-left:auto; display:flex; align-items:center; gap:12px;}
  .lang{ display:flex; align-items:center; gap:2px; border:1px solid var(--line); border-radius:999px; padding:4px; background:var(--bg); }
  .lang button{ font-family:var(--mono); font-size:11px; letter-spacing:0.08em; text-transform:uppercase; padding:6px 10px; border-radius:999px; color:var(--ink-soft); }
  .lang button.active{ background:var(--ink); color:var(--bg); }
  .btn{ display:inline-flex; align-items:center; gap:8px; font-weight:600; font-size:14.5px; padding:11px 18px; border-radius:999px; transition:transform .15s ease,background .2s,color .2s; }
  .btn:hover{ transform:translateY(-1px); }
  .btn-primary{ background:var(--ink); color:var(--bg); }
  .btn-primary:hover{ background:var(--sea-deep);}
  .btn-ghost{ background:transparent; color:var(--ink); border:1px solid var(--line);}
  .btn-ghost:hover{ border-color:var(--ink);}
  .btn-coral{ background:var(--coral); color:#fff;}

  /* HERO */
  .hero{ position:relative; overflow:hidden; padding:clamp(48px,8vw,96px) 0 clamp(64px,9vw,120px); }
  .hero-grid{ display:grid; grid-template-columns:1.05fr .95fr; gap:clamp(24px,4vw,56px); align-items:end; }
  .eyebrow{ font-family:var(--mono); font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:var(--sea-deep); display:inline-flex; align-items:center; gap:10px; margin-bottom:22px; }
  .eyebrow::before{ content:""; width:28px; height:1px; background:var(--sea-deep); }
  h1.headline{ font-family:var(--display); font-weight:700; font-size:clamp(48px,7.4vw,108px); line-height:0.95; letter-spacing:-0.035em; margin:0 0 24px; text-wrap:balance; }
  h1.headline em{ font-style:italic; font-weight:500; color:var(--sea-deep); font-variation-settings:"opsz" 96; }
  .sub{ font-size:clamp(16px,1.4vw,19px); color:var(--ink-soft); max-width:52ch; margin:0 0 32px; text-wrap:pretty; }
  .hero-ctas{ display:flex; gap:12px; flex-wrap:wrap; }
  .hero-stats{ display:flex; gap:clamp(20px,3vw,44px); margin-top:clamp(36px,6vw,64px); padding-top:24px; border-top:1px solid var(--line); }
  .hero-stats .s strong{ font-family:var(--display); font-weight:600; font-size:clamp(28px,3vw,40px); display:block; letter-spacing:-0.02em; }
  .hero-stats .s span{ font-size:13px; color:var(--ink-soft);}
  .hero-art{ position:relative; aspect-ratio:4/5; border-radius:var(--radius-xl); overflow:hidden; background:var(--sea-soft); }
  .hero-art .ph{ position:absolute; inset:0; }
  .badge-float{ position:absolute; bottom:18px; left:18px; background:var(--bg); border-radius:var(--radius-m); padding:14px 16px; display:flex; align-items:center; gap:12px; box-shadow:0 18px 50px -20px color-mix(in oklab,var(--sea-deep) 35%,transparent); max-width:78%; }
  .badge-float .dot{ width:10px;height:10px;border-radius:50%; background:var(--coral); box-shadow:0 0 0 4px color-mix(in oklab,var(--coral) 25%,transparent); flex-shrink:0;}
  .badge-float small{ display:block; color:var(--ink-soft); font-size:12px; font-family:var(--mono); letter-spacing:0.06em; text-transform:uppercase;}
  .badge-float strong{ font-family:var(--display); font-weight:600; font-size:15px;}
  .ph{ background:repeating-linear-gradient(135deg,color-mix(in oklab,var(--sea) 18%,var(--sea-soft)) 0 14px,var(--sea-soft) 14px 28px); color:var(--sea-deep); display:flex; align-items:center; justify-content:center; font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; text-align:center; padding:16px; }
  .ph.warm{ background:repeating-linear-gradient(135deg,color-mix(in oklab,var(--coral) 18%,var(--coral-soft)) 0 14px,var(--coral-soft) 14px 28px); color:oklch(0.4 0.1 35); }
  .ph .lbl{ background:color-mix(in oklab,var(--bg) 85%,transparent); border:1px solid color-mix(in oklab,var(--ink) 10%,transparent); padding:6px 10px; border-radius:6px; }

  /* SECTIONS */
  section{ padding:clamp(64px,9vw,120px) 0; }
  .section-head{ display:grid; grid-template-columns:1fr 1.4fr; gap:clamp(24px,4vw,64px); align-items:end; margin-bottom:clamp(40px,6vw,72px); }
  h2.title{ font-family:var(--display); font-weight:600; font-size:clamp(34px,4.4vw,64px); line-height:1; letter-spacing:-0.025em; margin:12px 0 0; text-wrap:balance; }
  h2.title em{ font-style:italic; font-weight:500; color:var(--sea-deep);}
  .section-lead{ font-size:17px; color:var(--ink-soft); max-width:56ch; text-wrap:pretty; }
  .kicker{ font-family:var(--mono); font-size:11px; letter-spacing:0.2em; text-transform:uppercase; color:var(--coral); display:inline-flex; align-items:center; gap:8px; }
  .kicker::before{content:"●"}

  /* VALUES */
  .values{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px;}
  .value{ border:1px solid var(--line); border-radius:var(--radius-l); padding:28px; background:var(--bg); transition:transform .25s ease,border-color .25s ease,background .25s ease; }
  .value:hover{ transform:translateY(-3px); border-color:var(--sea); background:color-mix(in oklab,var(--sea-soft) 50%,var(--bg));}
  .value .ico{ width:44px;height:44px;border-radius:12px; display:flex;align-items:center;justify-content:center; background:var(--sea-soft);color:var(--sea-deep); margin-bottom:18px; }
  .value h3{ font-family:var(--display); font-size:22px; font-weight:600; margin:0 0 8px; letter-spacing:-0.01em;}
  .value p{ margin:0; color:var(--ink-soft); font-size:15px;}

  /* CLASSES */
  .classes{ display:grid; grid-template-columns:repeat(12,1fr); gap:20px;}
  .class-card{ grid-column:span 6; border-radius:var(--radius-l); overflow:hidden; background:var(--bg-2); border:1px solid var(--line); display:flex;flex-direction:column; min-height:380px; transition:transform .3s ease; }
  .class-card:hover{ transform:translateY(-4px); }
  .class-card .media{ aspect-ratio:16/10; position:relative; overflow:hidden;}
  .class-card .body{ padding:24px 26px 28px; display:flex;flex-direction:column; gap:10px;}
  .class-card .tag{ display:inline-flex;align-self:flex-start; font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:var(--sea-deep); background:var(--sea-soft); padding:5px 10px; border-radius:6px; }
  .class-card h3{ font-family:var(--display); font-size:28px; font-weight:600; margin:4px 0 6px; letter-spacing:-0.02em;}
  .class-card p{ margin:0; color:var(--ink-soft); font-size:15px;}
  .class-card .meta{ margin-top:auto; padding-top:18px; display:flex; gap:18px; flex-wrap:wrap; font-size:13px; color:var(--ink-soft);}
  .class-card .meta span{ display:inline-flex; align-items:center; gap:6px;}
  .class-card .meta b{ color:var(--ink); font-weight:600;}

  /* INSTRUCTORS */
  .instructors{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px;}
  .coach{ display:flex; flex-direction:column; gap:14px;}
  .coach .photo{ aspect-ratio:4/5; border-radius:var(--radius-m); overflow:hidden; }
  .coach h4{ font-family:var(--display); font-size:20px; margin:0; font-weight:600; letter-spacing:-0.01em;}
  .coach .role{ font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:var(--ink-soft);}
  .coach p{ margin:4px 0 0; color:var(--ink-soft); font-size:14px;}

  /* PRICING */
  .pricing{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px;}
  .plan{ border:1px solid var(--line); border-radius:var(--radius-l); padding:32px 28px; background:var(--bg); display:flex;flex-direction:column; gap:18px; }
  .plan.featured{ background:var(--ink); color:var(--bg); border-color:var(--ink);}
  .plan.featured .price{ color:var(--bg);}
  .plan.featured .feat li{ color:color-mix(in oklab,var(--bg) 80%,transparent);}
  .plan.featured .feat li svg{ stroke:var(--coral);}
  .plan.featured .pill{ background:var(--coral); color:#fff;}
  .plan.featured .btn-primary{ background:var(--bg); color:var(--ink);}
  .plan.featured .btn-primary:hover{ background:var(--coral); color:#fff;}
  .plan .pill{ align-self:flex-start; font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; padding:5px 10px; border-radius:6px; background:var(--sea-soft); color:var(--sea-deep); }
  .plan h3{ font-family:var(--display); font-size:26px; margin:0; font-weight:600; letter-spacing:-0.015em;}
  .plan .price{ font-family:var(--display); font-size:52px; font-weight:600; letter-spacing:-0.03em; line-height:1;}
  .plan .price sup{ font-size:18px; font-weight:500; vertical-align:top; margin-right:4px; opacity:0.7;}
  .plan .price small{ font-size:14px; font-weight:500; margin-left:6px; opacity:0.7; font-family:var(--sans);}
  .feat{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;}
  .feat li{ display:flex; gap:10px; align-items:flex-start; font-size:14.5px; color:var(--ink-soft);}
  .feat li svg{ flex-shrink:0; stroke:var(--sea-deep); margin-top:2px;}
  .plan .btn{ justify-content:center; margin-top:6px;}

  /* GALLERY */
  .gallery{ display:grid; grid-template-columns:repeat(12,1fr); grid-auto-rows:160px; gap:14px;}
  .gallery > div{ border-radius:var(--radius-m); overflow:hidden;}
  .g1{ grid-column:span 5; grid-row:span 2;} .g2{ grid-column:span 4;} .g3{ grid-column:span 3;}
  .g4{ grid-column:span 4;} .g5{ grid-column:span 3;} .g6{ grid-column:span 7;} .g7{ grid-column:span 5;}

  /* TESTIMONIALS */
  .testimonials{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px;}
  .quote{ border:1px solid var(--line); border-radius:var(--radius-l); padding:28px; background:var(--bg); display:flex;flex-direction:column; gap:18px; }
  .quote .stars{ display:flex; gap:2px; color:var(--coral);}
  .quote p{ font-family:var(--display); font-size:19px; line-height:1.4; margin:0; letter-spacing:-0.005em; text-wrap:pretty;}
  .quote .who{ display:flex; align-items:center; gap:12px; margin-top:auto;}
  .quote .avatar{ width:42px;height:42px;border-radius:50%; background:conic-gradient(from 0deg,var(--coral),var(--sea),var(--sea-deep),var(--coral)); filter:saturate(0.7); }
  .quote .who b{ font-weight:600; display:block; font-size:14.5px;}
  .quote .who span{ font-size:13px; color:var(--ink-soft);}

  /* FAQ */
  .faq-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px 40px;}
  details.faq{ border-bottom:1px solid var(--line); padding:18px 0; }
  details.faq summary{ list-style:none; cursor:pointer; display:flex; align-items:center; gap:12px; font-family:var(--display); font-weight:600; font-size:19px; letter-spacing:-0.01em; }
  details.faq summary::-webkit-details-marker{display:none}
  details.faq summary::after{ content:"+"; margin-left:auto; font-weight:400; font-size:26px; color:var(--ink-soft); transition:transform .2s; }
  details.faq[open] summary::after{ content:"−";}
  details.faq p{ margin:12px 0 0; color:var(--ink-soft); font-size:15px; max-width:60ch;}

  /* CTA BAND */
  .cta-band{ background:var(--ink); color:var(--bg); border-radius:var(--radius-xl); padding:clamp(40px,6vw,80px); display:grid; grid-template-columns:1.2fr 1fr; gap:clamp(24px,4vw,56px); align-items:center; position:relative; overflow:hidden; }
  .cta-band::before{ content:""; position:absolute; right:-120px; top:-120px; width:420px;height:420px; border-radius:50%; background:radial-gradient(circle,var(--sea-deep),transparent 70%); opacity:0.7; }
  .cta-band h2{ font-family:var(--display); font-size:clamp(32px,4.4vw,60px); margin:0; font-weight:600; letter-spacing:-0.025em; line-height:1; }
  .cta-band h2 em{ font-style:italic; color:var(--coral); font-weight:500;}
  .cta-band p{ color:color-mix(in oklab,var(--bg) 75%,transparent); margin:18px 0 28px; font-size:17px; max-width:46ch;}
  .cta-band .btns{ display:flex; gap:10px; flex-wrap:wrap; position:relative; z-index:1;}
  .cta-band .btn-primary{ background:var(--bg); color:var(--ink);}
  .cta-band .btn-primary:hover{ background:var(--coral); color:#fff;}
  .cta-band .btn-ghost{ border-color:color-mix(in oklab,var(--bg) 30%,transparent); color:var(--bg);}
  .cta-band .info-card{ background:color-mix(in oklab,var(--bg) 8%,transparent); border:1px solid color-mix(in oklab,var(--bg) 18%,transparent); border-radius:var(--radius-l); padding:28px; display:flex;flex-direction:column; gap:16px; position:relative; z-index:1; backdrop-filter:blur(20px); }
  .cta-band .info-card .row{ display:flex; gap:14px; align-items:flex-start;}
  .cta-band .info-card .row svg{ flex-shrink:0; margin-top:2px; opacity:0.8;}
  .cta-band .info-card small{ font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:color-mix(in oklab,var(--bg) 60%,transparent); display:block;}
  .cta-band .info-card strong{ font-weight:500;}

  /* CONTACT FORM */
  .contact-grid{ display:grid; grid-template-columns:.9fr 1.1fr; gap:clamp(28px,5vw,64px); align-items:start; }
  .contact-side h3{ font-family:var(--display); font-weight:600; font-size:22px; letter-spacing:-0.01em; margin:0 0 8px; }
  .contact-side p{ color:var(--ink-soft); font-size:15px; margin:0 0 28px; max-width:38ch;}
  .contact-side .info-row{ display:flex; align-items:flex-start; gap:14px; padding:18px 0; border-top:1px solid var(--line); }
  .contact-side .info-row:last-of-type{ border-bottom:1px solid var(--line);}
  .contact-side .info-row svg{ flex-shrink:0; color:var(--sea-deep); margin-top:2px;}
  .contact-side .info-row small{ font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:var(--ink-soft); display:block; margin-bottom:2px; }
  .contact-side .info-row strong{ font-weight:500; font-size:15px;}
  form.contact-form{ background:var(--bg); border:1px solid var(--line); border-radius:var(--radius-l); padding:clamp(24px,3.5vw,36px); display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .field{ display:flex; flex-direction:column; gap:6px;}
  .field.full{ grid-column:span 2;}
  .field label{ font-family:var(--mono); font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:var(--ink-soft); }
  .field label .req{ color:var(--coral); margin-left:2px;}
  .field input,.field select,.field textarea{ width:100%; font:inherit; color:var(--ink); background:var(--bg-2); border:1px solid var(--line); border-radius:10px; padding:12px 14px; transition:border-color .2s ease,background .2s ease,box-shadow .2s ease; appearance:none; -webkit-appearance:none; }
  .field textarea{ min-height:130px; resize:vertical; line-height:1.5;}
  .field select{ background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' stroke='%23555' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/></svg>"); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px; }
  .field input:focus,.field select:focus,.field textarea:focus{ outline:none; background:var(--bg); border-color:var(--sea); box-shadow:0 0 0 4px color-mix(in oklab,var(--sea) 15%,transparent); }
  .field input::placeholder,.field textarea::placeholder{ color:color-mix(in oklab,var(--ink-soft) 70%,transparent);}
  .chips{ display:flex; gap:8px; flex-wrap:wrap;}
  .chip{ position:relative; font-family:var(--mono); font-size:11px; letter-spacing:0.12em; text-transform:uppercase; padding:9px 14px; border-radius:999px; border:1px solid var(--line); background:var(--bg-2); color:var(--ink); cursor:pointer; transition:all .15s ease; }
  .chip input{ position:absolute; opacity:0; inset:0; cursor:pointer;}
  .chip:hover{ border-color:var(--ink);}
  .chip:has(input:checked){ background:var(--ink); color:var(--bg); border-color:var(--ink);}
  .check-row{ display:flex; align-items:flex-start; gap:10px; font-size:13.5px; color:var(--ink-soft);}
  .check-row input{ margin-top:2px; width:16px;height:16px; accent-color:var(--sea-deep);}
  .check-row a{ color:var(--ink); text-decoration:underline; text-underline-offset:2px;}
  .form-foot{ grid-column:span 2; display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-top:4px; }
  .form-foot .submit{ display:flex; gap:10px; flex-wrap:wrap;}
  .form-foot .note{ font-size:12.5px; color:var(--ink-soft); font-family:var(--mono); letter-spacing:0.04em;}
  .form-success{ grid-column:span 2; display:none; background:color-mix(in oklab,var(--sea-soft) 60%,var(--bg)); border:1px solid color-mix(in oklab,var(--sea) 30%,var(--line)); border-radius:12px; padding:18px 20px; font-size:14.5px; color:var(--sea-deep); align-items:center; gap:12px; }
  .form-success.show{ display:flex;}

  /* FOOTER */
  footer{ margin-top:40px; padding:56px 0 32px; border-top:1px solid var(--line);}
  .foot-grid{ display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:40px;}
  .foot-grid h5{ font-family:var(--mono); font-size:11px; letter-spacing:0.16em; text-transform:uppercase; color:var(--ink-soft); margin:0 0 14px;}
  .foot-grid ul{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;}
  .foot-grid a{ font-size:14.5px; color:var(--ink); }
  .foot-grid a:hover{ color:var(--sea-deep);}
  .foot-grid p{ color:var(--ink-soft); font-size:14.5px; margin:6px 0 0; max-width:36ch;}
  .foot-bottom{ margin-top:48px; padding-top:22px; border-top:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; font-size:13px; color:var(--ink-soft); flex-wrap:wrap; gap:12px; }
  .foot-socials{ display:flex; gap:10px;}
  .foot-socials a{ width:36px;height:36px;border-radius:50%; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); transition:background .2s,color .2s,border-color .2s; }
  .foot-socials a:hover{ background:var(--ink); color:var(--bg); border-color:var(--ink);}

  /* FLOATING WHATSAPP */
  .whats{ position:fixed; right:clamp(16px,3vw,28px); bottom:clamp(16px,3vw,28px); z-index:100; background:var(--wa); color:#fff; width:64px;height:64px;border-radius:50%; display:flex;align-items:center;justify-content:center; box-shadow:0 14px 30px -8px color-mix(in oklab,var(--wa-deep) 70%,transparent),0 4px 10px -2px rgba(0,0,0,0.15); transition:transform .2s ease,box-shadow .2s ease,width .25s ease; overflow:hidden; cursor:pointer; }
  .whats:hover{ transform:scale(1.06);}
  .whats svg{ flex-shrink:0;}
  .whats .label{ font-weight:600; font-size:14px; white-space:nowrap; max-width:0; opacity:0; transition:max-width .3s ease,opacity .2s ease .05s,margin .3s ease; margin-left:0; }
  .whats:hover{ width:220px; border-radius:999px;}
  .whats:hover .label{ max-width:200px; opacity:1; margin-left:10px;}
  .whats::before{ content:""; position:absolute; inset:0; border-radius:50%; background:var(--wa); opacity:0.4; animation:pulse 2.2s ease-out infinite; z-index:-1; }
  @keyframes pulse{ 0%{transform:scale(1);opacity:0.45} 100%{transform:scale(1.6);opacity:0} }

  /* RESPONSIVE */
  @media(max-width:960px){
    .hero-grid,.cta-band,.contact-grid{ grid-template-columns:1fr; }
    .hero-art{ aspect-ratio:5/4; } .section-head{ grid-template-columns:1fr; }
    .values{ grid-template-columns:1fr 1fr; } .class-card,.class-card.tall{ grid-column:span 12;}
    .instructors{ grid-template-columns:repeat(2,1fr);} .pricing{ grid-template-columns:1fr;}
    .gallery{ grid-auto-rows:120px;} .g1{ grid-column:span 12; grid-row:span 2;}
    .g2,.g3,.g4,.g5,.g6,.g7{ grid-column:span 6;}
    .testimonials{ grid-template-columns:1fr;} .faq-grid{ grid-template-columns:1fr;}
    .foot-grid{ grid-template-columns:1fr 1fr;} .nav-links{ display:none;}
    form.contact-form{ grid-template-columns:1fr;} .field.full,.form-foot,.form-success{ grid-column:span 1;}
  }
  @media(max-width:560px){
    .values{ grid-template-columns:1fr;} .instructors{ grid-template-columns:1fr 1fr;}
    h1.headline{ font-size:48px;} .nav-cta .btn-ghost{ display:none;}
  }
</style>
</head>
<body>

<!-- NAV -->
<header class="nav">
  <div class="wrap nav-row">
    <a href="#" class="brand" aria-label="{{ $nomeEscola }} Escola de Surf">
      <span class="mark" aria-hidden="true"></span>
      <span>{{ $nomeEscola }}<span style="color:var(--coral)">.</span></span>
    </a>
    <nav class="nav-links" aria-label="Primary">
      <a href="#sobre" data-i18n="nav.about">Sobre</a>
      <a href="#aulas" data-i18n="nav.classes">Aulas</a>
      <a href="#instrutores" data-i18n="nav.instructors">Instrutores</a>
      <a href="#precos" data-i18n="nav.pricing">Preços</a>
      <a href="#galeria" data-i18n="nav.gallery">Galeria</a>
      <a href="#faq" data-i18n="nav.faq">FAQ</a>
    </nav>
    <div class="nav-cta">
      <div class="lang" role="tablist" aria-label="Language switcher">
        <button data-lang="pt" class="active">PT</button>
        <button data-lang="en">EN</button>
        <button data-lang="es">ES</button>
      </div>
      <a href="#contato" class="btn btn-primary" data-i18n="nav.book">Agendar aula</a>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow" data-i18n="hero.eyebrow">Escola de Surf · Desde 2012</span>
      <h1 class="headline">
        <span data-i18n="hero.h1a">Aprenda a surfar</span><br/>
        <em data-i18n="hero.h1b">a primeira onda</em><br/>
        <span data-i18n="hero.h1c">em um único dia.</span>
      </h1>
      <p class="sub" data-i18n="hero.sub">
        Aulas guiadas por instrutores certificados, em uma praia segura, com pranchas, lycras e seguro inclusos. Para iniciantes absolutos, intermediários e crianças.
      </p>
      <div class="hero-ctas">
        <a href="#aulas" class="btn btn-primary" data-i18n="hero.cta1">Ver aulas disponíveis →</a>
        <a href="#contato" class="btn btn-ghost" data-i18n="hero.cta2">Falar com a equipe</a>
      </div>
      <div class="hero-stats">
        <div class="s"><strong>12k+</strong><span data-i18n="hero.stat1">alunos formados</span></div>
        <div class="s"><strong>4.9★</strong><span data-i18n="hero.stat2">2.300+ avaliações</span></div>
        <div class="s"><strong>13</strong><span data-i18n="hero.stat3">anos na praia</span></div>
      </div>
    </div>
    <div class="hero-art">
      <div class="ph"><span class="lbl">Substituir &nbsp;/&nbsp; surfer riding wave · 4:5</span></div>
      <div class="badge-float">
        <span class="dot"></span>
        <div>
          <small data-i18n="hero.live">Turma de hoje</small>
          <strong data-i18n="hero.liveDesc">3 vagas · 16h · Praia Norte</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="sobre">
  <div class="wrap">
    <div class="section-head">
      <div><span class="kicker" data-i18n="about.kicker">Sobre nós</span></div>
      <h2 class="title">
        <span data-i18n="about.titleA">Surf não é só esporte.</span>
        <em data-i18n="about.titleB">É um jeito de ler o mar.</em>
      </h2>
    </div>
    <div class="values">
      <div class="value">
        <div class="ico" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/><path d="M2 20c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/><path d="M2 12c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/></svg>
        </div>
        <h3 data-i18n="about.v1t">Pequenas turmas</h3>
        <p data-i18n="about.v1d">No máximo 4 alunos por instrutor para que cada um receba correção individual em todas as ondas.</p>
      </div>
      <div class="value">
        <div class="ico" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 7v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-5Z"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <h3 data-i18n="about.v2t">Segurança em 1º lugar</h3>
        <p data-i18n="about.v2d">Instrutores com certificação ISA, salva-vidas em escala e equipamento revisado a cada aula.</p>
      </div>
      <div class="value">
        <div class="ico" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-12V5l-8-3-8 3v5c0 8 8 12 8 12Z"/></svg>
        </div>
        <h3 data-i18n="about.v3t">Tudo incluso</h3>
        <p data-i18n="about.v3d">Prancha, leash, lycra UV, parafina, vestiário e seguro do aluno. Você só precisa trazer sunga ou maiô.</p>
      </div>
    </div>
  </div>
</section>

<!-- CLASSES -->
<section id="aulas" style="background:var(--bg-2);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="classes.kicker">Nossas aulas</span>
        <h2 class="title"><span data-i18n="classes.titleA">Para cada pé</span> <em data-i18n="classes.titleB">na areia.</em></h2>
      </div>
      <p class="section-lead" data-i18n="classes.lead">Da primeira remada à manobra. Escolha o formato que combina com o seu nível, ritmo e curiosidade.</p>
    </div>
    <div class="classes">
      <article class="class-card">
        <div class="media"><div class="ph"><span class="lbl">beginner group class · 16:10</span></div></div>
        <div class="body">
          <span class="tag" data-i18n="classes.c1tag">Iniciantes</span>
          <h3 data-i18n="classes.c1t">Primeira Onda</h3>
          <p data-i18n="classes.c1d">Aula em grupo de 2h: teoria na areia, segurança, remada e a primeira onda em pé.</p>
          <div class="meta">
            <span>⏱ <b data-i18n="classes.dur2">2h</b></span>
            <span>👥 <b data-i18n="classes.size4">até 4 alunos</b></span>
            <span>📍 <b data-i18n="classes.beachN">Praia Norte</b></span>
          </div>
        </div>
      </article>
      <article class="class-card">
        <div class="media"><div class="ph warm"><span class="lbl">private 1-on-1 lesson · 16:10</span></div></div>
        <div class="body">
          <span class="tag" data-i18n="classes.c2tag">Privativa</span>
          <h3 data-i18n="classes.c2t">Aula Particular</h3>
          <p data-i18n="classes.c2d">1 instrutor, 1 aluno. Foco em correções de postura, remada e leitura da série. Para qualquer nível.</p>
          <div class="meta">
            <span>⏱ <b data-i18n="classes.dur15">1h30</b></span>
            <span>👤 <b data-i18n="classes.size1">1 aluno</b></span>
            <span>🎥 <b data-i18n="classes.video">vídeo análise</b></span>
          </div>
        </div>
      </article>
      <article class="class-card">
        <div class="media"><div class="ph"><span class="lbl">kids surf class · 16:10</span></div></div>
        <div class="body">
          <span class="tag" data-i18n="classes.c3tag">Kids · 6-12 anos</span>
          <h3 data-i18n="classes.c3t">Onda Mirim</h3>
          <p data-i18n="classes.c3d">Programa lúdico semanal para crianças. Brincadeiras na areia, equilíbrio e respeito ao mar.</p>
          <div class="meta">
            <span>⏱ <b data-i18n="classes.dur15a">1h30</b></span>
            <span>👶 <b data-i18n="classes.size6">até 6 crianças</b></span>
            <span>🛟 <b data-i18n="classes.lifeg">salva-vidas dedicado</b></span>
          </div>
        </div>
      </article>
      <article class="class-card">
        <div class="media"><div class="ph warm"><span class="lbl">intermediate coaching · 16:10</span></div></div>
        <div class="body">
          <span class="tag" data-i18n="classes.c4tag">Intermediário+</span>
          <h3 data-i18n="classes.c4t">Coaching Avançado</h3>
          <p data-i18n="classes.c4d">Pacote de 8 aulas para evoluir em manobras (cutback, bottom turn), com plano semanal e revisão em vídeo.</p>
          <div class="meta">
            <span>⏱ <b data-i18n="classes.dur8">8 sessões</b></span>
            <span>📈 <b data-i18n="classes.plan">plano personalizado</b></span>
            <span>🎥 <b data-i18n="classes.videoA">análise semanal</b></span>
          </div>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- INSTRUCTORS -->
<section id="instrutores">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="coaches.kicker">Equipe</span>
        <h2 class="title"><span data-i18n="coaches.titleA">Quem te leva</span> <em data-i18n="coaches.titleB">pra dentro d'água.</em></h2>
      </div>
      <p class="section-lead" data-i18n="coaches.lead">Surfistas de carreira com certificação internacional. Falam português, inglês e espanhol.</p>
    </div>
    <div class="instructors">
      <div class="coach">
        <div class="photo ph"><span class="lbl">coach portrait · 4:5</span></div>
        <span class="role" data-i18n="coaches.r1">Head coach · ISA L2</span>
        <h4>Marina Tavares</h4>
        <p data-i18n="coaches.b1">14 anos de surf. Especialista em iniciantes adultos com medo de mar aberto.</p>
      </div>
      <div class="coach">
        <div class="photo ph warm"><span class="lbl">coach portrait · 4:5</span></div>
        <span class="role" data-i18n="coaches.r2">Coach · Kids program</span>
        <h4>Diogo Albuquerque</h4>
        <p data-i18n="coaches.b2">Professor de educação física e pai de duas mini-surfistas. Especialista em crianças.</p>
      </div>
      <div class="coach">
        <div class="photo ph"><span class="lbl">coach portrait · 4:5</span></div>
        <span class="role" data-i18n="coaches.r3">Coach · Performance</span>
        <h4>Lucía Fernández</h4>
        <p data-i18n="coaches.b3">Ex-competidora do circuito sul-americano. Foco em manobras e leitura de bancos.</p>
      </div>
      <div class="coach">
        <div class="photo ph warm"><span class="lbl">coach portrait · 4:5</span></div>
        <span class="role" data-i18n="coaches.r4">Coach · Longboard</span>
        <h4>Tomás Carvalho</h4>
        <p data-i18n="coaches.b4">Shaper e longboarder. Aulas de estilo clássico, nose riding e ondas pequenas.</p>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section id="precos" style="background:var(--bg-2);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="pricing.kicker">Pacotes</span>
        <h2 class="title"><span data-i18n="pricing.titleA">Preços honestos.</span><em data-i18n="pricing.titleB">Sem letras miúdas.</em></h2>
      </div>
      <p class="section-lead" data-i18n="pricing.lead">Todos os pacotes incluem prancha, leash, lycra UV, parafina, vestiário com chuveiro quente e seguro do aluno.</p>
    </div>
    <div class="pricing">
      <div class="plan">
        <span class="pill" data-i18n="pricing.p1pill">Avulsa</span>
        <h3 data-i18n="pricing.p1t">Aula Única</h3>
        <div class="price"><sup>R$</sup>180<small data-i18n="pricing.perclass">/aula</small></div>
        <ul class="feat">
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p1f1">2 horas de aula em grupo</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p1f2">Todo o equipamento incluso</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p1f3">Fotos da sessão por WhatsApp</span></li>
        </ul>
        <a href="#contato" class="btn btn-ghost" data-i18n="pricing.cta">Agendar</a>
      </div>
      <div class="plan featured">
        <span class="pill" data-i18n="pricing.p2pill">Mais escolhido</span>
        <h3 data-i18n="pricing.p2t">Pacote 5 Aulas</h3>
        <div class="price"><sup>R$</sup>780<small data-i18n="pricing.total">/total</small></div>
        <ul class="feat">
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p2f1">5 aulas, válidas por 60 dias</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p2f2">Plano de evolução individual</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p2f3">1 análise em vídeo grátis</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p2f4">Acesso ao app de previsão</span></li>
        </ul>
        <a href="#contato" class="btn btn-primary" data-i18n="pricing.cta">Agendar</a>
      </div>
      <div class="plan">
        <span class="pill" data-i18n="pricing.p3pill">Particular</span>
        <h3 data-i18n="pricing.p3t">Aula Privativa</h3>
        <div class="price"><sup>R$</sup>340<small data-i18n="pricing.perclass">/aula</small></div>
        <ul class="feat">
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p3f1">1h30 com instrutor exclusivo</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p3f2">Análise em vídeo no mesmo dia</span></li>
          <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span data-i18n="pricing.p3f3">Horários flexíveis (até 18h)</span></li>
        </ul>
        <a href="#contato" class="btn btn-ghost" data-i18n="pricing.cta">Agendar</a>
      </div>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section id="galeria">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="gallery.kicker">Galeria</span>
        <h2 class="title"><span data-i18n="gallery.titleA">Um dia comum</span><em data-i18n="gallery.titleB">na {{ $nomeEscola }}.</em></h2>
      </div>
      <p class="section-lead" data-i18n="gallery.lead">Fotos tiradas em aulas reais ao longo da última temporada. Todas as imagens dos alunos são autorizadas.</p>
    </div>
    <div class="gallery">
      <div class="g1 ph"><span class="lbl">hero shot · wave & surfer</span></div>
      <div class="g2 ph warm"><span class="lbl">beach lineup</span></div>
      <div class="g3 ph"><span class="lbl">paddle out</span></div>
      <div class="g4 ph"><span class="lbl">sunset session</span></div>
      <div class="g5 ph warm"><span class="lbl">kids class</span></div>
      <div class="g6 ph"><span class="lbl">aerial drone shot of bay</span></div>
      <div class="g7 ph warm"><span class="lbl">coach giving feedback on sand</span></div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section style="background:var(--bg-2);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="testi.kicker">Depoimentos</span>
        <h2 class="title"><em data-i18n="testi.titleA">Quem já remou</em> <span data-i18n="testi.titleB">com a gente.</span></h2>
      </div>
      <p class="section-lead" data-i18n="testi.lead">2.300+ avaliações entre Google, Tripadvisor e Instagram. Selecionamos três que resumem o sentimento.</p>
    </div>
    <div class="testimonials">
      <div class="quote">
        <div class="stars">★★★★★</div>
        <p data-i18n="testi.q1">"Cheguei achando que ia engolir litros de água. Em duas horas já estava em pé. A Marina explica de um jeito que destrava o medo."</p>
        <div class="who"><div class="avatar"></div><div><b>Camila R.</b><span data-i18n="testi.l1">Iniciante · São Paulo</span></div></div>
      </div>
      <div class="quote">
        <div class="stars">★★★★★</div>
        <p data-i18n="testi.q2">"Trouxe meu filho de 7 anos. Ele saiu da água pedindo pra voltar no dia seguinte. Equipe atenciosa e séria com segurança."</p>
        <div class="who"><div class="avatar"></div><div><b>Rafael M.</b><span data-i18n="testi.l2">Pai · Florianópolis</span></div></div>
      </div>
      <div class="quote">
        <div class="stars">★★★★★</div>
        <p data-i18n="testi.q3">"Surfo há 4 anos e travei na evolução. Em 8 aulas com a Lucía finalmente entendi o bottom turn. Recomendo demais."</p>
        <div class="who"><div class="avatar"></div><div><b>Diego A.</b><span data-i18n="testi.l3">Intermediário · Buenos Aires</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="faq.kicker">FAQ</span>
        <h2 class="title"><span data-i18n="faq.titleA">Perguntas</span> <em data-i18n="faq.titleB">frequentes.</em></h2>
      </div>
      <p class="section-lead" data-i18n="faq.lead">Se a sua não está aqui, manda direto no nosso WhatsApp — respondemos em até 1 hora durante o dia.</p>
    </div>
    <div class="faq-grid">
      <details class="faq" open><summary data-i18n="faq.q1">Preciso saber nadar?</summary><p data-i18n="faq.a1">Sim. Você precisa se sentir confortável em águas até a altura do peito. Não exigimos natação avançada, mas é importante não ter pânico.</p></details>
      <details class="faq"><summary data-i18n="faq.q2">E se chover ou o mar estiver ruim?</summary><p data-i18n="faq.a2">Aulas só são canceladas em caso de risco real. Se acontecer, remarcamos sem custo para qualquer dia em até 90 dias.</p></details>
      <details class="faq"><summary data-i18n="faq.q3">Qual a idade mínima e máxima?</summary><p data-i18n="faq.a3">Atendemos a partir de 6 anos (turma Kids) e não temos idade máxima. Já tivemos alunos de 72 anos pegando a primeira onda.</p></details>
      <details class="faq"><summary data-i18n="faq.q4">O que devo levar?</summary><p data-i18n="faq.a4">Sunga ou maiô, toalha, protetor solar (de preferência mineral) e uma garrafa d'água. Todo o resto fica por nossa conta.</p></details>
      <details class="faq"><summary data-i18n="faq.q5">Vocês falam inglês e espanhol?</summary><p data-i18n="faq.a5">Sim. Todos os instrutores são fluentes em português, inglês e espanhol. Avise no agendamento o idioma de preferência.</p></details>
      <details class="faq"><summary data-i18n="faq.q6">Posso comprar a aula como presente?</summary><p data-i18n="faq.a6">Sim! Temos vale-presente digital, válido por 12 meses. Fale com a gente no WhatsApp que enviamos o link de pagamento.</p></details>
    </div>
  </div>
</section>

<!-- CONTACT FORM -->
<section id="contato" style="background:var(--bg-2);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="kicker" data-i18n="contact.kicker">Fale conosco</span>
        <h2 class="title"><span data-i18n="contact.titleA">Mande sua dúvida.</span><em data-i18n="contact.titleB">Respondemos no mesmo dia.</em></h2>
      </div>
      <p class="section-lead" data-i18n="contact.lead">Preencha o formulário ao lado e nossa equipe entra em contato pelo canal de sua preferência. Para confirmação imediata, prefira o WhatsApp.</p>
    </div>
    <div class="contact-grid">
      <aside class="contact-side">
        <h3 data-i18n="contact.sideT">Outras formas de falar com a gente</h3>
        <p data-i18n="contact.sideD">Estamos na praia das 7h às 18h, todos os dias. Em períodos de chuva forte, atendimento apenas online.</p>
        <div class="info-row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small data-i18n="contact.locL">Endereço</small><strong>{{ $endereco }}</strong></div>
        </div>
        <div class="info-row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small data-i18n="contact.phoneL">Telefone / WhatsApp</small><strong>{{ $telefone }}</strong></div>
        </div>
        <div class="info-row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><polyline points="22,6 12,13 2,6"/></svg>
          <div><small data-i18n="contact.mailL">E-mail</small><strong>{{ $emailContato }}</strong></div>
        </div>
        <div class="info-row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><small data-i18n="contact.hoursL">Horário</small><strong>{{ $horario }}</strong></div>
        </div>
      </aside>

      {{-- Form → salva lead no CRM --}}
      <form class="contact-form" id="ondaForm" novalidate>
        @csrf
        <div class="field">
          <label for="f-name" data-i18n="form.name">Nome <span class="req">*</span></label>
          <input id="f-name" name="name" type="text" required placeholder="Como podemos te chamar?" />
        </div>
        <div class="field">
          <label for="f-email" data-i18n="form.email">E-mail <span class="req">*</span></label>
          <input id="f-email" name="email" type="email" required placeholder="voce@email.com" />
        </div>
        <div class="field">
          <label for="f-phone" data-i18n="form.phone">Telefone / WhatsApp <span class="req">*</span></label>
          <input id="f-phone" name="phone" type="tel" required placeholder="+55 (11) 99999-9999" />
        </div>
        <div class="field">
          <label for="f-class" data-i18n="form.classT">Tipo de aula</label>
          <select id="f-class" name="class">
            <option value="first" data-i18n="form.opt1">Primeira Onda (iniciantes)</option>
            <option value="private" data-i18n="form.opt2">Aula particular</option>
            <option value="kids" data-i18n="form.opt3">Onda Mirim (kids)</option>
            <option value="advanced" data-i18n="form.opt4">Coaching avançado</option>
            <option value="gift" data-i18n="form.opt5">Vale-presente</option>
            <option value="other" data-i18n="form.opt6">Outro / Tenho dúvida</option>
          </select>
        </div>
        <div class="field full">
          <label data-i18n="form.langT">Idioma preferido</label>
          <div class="chips">
            <label class="chip"><input type="radio" name="langPref" value="pt" checked /><span>Português</span></label>
            <label class="chip"><input type="radio" name="langPref" value="en" /><span>English</span></label>
            <label class="chip"><input type="radio" name="langPref" value="es" /><span>Español</span></label>
          </div>
        </div>
        <div class="field full">
          <label for="f-msg" data-i18n="form.msg">Mensagem</label>
          <textarea id="f-msg" name="message" placeholder="Conte um pouco sobre o seu nível, datas pretendidas e o que você espera da aula."></textarea>
        </div>
        <div class="field full check-row">
          <input id="f-consent" type="checkbox" required />
          <label for="f-consent" data-i18n="form.consent">Concordo em receber contato por e-mail ou WhatsApp e li a Política de Privacidade.</label>
        </div>
        <div class="form-foot">
          <span class="note" data-i18n="form.note">Resposta em até 1h durante o horário comercial.</span>
          <div class="submit">
            <button type="submit" class="btn btn-primary" data-i18n="form.send">Enviar mensagem →</button>
            <a href="https://wa.me/{{ $wa }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-ghost" data-i18n="form.wa">Ou pelo WhatsApp</a>
          </div>
        </div>
        <div class="form-success" id="ondaSuccess" role="status">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
          <span data-i18n="form.ok">Recebemos sua mensagem! Vamos responder em breve.</span>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section id="agendar">
  <div class="wrap">
    <div class="cta-band">
      <div>
        <h2>
          <span data-i18n="cta.titleA">Pronto pra remar?</span><br/>
          <em data-i18n="cta.titleB">A primeira onda é por nossa conta.</em>
        </h2>
        <p data-i18n="cta.sub">Agende sua aula em menos de 1 minuto. Confirmação imediata por WhatsApp.</p>
        <div class="btns">
          <a href="https://wa.me/{{ $wa }}" class="btn btn-primary" data-i18n="cta.btn1">Agendar pelo WhatsApp</a>
          <a href="#contato" class="btn btn-ghost" data-i18n="cta.btn2">Preencher formulário</a>
        </div>
      </div>
      <div class="info-card">
        <div class="row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><small data-i18n="cta.locL">Localização</small><strong>{{ $endereco }}<br/>{{ $cep }}</strong></div>
        </div>
        <div class="row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><small data-i18n="cta.hoursL">Horário</small><strong>{{ $horario }}</strong></div>
        </div>
        <div class="row">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
          <div><small data-i18n="cta.phoneL">Telefone</small><strong>{{ $telefone }}</strong></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <a href="#" class="brand"><span class="mark"></span><span>{{ $nomeEscola }}<span style="color:var(--coral)">.</span></span></a>
        <p data-i18n="foot.tag">Escola de surf desde 2012. Levamos centenas de pessoas pra primeira onda — e treinamos quem quer ir além.</p>
      </div>
      <div>
        <h5 data-i18n="foot.h1">Escola</h5>
        <ul>
          <li><a href="#sobre" data-i18n="nav.about">Sobre</a></li>
          <li><a href="#instrutores" data-i18n="nav.instructors">Instrutores</a></li>
          <li><a href="#galeria" data-i18n="nav.gallery">Galeria</a></li>
        </ul>
      </div>
      <div>
        <h5 data-i18n="foot.h2">Aulas</h5>
        <ul>
          <li><a href="#aulas" data-i18n="foot.l1">Iniciantes</a></li>
          <li><a href="#aulas" data-i18n="foot.l2">Particular</a></li>
          <li><a href="#aulas" data-i18n="foot.l3">Kids</a></li>
          <li><a href="#aulas" data-i18n="foot.l4">Coaching</a></li>
        </ul>
      </div>
      <div>
        <h5 data-i18n="foot.h3">Contato</h5>
        <ul>
          <li><a href="mailto:{{ $emailContato }}">{{ $emailContato }}</a></li>
          <li><a href="tel:+{{ $wa }}">{{ $telefone }}</a></li>
          <li><a href="#contato" data-i18n="foot.l5">Como chegar</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} {{ $nomeEscola }} Escola de Surf · <span data-i18n="foot.rights">Todos os direitos reservados</span></span>
      <div class="foot-socials">
        <a href="#" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
        <a href="#" aria-label="YouTube"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33Z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg></a>
        <a href="#" aria-label="TikTok"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg></a>
      </div>
    </div>
  </div>
</footer>

<!-- FLOATING WHATSAPP -->
<a href="https://wa.me/{{ $wa }}?text={{ $waText }}" class="whats" aria-label="WhatsApp" target="_blank" rel="noopener">
  <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
    <path d="M16.003 3.2c-7.067 0-12.8 5.733-12.8 12.8 0 2.258.59 4.453 1.713 6.394L3.2 28.8l6.582-1.726a12.78 12.78 0 0 0 6.221 1.585h.006c7.067 0 12.8-5.733 12.803-12.8 0-3.42-1.331-6.635-3.749-9.054A12.71 12.71 0 0 0 16.003 3.2Zm0 23.466h-.005a10.61 10.61 0 0 1-5.408-1.481l-.388-.23-4.014 1.053 1.07-3.913-.252-.402a10.62 10.62 0 0 1-1.627-5.683c0-5.873 4.78-10.654 10.66-10.654a10.59 10.59 0 0 1 7.534 3.124 10.59 10.59 0 0 1 3.12 7.537c-.003 5.876-4.783 10.65-10.69 10.65Zm5.838-7.974c-.32-.16-1.893-.934-2.187-1.04-.293-.107-.507-.16-.72.16-.214.32-.827 1.04-1.014 1.254-.187.213-.373.24-.693.08-.32-.16-1.35-.498-2.572-1.587-.95-.848-1.59-1.895-1.777-2.214-.187-.32-.02-.493.14-.652.144-.143.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.053-.4-.027-.56-.08-.16-.72-1.733-.986-2.374-.26-.624-.524-.539-.72-.55-.187-.008-.4-.01-.613-.01a1.18 1.18 0 0 0-.854.4c-.293.32-1.12 1.094-1.12 2.667 0 1.573 1.147 3.094 1.307 3.307.16.213 2.253 3.44 5.46 4.825.763.33 1.358.527 1.822.674.766.243 1.464.209 2.014.127.615-.092 1.893-.774 2.16-1.52.267-.747.267-1.387.187-1.52-.08-.134-.293-.214-.613-.374Z"/>
  </svg>
  <span class="label" data-i18n="wa.label">Fale conosco</span>
</a>

<!-- I18N + FORM SUBMIT -->
<script>
const i18n = {
  pt: {
    "nav.about":"Sobre","nav.classes":"Aulas","nav.instructors":"Instrutores","nav.pricing":"Preços","nav.gallery":"Galeria","nav.faq":"FAQ","nav.book":"Agendar aula",
    "hero.eyebrow":"Escola de Surf · Desde 2012","hero.h1a":"Aprenda a surfar","hero.h1b":"a primeira onda","hero.h1c":"em um único dia.",
    "hero.sub":"Aulas guiadas por instrutores certificados, em uma praia segura, com pranchas, lycras e seguro inclusos. Para iniciantes absolutos, intermediários e crianças.",
    "hero.cta1":"Ver aulas disponíveis →","hero.cta2":"Falar com a equipe",
    "hero.stat1":"alunos formados","hero.stat2":"2.300+ avaliações","hero.stat3":"anos na praia",
    "hero.live":"Turma de hoje","hero.liveDesc":"3 vagas · 16h · Praia Norte",
    "about.kicker":"Sobre nós","about.titleA":"Surf não é só esporte.","about.titleB":"É um jeito de ler o mar.",
    "about.v1t":"Pequenas turmas","about.v1d":"No máximo 4 alunos por instrutor para que cada um receba correção individual em todas as ondas.",
    "about.v2t":"Segurança em 1º lugar","about.v2d":"Instrutores com certificação ISA, salva-vidas em escala e equipamento revisado a cada aula.",
    "about.v3t":"Tudo incluso","about.v3d":"Prancha, leash, lycra UV, parafina, vestiário e seguro do aluno. Você só precisa trazer sunga ou maiô.",
    "classes.kicker":"Nossas aulas","classes.titleA":"Para cada pé","classes.titleB":"na areia.",
    "classes.lead":"Da primeira remada à manobra. Escolha o formato que combina com o seu nível, ritmo e curiosidade.",
    "classes.c1tag":"Iniciantes","classes.c1t":"Primeira Onda","classes.c1d":"Aula em grupo de 2h: teoria na areia, segurança, remada e a primeira onda em pé.",
    "classes.c2tag":"Privativa","classes.c2t":"Aula Particular","classes.c2d":"1 instrutor, 1 aluno. Foco em correções de postura, remada e leitura da série. Para qualquer nível.",
    "classes.c3tag":"Kids · 6-12 anos","classes.c3t":"Onda Mirim","classes.c3d":"Programa lúdico semanal para crianças. Brincadeiras na areia, equilíbrio e respeito ao mar.",
    "classes.c4tag":"Intermediário+","classes.c4t":"Coaching Avançado","classes.c4d":"Pacote de 8 aulas para evoluir em manobras (cutback, bottom turn), com plano semanal e revisão em vídeo.",
    "classes.dur2":"2h","classes.dur15":"1h30","classes.dur15a":"1h30","classes.dur8":"8 sessões",
    "classes.size4":"até 4 alunos","classes.size1":"1 aluno","classes.size6":"até 6 crianças",
    "classes.beachN":"Praia Norte","classes.video":"vídeo análise","classes.videoA":"análise semanal","classes.lifeg":"salva-vidas dedicado","classes.plan":"plano personalizado",
    "coaches.kicker":"Equipe","coaches.titleA":"Quem te leva","coaches.titleB":"pra dentro d'água.",
    "coaches.lead":"Surfistas de carreira com certificação internacional. Falam português, inglês e espanhol.",
    "coaches.r1":"Head coach · ISA L2","coaches.r2":"Coach · Kids program","coaches.r3":"Coach · Performance","coaches.r4":"Coach · Longboard",
    "coaches.b1":"14 anos de surf. Especialista em iniciantes adultos com medo de mar aberto.",
    "coaches.b2":"Professor de educação física e pai de duas mini-surfistas. Especialista em crianças.",
    "coaches.b3":"Ex-competidora do circuito sul-americano. Foco em manobras e leitura de bancos.",
    "coaches.b4":"Shaper e longboarder. Aulas de estilo clássico, nose riding e ondas pequenas.",
    "pricing.kicker":"Pacotes","pricing.titleA":"Preços honestos.","pricing.titleB":"Sem letras miúdas.",
    "pricing.lead":"Todos os pacotes incluem prancha, leash, lycra UV, parafina, vestiário com chuveiro quente e seguro do aluno.",
    "pricing.p1pill":"Avulsa","pricing.p1t":"Aula Única","pricing.perclass":"/aula",
    "pricing.p1f1":"2 horas de aula em grupo","pricing.p1f2":"Todo o equipamento incluso","pricing.p1f3":"Fotos da sessão por WhatsApp",
    "pricing.p2pill":"Mais escolhido","pricing.p2t":"Pacote 5 Aulas","pricing.total":"/total",
    "pricing.p2f1":"5 aulas, válidas por 60 dias","pricing.p2f2":"Plano de evolução individual","pricing.p2f3":"1 análise em vídeo grátis","pricing.p2f4":"Acesso ao app de previsão",
    "pricing.p3pill":"Particular","pricing.p3t":"Aula Privativa",
    "pricing.p3f1":"1h30 com instrutor exclusivo","pricing.p3f2":"Análise em vídeo no mesmo dia","pricing.p3f3":"Horários flexíveis (até 18h)",
    "pricing.cta":"Agendar",
    "gallery.kicker":"Galeria","gallery.titleA":"Um dia comum","gallery.titleB":"na escola.",
    "gallery.lead":"Fotos tiradas em aulas reais ao longo da última temporada. Todas as imagens dos alunos são autorizadas.",
    "testi.kicker":"Depoimentos","testi.titleA":"Quem já remou","testi.titleB":"com a gente.",
    "testi.lead":"2.300+ avaliações entre Google, Tripadvisor e Instagram. Selecionamos três que resumem o sentimento.",
    "testi.q1":"\"Cheguei achando que ia engolir litros de água. Em duas horas já estava em pé. A Marina explica de um jeito que destrava o medo.\"",
    "testi.q2":"\"Trouxe meu filho de 7 anos. Ele saiu da água pedindo pra voltar no dia seguinte. Equipe atenciosa e séria com segurança.\"",
    "testi.q3":"\"Surfo há 4 anos e travei na evolução. Em 8 aulas com a Lucía finalmente entendi o bottom turn. Recomendo demais.\"",
    "testi.l1":"Iniciante · São Paulo","testi.l2":"Pai · Florianópolis","testi.l3":"Intermediário · Buenos Aires",
    "faq.kicker":"FAQ","faq.titleA":"Perguntas","faq.titleB":"frequentes.",
    "faq.lead":"Se a sua não está aqui, manda direto no nosso WhatsApp — respondemos em até 1 hora durante o dia.",
    "faq.q1":"Preciso saber nadar?","faq.a1":"Sim. Você precisa se sentir confortável em águas até a altura do peito. Não exigimos natação avançada, mas é importante não ter pânico.",
    "faq.q2":"E se chover ou o mar estiver ruim?","faq.a2":"Aulas só são canceladas em caso de risco real. Se acontecer, remarcamos sem custo para qualquer dia em até 90 dias.",
    "faq.q3":"Qual a idade mínima e máxima?","faq.a3":"Atendemos a partir de 6 anos (turma Kids) e não temos idade máxima. Já tivemos alunos de 72 anos pegando a primeira onda.",
    "faq.q4":"O que devo levar?","faq.a4":"Sunga ou maiô, toalha, protetor solar (de preferência mineral) e uma garrafa d'água. Todo o resto fica por nossa conta.",
    "faq.q5":"Vocês falam inglês e espanhol?","faq.a5":"Sim. Todos os instrutores são fluentes em português, inglês e espanhol. Avise no agendamento o idioma de preferência.",
    "faq.q6":"Posso comprar a aula como presente?","faq.a6":"Sim! Temos vale-presente digital, válido por 12 meses. Fale com a gente no WhatsApp que enviamos o link de pagamento.",
    "contact.kicker":"Fale conosco","contact.titleA":"Mande sua dúvida.","contact.titleB":"Respondemos no mesmo dia.",
    "contact.lead":"Preencha o formulário ao lado e nossa equipe entra em contato pelo canal de sua preferência. Para confirmação imediata, prefira o WhatsApp.",
    "contact.sideT":"Outras formas de falar com a gente","contact.sideD":"Estamos na praia das 7h às 18h, todos os dias.",
    "contact.locL":"Endereço","contact.phoneL":"Telefone / WhatsApp","contact.mailL":"E-mail","contact.hoursL":"Horário",
    "form.name":"Nome","form.email":"E-mail","form.phone":"Telefone / WhatsApp","form.classT":"Tipo de aula",
    "form.opt1":"Primeira Onda (iniciantes)","form.opt2":"Aula particular","form.opt3":"Onda Mirim (kids)","form.opt4":"Coaching avançado","form.opt5":"Vale-presente","form.opt6":"Outro / Tenho dúvida",
    "form.langT":"Idioma preferido","form.msg":"Mensagem",
    "form.consent":"Concordo em receber contato por e-mail ou WhatsApp e li a Política de Privacidade.",
    "form.note":"Resposta em até 1h durante o horário comercial.",
    "form.send":"Enviar mensagem →","form.wa":"Ou pelo WhatsApp","form.ok":"Recebemos sua mensagem! Vamos responder em breve.",
    "cta.titleA":"Pronto pra remar?","cta.titleB":"A primeira onda é por nossa conta.",
    "cta.sub":"Agende sua aula em menos de 1 minuto. Confirmação imediata por WhatsApp.",
    "cta.btn1":"Agendar pelo WhatsApp","cta.btn2":"Preencher formulário",
    "cta.locL":"Localização","cta.hoursL":"Horário","cta.phoneL":"Telefone",
    "foot.tag":"Escola de surf desde 2012. Levamos centenas de pessoas pra primeira onda — e treinamos quem quer ir além.",
    "foot.h1":"Escola","foot.h2":"Aulas","foot.h3":"Contato",
    "foot.l1":"Iniciantes","foot.l2":"Particular","foot.l3":"Kids","foot.l4":"Coaching","foot.l5":"Como chegar","foot.rights":"Todos os direitos reservados",
    "wa.label":"Fale conosco"
  },
  en: {
    "nav.about":"About","nav.classes":"Classes","nav.instructors":"Coaches","nav.pricing":"Pricing","nav.gallery":"Gallery","nav.faq":"FAQ","nav.book":"Book a class",
    "hero.eyebrow":"Surf School · Since 2012","hero.h1a":"Learn to surf","hero.h1b":"your first wave","hero.h1c":"in a single day.",
    "hero.sub":"Lessons led by certified instructors on a safe beach, with boards, rashguards and insurance included. For absolute beginners, intermediates and kids.",
    "hero.cta1":"See available classes →","hero.cta2":"Talk to the team",
    "hero.stat1":"students taught","hero.stat2":"2,300+ reviews","hero.stat3":"years on the beach",
    "hero.live":"Today's session","hero.liveDesc":"3 spots · 4pm · North Beach",
    "about.kicker":"About us","about.titleA":"Surf isn't just a sport.","about.titleB":"It's a way of reading the sea.",
    "about.v1t":"Small groups","about.v1d":"No more than 4 students per coach so everyone gets personal feedback on every single wave.",
    "about.v2t":"Safety first","about.v2d":"ISA-certified instructors, dedicated lifeguards on rotation, and gear inspected before every class.",
    "about.v3t":"Everything included","about.v3d":"Board, leash, UV rashguard, wax, locker room and student insurance. You just bring your swimsuit.",
    "classes.kicker":"Our classes","classes.titleA":"For every foot","classes.titleB":"in the sand.",
    "classes.lead":"From your first paddle to your first maneuver. Pick the format that matches your level, pace and curiosity.",
    "classes.c1tag":"Beginners","classes.c1t":"First Wave","classes.c1d":"2h group class: theory on the sand, safety, paddling and your first wave standing up.",
    "classes.c2tag":"Private","classes.c2t":"1-on-1 Class","classes.c2d":"One coach, one student. Focus on posture, paddling and reading sets. For any level.",
    "classes.c3tag":"Kids · 6-12 yrs","classes.c3t":"Mini Wave","classes.c3d":"Weekly playful program for kids. Beach games, balance work and respect for the ocean.",
    "classes.c4tag":"Intermediate+","classes.c4t":"Advanced Coaching","classes.c4d":"8-class package to level up your maneuvers (cutback, bottom turn), with weekly plan and video review.",
    "classes.dur2":"2h","classes.dur15":"1h30","classes.dur15a":"1h30","classes.dur8":"8 sessions",
    "classes.size4":"up to 4 students","classes.size1":"1 student","classes.size6":"up to 6 kids",
    "classes.beachN":"North Beach","classes.video":"video analysis","classes.videoA":"weekly review","classes.lifeg":"dedicated lifeguard","classes.plan":"personalized plan",
    "coaches.kicker":"Team","coaches.titleA":"Who takes you","coaches.titleB":"into the water.",
    "coaches.lead":"Career surfers with international certification. Fluent in Portuguese, English and Spanish.",
    "coaches.r1":"Head coach · ISA L2","coaches.r2":"Coach · Kids program","coaches.r3":"Coach · Performance","coaches.r4":"Coach · Longboard",
    "coaches.b1":"14 years surfing. Specialist for adult beginners afraid of open water.",
    "coaches.b2":"PE teacher and dad of two mini-surfers. Specialist for kids.",
    "coaches.b3":"Former South American circuit competitor. Focus on maneuvers and reading the lineup.",
    "coaches.b4":"Board shaper and longboarder. Classic style, nose riding and small-wave classes.",
    "pricing.kicker":"Packages","pricing.titleA":"Honest pricing.","pricing.titleB":"No fine print.",
    "pricing.lead":"All packages include board, leash, UV rashguard, wax, locker room with hot shower and student insurance.",
    "pricing.p1pill":"Single","pricing.p1t":"Drop-in Class","pricing.perclass":"/class",
    "pricing.p1f1":"2-hour group class","pricing.p1f2":"All equipment included","pricing.p1f3":"Session photos via WhatsApp",
    "pricing.p2pill":"Most popular","pricing.p2t":"5-Class Pack","pricing.total":"/total",
    "pricing.p2f1":"5 classes, valid for 60 days","pricing.p2f2":"Personal progression plan","pricing.p2f3":"1 free video analysis","pricing.p2f4":"Access to swell forecast app",
    "pricing.p3pill":"Private","pricing.p3t":"1-on-1 Class",
    "pricing.p3f1":"1h30 with an exclusive coach","pricing.p3f2":"Same-day video review","pricing.p3f3":"Flexible hours (until 6pm)",
    "pricing.cta":"Book now",
    "gallery.kicker":"Gallery","gallery.titleA":"An ordinary day","gallery.titleB":"at the school.",
    "gallery.lead":"Photos from real lessons over the last season. All student images are authorized.",
    "testi.kicker":"Reviews","testi.titleA":"Who already paddled","testi.titleB":"with us.",
    "testi.lead":"2,300+ reviews across Google, Tripadvisor and Instagram. We picked three that capture the feeling.",
    "testi.q1":"\"I thought I'd swallow gallons of water. Two hours in, I was standing up. Marina has a way of unlocking the fear.\"",
    "testi.q2":"\"I brought my 7-year-old. He left the water asking to come back the next day. Caring team, serious about safety.\"",
    "testi.q3":"\"I've surfed for 4 years and got stuck. In 8 classes with Lucía I finally understood the bottom turn. Highly recommend.\"",
    "testi.l1":"Beginner · São Paulo","testi.l2":"Dad · Florianópolis","testi.l3":"Intermediate · Buenos Aires",
    "faq.kicker":"FAQ","faq.titleA":"Frequently","faq.titleB":"asked questions.",
    "faq.lead":"If yours isn't here, drop it on our WhatsApp — we reply within an hour during the day.",
    "faq.q1":"Do I need to know how to swim?","faq.a1":"Yes. You should be comfortable in chest-deep water. Advanced swimming isn't required, but you shouldn't panic.",
    "faq.q2":"What if it rains or the sea is bad?","faq.a2":"Classes are only canceled when there's real risk. If so, we reschedule at no cost for any day within 90 days.",
    "faq.q3":"What's the minimum and maximum age?","faq.a3":"From age 6 (Kids class), no upper limit. We've taught 72-year-olds their first wave.",
    "faq.q4":"What should I bring?","faq.a4":"Swimsuit, towel, sunscreen (mineral preferred) and a water bottle. Everything else is on us.",
    "faq.q5":"Do you speak English and Spanish?","faq.a5":"Yes. All coaches are fluent in Portuguese, English and Spanish. Just mention your preference when booking.",
    "faq.q6":"Can I gift a class?","faq.a6":"Yes! We have digital gift vouchers, valid for 12 months. WhatsApp us and we'll send the payment link.",
    "contact.kicker":"Get in touch","contact.titleA":"Send us a question.","contact.titleB":"We reply same day.",
    "contact.lead":"Fill in the form and our team will reach back on your preferred channel. For instant confirmation, use WhatsApp.",
    "contact.sideT":"Other ways to reach us","contact.sideD":"We're on the beach from 7am to 6pm, every day.",
    "contact.locL":"Address","contact.phoneL":"Phone / WhatsApp","contact.mailL":"Email","contact.hoursL":"Hours",
    "form.name":"Name","form.email":"Email","form.phone":"Phone / WhatsApp","form.classT":"Class type",
    "form.opt1":"First Wave (beginners)","form.opt2":"Private 1-on-1","form.opt3":"Mini Wave (kids)","form.opt4":"Advanced coaching","form.opt5":"Gift voucher","form.opt6":"Other / I have a question",
    "form.langT":"Preferred language","form.msg":"Message",
    "form.consent":"I agree to be contacted by email or WhatsApp and I've read the Privacy Policy.",
    "form.note":"Reply within 1h during business hours.",
    "form.send":"Send message →","form.wa":"Or via WhatsApp","form.ok":"We got your message! We'll reply shortly.",
    "cta.titleA":"Ready to paddle out?","cta.titleB":"The first wave is on us.",
    "cta.sub":"Book your class in under a minute. Instant confirmation on WhatsApp.",
    "cta.btn1":"Book on WhatsApp","cta.btn2":"Open contact form",
    "cta.locL":"Location","cta.hoursL":"Hours","cta.phoneL":"Phone",
    "foot.tag":"Surf school since 2012. We've taken hundreds of people to their first wave — and trained those who want to go further.",
    "foot.h1":"School","foot.h2":"Classes","foot.h3":"Contact",
    "foot.l1":"Beginners","foot.l2":"Private","foot.l3":"Kids","foot.l4":"Coaching","foot.l5":"How to get here","foot.rights":"All rights reserved",
    "wa.label":"Chat with us"
  },
  es: {
    "nav.about":"Nosotros","nav.classes":"Clases","nav.instructors":"Instructores","nav.pricing":"Precios","nav.gallery":"Galería","nav.faq":"FAQ","nav.book":"Reservar clase",
    "hero.eyebrow":"Escuela de Surf · Desde 2012","hero.h1a":"Aprende a surfear","hero.h1b":"tu primera ola","hero.h1c":"en un solo día.",
    "hero.sub":"Clases con instructores certificados, en una playa segura, con tablas, licras y seguro incluidos. Para principiantes absolutos, intermedios y niños.",
    "hero.cta1":"Ver clases disponibles →","hero.cta2":"Hablar con el equipo",
    "hero.stat1":"alumnos formados","hero.stat2":"2.300+ reseñas","hero.stat3":"años en la playa",
    "hero.live":"Clase de hoy","hero.liveDesc":"3 lugares · 16h · Playa Norte",
    "about.kicker":"Sobre nosotros","about.titleA":"El surf no es solo deporte.","about.titleB":"Es una forma de leer el mar.",
    "about.v1t":"Grupos pequeños","about.v1d":"Máximo 4 alumnos por instructor para que cada uno reciba corrección personal en cada ola.",
    "about.v2t":"Seguridad primero","about.v2d":"Instructores con certificación ISA, salvavidas en turno y equipo revisado antes de cada clase.",
    "about.v3t":"Todo incluido","about.v3d":"Tabla, leash, licra UV, parafina, vestuario y seguro del alumno. Solo trae tu traje de baño.",
    "classes.kicker":"Nuestras clases","classes.titleA":"Para cada pie","classes.titleB":"en la arena.",
    "classes.lead":"Desde la primera remada hasta tu primera maniobra. Elige el formato que combina con tu nivel, ritmo y curiosidad.",
    "classes.c1tag":"Principiantes","classes.c1t":"Primera Ola","classes.c1d":"Clase grupal de 2h: teoría en la arena, seguridad, remada y tu primera ola de pie.",
    "classes.c2tag":"Privada","classes.c2t":"Clase Particular","classes.c2d":"1 instructor, 1 alumno. Foco en postura, remada y lectura de series. Para cualquier nivel.",
    "classes.c3tag":"Kids · 6-12 años","classes.c3t":"Olita","classes.c3d":"Programa semanal lúdico para niños. Juegos en la arena, equilibrio y respeto al mar.",
    "classes.c4tag":"Intermedio+","classes.c4t":"Coaching Avanzado","classes.c4d":"Paquete de 8 clases para mejorar maniobras (cutback, bottom turn), con plan semanal y análisis en video.",
    "classes.dur2":"2h","classes.dur15":"1h30","classes.dur15a":"1h30","classes.dur8":"8 sesiones",
    "classes.size4":"hasta 4 alumnos","classes.size1":"1 alumno","classes.size6":"hasta 6 niños",
    "classes.beachN":"Playa Norte","classes.video":"análisis en video","classes.videoA":"análisis semanal","classes.lifeg":"salvavidas dedicado","classes.plan":"plan personalizado",
    "coaches.kicker":"Equipo","coaches.titleA":"Quién te lleva","coaches.titleB":"al agua.",
    "coaches.lead":"Surfistas de carrera con certificación internacional. Hablan portugués, inglés y español.",
    "coaches.r1":"Head coach · ISA L2","coaches.r2":"Coach · Programa Kids","coaches.r3":"Coach · Performance","coaches.r4":"Coach · Longboard",
    "coaches.b1":"14 años de surf. Especialista en adultos principiantes con miedo al mar abierto.",
    "coaches.b2":"Profesor de educación física y papá de dos mini-surfistas. Especialista en niños.",
    "coaches.b3":"Ex competidora del circuito sudamericano. Foco en maniobras y lectura del pico.",
    "coaches.b4":"Shaper y longboarder. Clases de estilo clásico, nose riding y olas pequeñas.",
    "pricing.kicker":"Paquetes","pricing.titleA":"Precios honestos.","pricing.titleB":"Sin letra chica.",
    "pricing.lead":"Todos los paquetes incluyen tabla, leash, licra UV, parafina, vestuario con ducha caliente y seguro del alumno.",
    "pricing.p1pill":"Suelta","pricing.p1t":"Clase Suelta","pricing.perclass":"/clase",
    "pricing.p1f1":"Clase grupal de 2 horas","pricing.p1f2":"Todo el equipo incluido","pricing.p1f3":"Fotos de la sesión por WhatsApp",
    "pricing.p2pill":"Más elegido","pricing.p2t":"Pack 5 Clases","pricing.total":"/total",
    "pricing.p2f1":"5 clases, válidas por 60 días","pricing.p2f2":"Plan de progresión individual","pricing.p2f3":"1 análisis en video gratis","pricing.p2f4":"Acceso a app de pronóstico",
    "pricing.p3pill":"Privada","pricing.p3t":"Clase Particular",
    "pricing.p3f1":"1h30 con instructor exclusivo","pricing.p3f2":"Análisis en video el mismo día","pricing.p3f3":"Horarios flexibles (hasta 18h)",
    "pricing.cta":"Reservar",
    "gallery.kicker":"Galería","gallery.titleA":"Un día común","gallery.titleB":"en la escuela.",
    "gallery.lead":"Fotos tomadas en clases reales durante la última temporada. Todas las imágenes de alumnos están autorizadas.",
    "testi.kicker":"Reseñas","testi.titleA":"Quien ya remó","testi.titleB":"con nosotros.",
    "testi.lead":"2.300+ reseñas entre Google, Tripadvisor e Instagram. Elegimos tres que resumen el sentimiento.",
    "testi.q1":"\"Llegué pensando que iba a tragar litros de agua. En dos horas ya estaba de pie. Marina explica de un modo que destraba el miedo.\"",
    "testi.q2":"\"Llevé a mi hijo de 7 años. Salió del agua pidiendo volver al día siguiente. Equipo atento y serio con la seguridad.\"",
    "testi.q3":"\"Surfeo hace 4 años y me estanqué. En 8 clases con Lucía entendí por fin el bottom turn. Súper recomendable.\"",
    "testi.l1":"Principiante · São Paulo","testi.l2":"Padre · Florianópolis","testi.l3":"Intermedio · Buenos Aires",
    "faq.kicker":"FAQ","faq.titleA":"Preguntas","faq.titleB":"frecuentes.",
    "faq.lead":"Si la tuya no está, escríbenos por WhatsApp — respondemos en menos de una hora durante el día.",
    "faq.q1":"¿Necesito saber nadar?","faq.a1":"Sí. Debes sentirte cómodo en agua hasta el pecho. No exigimos natación avanzada, pero no debes entrar en pánico.",
    "faq.q2":"¿Y si llueve o el mar está malo?","faq.a2":"Solo cancelamos en caso de riesgo real. Si pasa, reprogramamos sin costo para cualquier día en hasta 90 días.",
    "faq.q3":"¿Cuál es la edad mínima y máxima?","faq.a3":"Desde los 6 años (Kids), sin edad máxima. Ya enseñamos a alumnos de 72 años su primera ola.",
    "faq.q4":"¿Qué debo llevar?","faq.a4":"Traje de baño, toalla, protector solar (mineral preferentemente) y una botella de agua. Todo lo demás corre por nuestra cuenta.",
    "faq.q5":"¿Hablan inglés y español?","faq.a5":"Sí. Todos los instructores son fluentes en portugués, inglés y español. Avísanos al reservar tu preferencia.",
    "faq.q6":"¿Puedo regalar una clase?","faq.a6":"¡Sí! Tenemos vale-regalo digital, válido por 12 meses. Escríbenos por WhatsApp y te enviamos el link de pago.",
    "contact.kicker":"Contáctanos","contact.titleA":"Envíanos tu duda.","contact.titleB":"Respondemos el mismo día.",
    "contact.lead":"Completa el formulario y nuestro equipo te contacta por el canal que prefieras. Para confirmación inmediata, mejor por WhatsApp.",
    "contact.sideT":"Otras formas de contactarnos","contact.sideD":"Estamos en la playa de 7h a 18h, todos los días.",
    "contact.locL":"Dirección","contact.phoneL":"Teléfono / WhatsApp","contact.mailL":"E-mail","contact.hoursL":"Horario",
    "form.name":"Nombre","form.email":"E-mail","form.phone":"Teléfono / WhatsApp","form.classT":"Tipo de clase",
    "form.opt1":"Primera Ola (principiantes)","form.opt2":"Clase particular","form.opt3":"Olita (kids)","form.opt4":"Coaching avanzado","form.opt5":"Vale-regalo","form.opt6":"Otro / Tengo una duda",
    "form.langT":"Idioma preferido","form.msg":"Mensaje",
    "form.consent":"Acepto recibir contacto por e-mail o WhatsApp y leí la Política de Privacidad.",
    "form.note":"Respuesta en hasta 1h en horario comercial.",
    "form.send":"Enviar mensaje →","form.wa":"O por WhatsApp","form.ok":"¡Recibimos tu mensaje! Te respondemos pronto.",
    "cta.titleA":"¿Listo para remar?","cta.titleB":"La primera ola va por nuestra cuenta.",
    "cta.sub":"Reserva tu clase en menos de un minuto. Confirmación inmediata por WhatsApp.",
    "cta.btn1":"Reservar por WhatsApp","cta.btn2":"Abrir formulario",
    "cta.locL":"Ubicación","cta.hoursL":"Horario","cta.phoneL":"Teléfono",
    "foot.tag":"Escuela de surf desde 2012. Llevamos a cientos de personas a su primera ola — y entrenamos a quienes quieren ir más lejos.",
    "foot.h1":"Escuela","foot.h2":"Clases","foot.h3":"Contacto",
    "foot.l1":"Principiantes","foot.l2":"Privada","foot.l3":"Kids","foot.l4":"Coaching","foot.l5":"Cómo llegar","foot.rights":"Todos los derechos reservados",
    "wa.label":"Habla con nosotros"
  }
};

/* ── i18n ── */
function applyLang(lang){
  const dict = i18n[lang] || i18n.pt;
  document.documentElement.lang = {pt:'pt-BR',en:'en',es:'es'}[lang] || lang;
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if(dict[key] != null){
      const req = el.querySelector('.req');
      el.textContent = dict[key];
      if(req){ el.appendChild(document.createTextNode(' ')); el.appendChild(req); }
    }
  });
  document.querySelectorAll('.lang button').forEach(b => b.classList.toggle('active', b.dataset.lang === lang));
  try { localStorage.setItem('onda.lang', lang); } catch(e){}
}

/* ── Lead form → salva no CRM via fetch ── */
const ondaForm = document.getElementById('ondaForm');
if(ondaForm){
  ondaForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    if(!ondaForm.checkValidity()){ ondaForm.reportValidity(); return; }

    const nome     = document.getElementById('f-name').value.trim();
    const email    = document.getElementById('f-email').value.trim();
    const whatsapp = document.getElementById('f-phone').value.trim();

    try {
      const res = await fetch('{{ route("site.landing.lead") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ nome, email, whatsapp, origem: '{{ $origemLead }}' }),
      });

      const ok = document.getElementById('ondaSuccess');
      ok.classList.add('show');
      ondaForm.querySelectorAll('input:not([type=radio]):not([type=checkbox]), textarea, select').forEach(f => f.value = '');
      document.getElementById('f-consent').checked = false;
      setTimeout(() => ok.classList.remove('show'), 7000);
    } catch(err) {
      console.error('Erro ao enviar lead:', err);
    }
  });
}

/* ── Language switcher ── */
document.querySelectorAll('.lang button').forEach(b => {
  b.addEventListener('click', () => applyLang(b.dataset.lang));
});

const stored  = (()=>{ try{ return localStorage.getItem('onda.lang'); }catch(e){ return null; }})();
const browser = (navigator.language || 'pt').slice(0,2);
applyLang(stored || (i18n[browser] ? browser : 'pt'));
</script>

</body>
</html>
