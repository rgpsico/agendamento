@php
    // E-mail que o dono do site recebe a cada contato do formulário. Tabelas e estilos inline: é o que os
    // clientes de e-mail (Gmail, Outlook/Hotmail, iPhone) desenham do mesmo jeito.
    $zapDigits = preg_replace('/\D+/', '', (string) ($whatsapp ?? ''));
    $zapLink = $zapDigits ? 'https://wa.me/' . $zapDigits . '?text=' . rawurlencode("Olá {$nome}! Aqui é da {$marca}, recebemos seu contato pelo site.") : null;
    $linhas = array_filter([
        'Data da viagem'  => $dataViagem ?? null,
        'Pessoas'         => $pessoas ?? null,
        'País'            => $pais ?? null,
    ], fn ($v) => filled($v));
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<title>Novo contato pelo site</title>
</head>
<body style="margin:0;padding:0;background:#eef3f8;font-family:Arial,Helvetica,sans-serif;color:#0f2238;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $nome }} quer saber mais{{ !empty($passeio) ? ' sobre ' . $passeio : '' }}.</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3f8;">
<tr><td align="center" style="padding:28px 12px;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 24px rgba(11,37,69,.08);">

    {{-- Cabeçalho --}}
    <tr><td style="background:#0b2545;background-image:linear-gradient(135deg,#0b2545 0%,#0b5ea8 60%,#14b8c4 100%);padding:28px 24px 24px;">
      <div style="font-size:24px;font-weight:800;letter-spacing:.3px;color:#ffffff;">{{ $marca }}</div>
      <div style="margin-top:4px;height:3px;width:44px;background:#f2b705;border-radius:2px;"></div>
      <div style="margin-top:18px;font-size:13px;letter-spacing:1.5px;text-transform:uppercase;color:#bfe9ee;">Novo contato pelo site</div>
      <div style="margin-top:6px;font-size:22px;line-height:1.3;font-weight:700;color:#ffffff;">{{ $nome }} quer saber mais{{ !empty($passeio) ? ' sobre' : '' }}</div>
      @if(!empty($passeio))<div style="margin-top:2px;font-size:18px;font-weight:700;color:#f2b705;">{{ $passeio }}</div>@endif
    </td></tr>

    {{-- Contato --}}
    <tr><td style="padding:26px 24px 6px;">
      <div style="font-size:12px;letter-spacing:1.2px;text-transform:uppercase;color:#53657a;font-weight:700;margin-bottom:10px;">Contato</div>
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
        <tr><td style="padding:6px 0;color:#53657a;width:90px;">Nome</td><td style="padding:6px 0;font-weight:700;">{{ $nome }}</td></tr>
        <tr><td style="padding:6px 0;color:#53657a;">E-mail</td><td style="padding:6px 0;"><a href="mailto:{{ $email }}" style="color:#0b5ea8;text-decoration:none;font-weight:700;">{{ $email }}</a></td></tr>
        @if(!empty($whatsapp))<tr><td style="padding:6px 0;color:#53657a;">WhatsApp</td><td style="padding:6px 0;font-weight:700;">{{ $whatsapp }}</td></tr>@endif
      </table>
    </td></tr>

    {{-- Detalhes da viagem --}}
    @if(count($linhas))
    <tr><td style="padding:18px 24px 6px;">
      <div style="font-size:12px;letter-spacing:1.2px;text-transform:uppercase;color:#53657a;font-weight:700;margin-bottom:10px;">Detalhes da viagem</div>
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f9fc;border:1px solid #dde7f0;border-radius:12px;font-size:15px;">
        @foreach($linhas as $rotulo => $valor)
        <tr><td style="padding:12px 16px;color:#53657a;width:125px;{{ !$loop->last ? 'border-bottom:1px solid #dde7f0;' : '' }}">{{ $rotulo }}</td>
            <td style="padding:12px 16px;font-weight:700;{{ !$loop->last ? 'border-bottom:1px solid #dde7f0;' : '' }}">{{ $valor }}</td></tr>
        @endforeach
      </table>
    </td></tr>
    @endif

    {{-- Mensagem --}}
    @if(!empty($mensagem))
    <tr><td style="padding:18px 24px 6px;">
      <div style="font-size:12px;letter-spacing:1.2px;text-transform:uppercase;color:#53657a;font-weight:700;margin-bottom:10px;">Mensagem</div>
      <div style="border-left:4px solid #14b8c4;background:#f5f9fc;border-radius:0 12px 12px 0;padding:14px 18px;font-size:15px;line-height:1.6;color:#0f2238;">{!! nl2br(e($mensagem)) !!}</div>
    </td></tr>
    @endif

    {{-- Ações --}}
    <tr><td style="padding:24px 24px 8px;">
      {{-- Botões em inline-block: lado a lado no computador, um embaixo do outro no celular. --}}
      <div>
        @if($zapLink)<a href="{{ $zapLink }}" style="display:inline-block;margin:0 8px 10px 0;background:#1fa855;color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;padding:13px 20px;border-radius:999px;white-space:nowrap;">Responder no WhatsApp</a>@endif
        <a href="mailto:{{ $email }}?subject={{ rawurlencode($marca . ' — seu contato pelo site') }}" style="display:inline-block;margin:0 8px 10px 0;background:#0b5ea8;color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;padding:13px 20px;border-radius:999px;white-space:nowrap;">Responder por e-mail</a>
      </div>
      <div style="font-size:13px;color:#53657a;margin-top:4px;">Responda rápido: quem é atendido na primeira hora fecha muito mais.</div>
    </td></tr>

    {{-- Rodapé --}}
    <tr><td style="padding:22px 24px 26px;">
      <div style="border-top:1px solid #dde7f0;padding-top:16px;font-size:12px;line-height:1.6;color:#8193a6;">
        Este contato também foi salvo no seu CRM: <a href="{{ $crmUrl }}" style="color:#0b5ea8;">ver leads</a>.<br>
        Enviado pelo formulário do site {{ $siteUrl }}
      </div>
    </td></tr>

  </table>
</td></tr>
</table>
</body>
</html>
