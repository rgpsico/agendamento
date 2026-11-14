@extends('barbearia.layout')
@section('area', 'dono')
@section('titulo', 'Equipe')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('conteudo')
    @include('barbearia._dono_menu', ['aba' => 'equipe'])

    <h2>Capa da barbearia</h2>
    <div class="caixa" id="caixa-capa" data-url="{{ route('barbearia.capa') }}" data-empresa="{{ $empresa->id }}">
        @include('barbearia._capa', ['empresa' => $empresa, 'classe' => 'capa-pagina'])
        <p class="suave" id="capa-msg" style="margin:0 0 10px">Foto larga (de preferência deitada) que aparece na lista e no topo da página da barbearia.</p>
        <div class="linha-acoes">
            <label class="btn escuro pequeno" style="margin:0;cursor:pointer;width:auto">{{ $empresa->capa ? 'Trocar foto' : 'Escolher foto' }}
                <input id="capa-arquivo" type="file" accept="image/jpeg,image/png,image/webp" style="display:none">
            </label>
            @if($empresa->capa)
                <form method="post" action="{{ route('barbearia.capa') }}" style="margin:0">@csrf <input type="hidden" name="empresa" value="{{ $empresa->id }}"><input type="hidden" name="remover" value="1"><button class="btn claro pequeno" type="submit">Remover capa</button></form>
            @endif
        </div>
        @error('capa')<div class="campo-erro">{{ $message }}</div>@enderror
    </div>
    <script>
        // reduz a foto no próprio celular antes de enviar (foto de câmera tem vários MB) e manda por fetch
        (function () {
            var caixa = document.getElementById('caixa-capa'), msg = document.getElementById('capa-msg'), campo = document.getElementById('capa-arquivo');
            var csrf = document.querySelector('meta[name=csrf-token]').content;
            function erro(t) { msg.textContent = t; msg.style.color = 'var(--erro)'; }
            campo.addEventListener('change', function () {
                var arq = campo.files[0];
                if (!arq) return;
                msg.style.color = ''; msg.textContent = 'Enviando…';
                var img = new Image(), url = URL.createObjectURL(arq);
                img.onload = function () {
                    var lado = 1600, esc = Math.min(1, lado / Math.max(img.width, img.height));
                    var c = document.createElement('canvas'); c.width = Math.round(img.width * esc); c.height = Math.round(img.height * esc);
                    c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
                    URL.revokeObjectURL(url);
                    c.toBlob(function (blob) {
                        if (!blob) return erro('Não foi possível preparar a foto. Tente outra.');
                        var f = new FormData(); f.append('capa', blob, 'capa.jpg'); f.append('empresa', caixa.dataset.empresa);
                        fetch(caixa.dataset.url, { method: 'POST', body: f, credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } })
                            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                            .then(function (r) {
                                if (r.ok) { location.reload(); return; }
                                erro((r.j.errors && r.j.errors.capa && r.j.errors.capa[0]) || r.j.message || 'Não foi possível enviar a foto.');
                            })
                            .catch(function () { erro('Falha de conexão. Tente de novo.'); });
                    }, 'image/jpeg', 0.85);
                };
                img.onerror = function () { URL.revokeObjectURL(url); erro('Esse arquivo não abriu como imagem. Tente outra foto.'); };
                img.src = url;
            });
        })();
    </script>

    <h2>{{ $nicho->termo('profissionais') }}</h2>
    <div class="caixa">
        <table class="tabela">
            @forelse($barbeiros as $b)
                <tr>
                    <td><strong>{{ optional($b->usuario)->nome }}</strong><br><span class="suave">{{ optional($b->usuario)->telefone }}</span></td>
                    <td style="text-align:right">
                        <form method="post" action="{{ route('barbearia.equipe.ativo', $b) }}" style="margin:0">
                            @csrf <input type="hidden" name="empresa" value="{{ $empresa->id }}">
                            <button class="btn claro pequeno" type="submit">{{ $b->ativo ? 'Desativar' : 'Ativar' }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="suave">Nenhum {{ mb_strtolower($nicho->termo('profissional')) }} cadastrado ainda.</td></tr>
            @endforelse
        </table>
    </div>

    <h2>Cadastrar {{ mb_strtolower($nicho->termo('profissional')) }}</h2>
    <form class="caixa" method="post" action="{{ route('barbearia.equipe.store') }}">
        @csrf <input type="hidden" name="empresa" value="{{ $empresa->id }}">
        <label for="nome">Nome</label>
        <input id="nome" type="text" name="nome" value="{{ old('nome') }}" required>
        @error('nome')<div class="campo-erro">{{ $message }}</div>@enderror
        <label for="telefone">Celular (será o login dele)</label>
        <input id="telefone" type="tel" inputmode="tel" name="telefone" value="{{ old('telefone') }}" placeholder="(21) 99999-9999" required>
        @error('telefone')<div class="campo-erro">{{ $message }}</div>@enderror
        <label for="senha">Senha</label>
        <input id="senha" type="text" name="senha" minlength="4" autocomplete="off" required>
        @error('senha')<div class="campo-erro">{{ $message }}</div>@enderror
        <p style="margin:14px 0 0"><button class="btn escuro" type="submit">Cadastrar</button></p>
    </form>

    <h2>QR code de check-in</h2>
    <div class="caixa" style="text-align:center">
        <p class="suave">Imprima e cole na parede da barbearia. Quem escaneia pode entrar na fila.</p>
        <div id="qr" style="display:inline-block;padding:10px;background:#fff"></div>
        <p class="suave" style="word-break:break-all;margin-top:10px;font-size:.8rem">{{ $linkQr }}</p>
        <form method="post" action="{{ route('barbearia.checkin.config') }}" style="text-align:left;margin-top:12px">
            @csrf <input type="hidden" name="empresa" value="{{ $empresa->id }}">
            <label style="display:flex;gap:10px;align-items:center;font-weight:500">
                <input type="checkbox" name="exige_qr" value="1" @checked($empresa->checkin_exige_qr)>
                Só deixar entrar na fila quem escaneou o QR (evita entrar na fila de casa)
            </label>
            <label style="display:flex;gap:10px;align-items:center;font-weight:500">
                <input type="checkbox" name="renovar" value="1">
                Gerar um QR novo (o impresso atual deixa de valer)
            </label>
            <p style="margin:12px 0 0"><button class="btn claro" type="submit">Salvar</button></p>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        if (window.QRCode) new QRCode(document.getElementById('qr'), { text: @json($linkQr), width: 220, height: 220 });
        else document.getElementById('qr').textContent = 'Não foi possível desenhar o QR agora. Use o link abaixo.';
    </script>
@endsection
