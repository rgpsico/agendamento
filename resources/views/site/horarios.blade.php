@php
  $nome   = $site->titulo ?? 'Escola';
  $wa     = preg_replace('/\D/', '', $site->whatsapp ?? '');
  $corPri = $site->cores['primaria'] ?? '#0a5c8a';
  $logo   = $site->logo ? asset('storage/'.$site->logo) : null;
  $voltar = url('/site/'.$site->slug);
  $temHorarios = $servicos->isNotEmpty();
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Datas e horários — {{ $nome }}</title>
<meta name="description" content="Veja as datas e os horários disponíveis da {{ $nome }} e reserve pelo WhatsApp.">
<meta property="og:type" content="website">
<meta property="og:title" content="Datas e horários — {{ $nome }}">
<meta property="og:description" content="Escolha a data, veja os horários livres e reserve pelo WhatsApp.">
<meta property="og:url" content="{{ request()->url() }}">
@if($site->capa)<meta property="og:image" content="{{ asset('storage/'.$site->capa) }}">@endif
<link rel="canonical" href="{{ str_replace('http://', 'https://', request()->url()) }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --pri: {{ $corPri }};
  --pri-deep: color-mix(in srgb, {{ $corPri }} 72%, #000);
  --pri-soft: color-mix(in srgb, {{ $corPri }} 11%, #fff);
  --bg:#f7f6f2; --card:#fff; --ink:#1d1f1c; --soft:#5d625b; --line:#e3e1da;
  --wa:#1fa855;
  --display:"Bricolage Grotesque",ui-sans-serif,system-ui,sans-serif;
  --sans:"DM Sans",ui-sans-serif,system-ui,sans-serif;
}
*{box-sizing:border-box;margin:0;padding:0}
html{-webkit-text-size-adjust:100%}
body{font-family:var(--sans);background:var(--bg);color:var(--ink);line-height:1.5;-webkit-font-smoothing:antialiased;min-height:100vh}
a{color:inherit;text-decoration:none}
button{font:inherit;color:inherit;cursor:pointer;border:0;background:none}
:focus-visible{outline:3px solid color-mix(in srgb,var(--pri) 55%,#fff);outline-offset:2px;border-radius:8px}
.wrap{width:100%;max-width:980px;margin-inline:auto;padding-inline:clamp(16px,4vw,32px)}

header.top{background:var(--card);border-bottom:1px solid var(--line)}
.top-in{display:flex;align-items:center;justify-content:space-between;gap:12px;padding-block:14px}
.brand{font-family:var(--display);font-weight:700;font-size:19px;letter-spacing:-.01em;display:flex;align-items:center;gap:10px}
.brand img{height:32px;width:auto}
.brand i{width:26px;height:26px;border-radius:50%;background:conic-gradient(from 200deg,var(--pri-deep),var(--pri) 40%,var(--pri-soft) 65%,var(--pri));flex-shrink:0}
.back{font-size:14px;color:var(--soft);padding:8px 12px;border-radius:999px;border:1px solid var(--line)}
.back:hover{border-color:var(--ink);color:var(--ink)}

.hero{padding-block:clamp(26px,5vw,48px) 8px}
.hero h1{font-family:var(--display);font-weight:700;font-size:clamp(28px,6vw,44px);line-height:1.08;letter-spacing:-.02em;text-wrap:balance}
.hero p{margin-top:10px;color:var(--soft);max-width:56ch;text-wrap:pretty}

.svc{display:flex;gap:8px;flex-wrap:wrap;margin-top:20px}
.pill{padding:9px 16px;border-radius:999px;border:1px solid var(--line);background:var(--card);font-weight:600;font-size:14px}
.pill[aria-pressed=true]{background:var(--pri);border-color:var(--pri);color:#fff}

.grid{display:grid;grid-template-columns:1.1fr .9fr;gap:20px;margin-top:20px;align-items:start}
.card{background:var(--card);border:1px solid var(--line);border-radius:20px;padding:clamp(14px,3vw,22px)}

.cal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.cal-head h2{font-family:var(--display);font-size:20px}
.cal-head h2::first-letter,.slots h2::first-letter{text-transform:uppercase}
.nav{width:40px;height:40px;border-radius:50%;border:1px solid var(--line);font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center}
.nav:disabled{opacity:.3;cursor:default}
.nav:not(:disabled):hover{border-color:var(--ink)}
.dow,.days{display:grid;grid-template-columns:repeat(7,1fr);gap:4px}
.dow span{text-align:center;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--soft);padding-block:6px}
.day{aspect-ratio:1/1;min-height:42px;border-radius:12px;font-size:15px;font-weight:500;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;position:relative;color:#b7b9b2}
.day.has{color:var(--ink);background:var(--pri-soft);font-weight:600}
.day.has::after{content:"";width:5px;height:5px;border-radius:50%;background:var(--pri)}
.day.has:hover{background:color-mix(in srgb,var(--pri) 22%,#fff)}
.day.today{box-shadow:inset 0 0 0 1.5px var(--ink)}
.day.sel{background:var(--pri);color:#fff}
.day.sel::after{background:#fff}
.day.empty{visibility:hidden}
.day:disabled{cursor:default}
.legend{display:flex;gap:14px;flex-wrap:wrap;margin-top:12px;font-size:13px;color:var(--soft)}
.legend b{display:inline-block;width:10px;height:10px;border-radius:3px;background:var(--pri-soft);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--pri) 40%,#fff);margin-right:6px;vertical-align:-1px}

.slots h2{font-family:var(--display);font-size:20px;line-height:1.2}
.slots .sub{color:var(--soft);font-size:14px;margin:4px 0 14px}
.chips{display:grid;grid-template-columns:repeat(auto-fill,minmax(128px,1fr));gap:8px}
.chip{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:11px 8px;border-radius:14px;border:1.5px solid color-mix(in srgb,var(--pri) 45%,#fff);background:#fff;font-weight:600;min-height:52px}
.chip small{font-weight:400;font-size:12px;color:var(--soft)}
a.chip:hover{background:var(--pri);border-color:var(--pri);color:#fff}
a.chip:hover small{color:rgba(255,255,255,.85)}
.hint{margin-top:14px;font-size:13px;color:var(--soft)}
.none{padding:18px;border-radius:14px;background:var(--bg);color:var(--soft);font-size:15px}

.cta{display:inline-flex;align-items:center;gap:8px;margin-top:14px;padding:12px 20px;border-radius:999px;background:var(--wa);color:#fff;font-weight:600}
.cta:hover{filter:brightness(.95)}

.share{margin-top:20px;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.share button,.share a{padding:11px 18px;border-radius:999px;border:1px solid var(--line);background:var(--card);font-weight:600;font-size:14px}
.share button:hover,.share a:hover{border-color:var(--ink)}
.share .ok{color:var(--pri-deep);font-size:14px}
.foot{padding-block:32px 48px;color:var(--soft);font-size:13px}

@media(max-width:760px){.grid{grid-template-columns:1fr}}
@media(max-width:380px){.day{min-height:38px;font-size:14px}}
</style>
@include('site._partials.tracking')
</head>
<body>
<header class="top">
  <div class="wrap top-in">
    <a class="brand" href="{{ $voltar }}">
      @if($logo)<img src="{{ $logo }}" alt="{{ $nome }}">@else<i aria-hidden="true"></i><span>{{ $nome }}</span>@endif
    </a>
    <a class="back" href="{{ $voltar }}">← Site</a>
  </div>
</header>

<main class="wrap">
  <section class="hero">
    <h1>Datas e horários disponíveis</h1>
    <p>Escolha o dia no calendário para ver os horários livres{{ $wa ? ' e reserve direto pelo WhatsApp' : '' }}.</p>
  </section>

@if($temHorarios)
  <div class="svc" id="svc" role="group" aria-label="Tipo de aula"></div>

  <div class="grid">
    <section class="card" aria-label="Calendário">
      <div class="cal-head">
        <button class="nav" id="prev" aria-label="Mês anterior">‹</button>
        <h2 id="mes"></h2>
        <button class="nav" id="next" aria-label="Próximo mês">›</button>
      </div>
      <div class="dow" aria-hidden="true"><span>Dom</span><span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span></div>
      <div class="days" id="days"></div>
      <div class="legend"><span><b></b>Dia com horários livres</span></div>
    </section>

    <section class="card slots" id="slots" aria-live="polite"></section>
  </div>
@else
  <div class="card" style="margin-top:20px">
    <h2 style="font-family:var(--display);font-size:22px">Horários em breve</h2>
    <p class="none" style="margin-top:12px">Ainda não há horários publicados nesta página. Fale com a gente para combinar o melhor dia.</p>
    @if($wa)<a class="cta" href="https://wa.me/{{ $wa }}?text={{ rawurlencode('Oi! Quero saber os horários de aula da '.$nome) }}" target="_blank" rel="noopener">Chamar no WhatsApp</a>@endif
  </div>
@endif

  <div class="share">
    <button type="button" id="copiar">Copiar link desta página</button>
    <span class="ok" id="copiado" role="status"></span>
  </div>
</main>

<footer class="wrap foot">© {{ date('Y') }} {{ $nome }} · Horários sujeitos a confirmação.</footer>

@if($temHorarios)
<script>
const AGENDA = @json($agenda);
const SERVICOS = @json($servicos);
const WA = @json($wa);
const NOME = @json($nome);
const HOJE = @json($hoje), ATE = @json($ate);

const $ = (id) => document.getElementById(id);
const MESES = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
const DIAS = ['domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado'];
const iso = (y, m, d) => `${y}-${String(m + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
const parse = (s) => { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d); };

let svc = SERVICOS[0].id;
const slotsDe = (dia) => (AGENDA[dia] && AGENDA[dia][svc]) || [];
const primeiroDia = () => Object.keys(AGENDA).sort().find((d) => slotsDe(d).length) || null;

let sel = primeiroDia();
let ref = parse(sel || HOJE); ref.setDate(1);

function desenhaServicos() {
  const box = $('svc');
  box.innerHTML = '';
  if (SERVICOS.length < 2) return;
  SERVICOS.forEach((s) => {
    const b = document.createElement('button');
    b.type = 'button'; b.className = 'pill'; b.textContent = s.titulo;
    b.setAttribute('aria-pressed', s.id === svc);
    b.onclick = () => {
      svc = s.id;
      if (!sel || !slotsDe(sel).length) { sel = primeiroDia(); if (sel) { ref = parse(sel); ref.setDate(1); } }
      desenha();
    };
    box.appendChild(b);
  });
}

function desenhaCalendario() {
  const y = ref.getFullYear(), m = ref.getMonth();
  $('mes').textContent = `${MESES[m]} de ${y}`;
  const hoje = parse(HOJE), ate = parse(ATE);
  $('prev').disabled = (y === hoje.getFullYear() && m === hoje.getMonth()) || ref < new Date(hoje.getFullYear(), hoje.getMonth(), 1);
  $('next').disabled = (y === ate.getFullYear() && m === ate.getMonth()) || ref > ate;

  const box = $('days');
  box.innerHTML = '';
  const lead = new Date(y, m, 1).getDay();
  for (let i = 0; i < lead; i++) { const e = document.createElement('span'); e.className = 'day empty'; box.appendChild(e); }
  const total = new Date(y, m + 1, 0).getDate();
  for (let d = 1; d <= total; d++) {
    const dia = iso(y, m, d);
    const livre = slotsDe(dia).length > 0;
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'day' + (livre ? ' has' : '') + (dia === HOJE ? ' today' : '') + (dia === sel ? ' sel' : '');
    b.textContent = d;
    b.disabled = !livre;
    b.setAttribute('aria-label', `${d} de ${MESES[m]}${livre ? ', ' + slotsDe(dia).length + ' horários livres' : ', sem horários'}`);
    if (dia === sel) b.setAttribute('aria-current', 'date');
    b.onclick = () => { sel = dia; desenha(); if (matchMedia('(max-width:760px)').matches) $('slots').scrollIntoView({ behavior: 'smooth', block: 'start' }); };
    box.appendChild(b);
  }
}

function desenhaHorarios() {
  const box = $('slots');
  const servico = SERVICOS.find((s) => s.id === svc);
  if (!sel || !slotsDe(sel).length) {
    box.innerHTML = '<h2>Escolha um dia</h2><p class="sub">Toque em uma data destacada no calendário para ver os horários.</p>';
    return;
  }
  const d = parse(sel), lista = slotsDe(sel);
  const titulo = `${DIAS[d.getDay()]}, ${d.getDate()} de ${MESES[d.getMonth()]}`;
  const dataBr = `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
  box.innerHTML = `<h2>${titulo}</h2><p class="sub">${servico.titulo} · ${lista.length} ${lista.length === 1 ? 'horário livre' : 'horários livres'}</p><div class="chips"></div>`;
  const chips = box.querySelector('.chips');
  lista.forEach((s) => {
    const txt = `Oi! Quero reservar ${servico.titulo} no dia ${dataBr} às ${s.i} (${NOME}).`;
    const el = document.createElement(WA ? 'a' : 'div');
    el.className = 'chip';
    el.innerHTML = `${s.i}<small>até ${s.f}</small>`;
    if (WA) { el.href = `https://wa.me/${WA}?text=${encodeURIComponent(txt)}`; el.target = '_blank'; el.rel = 'noopener'; el.setAttribute('aria-label', `Reservar às ${s.i} pelo WhatsApp`); }
    chips.appendChild(el);
  });
  if (WA) box.insertAdjacentHTML('beforeend', '<p class="hint">Toque no horário para reservar pelo WhatsApp. A reserva é confirmada pela equipe.</p>');
}

function desenha() { desenhaServicos(); desenhaCalendario(); desenhaHorarios(); }
$('prev').onclick = () => { ref = new Date(ref.getFullYear(), ref.getMonth() - 1, 1); desenhaCalendario(); };
$('next').onclick = () => { ref = new Date(ref.getFullYear(), ref.getMonth() + 1, 1); desenhaCalendario(); };
desenha();
</script>
@endif
<script>
document.getElementById('copiar').addEventListener('click', async () => {
  const url = location.href.split('#')[0], msg = document.getElementById('copiado');
  try {
    if (navigator.share && matchMedia('(pointer:coarse)').matches) { await navigator.share({ title: document.title, url }); return; }
    await navigator.clipboard.writeText(url);
    msg.textContent = 'Link copiado!';
  } catch (e) {
    const t = document.createElement('input'); t.value = url; document.body.appendChild(t); t.select();
    try { document.execCommand('copy'); msg.textContent = 'Link copiado!'; } catch (_) { msg.textContent = url; }
    t.remove();
  }
  setTimeout(() => msg.textContent = '', 3000);
});
</script>
@if($bot){!! $bot->widgetSnippet() !!}@endif
</body>
</html>
