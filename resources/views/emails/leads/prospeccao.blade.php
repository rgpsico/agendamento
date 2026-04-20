<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #2a5298; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #333; line-height: 1.7; }
        .body h2 { color: #2a5298; font-size: 18px; }
        .cta { display: inline-block; margin: 24px 0; background: #2a5298; color: #fff !important; padding: 14px 32px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .footer { background: #f4f4f4; padding: 16px 32px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Plataforma de Agendamento Online</h1>
    </div>
    <div class="body">
        <h2>Olá, {{ $lead->nome }}!</h2>

        <p>
            Somos uma plataforma completa de <strong>agendamento online</strong> desenvolvida especialmente
            para estúdios de pilates, academias e profissionais da saúde.
        </p>

        <p>Com nossa solução você pode:</p>
        <ul>
            <li>✅ Receber agendamentos 24h pelo seu site ou link</li>
            <li>✅ Gerenciar alunos, horários e professores em um só lugar</li>
            <li>✅ Enviar lembretes automáticos por WhatsApp e e-mail</li>
            <li>✅ Aceitar pagamentos online integrados</li>
            <li>✅ Ter seu próprio site profissional em minutos</li>
        </ul>

        <p>
            Sem mensalidades abusivas. Simples de usar. Suporte humanizado.
        </p>

        <a href="{{ route('lead.rastrear', $lead->token) }}" class="cta">
            Quero conhecer a plataforma →
        </a>

        <p style="font-size:13px; color:#666;">
            Tem alguma dúvida? Responda este e-mail ou nos chame no WhatsApp.
            Ficaremos felizes em apresentar uma demonstração gratuita.
        </p>
    </div>
    <div class="footer">
        Você está recebendo este e-mail pois seu estúdio foi indicado para nossa plataforma.<br>
        Para não receber mais mensagens, basta responder com "remover".
    </div>
</div>
</body>
</html>
