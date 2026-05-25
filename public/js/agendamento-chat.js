/* =========================================================
   Chat IA – Agendamentos
   Variáveis injetadas pelo Blade antes deste script:
     window.CHAT_CONFIG = { queryUrl, insightsUrl, csrfToken }
   ========================================================= */
(function () {
  'use strict';

  const { queryUrl, insightsUrl, csrfToken } = window.CHAT_CONFIG;

  const messagesEl  = document.getElementById('messages');
  const inputEl     = document.getElementById('chatInput');
  const sendBtn     = document.getElementById('sendBtn');
  const sugestoesEl = document.getElementById('sugestoes');
  const welcomeEl   = document.getElementById('welcome');
  const botSelect   = document.getElementById('botSelect');

  let conversationId = null;

  // ── Troca de bot reinicia a conversa ─────────────────────────────────
  if (botSelect) {
    botSelect.addEventListener('change', function () {
      conversationId = null;
      messagesEl.innerHTML = '';
      messagesEl.appendChild(welcomeEl);
      welcomeEl.style.display = '';
      var nome = botSelect.options[botSelect.selectedIndex].text;
      appendSystemMsg('Bot alterado para <strong>' + escHtml(nome) + '</strong>. Nova conversa iniciada.');
    });
  }

  // ── Auto-resize textarea ──────────────────────────────────────────────
  inputEl.addEventListener('input', function () {
    inputEl.style.height = 'auto';
    inputEl.style.height = Math.min(inputEl.scrollHeight, 120) + 'px';
  });
  inputEl.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviar(); }
  });
  sendBtn.addEventListener('click', enviar);

  // ── Sugestões iniciais ────────────────────────────────────────────────
  document.querySelectorAll('.sug-btn').forEach(function (btn) {
    btn.addEventListener('click', function () { inputEl.value = btn.dataset.msg; enviar(); });
  });

  // ── Limpar conversa ───────────────────────────────────────────────────
  document.getElementById('btnClear').addEventListener('click', function () {
    conversationId = null;
    messagesEl.innerHTML = '';
    messagesEl.appendChild(welcomeEl);
    welcomeEl.style.display = '';
    sugestoesEl.innerHTML = '';
    renderSugestoes([
      'Agendamentos de hoje',
      'Agendamentos deste mes',
      'Quais cancelamentos tive?',
      'Cadastre um aluno novo',
      'Verificar disponibilidade para amanha',
      'Me mostre os servicos disponiveis',
    ]);
  });

  // ── Refresh insights ──────────────────────────────────────────────────
  document.getElementById('btnRefresh').addEventListener('click', function () {
    var btn = document.getElementById('btnRefresh');
    btn.querySelector('svg').classList.add('spin');
    fetch(insightsUrl, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
      .then(function (r) { return r.json(); })
      .then(function (data) { if (data.insights) updateInsights(data.insights); })
      .catch(function () {})
      .finally(function () { btn.querySelector('svg').classList.remove('spin'); });
  });

  // ── ENVIAR MENSAGEM ───────────────────────────────────────────────────
  function enviar() {
    var texto = inputEl.value.trim();
    if (!texto || sendBtn.disabled) return;

    welcomeEl.style.display = 'none';
    sugestoesEl.innerHTML = '';

    appendUser(texto);
    inputEl.value = '';
    inputEl.style.height = 'auto';

    var thinkId = appendThinking();
    sendBtn.disabled = true;

    var body = { mensagem: texto };
    if (conversationId) body.conversation_id = conversationId;
    if (botSelect && botSelect.value) body.bot_id = parseInt(botSelect.value);

    fetch(queryUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: JSON.stringify(body),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.conversation_id) conversationId = data.conversation_id;
        removeThinking(thinkId);
        appendAI(data.reply || 'Sem resposta.');
        if (data.insights) updateInsights(data.insights);
        renderSugestoes([
          'Agendar outro horario',
          'Ver agendamentos de hoje',
          'Cadastrar novo aluno',
          'Listar servicos disponiveis',
          'Ver cancelamentos do mes',
        ]);
      })
      .catch(function () {
        removeThinking(thinkId);
        appendError('Ops! Nao consegui processar. Tente novamente.');
      })
      .finally(function () {
        sendBtn.disabled = false;
        inputEl.focus();
      });
  }

  // ── Helpers DOM ───────────────────────────────────────────────────────
  function appendUser(texto) {
    var d = document.createElement('div');
    d.className = 'msg-user';
    d.innerHTML = '<div class="bubble">' + escHtml(texto) + '</div>';
    messagesEl.appendChild(d);
    scrollBottom();
  }

  function appendThinking() {
    var id = 'th-' + Date.now();
    var d  = document.createElement('div');
    d.className = 'thinking';
    d.id = id;
    d.innerHTML =
      '<div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>' +
      '<div class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>' +
      '<span>IA pensando...</span>';
    messagesEl.appendChild(d);
    scrollBottom();
    return id;
  }

  function removeThinking(id) {
    var el = document.getElementById(id);
    if (el) el.remove();
  }

  function markdownToHtml(md) {
    return escHtml(md)
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.+?)\*/g, '<em>$1</em>')
      .replace(/^- (.+)$/gm, '<li>$1</li>')
      .replace(/\n\n/g, '</p><p>')
      .replace(/\n/g, '<br>');
  }

  function appendAI(texto) {
    var d = document.createElement('div');
    d.className = 'msg-ai';
    var html = markdownToHtml(texto);
    d.innerHTML =
      '<div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>' +
      '<div class="body">' +
        '<div class="label">Assistente IA <span class="badge">DeepSeek</span></div>' +
        '<div class="ai-bubble"><p>' + html + '</p></div>' +
      '</div>';
    messagesEl.appendChild(d);
    scrollBottom();
  }

  function appendError(msg) {
    var d = document.createElement('div');
    d.className = 'msg-ai';
    d.innerHTML =
      '<div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>' +
      '<div class="body">' +
        '<div class="label" style="color:var(--red)">Erro</div>' +
        '<div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:12px;padding:12px 16px;font-size:13px;color:#fca5a5">' + escHtml(msg) + '</div>' +
      '</div>';
    messagesEl.appendChild(d);
    scrollBottom();
  }

  function appendSystemMsg(html) {
    var d = document.createElement('div');
    d.style.cssText = 'text-align:center;padding:6px 0';
    d.innerHTML = '<span style="font-size:11px;color:var(--text-3);background:var(--glass);border:1px solid var(--border);border-radius:20px;padding:4px 12px;display:inline-block">' + html + '</span>';
    messagesEl.appendChild(d);
    scrollBottom();
  }

  function renderSugestoes(lista) {
    sugestoesEl.innerHTML = lista.slice(0, 6).map(function (s) {
      return '<button class="sug-btn" data-msg="' + escHtml(s) + '">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' +
        escHtml(s) + '</button>';
    }).join('');
    sugestoesEl.querySelectorAll('.sug-btn').forEach(function (btn) {
      btn.addEventListener('click', function () { inputEl.value = btn.dataset.msg; enviar(); });
    });
  }

  function scrollBottom() {
    setTimeout(function () { messagesEl.scrollTop = messagesEl.scrollHeight; }, 50);
  }

  function escHtml(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ── UPDATE INSIGHTS ───────────────────────────────────────────────────
  function updateInsights(ins) {
    document.getElementById('ins-mes').textContent      = ins.agendamentos_mes  || '0';
    document.getElementById('ins-hoje').textContent     = ins.agendamentos_hoje || '0';
    document.getElementById('ins-clientes').textContent = ins.clientes_novos    || '0';
    document.getElementById('ins-cancel').textContent   = ins.cancelamentos     || '0';
    document.getElementById('ins-receita').textContent  = ins.receita_prevista  || 'R$ 0,00';

    document.getElementById('insightsMes').textContent =
      new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });

    var horarios = ins.horarios_top || {};
    var keys = Object.keys(horarios);
    if (keys.length) {
      var maxVal = Math.max.apply(null, Object.values(horarios));
      document.getElementById('horariosSection').style.display = '';
      document.getElementById('horariosBody').innerHTML = keys.map(function (h) {
        var pct = maxVal > 0 ? Math.round((horarios[h] / maxVal) * 100) : 0;
        return '<div class="horario-bar">' +
          '<div class="hb-top"><span class="hora">' + h + '</span><span class="qtd">' + horarios[h] + ' aulas</span></div>' +
          '<div class="bar"><div class="fill" style="width:' + pct + '%"></div></div>' +
          '</div>';
      }).join('');
    }
  }

  // ── Carrega insights ao abrir ─────────────────────────────────────────
  fetch(insightsUrl, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
    .then(function (r) { return r.json(); })
    .then(function (data) { if (data.insights) updateInsights(data.insights); })
    .catch(function () {});

})();
