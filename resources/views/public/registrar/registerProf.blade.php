<head>
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/font-awesome.min.css') }}">

    <style>
        /* Estilos gerais */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        .register-box h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .register-box .account-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.3);
        }

        .btn-primary {
            background-color: #2575fc;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1a5bbf;
        }

        .btn-google {
            background: #dd4b39;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            display: block;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-google:hover {
            background-color: #c23321;
        }

        .btn-google i {
            margin-right: 5px;
        }

        .login-or {
            position: relative;
            text-align: center;
            margin: 20px 0;
        }

        .login-or .or-line {
            display: block;
            height: 1px;
            background: #ddd;
            width: 100%;
            position: absolute;
            top: 50%;
            left: 0;
            z-index: 1;
        }

        .login-or .span-or {
            background: #fff;
            padding: 0 10px;
            position: relative;
            z-index: 2;
            color: #666;
            font-size: 14px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 10px;
            }

            .register-box {
                padding: 20px;
            }

            .register-box h1 {
                font-size: 20px;
            }

            .register-box .account-subtitle {
                font-size: 12px;
            }

            .form-control {
                font-size: 12px;
            }

            .btn-primary, .btn-google {
                font-size: 14px;
            }

            .login-left {
                text-align: center;
                margin-bottom: 20px;
            }

            .login-left img {
                width: 80px;
                height: auto;
            }

            .row {
                flex-direction: column;
            }

            .col-md-6 {
                width: 100%;
            }
        }
    </style>
</head>

<div class="main-wrapper">
    <div class="register-box">
        <div class="row">
            <!-- Logo -->
            <div class="col-md-6 login-left d-flex align-items-center justify-content-center">
                <img class="img-fluid" src="{{ asset('admin/img/logo-white.png') }}" alt="Logo">
            </div>

            <!-- Formulário de Cadastro -->
            <div class="col-md-6 login-right">
                <div class="login-right-wrap">
                    <h1>Cadastro de Professor</h1>
                    <p class="account-subtitle">Preencha os dados para se registrar</p>

                    <!-- Formulário -->
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo_usuario" value="Professor">

                        <!-- Nome -->
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu email" required>
                        </div>

                        <!-- Senha -->
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>

                        <!-- Repetir Senha -->
                        <div class="form-group">
                            <label for="confirmar_senha">Repetir Senha</label>
                            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" placeholder="Repita sua senha" required>
                        </div>

                        <!-- Modalidade -->
                        <div class="form-group">
                            <label for="modalidade_id">Modalidade</label>
                            <select name="modalidade_id" class="form-control" id="modalidade_id" required>
                                @foreach ($modalidade as $value)
                                    <option value="{{ $value->id }}">{{ $value->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botão de Registro -->
                        <div class="form-group mb-3">
                            <button class="btn btn-primary w-100" type="submit">Registrar</button>
                        </div>
                    </form>

                    <!-- Divisor "Ou" -->
                    <div class="login-or">
                        <span class="or-line"></span>
                        <span class="span-or">Ou</span>
                    </div>

                    <!-- Login com Google -->
                    <div class="col-12 text-center">
                        <a href="{{ route('prof.login.google') }}" class="btn-google">
                            <i class="fab fa-google"></i> Registrar com Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>