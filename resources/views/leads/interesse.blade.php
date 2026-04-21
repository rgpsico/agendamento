<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso gratuito — Plataforma de Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
        .logo-area { background: #2a5298; border-radius: 16px 16px 0 0; padding: 32px; text-align: center; }
        .logo-area h1 { color: #fff; font-size: 24px; margin: 0; }
        .logo-area p { color: rgba(255,255,255,.85); margin: 8px 0 0; font-size: 15px; }
        .beneficios li { padding: 4px 0; }
        .btn-cta { background: #2a5298; border: none; padding: 14px; font-size: 17px; font-weight: bold; border-radius: 50px; }
        .btn-cta:hover { background: #1e3c72; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="logo-area">
                    <h1>Olá, {{ $lead->nome }}! 👋</h1>
                    <p>Preparamos um acesso gratuito para você testar nossa plataforma.</p>
                </div>
                <div class="card-body p-4">
                    <ul class="beneficios list-unstyled mb-4">
                        <li>✅ Agendamentos online 24h</li>
                        <li>✅ Gestão de alunos e horários</li>
                        <li>✅ Lembretes automáticos por WhatsApp</li>
                        <li>✅ Pagamentos integrados</li>
                        <li>✅ Seu site profissional em minutos</li>
                    </ul>

                    <form action="{{ route('lead.interesse.store', $lead->token) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-cta btn-primary w-100">
                            Quero meu acesso gratuito →
                        </button>
                    </form>

                    <p class="text-muted text-center small mt-3 mb-0">
                        Você receberá login e senha por e-mail em instantes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
