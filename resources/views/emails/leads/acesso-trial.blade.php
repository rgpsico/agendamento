<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #2a5298; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #333; line-height: 1.7; }
        .credenciais { background: #f0f4ff; border: 2px dashed #2a5298; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .credenciais p { margin: 6px 0; font-size: 16px; }
        .credenciais strong { color: #2a5298; }
        .cta { display: inline-block; margin: 24px 0; background: #2a5298; color: #fff !important; padding: 14px 32px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .footer { background: #f4f4f4; padding: 16px 32px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Seu acesso de teste está pronto!</h1>
    </div>
    <div class="body">
        <p>Olá, <strong>{{ $lead->nome }}</strong>!</p>
        <p>Criamos um acesso temporário para você explorar nossa plataforma à vontade.</p>

        <div class="credenciais">
            <p>🔗 <strong>Acesso:</strong> <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
            <p>📧 <strong>Login:</strong> {{ $lead->email }}</p>
            <p>🔑 <strong>Senha:</strong> {{ $senhaClear }}</p>
        </div>

        <a href="{{ config('app.url') }}" class="cta">Acessar agora →</a>

        <p style="font-size:13px; color:#666;">
            Qualquer dúvida, responda este e-mail ou nos chame no WhatsApp. Temos prazer em ajudar!
        </p>
    </div>
    <div class="footer">
        Acesso temporário para avaliação da plataforma.
    </div>
</div>
</body>
</html>
