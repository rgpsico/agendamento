@extends('barbearia.layout')
@section('titulo', 'Minha fila')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ route('barbearia.manifesto', [], false) }}">
    <link rel="apple-touch-icon" href="/images/barbearia/icone-192.png">
    <meta name="theme-color" content="#1f1f1f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Fila">
@endsection
@section('conteudo')
    <h1>Olá, {{ auth()->user()->nome }}</h1>
    <p class="suave">{{ $barbeiro->empresa->nome }} · fila e agenda de hoje</p>

    {{-- aviso no celular: push com a tela apagada + alarme sonoro com esta tela aberta --}}
    <div class="caixa" id="aviso-box"
         data-chave="{{ config('webpush.public_key') }}"
         data-sw="{{ route('barbearia.sw', [], false) }}"
         data-url-inscrever="{{ route('barbearia.painel.aviso', [], false) }}"
         data-url-testar="{{ route('barbearia.painel.aviso.testar', [], false) }}"
         style="display:none">
        <strong>🔔 Avisos de cliente na fila</strong>
        <p class="suave" id="aviso-estado" style="margin:6px 0 10px">Verificando…</p>
        <div class="linha-acoes">
            <button type="button" class="btn pequeno" id="aviso-ativar" style="display:none">Ativar avisos</button>
            <button type="button" class="btn claro pequeno" id="aviso-testar" style="display:none">Enviar teste</button>
            <button type="button" class="btn claro pequeno" id="aviso-desativar" style="display:none">Desativar</button>
        </div>
    </div>

    <div id="painel-fila" data-url="{{ route('barbearia.painel.fila', ['fragmento' => 1]) }}">
        @include('barbearia._fila_barbeiro')
    </div>

    <script>
        // 1) fila ao vivo: atualiza a cada 8 segundos e toca o alarme quando chega cliente novo
        (function () {
            var caixa = document.getElementById('painel-fila');
            var tituloOriginal = document.title;
            var audio = null;

            function idsAtuais() {
                var el = document.getElementById('ids-espera');
                return el && el.dataset.ids ? el.dataset.ids.split(',') : [];
            }
            var conhecidos = idsAtuais();

            // o navegador só libera som depois de um toque do barbeiro na tela
            function liberarSom() {
                try { audio = audio || new (window.AudioContext || window.webkitAudioContext)(); if (audio.state === 'suspended') audio.resume(); } catch (e) {}
            }
            ['pointerdown', 'keydown'].forEach(function (ev) { document.addEventListener(ev, liberarSom, { once: false, passive: true }); });

            function apito() {
                if (!audio) return;
                [0, 0.35, 0.7].forEach(function (atraso) {
                    var o = audio.createOscillator(), g = audio.createGain();
                    o.type = 'square'; o.frequency.value = 880;
                    g.gain.setValueAtTime(0.0001, audio.currentTime + atraso);
                    g.gain.exponentialRampToValueAtTime(0.25, audio.currentTime + atraso + 0.02);
                    g.gain.exponentialRampToValueAtTime(0.0001, audio.currentTime + atraso + 0.25);
                    o.connect(g); g.connect(audio.destination);
                    o.start(audio.currentTime + atraso); o.stop(audio.currentTime + atraso + 0.3);
                });
            }

            function avisarNovos(novos) {
                apito();
                if (navigator.vibrate) navigator.vibrate([300, 150, 300]);
                document.title = '(' + novos + ') Cliente na fila · ' + tituloOriginal;
            }
            document.addEventListener('visibilitychange', function () { if (!document.hidden) document.title = tituloOriginal; });

            setInterval(function () {
                fetch(caixa.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                    .then(function (r) { return r.ok ? r.text() : null; })
                    .then(function (html) {
                        if (html === null) return;
                        caixa.innerHTML = html;
                        var agora = idsAtuais();
                        var novos = agora.filter(function (id) { return conhecidos.indexOf(id) === -1; });
                        conhecidos = agora;
                        if (novos.length) avisarNovos(novos.length);
                    })
                    .catch(function () {});
            }, 8000);
        })();

        // 2) aviso no celular com o app fechado (Web Push)
        (function () {
            var box = document.getElementById('aviso-box');
            var estado = document.getElementById('aviso-estado');
            var bAtivar = document.getElementById('aviso-ativar');
            var bTestar = document.getElementById('aviso-testar');
            var bDesativar = document.getElementById('aviso-desativar');
            var csrf = document.querySelector('meta[name=csrf-token]').content;
            var ios = /iphone|ipad|ipod/i.test(navigator.userAgent);
            var instalado = window.navigator.standalone === true || (window.matchMedia && matchMedia('(display-mode: standalone)').matches);

            box.style.display = '';

            function mostrar(msg, ativar, testar, desativar) {
                estado.textContent = msg;
                bAtivar.style.display = ativar ? '' : 'none';
                bTestar.style.display = testar ? '' : 'none';
                bDesativar.style.display = desativar ? '' : 'none';
            }

            if (!box.dataset.chave) { mostrar('Os avisos no celular ainda não foram configurados no servidor.', false, false, false); return; }

            if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                mostrar(ios && !instalado
                    ? 'No iPhone: toque em Compartilhar → "Adicionar à Tela de Início", abra o app por lá e ative os avisos.'
                    : 'Este navegador não suporta aviso no celular. Use o Chrome (Android) ou instale o app (iPhone).', false, false, false);
                return;
            }

            function chave(s) {
                var p = '='.repeat((4 - s.length % 4) % 4), b = (s + p).replace(/-/g, '+').replace(/_/g, '/'), r = atob(b), a = new Uint8Array(r.length);
                for (var i = 0; i < r.length; i++) a[i] = r.charCodeAt(i);
                return a;
            }
            function post(url, corpo, metodo) {
                return fetch(url, { method: metodo || 'POST', credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify(corpo || {}) });
            }
            function registro() { return navigator.serviceWorker.register(box.dataset.sw, { scope: '/barbearia/' }).then(function () { return navigator.serviceWorker.ready; }); }

            function atualizar() {
                if (Notification.permission === 'denied') { mostrar('Os avisos estão bloqueados neste navegador. Libere nas configurações do site e volte aqui.', false, false, false); return Promise.resolve(); }
                return registro().then(function (reg) { return reg.pushManager.getSubscription(); }).then(function (sub) {
                    if (sub) mostrar('Ativados neste aparelho. Você será avisado mesmo com a tela apagada.', false, true, true);
                    else if (ios && !instalado) mostrar('No iPhone o aviso só funciona com o app instalado: toque em Compartilhar → "Adicionar à Tela de Início", abra por lá e ative.', false, false, false);
                    else mostrar('Ative para receber aviso quando entrar cliente na sua fila, mesmo com a tela apagada.', true, false, false);
                }).catch(function () { mostrar('Não foi possível preparar os avisos neste aparelho.', false, false, false); });
            }

            bAtivar.addEventListener('click', function () {
                bAtivar.disabled = true;
                Notification.requestPermission().then(function (perm) {
                    if (perm !== 'granted') { bAtivar.disabled = false; return atualizar(); }
                    return registro().then(function (reg) {
                        return reg.pushManager.getSubscription().then(function (sub) {
                            return sub || reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: chave(box.dataset.chave) });
                        });
                    }).then(function (sub) { return post(box.dataset.urlInscrever, sub.toJSON()); })
                      .then(atualizar);
                }).catch(function () { mostrar('Não foi possível ativar os avisos. Tente de novo.', true, false, false); })
                  .then(function () { bAtivar.disabled = false; });
            });

            bTestar.addEventListener('click', function () {
                bTestar.disabled = true;
                post(box.dataset.urlTestar).then(function (r) { return r.json(); }).then(function (j) {
                    estado.textContent = j.enviados > 0 ? 'Teste enviado. Se não apareceu em alguns segundos, desative e ative de novo.' : 'Nenhum aparelho recebeu o teste. Desative e ative de novo.';
                }).catch(function () { estado.textContent = 'Falha ao enviar o teste.'; })
                  .then(function () { bTestar.disabled = false; });
            });

            bDesativar.addEventListener('click', function () {
                navigator.serviceWorker.ready.then(function (reg) { return reg.pushManager.getSubscription(); }).then(function (sub) {
                    if (!sub) return;
                    return post(box.dataset.urlInscrever, { endpoint: sub.endpoint }, 'DELETE').then(function () { return sub.unsubscribe(); });
                }).then(atualizar);
            });

            atualizar();
        })();
    </script>
@endsection
