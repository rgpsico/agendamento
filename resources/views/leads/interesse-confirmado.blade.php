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
                    <hr>
                    <p class="small text-muted mb-0">
                        Plataforma de Agendamento Online<br>
                        {{ config('app.url') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
