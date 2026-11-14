@extends('barbearia.layout')
@section('titulo', $empresa->nome)
@section('head')
    <style>
        .checkin { background:var(--cartao); border:1px solid var(--linha); border-radius:14px; padding:16px; margin:0 0 18px; }
        .checkin h2 { margin:0 0 6px; font-size:1.05rem; }
        .checkin .suave { margin:0 0 12px; }
        .leitor { display:none; margin-top:12px; }
        .leitor.aberto { display:block; }
        .leitor video { width:100%; max-height:320px; border-radius:12px; background:#000; object-fit:cover; }
        .leitor canvas { display:none; }
        .cartao.barbeiro-linha { gap:12px; }
        .cartao .nome { flex:1; min-width:0; color:inherit; text-decoration:none; }
        .cartao form { margin:0; flex:none; }
    </style>
@endsection
@section('conteudo')
    @include('barbearia._capa', ['empresa' => $empresa, 'classe' => 'capa-pagina'])
    <p class="passos">Escolha o {{ mb_strtolower($nicho->termo('profissional')) }} e entre na fila</p>
    <h1>{{ $empresa->nome }}</h1>
    <p class="suave">{{ $empresa->descricao }}</p>

    {{-- Check-in na mesma tela: entrar (se ainda não entrou) -> ler o QR da parede (se a barbearia exige) -> entrar na fila --}}
    <div class="checkin" id="checkin">
        @guest
            <h2>1. Entre para fazer check-in</h2>
            <p class="suave">Só nome, celular e senha. Depois é só tocar em "Entrar na fila" no {{ mb_strtolower($nicho->termo('profissional')) }}.</p>
            @include('barbearia._acesso', ['voltar' => '/barbearia/'.$empresa->id])
        @else
            @if(! $ehCliente)
                <h2>Check-in</h2>
                <p class="suave" style="margin:0">Você está logado como {{ mb_strtolower($nicho->termo('profissional')) }}/dono. Para entrar na fila, use a conta de cliente.</p>
            @elseif($minhaFila)
                <h2>Você já está na fila</h2>
                <p class="suave">Acompanhe a sua posição.</p>
                <a class="btn" href="{{ route('barbearia.fila', $minhaFila) }}">Ver minha posição</a>
            @elseif(! $noLocal)
                <h2>Leia o QR code da barbearia</h2>
                <p class="suave">O QR code está na parede da barbearia. Leia aqui ou com a câmera do celular. Depois escolha o {{ mb_strtolower($nicho->termo('profissional')) }}.</p>
                <button type="button" class="btn" id="ler-qr">Ler QR code</button>
                <div class="leitor" id="leitor">
                    <video id="video" playsinline muted></video>
                    <canvas id="quadro"></canvas>
                    <p class="suave" id="leitor-msg" style="margin:8px 0 0">Aponte para o QR code da barbearia…</p>
                </div>
            @else
                <h2>Check-in liberado</h2>
                <p class="suave" style="margin:0">Toque em "Entrar na fila" no {{ mb_strtolower($nicho->termo('profissional')) }} que vai atender você. Você será chamado na ordem de chegada.</p>
            @endif
        @endguest
    </div>

    <h2>{{ $nicho->termo('profissionais') }}</h2>
    <div class="lista">
        @forelse($barbeiros as $b)
            <div class="cartao barbeiro-linha">
                <a class="nome" href="{{ route('barbearia.barbeiro', [$empresa, $b]) }}" style="display:flex;align-items:center;gap:12px">
                    <span class="avatar">{{ mb_strtoupper(mb_substr(optional($b->usuario)->nome ?? '?', 0, 1)) }}</span>
                    <span class="meio"><strong>{{ optional($b->usuario)->nome }}</strong><span class="selo {{ $b->esperando ? '' : 'vazio' }}">{{ $b->esperando ? $b->esperando.' na fila' : 'sem fila' }}</span></span>
                </a>
                @auth
                    @if($ehCliente && $noLocal && ! $minhaFila)
                        <form method="post" action="{{ route('barbearia.checkin', [$empresa, $b]) }}">
                            @csrf
                            <button class="btn pequeno" type="submit">Entrar na fila</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <div class="aviso info">Esta barbearia ainda não cadastrou {{ mb_strtolower($nicho->termo('profissionais')) }}.</div>
        @endforelse
    </div>

    @auth
        @if($ehCliente && ! $noLocal && ! $minhaFila)
            <script src="{{ asset('js/vendor/jsqr-1.4.0.js') }}"></script>
            <script>
                (function () {
                    var botao = document.getElementById('ler-qr'), leitor = document.getElementById('leitor'),
                        video = document.getElementById('video'), quadro = document.getElementById('quadro'),
                        msg = document.getElementById('leitor-msg'), fluxo = null, parado = true;
                    // só aceita o link do QR desta barbearia: /barbearia/{id}/c/{token}, neste mesmo site
                    var padrao = new RegExp('^/barbearia/{{ $empresa->id }}/c/[A-Za-z0-9]+$');

                    function parar() { parado = true; if (fluxo) { fluxo.getTracks().forEach(function (t) { t.stop(); }); fluxo = null; } }

                    function achou(texto) {
                        var u;
                        try { u = new URL(texto, location.href); } catch (e) { return false; }
                        if (u.origin !== location.origin || !padrao.test(u.pathname)) { msg.textContent = 'Esse QR code não é desta barbearia. Tente o da parede.'; return false; }
                        parar();
                        location.href = u.pathname;
                        return true;
                    }

                    function ler() {
                        if (parado) return;
                        var texto = null;
                        if (video.readyState >= 2 && video.videoWidth) {
                            quadro.width = video.videoWidth; quadro.height = video.videoHeight;
                            var c = quadro.getContext('2d', { willReadFrequently: true });
                            c.drawImage(video, 0, 0);
                            var img = c.getImageData(0, 0, quadro.width, quadro.height);
                            var r = window.jsQR && jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
                            if (r && r.data) texto = r.data;
                        }
                        if (texto && achou(texto)) return;
                        setTimeout(ler, 250);
                    }

                    botao.addEventListener('click', function () {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            leitor.classList.add('aberto');
                            msg.textContent = 'Este navegador não abre a câmera aqui. Use o app de câmera do celular e aponte para o QR code da parede.';
                            return;
                        }
                        leitor.classList.add('aberto');
                        msg.textContent = 'Abrindo a câmera…';
                        navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false }).then(function (s) {
                            fluxo = s; video.srcObject = s; parado = false;
                            return video.play();
                        }).then(function () {
                            msg.textContent = 'Aponte para o QR code da barbearia…';
                            ler();
                        }).catch(function () {
                            parar();
                            msg.textContent = 'Não consegui abrir a câmera. Libere a câmera para este site ou use o app de câmera do celular e aponte para o QR code da parede.';
                        });
                    });
                    window.addEventListener('pagehide', parar);
                })();
            </script>
        @endif
    @endauth
@endsection
