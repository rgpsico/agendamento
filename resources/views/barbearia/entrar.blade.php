@extends('barbearia.layout')
@section('titulo', 'Entrar')
@section('conteudo')
    <h1>Entrar</h1>
    <p class="suave">Barbeiros, donos e clientes entram com o celular e a senha.</p>

    <div class="caixa">
        <form method="post" action="{{ route('barbearia.entrar') }}">
            @csrf
            <label for="e_tel">Celular</label>
            <input id="e_tel" type="tel" inputmode="tel" name="telefone" value="{{ old('telefone') }}" placeholder="(21) 99999-9999" autocomplete="tel" required autofocus>
            <label for="e_senha">Senha</label>
            <input id="e_senha" type="password" name="senha" autocomplete="current-password" required>
            @if($errors->hasBag('entrar'))<div class="campo-erro">{{ $errors->getBag('entrar')->first('telefone') }}</div>@endif
            <p style="margin:14px 0 0"><button class="btn escuro" type="submit">Entrar</button></p>
        </form>
    </div>

    <p class="suave">Quer entrar na fila de uma barbearia? <a href="{{ route('barbearia.index') }}">Escolha a barbearia</a> para se cadastrar.</p>
@endsection
