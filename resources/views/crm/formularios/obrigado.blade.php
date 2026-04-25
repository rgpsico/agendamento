<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro recebido</title>
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <style>
        body { background: #f3f6fa; min-height: 100vh; display: grid; place-items: center; padding: 16px; }
        .panel { max-width: 620px; background: #fff; border: 1px solid #e6eaf0; border-radius: 8px; padding: 36px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    <main class="panel text-center">
        <p class="text-success fw-semibold mb-2">Cadastro recebido</p>
        <h1 class="h3 mb-3">Obrigado, {{ $lead->nome }}.</h1>
        <p class="text-muted mb-0">Sua solicitacao entrou no atendimento da campanha {{ $campanha->nome }}. Em breve a equipe entrara em contato.</p>
    </main>
</body>
</html>
