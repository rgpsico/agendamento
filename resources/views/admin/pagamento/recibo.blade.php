<x-public.layout title="RECIBO">
    <style>
        .receipt-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin: 20px 0;
            padding: 20px;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .receipt-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .receipt-header p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .receipt-details p,
        .user-details p,
        .payment-details p {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }

        .receipt-details strong,
        .user-details strong,
        .payment-details strong {
            color: #555;
        }

        .receipt-details,
        .user-details,
        .payment-details {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .btn-print {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .receipt-card {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }

        @media (max-width: 576px) {
            .receipt-card {
                padding: 15px;
            }

            .receipt-header h3 {
                font-size: 20px;
            }

            .receipt-details p,
            .user-details p,
            .payment-details p {
                font-size: 14px;
            }

            .btn-print {
                width: 100%;
                padding: 8px;
            }
        }
    </style>

    <!-- Breadcrumb -->
    <x-home.breadcrumb title="RECIBO"/>
    <!-- /Breadcrumb -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="receipt-card">
                    <div class="receipt-header">
                        <h3>Recibo de Agendamento</h3>
                        <p>Emitido em: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="user-details">
                        <p><strong>Aluno:</strong> {{ Str::ucfirst($aluno->usuario->nome ?? 'Aluno') }}</p>
                        <p><strong>Professor:</strong> {{ Str::ucfirst($professor->usuario->nome ?? 'Professor') }}</p>
                    </div>

                    <div class="receipt-details">
                        <p><strong>Atividade:</strong> Surfe</p>
                        <p><strong>Data da Aula:</strong> {{ \Carbon\Carbon::parse($agendamento->data_da_aula)->format('d/m/Y') }}</p>
                        <p><strong>Horário:</strong> {{ $agendamento->horario }}</p>
                        <p><strong>Valor:</strong> R$ {{ number_format($agendamento->valor_aula, 2, ',', '.') }}</p>
                    </div>

                    <div class="payment-details">
                        <p><strong>Método de Pagamento:</strong> 
                            @if ($pagamento->metodo_pagamento === 'PRESENCIAL')
                                Presencial
                            @elseif ($pagamento->metodo_pagamento === 'PIX')
                                PIX
                            @elseif ($pagamento->metodo_pagamento === 'CREDIT_CARD')
                                Cartão de Crédito
                            @else
                                Não especificado
                            @endif
                        </p>
                        <p><strong>Status do Pagamento:</strong> 
                            @if ($pagamento->status === 'PENDING')
                                Pendente
                            @elseif ($pagamento->status === 'RECEIVED')
                                Confirmado
                            @else
                                {{ $pagamento->status }}
                            @endif
                        </p>
                        <p><strong>Valor Pago:</strong> R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                    </div>

                    <div class="text-center no-print">
                        <button class="btn btn-print" onclick="window.print()">Imprimir Recibo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public.layout>