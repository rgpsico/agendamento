<?php

namespace App\Http\Controllers;

use App\Models\ModalCaptura;
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
            ->header('Cache-Control', 'public, max-age=300');
    }
}
