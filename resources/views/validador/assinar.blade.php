<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Assinar</title>
  <style>
    body{font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#0f172a;padding:16px}
    .card{max-width:460px;width:100%;background:#fff;border-radius:18px;padding:30px 24px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.08)}
    h2{margin-bottom:8px} p{color:#64748b;line-height:1.5;margin-bottom:16px} a{color:#0d9488;font-weight:600}
  </style>
</head>
<body>
  <main class="card">
    @switch($estado)
      @case('ja_cliente')
        <h2>Sua assinatura já está ativa ✅</h2><p>Está tudo certo, não precisa fazer mais nada.</p>
        @break
      @case('indisponivel')
        <h2>Quase lá!</h2><p>O pagamento online está sendo ativado. Já avisei a equipe, e entraremos em contato para concluir a sua assinatura.</p>
        @break
      @case('erro')
        <h2>Não consegui gerar o pagamento</h2><p>Foi um problema do nosso lado. Já avisei a equipe, e vamos resolver e te chamar.</p>
        @break
      @default
        <h2>Assinatura</h2><p>Não encontrei um teste ligado à sua conta. Fale com a gente que ajudamos.</p>
    @endswitch
    <a href="{{ route('cliente.dashboard') }}">Voltar ao painel</a>
  </main>
</body>
</html>
