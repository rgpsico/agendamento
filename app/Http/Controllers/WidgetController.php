<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\ModalCaptura;
use App\Models\WidgetSite;
use Illuminate\Http\Response;

class WidgetController extends Controller
{
    public function js(string $token): Response
    {
        $modal = ModalCaptura::where('token', $token)->where('ativo', true)->first();

        if (! $modal) {
            return response('/* widget not found */', 404)
                ->header('Content-Type', 'application/javascript');
        }

        $larguras = ['pequeno' => '320px', 'medio' => '420px', 'grande' => '560px'];
        $largura  = $larguras[$modal->tamanho ?? 'medio'] ?? '420px';

        $config = json_encode([
            'titulo'          => $modal->titulo,
            'descricao'       => $modal->descricao ?? '',
            'botaoTexto'      => $modal->botao_texto,
            'corPrimaria'     => $modal->cor_primaria,
            'corTexto'        => $modal->cor_texto    ?? '#333333',
            'corFundo'        => $modal->cor_fundo    ?? '#ffffff',
            'bordas'          => (int) ($modal->bordas ?? 12),
            'largura'         => $largura,
            'imagemUrl'       => $modal->imagem_url   ?? '',
            'posicao'         => $modal->posicao      ?? 'centro',
            'gatilho'         => $modal->gatilho      ?? 'imediato',
            'gatilhoValor'    => $modal->gatilho_valor ?? '',
            'mostrarUmaVez'   => (bool) ($modal->mostrar_uma_vez ?? true),
            'campoNome'       => $modal->campo_nome,
            'campoEmail'      => $modal->campo_email,
            'campoTelefone'   => $modal->campo_telefone,
            'mensagemSucesso' => $modal->mensagem_sucesso,
            'submitUrl'       => url('/api/widget/' . $token . '/lead'),
            'storageKey'      => 'pg_visto_' . $token,
        ]);

        $js = <<<JS
(function() {
  var cfg = {$config};

  // Mostrar apenas uma vez (localStorage)
  if (cfg.mostrarUmaVez && localStorage.getItem(cfg.storageKey)) return;

  // ── Estilos ──────────────────────────────────────────────────────────────
  var borderRadius = cfg.bordas + 'px';
  var isCorner     = cfg.posicao === 'direito' || cfg.posicao === 'esquerdo';

  var overlayAlign = isCorner
    ? 'align-items:flex-end;justify-content:' + (cfg.posicao === 'direito' ? 'flex-end' : 'flex-start') + ';padding:24px'
    : 'align-items:center;justify-content:center';

  var style = document.createElement('style');
  style.textContent = [
    '#pg-overlay{position:fixed;inset:0;background:rgba(0,0,0,0);z-index:99999;display:flex;' + overlayAlign + ';transition:background .3s ease;pointer-events:none}',
    '#pg-overlay.pg-show{background:' + (isCorner ? 'rgba(0,0,0,0)' : 'rgba(0,0,0,.55)') + ';pointer-events:auto}',
    '#pg-modal{background:' + cfg.corFundo + ';border-radius:' + borderRadius + ';max-width:' + cfg.largura + ';width:' + (isCorner ? cfg.largura : '90%') + ';position:relative;box-shadow:0 8px 40px rgba(0,0,0,.22);font-family:Arial,sans-serif;opacity:0;transform:translateY(24px);transition:opacity .3s ease,transform .3s ease;overflow:hidden;pointer-events:auto}',
    '#pg-overlay.pg-show #pg-modal{opacity:1;transform:translateY(0)}',
    '#pg-modal-body{padding:28px 28px 24px}',
    '#pg-modal img.pg-banner{width:100%;display:block;object-fit:cover;max-height:180px}',
    '#pg-modal h2{margin:0 0 8px;font-size:20px;color:' + cfg.corPrimaria + '}',
    '#pg-modal p{margin:0 0 20px;color:' + cfg.corTexto + ';font-size:14px;line-height:1.6}',
    '#pg-modal input{width:100%;padding:10px 14px;margin-bottom:12px;border:1px solid #ddd;border-radius:' + Math.max(4, cfg.bordas - 4) + 'px;font-size:14px;box-sizing:border-box;color:' + cfg.corTexto + ';background:#fafafa}',
    '#pg-modal input:focus{outline:none;border-color:' + cfg.corPrimaria + '}',
    '#pg-btn{width:100%;padding:12px;background:' + cfg.corPrimaria + ';color:#fff;border:none;border-radius:' + Math.max(4, cfg.bordas - 4) + 'px;font-size:15px;font-weight:bold;cursor:pointer;transition:opacity .2s}',
    '#pg-btn:hover{opacity:.88}',
    '#pg-close{position:absolute;top:10px;right:14px;background:rgba(255,255,255,.8);border:none;width:28px;height:28px;border-radius:50%;font-size:18px;cursor:pointer;color:#666;line-height:1;display:flex;align-items:center;justify-content:center;z-index:1}',
    '#pg-success{text-align:center;color:' + cfg.corPrimaria + ';font-weight:bold;padding:24px 28px;font-size:15px}',
    '#pg-error{color:#c0392b;font-size:13px;margin-bottom:10px}',
  ].join('');
  document.head.appendChild(style);

  // ── HTML do modal ────────────────────────────────────────────────────────
  var fields = '';
  if (cfg.campoNome)      fields += '<input type="text"  id="pg-nome"  placeholder="Seu nome"     required>';
  if (cfg.campoEmail)     fields += '<input type="email" id="pg-email" placeholder="Seu e-mail"   required>';
  if (cfg.campoTelefone)  fields += '<input type="tel"   id="pg-tel"   placeholder="Seu telefone">';

  var banner = cfg.imagemUrl ? '<img class="pg-banner" src="' + cfg.imagemUrl + '" alt="">' : '';

  var html = '<div id="pg-overlay">'
    + '<div id="pg-modal">'
    +   '<button id="pg-close" aria-label="Fechar">&times;</button>'
    +   banner
    +   '<div id="pg-modal-body">'
    +     '<h2>' + cfg.titulo + '</h2>'
    +     (cfg.descricao ? '<p>' + cfg.descricao + '</p>' : '')
    +     '<div id="pg-error" style="display:none"></div>'
    +     fields
    +     '<button id="pg-btn">' + cfg.botaoTexto + '</button>'
    +   '</div>'
    + '</div></div>';

  var wrapper = document.createElement('div');
  wrapper.innerHTML = html;
  document.body.appendChild(wrapper);

  // ── Funções de controle ──────────────────────────────────────────────────
  function abrir() {
    requestAnimationFrame(function() {
      requestAnimationFrame(function() {
        document.getElementById('pg-overlay').classList.add('pg-show');
      });
    });
  }

  function fechar() {
    if (cfg.mostrarUmaVez) {
      try { localStorage.setItem(cfg.storageKey, '1'); } catch(e) {}
    }
    var overlay = document.getElementById('pg-overlay');
    if (overlay) {
      overlay.classList.remove('pg-show');
      setTimeout(function() { wrapper.remove(); }, 300);
    }
  }

  // ── Fechar via overlay / botão ───────────────────────────────────────────
  document.getElementById('pg-close').onclick = fechar;
  document.getElementById('pg-overlay').onclick = function(e) {
    if (e.target.id === 'pg-overlay') fechar();
  };

  // ── Submit ───────────────────────────────────────────────────────────────
  document.getElementById('pg-btn').onclick = function() {
    var erro = document.getElementById('pg-error');
    erro.style.display = 'none';

    var data    = {};
    var nomeEl  = document.getElementById('pg-nome');
    var emailEl = document.getElementById('pg-email');
    var telEl   = document.getElementById('pg-tel');

    if (nomeEl)  { if (!nomeEl.value.trim())  { erro.textContent='Nome obrigatório.';   erro.style.display='block'; return; } data.nome     = nomeEl.value.trim(); }
    if (emailEl) { if (!emailEl.value.trim()) { erro.textContent='E-mail obrigatório.'; erro.style.display='block'; return; } data.email    = emailEl.value.trim(); }
    if (telEl)   { data.telefone = telEl.value.trim(); }

    var btn = document.getElementById('pg-btn');
    btn.disabled    = true;
    btn.textContent = 'Enviando...';

    fetch(cfg.submitUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      if (res.error) {
        erro.textContent = res.error;
        erro.style.display = 'block';
        btn.disabled    = false;
        btn.textContent = cfg.botaoTexto;
      } else {
        document.getElementById('pg-modal').innerHTML = '<div id="pg-success">✅ ' + cfg.mensagemSucesso + '</div>';
        setTimeout(function() { fechar(); }, 4000);
      }
    })
    .catch(function() {
      erro.textContent = 'Erro ao enviar. Tente novamente.';
      erro.style.display = 'block';
      btn.disabled    = false;
      btn.textContent = cfg.botaoTexto;
    });
  };

  // ── Gatilho ──────────────────────────────────────────────────────────────
  switch (cfg.gatilho) {

    case 'imediato':
      abrir();
      break;

    case 'delay':
      var secs = parseFloat(cfg.gatilhoValor) || 3;
      setTimeout(abrir, secs * 1000);
      break;

    case 'scroll':
      var pct = parseFloat(cfg.gatilhoValor) || 50;
      var scrollDisparado = false;
      window.addEventListener('scroll', function onScroll() {
        if (scrollDisparado) return;
        var scrolled = (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight * 100;
        if (scrolled >= pct) {
          scrollDisparado = true;
          window.removeEventListener('scroll', onScroll);
          abrir();
        }
      }, { passive: true });
      break;

    case 'elemento':
      var selector = cfg.gatilhoValor || '';
      if (selector && typeof IntersectionObserver !== 'undefined') {
        var alvo = document.querySelector(selector);
        if (alvo) {
          var obs = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting) {
              obs.disconnect();
              abrir();
            }
          }, { threshold: 0.3 });
          obs.observe(alvo);
        }
      }
      break;

    case 'saida':
      var saidaDisparada = false;
      document.addEventListener('mouseleave', function onSaida(e) {
        if (saidaDisparada || e.clientY > 10) return;
        saidaDisparada = true;
        document.removeEventListener('mouseleave', onSaida);
        abrir();
      });
      break;
  }

})();
JS;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }

    // ─── Bot Chat Widget ──────────────────────────────────────────────────────

    public function botJs(string $token): Response
    {
        $bot = Bot::where('widget_token', $token)
                  ->where('widget_ativo', true)
                  ->where('status', true)
                  ->first();

        if (! $bot) {
            return response('/* bot widget not found */', 404)
                ->header('Content-Type', 'application/javascript');
        }

        $config = json_encode([
            'nomBot'      => $bot->widget_nome_bot ?: $bot->nome,
            'cor'         => $bot->widget_cor       ?: '#2a5298',
            'posicao'     => $bot->widget_posicao   ?: 'direito',
            'saudacao'    => $bot->widget_saudacao  ?: 'Olá! Como posso ajudar?',
            'avatarUrl'   => $bot->widget_avatar_url ?: '',
            'chatUrl'     => url('/api/bot/' . $token . '/chat'),
        ]);

        $js = <<<JS
(function() {
  var cfg = {$config};
  var conversationId = null;
  var isOpen = false;

  var lado = cfg.posicao === 'esquerdo' ? 'left:20px' : 'right:20px';

  // ── Estilos ────────────────────────────────────────────────────────────────
  var style = document.createElement('style');
  style.textContent = [
    '#pgb-bubble{position:fixed;bottom:20px;' + lado + ';width:56px;height:56px;border-radius:50%;background:' + cfg.cor + ';color:#fff;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.25);display:flex;align-items:center;justify-content:center;z-index:99990;transition:transform .2s,box-shadow .2s}',
    '#pgb-bubble:hover{transform:scale(1.08);box-shadow:0 6px 24px rgba(0,0,0,.3)}',
    '#pgb-bubble svg{width:28px;height:28px;fill:#fff}',
    '#pgb-badge{position:absolute;top:-3px;right:-3px;background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;font-size:11px;display:none;align-items:center;justify-content:center;font-weight:bold}',
    '#pgb-window{position:fixed;bottom:88px;' + lado + ';width:340px;max-width:calc(100vw - 24px);height:480px;max-height:calc(100vh - 120px);background:#fff;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,.22);display:flex;flex-direction:column;z-index:99989;opacity:0;transform:translateY(20px) scale(.96);transition:opacity .25s,transform .25s;pointer-events:none}',
    '#pgb-window.pgb-open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto}',
    '#pgb-header{background:' + cfg.cor + ';color:#fff;padding:14px 16px;border-radius:16px 16px 0 0;display:flex;align-items:center;gap:10px;flex-shrink:0}',
    '#pgb-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;background:rgba(255,255,255,.25);flex-shrink:0}',
    '#pgb-header-info{flex:1;min-width:0}',
    '#pgb-header-nome{font-weight:bold;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}',
    '#pgb-header-status{font-size:11px;opacity:.85}',
    '#pgb-close-btn{background:none;border:none;color:#fff;cursor:pointer;font-size:20px;line-height:1;padding:0 0 0 8px;opacity:.8}',
    '#pgb-close-btn:hover{opacity:1}',
    '#pgb-msgs{flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;scroll-behavior:smooth}',
    '.pgb-msg{max-width:80%;padding:9px 13px;border-radius:12px;font-size:13px;line-height:1.5;word-break:break-word}',
    '.pgb-msg.user{background:' + cfg.cor + ';color:#fff;align-self:flex-end;border-bottom-right-radius:3px}',
    '.pgb-msg.bot{background:#f0f2f5;color:#333;align-self:flex-start;border-bottom-left-radius:3px}',
    '.pgb-msg.typing{color:#888;font-style:italic;font-size:12px}',
    '#pgb-form{display:flex;gap:8px;padding:10px 12px;border-top:1px solid #eee;flex-shrink:0}',
    '#pgb-input{flex:1;border:1px solid #ddd;border-radius:20px;padding:8px 14px;font-size:13px;outline:none;resize:none;max-height:80px;overflow-y:auto}',
    '#pgb-input:focus{border-color:' + cfg.cor + '}',
    '#pgb-send{background:' + cfg.cor + ';color:#fff;border:none;border-radius:50%;width:36px;height:36px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:opacity .2s}',
    '#pgb-send:disabled{opacity:.5;cursor:default}',
    '#pgb-send svg{width:18px;height:18px;fill:#fff}',
    '@media(max-width:400px){#pgb-window{width:calc(100vw - 24px);' + (cfg.posicao === 'esquerdo' ? 'left:12px' : 'right:12px') + '}}',
  ].join('');
  document.head.appendChild(style);

  // ── HTML ────────────────────────────────────────────────────────────────────
  var avatarHtml = cfg.avatarUrl
    ? '<img id="pgb-avatar" src="' + cfg.avatarUrl + '" alt="">'
    : '<div id="pgb-avatar" style="display:flex;align-items:center;justify-content:center"><svg viewBox="0 0 24 24" style="width:20px;height:20px;fill:#fff"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg></div>';

  var wrapper = document.createElement('div');
  wrapper.innerHTML =
    '<button id="pgb-bubble" aria-label="Abrir chat">'
    + '<span id="pgb-badge"></span>'
    + '<svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>'
    + '</button>'
    + '<div id="pgb-window">'
    +   '<div id="pgb-header">'
    +     avatarHtml
    +     '<div id="pgb-header-info">'
    +       '<div id="pgb-header-nome">' + cfg.nomBot + '</div>'
    +       '<div id="pgb-header-status">● Online</div>'
    +     '</div>'
    +     '<button id="pgb-close-btn" aria-label="Fechar">&times;</button>'
    +   '</div>'
    +   '<div id="pgb-msgs"></div>'
    +   '<div id="pgb-form">'
    +     '<textarea id="pgb-input" placeholder="Digite sua mensagem..." rows="1"></textarea>'
    +     '<button id="pgb-send" aria-label="Enviar">'
    +       '<svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>'
    +     '</button>'
    +   '</div>'
    + '</div>';

  document.body.appendChild(wrapper);

  var bubble  = document.getElementById('pgb-bubble');
  var win     = document.getElementById('pgb-window');
  var msgs    = document.getElementById('pgb-msgs');
  var input   = document.getElementById('pgb-input');
  var sendBtn = document.getElementById('pgb-send');
  var badge   = document.getElementById('pgb-badge');

  // ── Saudação inicial ────────────────────────────────────────────────────────
  function addMsg(text, from) {
    var div = document.createElement('div');
    div.className = 'pgb-msg ' + from;
    div.textContent = text;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
    return div;
  }

  var saudacaoMostrada = false;
  function mostrarSaudacao() {
    if (saudacaoMostrada || !cfg.saudacao) return;
    saudacaoMostrada = true;
    setTimeout(function() { addMsg(cfg.saudacao, 'bot'); }, 400);
  }

  // ── Abrir / Fechar ──────────────────────────────────────────────────────────
  function abrir() {
    isOpen = true;
    win.classList.add('pgb-open');
    badge.style.display = 'none';
    mostrarSaudacao();
    setTimeout(function() { input.focus(); }, 300);
  }

  function fechar() {
    isOpen = false;
    win.classList.remove('pgb-open');
  }

  bubble.addEventListener('click', function() { isOpen ? fechar() : abrir(); });
  document.getElementById('pgb-close-btn').addEventListener('click', fechar);

  // ── Enviar mensagem ─────────────────────────────────────────────────────────
  function enviar() {
    var text = input.value.trim();
    if (!text) return;

    addMsg(text, 'user');
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

    var typing = addMsg('digitando…', 'bot typing');

    fetch(cfg.chatUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ message: text, conversation_id: conversationId })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      typing.remove();
      if (res.error) {
        addMsg('⚠️ ' + res.error, 'bot');
      } else {
        conversationId = res.conversation_id;
        addMsg(res.reply, 'bot');
        if (!isOpen) {
          badge.textContent = '1';
          badge.style.display = 'flex';
        }
      }
    })
    .catch(function() {
      typing.remove();
      addMsg('⚠️ Erro de conexão. Tente novamente.', 'bot');
    })
    .finally(function() {
      sendBtn.disabled = false;
    });
  }

  sendBtn.addEventListener('click', enviar);

  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      enviar();
    }
  });

  // Auto-resize do textarea
  input.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 80) + 'px';
  });

})();
JS;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=60');
    }

    // ─── Tracking Script ──────────────────────────────────────────────────────

    public function trackJs(string $token): Response
    {
        $site = WidgetSite::where('token', $token)->where('ativo', true)->first();

        if (! $site) {
            return response('/* tracker not found */', 200)
                ->header('Content-Type', 'application/javascript');
        }

        $apiUrl    = url('/api/track/' . $token . '/evento');
        $waSelector = addslashes($site->whatsapp_selector ?? '');

        $js = <<<JS
(function() {
  var API = '{$apiUrl}';

  // ── Session ID (persiste na aba, não entre abas) ───────────────────────────
  var sid = sessionStorage.getItem('_pgSid');
  if (!sid) {
    sid = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2);
    sessionStorage.setItem('_pgSid', sid);
  }

  // ── Detecta dispositivo ───────────────────────────────────────────────────
  var ua  = navigator.userAgent;
  var dev = /tablet|ipad|playbook|silk/i.test(ua) ? 'tablet'
          : /mobile|android|iphone|ipod|blackberry|opera mini|iemobile/i.test(ua) ? 'mobile'
          : 'desktop';

  // ── Envia evento para a API ───────────────────────────────────────────────
  // Usamos fetch + keepalive:true em vez de sendBeacon porque sendBeacon sempre
  // envia credentials:'include', o que conflita com Access-Control-Allow-Origin:*
  // fetch + credentials:'omit' + keepalive:true = sem CORS issue + sobrevive ao unload
  function enviar(tipo, extra) {
    var payload = Object.assign({
      session_id:  sid,
      tipo:        tipo,
      pagina:      location.href.slice(0, 500),
      referrer:    (document.referrer || '').slice(0, 500),
      dispositivo: dev,
    }, extra || {});

    try {
      fetch(API, {
        method:      'POST',
        headers:     { 'Content-Type': 'application/json' },
        body:        JSON.stringify(payload),
        credentials: 'omit',      // sem cookies — cross-origin funciona com Allow-Origin:*
        keepalive:   true,        // requisição sobrevive ao fechamento da página
      }).catch(function(){});
    } catch(e) {}
  }

  // ── Visita ────────────────────────────────────────────────────────────────
  // Evita contar a mesma página mais de uma vez por aba
  var pageKey = '_pgVisit_' + location.pathname;
  if (!sessionStorage.getItem(pageKey)) {
    sessionStorage.setItem(pageKey, '1');
    enviar('visita');
  }

  // ── Tempo na página ───────────────────────────────────────────────────────
  var inicio = Date.now();
  var tempoEnviado = false;

  function enviarTempo() {
    if (tempoEnviado) return;
    tempoEnviado = true;
    var segundos = Math.round((Date.now() - inicio) / 1000);
    if (segundos > 1) {
      enviar('tempo', { duracao: segundos });
    }
  }

  document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden') enviarTempo();
  });
  window.addEventListener('pagehide', enviarTempo);
  window.addEventListener('beforeunload', enviarTempo);

  // ── Cliques no WhatsApp (links <a href="wa.me/..."> ) ─────────────────────
  document.addEventListener('click', function(e) {
    var el = e.target.closest('a[href]');
    if (!el) return;
    var href = el.getAttribute('href') || '';
    if (/wa\.me|whatsapp\.com\/send|api\.whatsapp/i.test(href)) {
      enviar('whatsapp', { meta: href.slice(0, 255) });
    }
  }, true);

  // ── Cliques no WhatsApp (botão/ícone customizado via seletor CSS) ──────────
  var waSelector = '{$waSelector}';
  if (waSelector) {
    // Tenta vincular imediatamente e também após o DOM carregar completamente
    function bindWaSelector() {
      var els = document.querySelectorAll(waSelector);
      els.forEach(function(el) {
        if (el._pgWaBound) return;
        el._pgWaBound = true;
        el.addEventListener('click', function() {
          enviar('whatsapp', { meta: waSelector });
        }, true);
      });
    }
    // Bind inicial + observer para elementos que aparecem depois (widgets lazy)
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', bindWaSelector);
    } else {
      bindWaSelector();
    }
    if (typeof MutationObserver !== 'undefined') {
      new MutationObserver(bindWaSelector).observe(document.body, { childList: true, subtree: true });
    }
  }

  // ── Cliques em telefone ───────────────────────────────────────────────────
  document.addEventListener('click', function(e) {
    var el = e.target.closest('a[href^="tel:"]');
    if (el) enviar('tel', { meta: el.getAttribute('href').slice(0, 50) });
  }, true);

})();
JS;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }
}
