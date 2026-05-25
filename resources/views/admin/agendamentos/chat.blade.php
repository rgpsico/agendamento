<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Chat IA · Agendamentos</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
/* ══════════════════════════════════════════════════════
   RESET & VARS
══════════════════════════════════════════════════════ */
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:       #0a0d14;
  --bg-2:     #0f1320;
  --bg-3:     #141824;
  --glass:    rgba(255,255,255,.04);
  --glass-2:  rgba(255,255,255,.07);
  --border:   rgba(255,255,255,.08);
  --border-2: rgba(255,255,255,.14);
  --neon:     #3b82f6;
  --neon-glow:rgba(59,130,246,.35);
  --neon-soft:rgba(59,130,246,.12);
  --teal:     #06b6d4;
  --purple:   #8b5cf6;
  --green:    #10b981;
  --red:      #ef4444;
  --amber:    #f59e0b;
  --text:     #e2e8f0;
  --text-2:   #94a3b8;
  --text-3:   #475569;
  --sans:     "Inter", system-ui, sans-serif;
  --mono:     "JetBrains Mono", monospace;
  --r:        14px;
  --r-l:      20px;
  --sidebar:  220px;
  --right:    280px;
}
html,body{height:100%;font-family:var(--sans);background:var(--bg);color:var(--text);overflow:hidden;font-size:14px;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
button{font:inherit;cursor:pointer;border:0;background:none;color:inherit}
img{display:block;max-width:100%}
::-webkit-scrollbar{width:4px;height:4px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border-2);border-radius:4px}

/* ══════════════════════════════════════════════════════
   LAYOUT
══════════════════════════════════════════════════════ */
.shell{display:grid;grid-template-columns:var(--sidebar) 1fr var(--right);height:100vh;overflow:hidden}

/* ══════════════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════════════ */
.sidebar{
  background:var(--bg-2);
  border-right:1px solid var(--border);
  display:flex;flex-direction:column;
  padding:0;overflow:hidden;
}
.sidebar-logo{
  padding:22px 20px 18px;
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:10px;
}
.sidebar-logo .orb{
  width:30px;height:30px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,var(--neon),var(--purple));
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 0 16px var(--neon-glow);
}
.sidebar-logo .orb svg{width:16px;height:16px}
.sidebar-logo span{font-weight:700;font-size:15px;letter-spacing:-.02em}
.sidebar-logo small{display:block;font-size:10px;color:var(--text-2);font-family:var(--mono);letter-spacing:.06em;margin-top:1px}

.nav-section{padding:18px 12px 8px;font-family:var(--mono);font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:var(--text-3)}
.nav-item{
  display:flex;align-items:center;gap:10px;
  padding:10px 14px;margin:1px 8px;
  border-radius:10px;font-size:13px;font-weight:500;color:var(--text-2);
  transition:background .15s,color .15s;cursor:pointer;
  text-decoration:none;
}
.nav-item:hover{background:var(--glass-2);color:var(--text)}
.nav-item.active{background:var(--neon-soft);color:var(--neon);border:1px solid rgba(59,130,246,.2)}
.nav-item svg{width:16px;height:16px;flex-shrink:0;opacity:.8}
.nav-item.active svg{opacity:1}

.sidebar-footer{margin-top:auto;padding:14px 12px;border-top:1px solid var(--border)}
.user-chip{display:flex;align-items:center;gap:10px;padding:10px 10px;border-radius:10px;background:var(--glass);border:1px solid var(--border)}
.user-chip .avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--neon),var(--teal));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:700;color:#fff}
.user-chip .info{flex:1;min-width:0}
.user-chip .info b{display:block;font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-chip .info span{display:block;font-size:10px;color:var(--text-2)}

/* ══════════════════════════════════════════════════════
   MAIN CHAT
══════════════════════════════════════════════════════ */
.main{display:flex;flex-direction:column;overflow:hidden;position:relative;background:var(--bg)}

/* Glow de fundo decorativo */
.main::before{
  content:"";position:absolute;left:50%;top:-120px;transform:translateX(-50%);
  width:600px;height:600px;border-radius:50%;
  background:radial-gradient(circle,rgba(59,130,246,.06) 0%,transparent 70%);
  pointer-events:none;z-index:0
}

.chat-header{
  padding:16px 24px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
  background:rgba(10,13,20,.8);backdrop-filter:blur(16px);
  position:relative;z-index:2;flex-shrink:0;
}
.chat-header-left{display:flex;align-items:center;gap:12px}
.chat-header-left .pulse{width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0;position:relative}
.chat-header-left .pulse::after{content:"";position:absolute;inset:-3px;border-radius:50%;background:rgba(16,185,129,.3);animation:pulse 2s ease-out infinite}
@keyframes pulse{0%{transform:scale(1);opacity:.8}100%{transform:scale(2);opacity:0}}
.chat-header-left h1{font-size:15px;font-weight:600;letter-spacing:-.01em}
.chat-header-left p{font-size:11px;color:var(--text-2);font-family:var(--mono)}
.chat-header-actions{display:flex;gap:8px}
.icon-btn{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;background:var(--glass);border:1px solid var(--border);transition:background .15s,border-color .15s;color:var(--text-2)}
.icon-btn:hover{background:var(--glass-2);border-color:var(--border-2);color:var(--text)}
.icon-btn svg{width:15px;height:15px}

/* ── Messages ── */
.messages-wrap{flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:20px;position:relative;z-index:1}

/* Welcome */
.welcome{text-align:center;padding:40px 20px 20px;max-width:480px;margin:0 auto}
.welcome .big-icon{width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--neon),var(--purple));display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 0 40px var(--neon-glow)}
.welcome .big-icon svg{width:32px;height:32px}
.welcome h2{font-size:22px;font-weight:700;letter-spacing:-.02em;margin-bottom:10px}
.welcome h2 span{background:linear-gradient(90deg,var(--neon),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.welcome p{color:var(--text-2);font-size:13px;line-height:1.65}

/* ── Mensagem usuário ── */
.msg-user{display:flex;justify-content:flex-end;gap:10px}
.msg-user .bubble{
  background:linear-gradient(135deg,var(--neon),rgba(59,130,246,.8));
  color:#fff;padding:12px 16px;border-radius:16px 16px 4px 16px;
  max-width:72%;font-size:14px;line-height:1.55;
  box-shadow:0 4px 20px rgba(59,130,246,.25)
}

/* ── Mensagem IA ── */
.msg-ai{display:flex;gap:10px;align-items:flex-start}
.msg-ai .ai-avatar{width:32px;height:32px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,var(--bg-3),rgba(139,92,246,.5));
  border:1px solid rgba(139,92,246,.3);
  display:flex;align-items:center;justify-content:center;margin-top:2px}
.msg-ai .ai-avatar svg{width:16px;height:16px}
.msg-ai .body{flex:1}
.msg-ai .label{font-size:11px;color:var(--text-2);font-family:var(--mono);margin-bottom:8px;display:flex;align-items:center;gap:6px}
.msg-ai .label .badge{background:var(--neon-soft);color:var(--neon);padding:2px 7px;border-radius:5px;font-size:10px;letter-spacing:.04em}

/* ── Thinking ── */
.thinking{display:flex;gap:10px;align-items:center}
.thinking .ai-avatar{width:32px;height:32px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,var(--bg-3),rgba(139,92,246,.5));
  border:1px solid rgba(139,92,246,.3);
  display:flex;align-items:center;justify-content:center}
.thinking .ai-avatar svg{width:16px;height:16px}
.thinking .dots{display:flex;gap:5px;align-items:center;padding:12px 16px;
  background:var(--glass);border:1px solid var(--border);border-radius:12px}
.thinking .dot{width:6px;height:6px;border-radius:50%;background:var(--neon);animation:blink 1.4s ease-in-out infinite}
.thinking .dot:nth-child(2){animation-delay:.2s}
.thinking .dot:nth-child(3){animation-delay:.4s}
@keyframes blink{0%,80%,100%{opacity:.2;transform:scale(.9)}40%{opacity:1;transform:scale(1)}}
.thinking span{font-size:12px;color:var(--text-2);font-family:var(--mono);margin-left:4px}

/* ── Tabela resultado ── */
.result-wrap{background:var(--glass);border:1px solid var(--border);border-radius:var(--r-l);overflow:hidden;margin-top:4px}
.result-head{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.result-head .r-title{font-weight:600;font-size:13px}
.result-head .r-count{font-family:var(--mono);font-size:11px;color:var(--text-2)}
.result-head .r-count strong{color:var(--neon)}
.result-table-wrap{overflow-x:auto;max-height:360px;overflow-y:auto}
table.rt{width:100%;border-collapse:collapse;font-size:12.5px}
table.rt thead tr{background:rgba(255,255,255,.03)}
table.rt th{padding:10px 14px;text-align:left;font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--text-3);white-space:nowrap;border-bottom:1px solid var(--border)}
table.rt td{padding:11px 14px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
table.rt tr:last-child td{border-bottom:none}
table.rt tbody tr{transition:background .12s}
table.rt tbody tr:hover{background:var(--glass-2)}
.status-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.06em}
.status-chip.agendado{background:rgba(59,130,246,.15);color:var(--neon)}
.status-chip.confirmado{background:rgba(16,185,129,.15);color:var(--green)}
.status-chip.cancelado{background:rgba(239,68,68,.15);color:var(--red)}
.status-chip.pendente{background:rgba(245,158,11,.15);color:var(--amber)}
.wa-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 9px;border-radius:7px;background:rgba(37,211,102,.12);color:#25d366;font-size:11px;font-weight:600;transition:background .15s}
.wa-btn:hover{background:rgba(37,211,102,.22)}
.wa-btn svg{width:13px;height:13px}

.empty-state{padding:40px;text-align:center;color:var(--text-2)}
.empty-state svg{width:40px;height:40px;margin:0 auto 12px;opacity:.4}
.empty-state p{font-size:13px}

/* ── Sugestões ── */
.sugestoes{display:flex;flex-wrap:wrap;gap:7px;padding:0 24px 12px;position:relative;z-index:1}
.sug-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 13px;border-radius:999px;font-size:12px;font-weight:500;color:var(--text-2);background:var(--glass);border:1px solid var(--border);transition:all .15s;cursor:pointer}
.sug-btn:hover{background:var(--neon-soft);border-color:rgba(59,130,246,.3);color:var(--neon);transform:translateY(-1px)}
.sug-btn svg{width:12px;height:12px;flex-shrink:0}

/* ── Input ── */
.chat-input-wrap{padding:14px 20px 18px;border-top:1px solid var(--border);background:rgba(10,13,20,.9);backdrop-filter:blur(16px);flex-shrink:0;position:relative;z-index:2}
.chat-input-row{display:flex;gap:10px;align-items:flex-end;background:var(--bg-3);border:1px solid var(--border-2);border-radius:var(--r-l);padding:10px 10px 10px 16px;transition:border-color .2s,box-shadow .2s}
.chat-input-row:focus-within{border-color:rgba(59,130,246,.5);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.chat-input-row textarea{flex:1;background:none;border:none;outline:none;color:var(--text);font:inherit;font-size:14px;resize:none;min-height:24px;max-height:120px;line-height:1.5}
.chat-input-row textarea::placeholder{color:var(--text-3)}
.send-btn{width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--neon),rgba(59,130,246,.7));color:#fff;transition:transform .15s,box-shadow .15s;box-shadow:0 0 16px rgba(59,130,246,.3)}
.send-btn:hover{transform:scale(1.05);box-shadow:0 0 24px rgba(59,130,246,.45)}
.send-btn:disabled{opacity:.4;transform:none;box-shadow:none;cursor:not-allowed}
.send-btn svg{width:16px;height:16px}
.input-hint{margin-top:8px;text-align:center;font-size:11px;color:var(--text-3);font-family:var(--mono)}

/* ══════════════════════════════════════════════════════
   PAINEL DIREITO — INSIGHTS
══════════════════════════════════════════════════════ */
.insights-panel{
  background:var(--bg-2);border-left:1px solid var(--border);
  display:flex;flex-direction:column;overflow:hidden;
}
.insights-header{padding:20px 18px 14px;border-bottom:1px solid var(--border);flex-shrink:0}
.insights-header h2{font-size:13px;font-weight:600;letter-spacing:-.01em;display:flex;align-items:center;gap:8px}
.insights-header h2 svg{width:15px;height:15px;color:var(--neon)}
.insights-header p{font-size:11px;color:var(--text-2);margin-top:3px}
.insights-body{flex:1;overflow-y:auto;padding:14px}

.metric-card{
  background:var(--glass);border:1px solid var(--border);border-radius:var(--r);
  padding:14px 15px;margin-bottom:10px;
  transition:border-color .2s,background .2s
}
.metric-card:hover{background:var(--glass-2);border-color:var(--border-2)}
.metric-card .m-label{font-size:10px;color:var(--text-2);font-family:var(--mono);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:6px}
.metric-card .m-label svg{width:12px;height:12px}
.metric-card .m-value{font-size:26px;font-weight:700;letter-spacing:-.03em;font-family:"Inter",sans-serif;line-height:1}
.metric-card .m-sub{font-size:11px;color:var(--text-2);margin-top:4px}
.metric-card.neon .m-value{color:var(--neon)}
.metric-card.green .m-value{color:var(--green)}
.metric-card.red .m-value{color:var(--red)}
.metric-card.amber .m-value{color:var(--amber)}
.metric-card.purple .m-value{color:var(--purple)}

.horarios-section{margin-top:4px}
.horarios-section h4{font-size:10px;color:var(--text-3);font-family:var(--mono);letter-spacing:.14em;text-transform:uppercase;margin-bottom:10px;padding-left:2px}
.horario-bar{margin-bottom:8px}
.horario-bar .hb-top{display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px}
.horario-bar .hb-top .hora{color:var(--text-2);font-family:var(--mono)}
.horario-bar .hb-top .qtd{color:var(--neon);font-weight:600}
.horario-bar .bar{height:5px;border-radius:5px;background:rgba(255,255,255,.06)}
.horario-bar .bar .fill{height:100%;border-radius:5px;background:linear-gradient(90deg,var(--neon),var(--teal));transition:width .8s ease}

.insights-refresh{padding:12px 14px;border-top:1px solid var(--border);flex-shrink:0}
.refresh-btn{width:100%;padding:9px;border-radius:10px;background:var(--glass);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;gap:7px;font-size:12px;font-weight:500;color:var(--text-2);transition:all .15s}
.refresh-btn:hover{background:var(--glass-2);border-color:var(--border-2);color:var(--text)}
.refresh-btn svg{width:13px;height:13px}

/* ══════════════════════════════════════════════════════
   ANIMAÇÕES
══════════════════════════════════════════════════════ */
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.msg-user,.msg-ai,.thinking{animation:fadeUp .25s ease}
@keyframes spin{to{transform:rotate(360deg)}}
.spin{animation:spin 1s linear infinite}

/* ── Responsive esconde painel direito em telas pequenas ── */
@media(max-width:1100px){
  :root{--right:0px}
  .insights-panel{display:none}
}
@media(max-width:720px){
  :root{--sidebar:0px}
  .sidebar{display:none}
}
</style>
</head>
<body>

<div class="shell">

  {{-- ═══ SIDEBAR ═══ --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="orb">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
      </div>
      <div>
        <span>{{ $empresa->nome ?? 'GestãoPro' }}</span>
        <small>ADMIN · IA</small>
      </div>
    </div>

    <div class="nav-section">Menu</div>

    <a href="{{ url('/admin') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>

    <a href="{{ url('/admin/agendamentos/chat') }}" class="nav-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Agendamentos
    </a>

    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Clientes
    </a>

    <a href="{{ route('admin.financeiro.dashboard') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Financeiro
    </a>

    <a href="{{ route('admin.site.configuracoes') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
      Configurações
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

  {{-- ═══ CHAT PRINCIPAL ═══ --}}
  <main class="main">

    {{-- Header --}}
    <div class="chat-header">
      <div class="chat-header-left">
        <span class="pulse"></span>
        <div>
          <h1>Assistente de Agendamentos
            @if($empresa)
              <span style="font-weight:400;font-size:12px;color:var(--text-2);margin-left:8px">· {{ $empresa->nome }}</span>
            @endif
          </h1>
          <p>
            Logado como <strong style="color:var(--text)">{{ $usuario->name ?? $usuario->nome ?? 'Usuário' }}</strong>
            · resposta por linguagem natural
          </p>
        </div>
      </div>
      <div class="chat-header-actions" style="display:flex;align-items:center;gap:10px">

        {{-- Select de Bot --}}
        @if($bots->isNotEmpty())
        <div style="display:flex;align-items:center;gap:8px">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <select id="botSelect" style="background:var(--bg-3);border:1px solid var(--border-2);color:var(--text);border-radius:9px;padding:7px 12px;font-size:12px;font-family:var(--sans);outline:none;cursor:pointer;min-width:160px">
            @foreach($bots as $bot)
              <option value="{{ $bot->id }}" {{ $bot->status ? '' : 'style=color:#64748b' }}>
                {{ $bot->nome }}{{ $bot->status ? '' : ' (inativo)' }}
              </option>
            @endforeach
          </select>
        </div>
        @else
          <span style="font-size:11px;color:var(--red);background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);padding:5px 10px;border-radius:8px">
            ⚠ Nenhum bot encontrado
            @if(!$empresaId)· empresa não vinculada@endif
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

    {{-- Área de mensagens --}}
    <div class="messages-wrap" id="messages">

      {{-- Welcome --}}
      <div class="welcome" id="welcome">
        <div class="big-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h2>Olá! Sou seu <span>Assistente IA</span></h2>
        <p>Pergunte sobre seus agendamentos em linguagem natural.<br/>
        Tente: <em style="color:var(--neon)">"Mostre os agendamentos de hoje"</em> ou <em style="color:var(--teal)">"Quais cancelamentos tive este mês?"</em></p>
      </div>

    </div>

    {{-- Sugestões rápidas --}}
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

    {{-- Input --}}
    <div class="chat-input-wrap">
      <div class="chat-input-row">
        <textarea id="chatInput" placeholder="Pergunte sobre seus agendamentos... ex: mostre os de hoje" rows="1"></textarea>
        <button class="send-btn" id="sendBtn" title="Enviar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>
      <p class="input-hint">↵ Enter para enviar · Shift+Enter nova linha</p>
    </div>
  </main>

  {{-- ═══ PAINEL INSIGHTS ═══ --}}
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
        <div class="m-value" id="ins-mes">—</div>
        <div class="m-sub">total registrado</div>
      </div>

      <div class="metric-card amber">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Hoje
        </div>
        <div class="m-value" id="ins-hoje">—</div>
        <div class="m-sub">agendamentos hoje</div>
      </div>

      <div class="metric-card green">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          Clientes novos
        </div>
        <div class="m-value" id="ins-clientes">—</div>
        <div class="m-sub">este mês</div>
      </div>

      <div class="metric-card red">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          Cancelamentos
        </div>
        <div class="m-value" id="ins-cancel">—</div>
        <div class="m-sub">este mês</div>
      </div>

      <div class="metric-card purple">
        <div class="m-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Receita prevista
        </div>
        <div class="m-value" style="font-size:19px" id="ins-receita">—</div>
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

</div>{{-- .shell --}}

<script>
const QUERY_URL    = @json(route('admin.agendamentos.chat.query'));
const INSIGHTS_URL = @json(route('admin.agendamentos.chat.insights'));
const CSRF_TOKEN   = document.querySelector('meta[name=csrf-token]').content;

const messagesEl = document.getElementById('messages');
const inputEl    = document.getElementById('chatInput');
const sendBtn    = document.getElementById('sendBtn');
const sugestoesEl= document.getElementById('sugestoes');
const welcomeEl  = document.getElementById('welcome');
const botSelect  = document.getElementById('botSelect'); // pode ser null se sem bots

let conversationId = null;

// Quando trocar de bot, reinicia a conversa
if (botSelect) {
  botSelect.addEventListener('change', () => {
    conversationId = null;
    messagesEl.innerHTML = '';
    messagesEl.appendChild(welcomeEl);
    welcomeEl.style.display = '';
    const nome = botSelect.options[botSelect.selectedIndex].text;
    appendSystemMsg(`🤖 Bot alterado para <strong>${escHtml(nome)}</strong>. Nova conversa iniciada.`);
  });
}

// ── Auto-resize textarea ──────────────────────────────────────────────────
inputEl.addEventListener('input', () => {
  inputEl.style.height = 'auto';
  inputEl.style.height = Math.min(inputEl.scrollHeight, 120) + 'px';
});
inputEl.addEventListener('keydown', e => {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviar(); }
});
sendBtn.addEventListener('click', enviar);

// ── Sugestões iniciais ────────────────────────────────────────────────────
document.querySelectorAll('.sug-btn').forEach(btn => {
  btn.addEventListener('click', () => { inputEl.value = btn.dataset.msg; enviar(); });
});

// ── Limpar conversa ───────────────────────────────────────────────────────
document.getElementById('btnClear').addEventListener('click', () => {
  conversationId = null;
  messagesEl.innerHTML = '';
  messagesEl.appendChild(welcomeEl);
  welcomeEl.style.display = '';
  sugestoesEl.innerHTML = '';
  renderSugestoes([
    'Agendamentos de hoje',
    'Agendamentos deste mês',
    'Quais cancelamentos tive?',
    'Cadastre um aluno novo',
    'Verificar disponibilidade para amanhã',
    'Me mostre os serviços disponíveis',
  ]);
});

// ── Refresh insights ──────────────────────────────────────────────────────
document.getElementById('btnRefresh').addEventListener('click', async () => {
  const btn = document.getElementById('btnRefresh');
  btn.querySelector('svg').classList.add('spin');
  try {
    const res  = await fetch(INSIGHTS_URL, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const data = await res.json();
    if (data.insights) updateInsights(data.insights);
  } catch(e) {}
  btn.querySelector('svg').classList.remove('spin');
});

// ── ENVIAR MENSAGEM ───────────────────────────────────────────────────────
async function enviar() {
  const texto = inputEl.value.trim();
  if (!texto || sendBtn.disabled) return;

  welcomeEl.style.display = 'none';
  sugestoesEl.innerHTML = '';

  appendUser(texto);
  inputEl.value = '';
  inputEl.style.height = 'auto';

  const thinkId = appendThinking();
  sendBtn.disabled = true;

  try {
    const body = { mensagem: texto };
    if (conversationId) body.conversation_id = conversationId;
    if (botSelect && botSelect.value) body.bot_id = parseInt(botSelect.value);

    const res  = await fetch(QUERY_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json();

    // Mantém o ID da conversa para contexto
    if (data.conversation_id) conversationId = data.conversation_id;

    removeThinking(thinkId);
    appendAI(data.reply ?? 'Sem resposta.');
    if (data.insights) updateInsights(data.insights);

    // Sugestões de follow-up
    renderSugestoes([
      'Agendar outro horário',
      'Ver agendamentos de hoje',
      'Cadastrar novo aluno',
      'Listar serviços disponíveis',
      'Ver cancelamentos do mês',
    ]);

  } catch(err) {
    removeThinking(thinkId);
    appendError('Ops! Não consegui processar. Tente novamente.');
  } finally {
    sendBtn.disabled = false;
    inputEl.focus();
  }
}

// ── Helpers de DOM ────────────────────────────────────────────────────────
function appendUser(texto) {
  const d = document.createElement('div');
  d.className = 'msg-user';
  d.innerHTML = `<div class="bubble">${escHtml(texto)}</div>`;
  messagesEl.appendChild(d);
  scrollBottom();
}

function appendThinking() {
  const id = 'th-' + Date.now();
  const d  = document.createElement('div');
  d.className = 'thinking'; d.id = id;
  d.innerHTML = `
    <div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
    <div class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
    <span>IA pensando...</span>`;
  messagesEl.appendChild(d);
  scrollBottom();
  return id;
}

function removeThinking(id) {
  const el = document.getElementById(id);
  if (el) el.remove();
}

// Converte markdown simples → HTML
function markdownToHtml(md) {
  return md
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.+?)\*/g, '<em>$1</em>')
    .replace(/`(.+?)`/g, '<code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:4px;font-family:var(--mono);font-size:12px">$1</code>')
    .replace(/^### (.+)$/gm, '<h4 style="margin:10px 0 4px;font-size:14px;font-weight:600">$1</h4>')
    .replace(/^## (.+)$/gm, '<h3 style="margin:10px 0 4px;font-size:15px;font-weight:700">$1</h3>')
    .replace(/^- (.+)$/gm, '<li style="margin:3px 0;padding-left:4px">$1</li>')
    .replace(/(<li.*<\/li>)/s, '<ul style="list-style:none;padding:0;margin:8px 0">$1</ul>')
    .replace(/\n\n/g, '</p><p style="margin:8px 0">')
    .replace(/\n/g, '<br>');
}

function appendAI(texto) {
  const d = document.createElement('div');
  d.className = 'msg-ai';
  const html = markdownToHtml(texto);
  d.innerHTML = `
    <div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
    <div class="body">
      <div class="label">Assistente IA <span class="badge">DeepSeek</span></div>
      <div class="ai-bubble">${html}</div>
    </div>`;
  messagesEl.appendChild(d);
  scrollBottom();
}

function appendError(msg) {
  const d = document.createElement('div');
  d.className = 'msg-ai';
  d.innerHTML = `
    <div class="ai-avatar"><svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
    <div class="body">
      <div class="label" style="color:var(--red)">Erro</div>
      <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:12px;padding:12px 16px;font-size:13px;color:#fca5a5">${escHtml(msg)}</div>
    </div>`;
  messagesEl.appendChild(d);
  scrollBottom();
}

function renderSugestoes(lista) {
  sugestoesEl.innerHTML = lista.slice(0,6).map(s =>
    `<button class="sug-btn" data-msg="${escHtml(s)}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      ${escHtml(s)}
    </button>`
  ).join('');
  sugestoesEl.querySelectorAll('.sug-btn').forEach(btn => {
    btn.addEventListener('click', () => { inputEl.value = btn.dataset.msg; enviar(); });
  });
}

function appendSystemMsg(html) {
  const d = document.createElement('div');
  d.style.cssText = 'text-align:center;padding:6px 0';
  d.innerHTML = `<span style="font-size:11px;color:var(--text-3);background:var(--glass);border:1px solid var(--border);border-radius:20px;padding:4px 12px;display:inline-block">${html}</span>`;
  messagesEl.appendChild(d);
  scrollBottom();
}

function scrollBottom() {
  setTimeout(() => messagesEl.scrollTop = messagesEl.scrollHeight, 50);
}

function escHtml(s) {
  return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── UPDATE INSIGHTS ───────────────────────────────────────────────────────
function updateInsights(ins) {
  document.getElementById('ins-mes').textContent      = ins.agendamentos_mes  ?? '—';
  document.getElementById('ins-hoje').textContent     = ins.agendamentos_hoje ?? '—';
  document.getElementById('ins-clientes').textContent = ins.clientes_novos    ?? '—';
  document.getElementById('ins-cancel').textContent   = ins.cancelamentos     ?? '—';
  document.getElementById('ins-receita').textContent  = ins.receita_prevista  ?? '—';

  document.getElementById('insightsMes').textContent =
    new Date().toLocaleDateString('pt-BR', { month:'long', year:'numeric' });

  const horarios = ins.horarios_top ?? {};
  const keys = Object.keys(horarios);
  if (keys.length) {
    const maxVal = Math.max(...Object.values(horarios));
    document.getElementById('horariosSection').style.display = '';
    document.getElementById('horariosBody').innerHTML = keys.map(h => {
      const pct = maxVal > 0 ? Math.round((horarios[h] / maxVal) * 100) : 0;
      return `<div class="horario-bar">
        <div class="hb-top"><span class="hora">${h}</span><span class="qtd">${horarios[h]} aulas</span></div>
        <div class="bar"><div class="fill" style="width:${pct}%"></div></div>
      </div>`;
    }).join('');
  }
}

// ── Carrega insights silenciosamente ao abrir ─────────────────────────────
(async function () {
  try {
    const res  = await fetch(INSIGHTS_URL, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const data = await res.json();
    if (data.insights) updateInsights(data.insights);
  } catch(e) {}
})();
</script>

<style>
/* Bubble da IA com texto formatado */
.ai-bubble{
  background:var(--glass);border:1px solid var(--border);border-radius:4px 16px 16px 16px;
  padding:14px 18px;font-size:14px;line-height:1.65;color:var(--text);
  max-width:680px;
}
.ai-bubble strong{color:#fff;font-weight:600}
.ai-bubble code{font-size:12px}
.ai-bubble ul{padding-left:16px;margin:6px 0}
.ai-bubble li::marker{color:var(--neon)}
</style>
</body>
</html>
