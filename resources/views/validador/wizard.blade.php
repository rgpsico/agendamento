@php
    $nichosJs = [];
    foreach (\App\Models\LeadQualificacao::NICHOS as $chave => $n) {
        $nichosJs[] = ['v' => $chave, 't' => $n['rotulo'], 'e' => $n['emoji']];
    }
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Conta um pouco sobre você — {{ $marca }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --a: {{ $corA }}; --b: {{ $corB }}; --ink: #0f172a; --soft: #64748b; --line: #e2e8f0; }
    html, body { height: 100%; }
    body {
      font-family: 'Poppins', system-ui, sans-serif; color: var(--ink);
      background: linear-gradient(160deg, var(--a), var(--b)); min-height: 100vh;
      display: flex; align-items: center; justify-content: center; padding: 16px;
    }
    .card {
      width: 100%; max-width: 520px; background: #fff; border-radius: 22px; padding: 26px 22px 22px;
      box-shadow: 0 24px 60px rgba(0,0,0,.25);
    }
    .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; font-weight: 700; }
    .brand img { height: 34px; width: auto; }
    .brand .emoji { font-size: 1.6rem; }
    .progress { height: 6px; background: var(--line); border-radius: 99px; overflow: hidden; margin-bottom: 8px; }
    .progress > i { display: block; height: 100%; width: 0; background: var(--a); transition: width .25s ease; }
    .count { font-size: .75rem; color: var(--soft); margin-bottom: 18px; }
    h1 { font-size: 1.35rem; line-height: 1.25; margin-bottom: 6px; font-weight: 700; }
    .hint { font-size: .85rem; color: var(--soft); margin-bottom: 16px; }
    .opts { display: flex; flex-direction: column; gap: 10px; }
    .opt {
      display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; cursor: pointer;
      min-height: 56px; padding: 12px 14px; border-radius: 14px; border: 2px solid var(--line);
      background: #fff; font: inherit; color: var(--ink); transition: border-color .12s, background .12s;
    }
    .opt:hover, .opt:focus-visible { border-color: var(--a); outline: none; }
    .opt.sel { border-color: var(--a); background: color-mix(in srgb, var(--a) 9%, #fff); }
    .opt .em { font-size: 1.5rem; flex: none; }
    .opt strong { display: block; font-size: .98rem; }
    .opt small { display: block; color: var(--soft); font-size: .8rem; margin-top: 2px; }
    input[type=text], input[type=tel], textarea {
      width: 100%; font: inherit; font-size: 1.05rem; padding: 14px; border-radius: 14px;
      border: 2px solid var(--line); color: var(--ink); background: #fff;
    }
    textarea { min-height: 110px; resize: vertical; }
    input:focus, textarea:focus { outline: none; border-color: var(--a); }
    .err { color: #dc2626; font-size: .85rem; min-height: 1.2em; margin-top: 8px; }
    .nav { display: flex; gap: 10px; margin-top: 16px; }
    .btn { flex: 1; min-height: 52px; border-radius: 14px; border: none; font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; }
    .btn-main { background: var(--a); color: #fff; }
    .btn-main:disabled { opacity: .55; cursor: not-allowed; }
    .btn-ghost { flex: 0 0 auto; padding: 0 18px; background: #fff; border: 2px solid var(--line); color: var(--soft); }
    .done { text-align: center; padding: 14px 0 6px; }
    .done .big { font-size: 3rem; }
    [hidden] { display: none !important; }
  </style>
</head>
<body>
  <main class="card">
    <div class="brand">
      @if($logo)<img src="{{ $logo }}" alt="{{ $marca }}">@else<span class="emoji">{{ $emoji }}</span>@endif
      <span>{{ $marca }}</span>
    </div>

    <div id="wizard">
      <div class="progress"><i id="bar"></i></div>
      <div class="count" id="count"></div>
      <div id="step"></div>
      <p class="err" id="err" role="alert"></p>
      <div class="nav">
        <button type="button" class="btn btn-ghost" id="back" hidden>← Voltar</button>
        <button type="button" class="btn btn-main" id="next">Continuar</button>
      </div>
    </div>

    <div class="done" id="done" hidden>
      <div class="big">🎉</div>
      <h1>Obrigado!</h1>
      <p class="hint">Recebi suas respostas e já vou entrar em contato.</p>
    </div>
  </main>

<script>
  const NICHOS = @json($nichosJs);
  const GOOGLE = [
    {v:'sim', t:'Sim, já tenho', e:'✅'},
    {v:'nao', t:'Não tenho', e:'❌'},
    {v:'nao_sei', t:'Não sei o que é', e:'🤔', s:'Sem problema, eu te explico.'},
  ];
  const CAMPANHA = [
    {v:'nunca', t:'Nunca fiz', e:'🙂', s:'Nunca anunciei na internet.'},
    {v:'sozinho', t:'Já fiz por conta própria', e:'🛠️', s:'Impulsionei post ou criei anúncio sozinho(a).'},
    {v:'agencia', t:'Já contratei alguém', e:'🤝', s:'Agência ou profissional de tráfego.'},
    {v:'sempre', t:'Faço campanha sempre', e:'🚀'},
  ];
  const NIVEL = [
    {v:'basico', t:'Só o básico', e:'📱', s:'Uso WhatsApp e redes pessoais, mexo pouco com computador.'},
    {v:'intermediario', t:'Me viro bem', e:'💻', s:'Tenho Instagram da empresa e posto de vez em quando.'},
    {v:'avancado', t:'Sou bem ativo(a) digitalmente', e:'⚡', s:'Uso anúncios, métricas e várias ferramentas.'},
  ];

  const STEPS = [
    {k:'nome', type:'text', q:'Qual é o seu nome?', hint:'Como posso te chamar?', ph:'Seu nome', req:true, auto:'name'},
    {k:'cidade', type:'text', q:'De onde você é?', hint:'Cidade e estado.', ph:'Ex: Rio de Janeiro, RJ', req:true, auto:'address-level2'},
    {k:'nicho', type:'choice', q:'Qual é o nicho da sua empresa?', opts:NICHOS},
    {k:'nicho_outro', type:'text', q:'Qual é o seu nicho?', ph:'Ex: Fotografia, Academia…', req:true, onlyIf:a => a.nicho === 'outro'},
    {k:'tem_google_meu_negocio', type:'choice', q:'Você já tem o Google Meu Negócio?', hint:'É o perfil da empresa que aparece no Google e no Maps.', opts:GOOGLE},
    {k:'ja_fez_campanha', type:'choice', q:'Você já fez alguma campanha de anúncio?', hint:'No Instagram, Facebook ou Google.', opts:CAMPANHA},
    {k:'nivel_digital', type:'choice', q:'Como você se considera no digital?', hint:'Pode ser sincero(a), isso me ajuda a te atender melhor.', opts:NIVEL},
    {k:'whatsapp', type:'tel', q:'Qual é o seu WhatsApp?', hint:'Opcional, só pra eu te chamar se precisar.', ph:'(21) 99999-9999', req:false, auto:'tel'},
    {k:'observacao', type:'textarea', q:'Quer me contar mais alguma coisa?', hint:'Opcional.', ph:'Ex: o que você espera do sistema…', req:false},
  ];

  const answers = {
    nome: @json($nome ?? ''),
    whatsapp: @json($whatsapp ?? ''),
  };
  let idx = 0;

  const $ = id => document.getElementById(id);
  const visibles = () => STEPS.filter(s => !s.onlyIf || s.onlyIf(answers));

  function render() {
    const list = visibles();
    const s = list[idx];
    $('count').textContent = `Pergunta ${idx + 1} de ${list.length}`;
    $('bar').style.width = ((idx + 1) / list.length * 100) + '%';
    $('back').hidden = idx === 0;
    $('next').textContent = idx === list.length - 1 ? 'Enviar' : 'Continuar';
    $('next').hidden = s.type === 'choice';
    $('err').textContent = '';

    let html = `<h1>${s.q}</h1>` + (s.hint ? `<p class="hint">${s.hint}</p>` : '<div style="height:12px"></div>');
    if (s.type === 'choice') {
      html += '<div class="opts">' + s.opts.map(o =>
        `<button type="button" class="opt ${answers[s.k] === o.v ? 'sel' : ''}" data-v="${o.v}">
           <span class="em">${o.e}</span><span><strong>${o.t}</strong>${o.s ? `<small>${o.s}</small>` : ''}</span>
         </button>`).join('') + '</div>';
    } else if (s.type === 'textarea') {
      html += `<textarea id="f" maxlength="1000" placeholder="${s.ph || ''}"></textarea>`;
    } else {
      html += `<input id="f" type="${s.type}" ${s.type === 'tel' ? 'inputmode="tel"' : ''} maxlength="${s.type === 'tel' ? 20 : 120}" placeholder="${s.ph || ''}" autocomplete="${s.auto || 'off'}">`;
    }
    $('step').innerHTML = html;

    const f = $('f');
    if (f) { f.value = answers[s.k] || ''; if (s.type === 'tel') f.addEventListener('input', maskTel); setTimeout(() => f.focus(), 50); f.addEventListener('keydown', e => { if (e.key === 'Enter' && s.type !== 'textarea') { e.preventDefault(); avancar(); } }); }
    document.querySelectorAll('.opt').forEach(b => b.addEventListener('click', () => { answers[s.k] = b.dataset.v; avancar(true); }));
  }

  function maskTel() {
    let v = this.value.replace(/\D/g, '').slice(0, 11);
    if (v.length > 6) v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
    else if (v.length) v = v.replace(/^(\d{0,2})/, '($1');
    this.value = v;
  }

  function avancar(fromChoice) {
    const list = visibles();
    const s = list[idx];
    if (!fromChoice && s.type !== 'choice') {
      const val = ($('f').value || '').trim();
      if (s.req && !val) { $('err').textContent = 'Preencha pra continuar.'; return; }
      answers[s.k] = val;
    }
    if (idx < visibles().length - 1) { idx++; render(); } else { enviar(); }
  }

  async function enviar() {
    $('next').disabled = true; $('next').textContent = 'Enviando…';
    try {
      const res = await fetch(@json(route('validador.responder', $q->token)), {
        method: 'POST',
        headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body: JSON.stringify(answers),
      });
      if (!res.ok) throw new Error((await res.json().catch(() => ({}))).message || 'Não foi possível enviar.');
      $('wizard').hidden = true; $('done').hidden = false;
    } catch (e) {
      $('err').textContent = e.message + ' Tente de novo.';
      $('next').disabled = false; $('next').textContent = 'Enviar';
    }
  }

  $('next').addEventListener('click', () => avancar());
  $('back').addEventListener('click', () => { if (idx > 0) { idx--; render(); } });
  render();
</script>
</body>
</html>
