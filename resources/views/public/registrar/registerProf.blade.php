<head>
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/font-awesome.min.css') }}">

    <style>
        /* Ajustes para telas menores */
        @media (max-width: 768px) {
            .loginbox {
                padding: 20px;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }

            .login-left {
                text-align: center;
                padding: 10px;
            }

            .login-left img {
                width: 100px; /* Redimensiona a imagem em telas menores */
                height: auto;
            }

            .login-right-wrap {
                padding: 20px;
            }
        }

        .main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .loginbox {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        .btn-google {
            background: #dd4b39;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            display: block;
            margin-top: 15px;
        }

        .btn-google i {
            margin-right: 5px;
        }
    </style>
</head>

<div class="main-wrapper">
    <div class="loginbox">
        <div class="row">
            <!-- Logo (visível em todas as telas agora) -->
            <div class="col-md-6 login-left d-flex align-items-center justify-content-center">
                <img class="img-fluid" src="{{ asset('admin/img/logo-white.png') }}" alt="Logo">
            </div>

            <div class="col-md-6 login-right">
                <div class="login-right-wrap">
                    <h1 class="text-center">Registrar Escola de Surf</h1>
                    <p class="account-subtitle text-center">Acesse seu painel administrativo</p>

                    <!-- Formulário -->
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo_usuario" value="Professor">

                        <div class="form-group">
                            <x-text-input name="nome" label="Nome" placeholder="Nome: Roger Ne" />
                        </div>

                        <div class="form-group">
                            <x-text-input name="email" label="Email" placeholder="email@124.com" />
                        </div>

                        <div class="form-group">
                            <x-text-input type="password" name="senha" label="Senha" />
                        </div>

                        <div class="form-group">
                            <x-text-input type="password" name="senha" label="Repetir Senha" />
                        </div>

                        <div class="form-group">
                            <label for="modalidade_id">Modalidade</label>
                            <select name="modalidade_id" class="form-control" id="modalidade_id">
                                @foreach ($modalidade as $value)
                                    <option value="{{ $value->id }}">{{ $value->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <button class="btn btn-primary w-100" type="submit">Registrar</button>
                        </div>
                    </form>

                    <!-- Linha divisória -->
                    <div class="login-or text-center">
                        <span class="or-line"></span>
                        <span class="span-or">Ou</span>
                    </div>

                    <!-- Login com Google -->
                    <div class="col-12 text-center">
                        <a href="{{ route('prof.login.google') }}" class="btn-google">
                            <i class="fab fa-google"></i> Login com Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
