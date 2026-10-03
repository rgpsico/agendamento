<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Preparando seu teste…</title>
  <style>
    body{font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;color:#0f172a;text-align:center;padding:16px}
    .big{font-size:3rem}
  </style>
</head>
<body>
  <div><div class="big">🚀</div><h2>Teste liberado!</h2><p>Preparando o seu painel…</p></div>
  @include('validador._pixel', ['pixelEvento' => 'StartTrial'])
  <script>setTimeout(function () { window.location.replace(@json(route('funil.onboarding'))); }, 700);</script>
  <noscript><meta http-equiv="refresh" content="1; url={{ route('funil.onboarding') }}"></noscript>
</body>
</html>
