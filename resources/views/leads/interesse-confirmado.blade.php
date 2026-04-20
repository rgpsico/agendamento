<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recebemos seu contato!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
        .check-circle { width: 80px; height: 80px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 36px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-5 text-center">
                    <div class="check-circle">✅</div>
                    <h2 class="fw-bold">Recebemos seu contato!</h2>
                    <p class="text-muted mt-2">
                        Obrigado, <strong>{{ $lead->nome }}</strong>!<br>
                        Nossa equipe entrará em contato com você em breve pelo WhatsApp.
                    </p>

                    @if($senhaClear)
                        <div class="mt-3 p-3 rounded text-start" style="background:#f0f4ff; border:2px dashed #2a5298;">
                            <p class="fw-bold mb-2">🎉 Seu acesso de teste foi criado!</p>
                            <p class="mb-1">📧 <strong>Login:</strong> {{ $lead->email }}</p>
                            <p class="mb-1">🔑 <strong>Senha:</strong> <code class="fs-5">{{ $senhaClear }}</code></p>
                            <p class="mb-0 small text-muted">Estas credenciais também foram enviadas para o seu e-mail.</p>
                        </div>
                        <a href="{{ config('app.url') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                            Acessar agora →
                        </a>
                    @else
                        <hr>
                        <p class="small text-muted mb-0">
                            Plataforma de Agendamento Online<br>
                            {{ config('app.url') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
