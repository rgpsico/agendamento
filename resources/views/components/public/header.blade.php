<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!empty($empresaId))
        <meta name="empresa-id" content="{{ $empresaId }}">
    @endif

    <!-- Favicons -->
    <link href="{{ asset('template/assets/img/favicon.png') }}" rel="icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/feather.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/select2/css/select2.min.css') }}">

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fancybox/jquery.fancybox.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Google tag (gtag.js) -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-4ZMP2C63TR"> --}}
    </script>
    {{-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-4ZMP2C63TR');
  </script> --}}
</head>

<body>

    <!-- Main Wrapper -->
    <x-header />

    <!-- Scripts -->

    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/analytics.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validação de confirmação de senha
            try {


                function validatePasswordMatch(passwordId, confirmPasswordId) {
                    const password = document.getElementById(passwordId);
                    const confirmPassword = document.getElementById(confirmPasswordId);

                    if (!password || !confirmPassword) return; // ← evita o erro

                    function checkMatch() {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('As senhas não coincidem');
                        } else {
                            confirmPassword.setCustomValidity('');
                        }
                    }

                    password.addEventListener('input', checkMatch);
                    confirmPassword.addEventListener('input', checkMatch);
                }


                // Aplicar validação para ambos os formulários
                validatePasswordMatch('alunoSenha', 'alunoConfirmarSenha');
                validatePasswordMatch('professorSenha', 'professorConfirmarSenha');

                // Handlers para os formulários (para demonstração)
                document.getElementById('alunoForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Cadastro de aluno enviado! (Demonstração)');
                    // Aqui você colocaria a lógica real de envio
                });

                document.getElementById('professorForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Cadastro de professor enviado! (Demonstração)');
                    // Aqui você colocaria a lógica real de envio
                });

                // Log quando o modal for aberto
                const registerModal = document.getElementById('registerModal');
                registerModal.addEventListener('show.bs.modal', function() {
                    console.log('Modal de registro está sendo exibido.');
                });
            } catch (error) {
                console.log('aa')
            }
        });
    </script>
</body>

</html>
