<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Contato</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 30px 25px;
        }
        
        .contact-info {
            background: #f8f9ff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
            display: flex;
            align-items: center;
        }
        
        .info-label::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            margin-right: 10px;
        }
        
        .info-value {
            color: #212529;
            flex: 1;
        }
        
        .message-section {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }
        
        .message-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        
        .message-title::before {
            content: '💬';
            margin-right: 8px;
        }
        
        .message-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #667eea;
            font-style: italic;
            color: #495057;
            line-height: 1.7;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header {
                padding: 20px 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎯 Novo Contato Recebido!</h1>
            <p>{{ $site->nome ?? 'Seu Site' }}</p>
        </div>
        
        <div class="content">
            <div class="contact-info">
                <div class="info-row">
                    <div class="info-label">Nome</div>
                    <div class="info-value"><strong>{{ $data['nome'] }}</strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <a href="mailto:{{ $data['email'] }}" style="color: #667eea; text-decoration: none;">
                            {{ $data['email'] }}
                        </a>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Telefone</div>
                    <div class="info-value">
                        @if(isset($data['telefone']) && $data['telefone'])
                            <a href="tel:{{ $data['telefone'] }}" style="color: #667eea; text-decoration: none;">
                                {{ $data['telefone'] }}
                            </a>
                        @else
                            <span style="color: #6c757d; font-style: italic;">Não informado</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Serviço</div>
                    <div class="info-value">
                        @if(isset($data['servico']) && $data['servico'])
                            <span style="background: #e7f3ff; color: #0066cc; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                {{ $data['servico'] }}
                            </span>
                        @else
                            <span style="color: #6c757d; font-style: italic;">Não informado</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="message-section">
                <div class="message-title">Mensagem do Cliente</div>
                <div class="message-content">
                    @if(isset($data['mensagem']) && $data['mensagem'])
                        "{{ $data['mensagem'] }}"
                    @else
                        <span style="color: #6c757d;">Nenhuma mensagem foi enviada.</span>
                    @endif
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="mailto:{{ $data['email'] }}" class="cta-button">
                    📧 Responder Cliente
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>Este email foi enviado automaticamente pelo sistema de contato do site {{ $site->nome ?? 'seu site' }}.</p>
            <p style="margin: 5px 0 0 0;">📅 Recebido em {{ date('d/m/Y \à\s H:i') }}</p>
        </div>
    </div>
</body>
</html>