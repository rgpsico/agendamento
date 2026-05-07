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

        $config = json_encode([
            'titulo'           => $modal->titulo,
            'descricao'        => $modal->descricao ?? '',
            'botaoTexto'       => $modal->botao_texto,
            'corPrimaria'      => $modal->cor_primaria,
            'campoNome'        => $modal->campo_nome,
            'campoEmail'       => $modal->campo_email,
            'campoTelefone'    => $modal->campo_telefone,
            'mensagemSucesso'  => $modal->mensagem_sucesso,
            'submitUrl'        => url('/api/widget/' . $token . '/lead'),
        ]);

        $js = <<<JS
(function() {
  var cfg = {$config};

  // Injeta estilos
  var style = document.createElement('style');
  style.textContent = [
    '#pg-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99999;display:flex;align-items:center;justify-content:center}',
    '#pg-modal{background:#fff;border-radius:12px;padding:32px;max-width:420px;width:90%;position:relative;box-shadow:0 8px 40px rgba(0,0,0,.18);font-family:Arial,sans-serif}',
    '#pg-modal h2{margin:0 0 8px;font-size:20px;color:' + cfg.corPrimaria + '}',
    '#pg-modal p{margin:0 0 20px;color:#555;font-size:14px;line-height:1.6}',
    '#pg-modal input{width:100%;padding:10px 14px;margin-bottom:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box}',
    '#pg-modal input:focus{outline:none;border-color:' + cfg.corPrimaria + '}',
    '#pg-btn{width:100%;padding:12px;background:' + cfg.corPrimaria + ';color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:bold;cursor:pointer}',
    '#pg-btn:hover{opacity:.9}',
    '#pg-close{position:absolute;top:12px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:#999;line-height:1}',
    '#pg-success{text-align:center;color:' + cfg.corPrimaria + ';font-weight:bold;padding:16px 0;font-size:15px}',
    '#pg-error{color:#c0392b;font-size:13px;margin-bottom:10px}',
  ].join('');
  document.head.appendChild(style);

  // Monta HTML do modal
  var fields = '';
  if (cfg.campoNome)      fields += '<input type="text" id="pg-nome" placeholder="Seu nome" required>';
  if (cfg.campoEmail)     fields += '<input type="email" id="pg-email" placeholder="Seu e-mail" required>';
  if (cfg.campoTelefone)  fields += '<input type="tel" id="pg-tel" placeholder="Seu telefone">';

  var html = '<div id="pg-overlay"><div id="pg-modal">'
    + '<button id="pg-close" aria-label="Fechar">&times;</button>'
    + '<h2>' + cfg.titulo + '</h2>'
    + (cfg.descricao ? '<p>' + cfg.descricao + '</p>' : '')
    + '<div id="pg-error" style="display:none"></div>'
    + fields
    + '<button id="pg-btn">' + cfg.botaoTexto + '</button>'
    + '</div></div>';

  var wrapper = document.createElement('div');
  wrapper.innerHTML = html;
  document.body.appendChild(wrapper);

  // Fechar
  document.getElementById('pg-close').onclick = function() {
    wrapper.remove();
  };
  document.getElementById('pg-overlay').onclick = function(e) {
    if (e.target.id === 'pg-overlay') wrapper.remove();
  };

  // Submit
  document.getElementById('pg-btn').onclick = function() {
    var erro = document.getElementById('pg-error');
    erro.style.display = 'none';

    var data = {};
    var nomeEl  = document.getElementById('pg-nome');
    var emailEl = document.getElementById('pg-email');
    var telEl   = document.getElementById('pg-tel');

    if (nomeEl)  { if (!nomeEl.value.trim())  { erro.textContent='Nome obrigatório.';  erro.style.display='block'; return; } data.nome  = nomeEl.value.trim(); }
    if (emailEl) { if (!emailEl.value.trim()) { erro.textContent='E-mail obrigatório.'; erro.style.display='block'; return; } data.email = emailEl.value.trim(); }
    if (telEl)   { data.telefone = telEl.value.trim(); }

    var btn = document.getElementById('pg-btn');
    btn.disabled = true;
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
        btn.disabled = false;
        btn.textContent = cfg.botaoTexto;
      } else {
        document.getElementById('pg-modal').innerHTML = '<div id="pg-success">✅ ' + cfg.mensagemSucesso + '</div>';
        setTimeout(function() { wrapper.remove(); }, 4000);
      }
    })
    .catch(function() {
      erro.textContent = 'Erro ao enviar. Tente novamente.';
      erro.style.display = 'block';
      btn.disabled = false;
      btn.textContent = cfg.botaoTexto;
    });
  };
})();
JS;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=300');
    }
}
