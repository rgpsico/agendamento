<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Chat IA &middot; Agendamentos</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/css/agendamento-chat.css?v={{ filemtime(public_path('css/agendamento-chat.css')) }}"/>
<style>
.mic-btn{width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:#94a3b8;cursor:pointer;transition:all .2s}
.mic-btn:hover{background:rgba(255,255,255,.1);color:#e2e8f0}
.mic-btn svg{width:17px;height:17px;pointer-events:none}
.mic-btn.recording{background:rgba(239,68,68,.18);border-color:rgba(239,68,68,.55);color:#ef4444;animation:mic-pulse 1s ease-in-out infinite}
@keyframes mic-pulse{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)}50%{box-shadow:0 0 0 7px rgba(239,68,68,0)}}
.input-hint.recording{color:#ef4444}
</style>
</head>
<body>

<div class="shell">

  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="orb">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
      </div>
      <div>
        <span>{{ $empresa->nome ?? 'GestãoPro' }}</span>
        <small>ADMIN &middot; IA</small>
      </div>
    </div>

    <div class="nav-section">Menu</div>

    <a href="{{ url('/admin') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>

    <a href="{{ url('/admin/agendamentos/chat') }}" class="nav-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Agendamentos IA
    </a>

    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Clientes
    </a>

    <div class="sidebar-footer">
      <div class="user-chip">
        <div class="avatar">{{ mb_strtoupper(mb_substr($usuario->name ?? $usuario->nome ?? 'U', 0, 1)) }}</div>
        <div class="info">
          <b>{{ $usuario->name ?? $usuario->nome ?? 'Usuário' }}</b>
          <span>{{ $empresa->nome ?? 'Empresa' }}</span>
        </div>
      </div>
    </div>
  </aside>

  {{-- CHAT PRINCIPAL --}}
  <main class="main">

    <div class="chat-header">
      <div class="chat-header-left">
        <span class="pulse"></span>
        <div>
          <h1>Assistente de Agendamentos
            @if($empresa)
              <span style="font-weight:400;font-size:12px;color:#94a3b8;margin-left:8px">&middot; {{ $empresa->nome }}</span>
            @endif
          </h1>
          <p>
            Logado como <strong style="color:#e2e8f0">{{ $usuario->name ?? $usuario->nome ?? 'Usuário' }}</strong>
            &middot; resposta por linguagem natural
          </p>
        </div>
      </div>
      <div class="chat-header-actions" style="display:flex;align-items:center;gap:10px">

        @if($bots->isNotEmpty())
        <div style="display:flex;align-items:center;gap:8px">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <select id="botSelect" style="background:#141824;border:1px solid rgba(255,255,255,.14);color:#e2e8f0;border-radius:9px;padding:7px 12px;font-size:12px;outline:none;cursor:pointer;min-width:160px">
            @foreach($bots as $bot)
              <option value="{{ $bot->id }}">{{ $bot->nome }}{{ $bot->status ? '' : ' (inativo)' }}</option>
            @endforeach
          </select>
        </div>
        @else
          <span style="font-size:11px;color:#ef4444;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);padding:5px 10px;border-radius:8px">
            Nenhum bot encontrado
          </span>
        @endif

        <button class="icon-btn" title="Nova conversa" id="btnClear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.49"/></svg>
        </button>
        <a href="{{ url('/admin') }}" class="icon-btn" title="Voltar ao admin">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
      </div>
    </div>

    <div class="messages-wrap" id="messages">
      <div class="welcome" id="welcome">
        <div class="big-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h2>Olá! Sou seu <span>Assistente IA</span></h2>
        <p>Pergunte sobre seus agendamentos em linguagem natural.<br/>
        Tente: <em style="color:#3b82f6">"Mostre os agendamentos de hoje"</em> ou <em style="color:#06b6d4">"Quais cancelamentos tive este mês?"</em></p>
      </div>
    </div>

    <div class="sugestoes" id="sugestoes">
      <button class="sug-btn" data-msg="Agendamentos de hoje">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Hoje
      </button>
      <button class="sug-btn" data-msg="Agendamentos deste mês">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Este mês
      </button>
      <button class="sug-btn" data-msg="Cancelamentos do mês">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        Cancelamentos
      </button>
      <button class="sug-btn" data-msg="Clientes novos deste mês">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
        Clientes novos
      </button>
      <button class="sug-btn" data-msg="Agendamentos da semana">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Esta semana
      </button>
      <button class="sug-btn" data-msg="Agendamentos confirmados deste mês">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Confirmados
      </button>
    </div>

    <div class="chat-input-wrap">
      <div class="chat-input-row">
        <button class="mic-btn" id="micBtn" title="Falar (voz para texto)">
          <svg id="micIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
        </button>
        <textarea id="chatInput" placeholder="Digite ou clique no microfone para falar..." rows="1"></textarea>
        <button class="send-btn" id="sendBtn" title="Enviar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>
      <p class="input-hint" id="inputHint">Enter para enviar &middot; Shift+Enter nova linha &middot; clique no mic para falar</p>
    </div>
  </main>

  {{-- PAINEL INSIGHTS --}}
  <aside class="insights-panel">
    <div class="insights-header">
      <h2>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Insights do mês
      </h2>
      <p id="insightsMes">Carregando...</p>
    </div>

    <div class="insights-body" id="insightsBody">
      <div class="metric-card neon">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Agendamentos do mês
        </div>
        <div class="m-value" id="ins-mes">&#8212;</div>
        <div class="m-sub">total registrado</div>
      </div>

      <div class="metric-card amber">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Hoje
        </div>
        <div class="m-value" id="ins-hoje">&#8212;</div>
        <div class="m-sub">agendamentos hoje</div>
      </div>

      <div class="metric-card green">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          Clientes novos
        </div>
        <div class="m-value" id="ins-clientes">&#8212;</div>
        <div class="m-sub">este mês</div>
      </div>

      <div class="metric-card red">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          Cancelamentos
        </div>
        <div class="m-value" id="ins-cancel">&#8212;</div>
        <div class="m-sub">este mês</div>
      </div>

      <div class="metric-card purple">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Receita prevista
        </div>
        <div class="m-value" style="font-size:19px" id="ins-receita">&#8212;</div>
        <div class="m-sub">soma dos agendamentos</div>
      </div>

      <div class="horarios-section" id="horariosSection" style="display:none">
        <h4>Horários + movimentados</h4>
        <div id="horariosBody"></div>
      </div>
    </div>

    <div class="insights-refresh">
      <button class="refresh-btn" id="btnRefresh">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.34-4.25"/></svg>
        Atualizar insights
      </button>
    </div>
  </aside>

</div>

<script>
window.CHAT_CONFIG = {
  queryUrl:    {{ Js::from(route('admin.agendamentos.chat.query')) }},
  insightsUrl: {{ Js::from(route('admin.agendamentos.chat.insights')) }},
  csrfToken:   document.querySelector('meta[name=csrf-token]').content,
};
</script>
<script src="/js/agendamento-chat.js?v={{ filemtime(public_path('js/agendamento-chat.js')) }}" defer></script>
</body>
</html>
