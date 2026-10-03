<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Seu teste grátis — {{ $marca }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --a: {{ $corA }}; --b: {{ $corB }}; --ink: #0f172a; --soft: #64748b; --line: #e2e8f0; }
    body { font-family: 'Poppins', system-ui, sans-serif; color: var(--ink); min-height: 100vh; padding: 16px;
      background: linear-gradient(160deg, var(--a), var(--b)); display: flex; justify-content: center; align-items: flex-start; }
    .card { width: 100%; max-width: 560px; background: #fff; border-radius: 22px; padding: 28px 22px; margin: 12px 0 24px; box-shadow: 0 24px 60px rgba(0,0,0,.25); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; margin-bottom: 18px; }
    .brand img { height: 34px; } .brand .emoji { font-size: 1.6rem; }
    h1 { font-size: 1.5rem; line-height: 1.25; margin-bottom: 8px; }
    .lead { color: var(--soft); font-size: .95rem; line-height: 1.5; margin-bottom: 16px; }
    .perfil { background: color-mix(in srgb, var(--a) 10%, #fff); border-radius: 14px; padding: 12px 14px; font-size: .88rem; margin-bottom: 18px; }
    .plano { border: 2px solid var(--a); border-radius: 18px; padding: 18px; margin-bottom: 18px; }
    .plano .nome { font-weight: 700; font-size: 1.05rem; }
    .plano .preco { font-size: 2rem; font-weight: 800; margin: 4px 0; }
    .plano .preco small { font-size: .9rem; font-weight: 500; color: var(--soft); }
    .plano .selo { display: inline-block; background: var(--a); color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 99px; margin-bottom: 8px; }
    .plano ul { list-style: none; margin-top: 10px; display: grid; gap: 6px; font-size: .9rem; }
    .plano li::before { content: '✓'; color: var(--a); font-weight: 800; margin-right: 8px; }
    label { display: block; font-size: .8rem; font-weight: 600; color: var(--soft); margin: 12px 0 5px; }
    input { width: 100%; font: inherit; font-size: 1rem; padding: 13px 14px; border-radius: 12px; border: 2px solid var(--line); }
    input:focus { outline: none; border-color: var(--a); }
    .erro { background: #fee2e2; color: #991b1b; border-radius: 12px; padding: 10px 12px; font-size: .88rem; margin-bottom: 12px; }
    .btn { width: 100%; margin-top: 18px; min-height: 54px; border: none; border-radius: 14px; background: var(--a); color: #fff; font: inherit; font-weight: 700; font-size: 1.05rem; cursor: pointer; }
    .nota { text-align: center; color: var(--soft); font-size: .78rem; margin-top: 12px; }
  </style>
</head>
<body>
  <main class="card">
    <div class="brand">@if($logo)<img src="{{ $logo }}" alt="{{ $marca }}">@else<span class="emoji">{{ $emoji }}</span>@endif<span>{{ $marca }}</span></div>

    <h1>Pronto{{ $q->nome ? ', ' . e(strtok($q->nome, ' ')) : '' }}! Seu teste grátis está liberado.</h1>
    <p class="lead">
      Com o que você me contou, o plano abaixo atende o seu negócio
      @if($q->nicho && $q->nicho !== 'outro') de <strong>{{ strtolower($q->nichoRotulo()) }}</strong>@endif.
      @if($q->nivel_digital === 'basico')
        Como você está começando no digital, <strong>eu mesmo vou te ajudar a montar tudo</strong> nos primeiros dias.
      @elseif($q->nivel_digital === 'intermediario')
        Dá pra começar sozinho(a), e se travar em algum ponto é só me chamar.
      @else
        Pelo seu perfil, você deve começar sozinho(a) em poucos minutos.
      @endif
    </p>

    <div class="plano">
      <span class="selo">{{ $dias }} dias grátis</span>
      <div class="nome">Plano {{ $plano['nome'] }}</div>
      <div class="preco">R$ {{ number_format($plano['valor'], 0, ',', '.') }}<small>/mês depois do teste</small></div>
      <ul>
        <li>Agenda online e reservas pelo celular</li>
        <li>Cadastro de clientes e financeiro completo</li>
        <li>Site próprio e domínio personalizado</li>
        <li>Cobrança automática dos seus clientes</li>
        <li>Sem fidelidade: cancele quando quiser</li>
      </ul>
    </div>

    @if($errors->any())<div class="erro">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('validador.teste', $q->token) }}">
      @csrf
      <label for="negocio">Nome do seu negócio</label>
      <input id="negocio" name="negocio" type="text" maxlength="120" required value="{{ old('negocio') }}" placeholder="Ex: Passeios do Zé">
      <label for="email">Seu melhor e-mail (será seu login)</label>
      <input id="email" name="email" type="email" maxlength="150" required value="{{ old('email') }}" autocomplete="email" placeholder="voce@email.com">
      <label for="senha">Crie uma senha</label>
      <input id="senha" name="senha" type="password" minlength="6" maxlength="100" required autocomplete="new-password" placeholder="Mínimo 6 caracteres">
      <button class="btn" type="submit">Começar meu teste grátis</button>
    </form>
    <p class="nota">Você só paga depois dos {{ $dias }} dias, se quiser continuar.</p>
  </main>
  @include('validador._pixel', ['pixelEvento' => 'ViewContent'])
</body>
</html>
