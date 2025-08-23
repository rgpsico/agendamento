<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação de Agendamento</title>
</head>
<body>
    <h2>Olá, {{ $dados['cliente'] }}</h2>
    <p>Seu agendamento foi confirmado!</p>
    <p><b>Serviço:</b> {{ $dados['servico'] }}</p>
    <p><b>Data:</b> {{ $dados['data'] }}</p>
</body>
</html>
