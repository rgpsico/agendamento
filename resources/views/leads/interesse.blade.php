<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quero conhecer a plataforma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
        .logo-area { background: #2a5298; border-radius: 16px 16px 0 0; padding: 32px; text-align: center; }
        .logo-area h1 { color: #fff; font-size: 22px; margin: 0; }
        .logo-area p { color: rgba(255,255,255,.8); margin: 6px 0 0; font-size: 14px; }
        .btn-primary { background: #2a5298; border-color: #2a5298; padding: 12px; font-size: 16px; }
        .btn-primary:hover { background: #1e3c72; border-color: #1e3c72; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="logo-area">
                    <h1>🚀 Que ótimo!</h1>
                    <p>Olá, <strong>{{ $lead->nome }}</strong>! Deixe seu WhatsApp e entraremos em contato.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('lead.interesse.store', $lead->token) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Seu WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp" class="form-control form-control-lg @error('whatsapp') is-invalid @enderror"
                                   placeholder="(21) 99999-9999" required autofocus>
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" class="form-control" value="{{ $lead->email }}" disabled>
                        </div>

                        <div class="mb-3 p-3 rounded" style="background:#f0f4ff; border:1.5px solid #2a5298;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="quer_trial" value="1" id="querTrial">
                                <label class="form-check-label fw-semibold" for="querTrial">
                                    🚀 Quero testar o sistema gratuitamente
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1 ms-4">
                                Vou receber um login e senha por e-mail para explorar a plataforma.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            Confirmar interesse →
                        </button>
                    </form>

                    <p class="text-muted text-center small mt-3 mb-0">
                        Entraremos em contato em até 24 horas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
