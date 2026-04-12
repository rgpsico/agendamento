<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 32px; text-align: center; }
  .header h1 { color: #fff; margin: 0; font-size: 22px; }
  .header p { color: rgba(255,255,255,0.8); margin: 6px 0 0; font-size: 14px; }
  .body { padding: 32px; }
  .badge { display: inline-block; background: #f0fdf4; color: #16a34a; font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 20px; margin-bottom: 20px; }
  .field { margin-bottom: 16px; }
  .field label { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #9ca3af; margin-bottom: 4px; }
  .field span { display: block; font-size: 16px; color: #111827; font-weight: 500; }
  .divider { border: none; border-top: 1px solid #f3f4f6; margin: 24px 0; }
  .meta { font-size: 12px; color: #9ca3af; }
  .footer { background: #f9fafb; padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>🎯 Novo Lead Recebido</h1>
    <p>Pilates Gestão — Landing Page</p>
  </div>
  <div class="body">
    <span class="badge">✅ Lead qualificado</span>

    <div class="field">
      <label>Nome</label>
      <span>{{ $data['nome'] }}</span>
    </div>
    <div class="field">
      <label>Email</label>
      <span>{{ $data['email'] }}</span>
    </div>
    <div class="field">
      <label>WhatsApp</label>
      <span>{{ $data['whatsapp'] }}</span>
    </div>

    <hr class="divider">

    <p class="meta">🌐 IP: {{ $ip }}<br>🕐 {{ now()->format('d/m/Y H:i') }}</p>
  </div>
  <div class="footer">
    Pilates Gestão &copy; {{ date('Y') }} — pilatesgestao.com.br
  </div>
</div>
</body>
</html>