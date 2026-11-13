@extends('barbearia.layout')
@section('titulo', 'Equipe')
@section('conteudo')
    <h1>{{ $empresa->nome }}</h1>
    <p class="suave">Painel do dono</p>

    @if($barbearias->count() > 1)
        <form method="get" style="margin-bottom:12px">
            <select name="empresa" onchange="this.form.submit()" style="width:100%;padding:12px;border-radius:10px;border:1px solid var(--linha)">
                @foreach($barbearias as $b)<option value="{{ $b->id }}" @selected($b->id === $empresa->id)>{{ $b->nome }}</option>@endforeach
            </select>
        </form>
    @endif

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
