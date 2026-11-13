{{-- Login e cadastro rápido do cliente (só nome, celular e senha). Espera $voltar (caminho da própria barbearia). --}}
<div class="caixa" id="acesso">
    <div class="abas">
        <button type="button" data-aba="entrar" class="{{ $errors->hasBag('cadastro') ? '' : 'ativa' }}">Já tenho cadastro</button>
        <button type="button" data-aba="cadastrar" class="{{ $errors->hasBag('cadastro') ? 'ativa' : '' }}">Primeira vez</button>
    </div>

    <form method="post" action="{{ route('barbearia.entrar') }}" data-painel="entrar" @if($errors->hasBag('cadastro')) hidden @endif>
        @csrf
        <input type="hidden" name="voltar" value="{{ $voltar }}">
        <label for="e_tel">Celular</label>
        <input id="e_tel" type="tel" inputmode="tel" name="telefone" value="{{ old('telefone') }}" placeholder="(21) 99999-9999" autocomplete="tel" required>
        <label for="e_senha">Senha</label>
        <input id="e_senha" type="password" name="senha" autocomplete="current-password" required>
        @if($errors->hasBag('entrar'))<div class="campo-erro">{{ $errors->getBag('entrar')->first('telefone') }}</div>@endif
        <p style="margin:14px 0 0"><button class="btn escuro" type="submit">Entrar</button></p>
    </form>

    <form method="post" action="{{ route('barbearia.cadastro') }}" data-painel="cadastrar" @unless($errors->hasBag('cadastro')) hidden @endunless>
        @csrf
        <input type="hidden" name="voltar" value="{{ $voltar }}">
        <label for="c_nome">Seu nome</label>
        <input id="c_nome" type="text" name="nome" value="{{ old('nome') }}" autocomplete="name" required>
        @if($errors->hasBag('cadastro') && $errors->getBag('cadastro')->has('nome'))<div class="campo-erro">{{ $errors->getBag('cadastro')->first('nome') }}</div>@endif
        <label for="c_tel">Celular</label>
        <input id="c_tel" type="tel" inputmode="tel" name="telefone" value="{{ old('telefone') }}" placeholder="(21) 99999-9999" autocomplete="tel" required>
        @if($errors->hasBag('cadastro') && $errors->getBag('cadastro')->has('telefone'))<div class="campo-erro">{{ $errors->getBag('cadastro')->first('telefone') }}</div>@endif
        <label for="c_senha">Crie uma senha</label>
        <input id="c_senha" type="password" name="senha" autocomplete="new-password" minlength="4" required>
        @if($errors->hasBag('cadastro') && $errors->getBag('cadastro')->has('senha'))<div class="campo-erro">{{ $errors->getBag('cadastro')->first('senha') }}</div>@endif
        <p style="margin:14px 0 0"><button class="btn escuro" type="submit">Cadastrar e continuar</button></p>
    </form>
</div>
<script>
    document.querySelectorAll('#acesso [data-aba]').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('#acesso [data-aba]').forEach(function (x) { x.classList.toggle('ativa', x === b); });
            document.querySelectorAll('#acesso [data-painel]').forEach(function (f) { f.hidden = f.dataset.painel !== b.dataset.aba; });
        });
    });
</script>
