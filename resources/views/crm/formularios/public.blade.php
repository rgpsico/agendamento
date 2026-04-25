<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campanha->formulario_titulo_final }}</title>
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <style>
        body { background: #f3f6fa; min-height: 100vh; }
        .form-shell { max-width: 760px; margin: 0 auto; padding: 48px 16px; }
        .form-panel { background: #fff; border: 1px solid #e6eaf0; border-radius: 8px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .form-header { padding: 32px 32px 0; }
        .form-body { padding: 24px 32px 32px; }
    </style>
</head>
<body>
    <main class="form-shell">
        <div class="form-panel">
            <div class="form-header">
                <p class="text-primary fw-semibold mb-2">{{ $campanha->canal }}</p>
                <h1 class="h3 mb-3">{{ $campanha->formulario_titulo_final }}</h1>
                @if($campanha->formulario_descricao)
                    <p class="text-muted mb-0">{{ $campanha->formulario_descricao }}</p>
                @endif
            </div>
            <div class="form-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        Verifique os campos destacados e tente novamente.
                    </div>
                @endif

                <form method="POST" action="{{ route('public.campanhas.formulario.store', $campanha->public_token) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" required>
                        @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interesse</label>
                        <input type="text" name="interesse" class="form-control @error('interesse') is-invalid @enderror" value="{{ old('interesse') }}" placeholder="Pilates aparelho, aula experimental, estetica...">
                        @error('interesse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Observacoes</label>
                        <textarea name="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="4">{{ old('observacoes') }}</textarea>
                        @error('observacoes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary btn-lg w-100">{{ $campanha->formulario_botao ?: 'Quero agendar uma aula' }}</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
