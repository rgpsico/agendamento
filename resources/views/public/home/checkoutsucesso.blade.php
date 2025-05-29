<x-public.layout title="HOME">
    <style>
        .success-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .success-cont {
            text-align: center;
            padding: 30px;
        }

        .success-cont i.fa-check {
            font-size: 50px;
            color: #28a745;
            background-color: #e7f3e9;
            border-radius: 50%;
            padding: 20px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .success-cont h3 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .success-cont p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .success-cont .btn {
            font-size: 16px;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .success-cont .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .success-cont .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .success-cont .btn-whatsapp {
            background-color: #25d366;
            border-color: #25d366;
            color: #fff;
        }

        .success-cont .btn-whatsapp:hover {
            background-color: #1ebe56;
            border-color: #1ebe56;
        }

        .success-cont .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .success-cont .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }

        .lesson-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .lesson-details p {
            margin: 5px 0;
            font-size: 15px;
        }

        @media (max-width: 576px) {
            .success-cont {
                padding: 20px;
            }

            .success-cont h3 {
                font-size: 20px;
            }

            .success-cont p {
                font-size: 14px;
            }

            .success-cont .btn {
                font-size: 14px;
                padding: 8px 16px;
                display: block;
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>

    <!-- Breadcrumb -->
    <x-home.breadcrumb title="CONFIRMAÇÃO"/>
    <!-- /Breadcrumb -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Success Card -->
                <div class="card success-card">
                    <div class="card-body">
                        <div class="success-cont">
                            <i class="fas fa-check"></i>
                            <h3>Sua aula foi agendada com sucesso!</h3>
                            <div class="lesson-details">
                                <p>
                                    <strong>Professor:</strong> {{ Str::ucfirst($professor->usuario->nome ?? 'Professor') }}
                                </p>
                                <p>
                                    <strong>Atividade:</strong> Surfe
                                </p>
                                <p>
                                    <strong>Data:</strong> <span class="data_aula">Não informado</span>
                                </p>
                                <p>
                                    <strong>Hora:</strong> <span class="hora_aula">Não informado</span>
                                </p>
                                <p>
                                    <strong>Pagamento:</strong> 
                                    @if (session('payment_method') === 'presencial')
                                        Presencial no dia da aula
                                    @elseif (session('payment_method') === 'pix')
                                        Via PIX 
                                    @elseif (session('payment_method') === 'cartao')
                                        Via Cartão de Crédito (confirmado)
                                    @else
                                        Método não especificado
                                    @endif
                                </p>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('recibo', ['id' => $professor->id]) }}" class="btn btn-primary view-inv-btn">Ver Recibo</a>
                            </div>
                            <div class="col-12 my-2">
                                <a href="https://wa.me/{{ $professor->usuario->telefone }}" target="_blank" class="btn btn-whatsapp">
                                    <i class="fab fa-whatsapp"></i> Falar com o Professor
                                </a>
                            </div>
                            <div class="col-12 my-2">
                                <a href="{{ route('home.index') }}" class="btn btn-secondary">
                                    Voltar para a Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Success Card -->
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Carregar dados do localStorage
            var diaDaSemana = localStorage.getItem('diaDaSemana');
            var data = localStorage.getItem('data');
            var horaDaAula = localStorage.getItem('horaDaAula');

            // Formatar a data para DD/MM/YYYY
            if (data) {
                var partes = data.split('-');
                if (partes.length === 3) {
                    var dataFormatada = partes[2] + '/' + partes[1] + '/' + partes[0];
                    $('.data_aula').text(dataFormatada);
                } else {
                    $('.data_aula').text(data);
                }
            } else {
                $('.data_aula').text('Não informado');
            }

            // Exibir hora da aula
            if (horaDaAula) {
                $('.hora_aula').text(horaDaAula);
            } else {
                $('.hora_aula').text('Não informado');
            }
        });
    </script>
</x-public.layout>