<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação de Agendamento</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin:0; padding:0;">

    <table align="center" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 20px; border-radius: 8px; margin-top: 30px;">
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                <h2 style="color: #333333;">Olá, {{ $dados['aluno'] }}</h2>
                <p style="color: #555555; font-size: 16px;">Seu agendamento foi confirmado!</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">
                <table width="100%" cellpadding="5" cellspacing="0">
                    <tr>
                        <td style="font-weight: bold; color: #333;">Serviço / Modalidade:</td>
                        <td style="color: #555;">{{ $dados['modalidade'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Data da Aula:</td>
                        <td style="color: #555;">{{ $dados['data'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Horário:</td>
                        <td style="color: #555;">{{ $dados['horario'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Valor:</td>
                        <td style="color: #555;">R$ {{ number_format($dados['valor'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Status do Pagamento:</td>
                        <td style="color: #555;">{{ ucfirst($dados['status']) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px; text-align: center;">
                <p style="color: #777; font-size: 14px;">Obrigado por usar nossos serviços!<br>Equipe RJPASSEIOS</p>
            </td>
        </tr>
    </table>

</body>
</html>
