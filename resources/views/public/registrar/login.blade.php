<x-public.layout title="Login">
@php
    // Configuração de nicho vinda do DetectTenant (cadastrada no super admin)
    $nicho = app()->has('currentNicho') ? app('currentNicho') : null;
    $site  = app()->has('currentSite')  ? app('currentSite')  : null;

    // Cores: nicho config > site > padrão
    $corA = $nicho?->cor_primaria   ?? $site?->cores['primaria']   ?? '#6a11cb';
    $corB = $nicho?->cor_secundaria ?? $site?->cores['secundaria'] ?? '#2575fc';

    // Imagem lateral: nicho config > capa do site > loginImage da config geral
    $imagemLateral = $nicho?->login_imagem_url
        ?? ($site?->capa ? asset('storage/' . $site->capa) : null)
        ?? ($loginImage ?? null);

    // Logo, emoji e nome do sistema
    $logoUrl     = $nicho?->logo_url ?? ($site?->logo ? asset('storage/' . $site->logo) : null);
    $nichoEmoji  = $nicho?->emoji ?? null;
    $nomeSistema = $nicho?->nome  ?? $site?->titulo ?? 'Bem-vindo de volta!';
@endphp

    <!-- Page Content -->
    @include('public.home._partials.modalregister')

    <div class="content top-space" style="
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, {{ $corA }}, {{ $corB }});
    ">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                        <div class="row g-0">

                            <!-- Lado esquerdo — imagem ou branding colorido -->
                            <div class="col-md-6 d-none d-md-flex" style="
                                background: linear-gradient(160deg, {{ $corA }}, {{ $corB }});
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                padding: 40px 24px;
                                position: relative;
                                overflow: hidden;
                            ">
                                @if($imagemLateral)
                                    <img src="{{ $imagemLateral }}"
                                         class="img-fluid h-100 w-100"
                                         alt="Login"
                                         style="object-fit: cover; position: absolute; inset: 0; opacity: .85;">
                                    <div style="position:relative;z-index:1;text-align:center">
                                        <div style="font-size:3rem">{{ $nichoEmoji }}</div>
                                        <h4 style="color:#fff;font-weight:700;margin-top:12px;text-shadow:0 2px 8px rgba(0,0,0,.3)">
                                            {{ $nomeSistema }}
                                        </h4>
                                    </div>
                                @else
                                    <div style="text-align:center;color:#fff">
                                        <div style="font-size:4rem;margin-bottom:16px">{{ $nichoEmoji ?? '🎯' }}</div>
                                        <h4 style="font-weight:800;margin-bottom:8px">{{ $nomeSistema }}</h4>
                                        <p style="opacity:.8;font-size:.9rem">Gerencie tudo em um só lugar.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Lado direito — formulário -->
                            <div class="col-md-6 p-5 bg-white">
                                <div class="login-header text-center mb-4">
                                    <x-alert />

                                    {{-- Logo do site se tiver --}}
                                    @if($site?->logo)
                                        <img src="{{ asset('storage/' . $site->logo) }}"
                                             alt="{{ $nomeSistema }}"
                                             style="max-height:52px;object-fit:contain;margin-bottom:12px">
                                    @else
                                        <div style="font-size:2rem;margin-bottom:8px">{{ $nichoEmoji ?? '👋' }}</div>
                                    @endif

                                    <h3 class="fw-bold" style="color:#1a1f36">Bem-vindo de volta!</h3>
                                    <p class="text-muted">{{ $nomeSistema }}</p>
                                </div>

                                <form action="{{ route('user.login') }}" method="POST">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label fw-semibold">E-mail</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control form-control-lg"
                                               placeholder="Digite seu e-mail"
                                               value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="senha" class="form-label fw-semibold">Senha</label>
                                        <input type="password" name="senha" id="senha"
                                               class="form-control form-control-lg"
                                               placeholder="Digite sua senha">
                                        @error('senha')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-end mb-4">
                                        <a href="#" class="text-decoration-none small" style="color:{{ $corA }}">
                                            Esqueceu a senha?
                                        </a>
                                    </div>

                                    <button class="btn btn-lg w-100 mb-3 text-white fw-bold" type="submit"
                                            style="background: linear-gradient(135deg, {{ $corA }}, {{ $corB }}); border: none;">
                                        Entrar
                                    </button>

                                    <div class="login-or text-center my-3">
                                        <span class="text-muted small">Ou entre com</span>
                                    </div>

                                    <a href="{{ route('aluno.googleAuth.redirect') }}"
                                       class="btn btn-outline-danger w-100 mb-2">
                                        <i class="fab fa-google me-2"></i> Google
                                    </a>

                                    <div class="text-center mt-3">
                                        <p class="text-muted small">Não tem uma conta?
                                            <a href="{{ route('home.registerAluno') }}"
                                               class="text-decoration-none fw-semibold"
                                               style="color:{{ $corA }}">Registre-se</a>
                                        </p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public.layout>
