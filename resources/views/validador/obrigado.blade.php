<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Obrigado — {{ $marca }}</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;
      background:linear-gradient(160deg,{{ $corA }},{{ $corB }});color:#0f172a}
    .card{width:100%;max-width:460px;background:#fff;border-radius:22px;padding:34px 24px;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.25)}
    .big{font-size:3rem;margin-bottom:8px} h1{font-size:1.4rem;margin-bottom:6px} p{color:#64748b}
  </style>
</head>
<body>
  <main class="card">
    <div class="big">🎉</div>
    <h1>Já recebi suas respostas!</h1>
    <p>Obrigado{{ $q->nome ? ', ' . e(strtok($q->nome, ' ')) : '' }}. Em breve entro em contato.</p>
  </main>
</body>
</html>
