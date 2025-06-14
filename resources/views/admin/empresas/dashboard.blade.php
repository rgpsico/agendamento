<x-admin.layout title="DashBoard">
    <style>
        .date-filter .form-control {
            width: 40%;
            margin: 5px;
        }

        #filter-button {
            width: 15%;
        }

        .btn {
            margin-top: 5px;
            height: 40px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="modal fade" id="empresaInativaModal" tabindex="-1" aria-labelledby="empresaInativaModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empresaInativaModalLabel">Sistema Bloqueado</h5>
                </div>
                <div class="modal-body">
                    <p>Sua empresa está <strong>inativa</strong>. Para desbloquear o acesso ao sistema, é necessário
                        efetuar o pagamento do boleto pendente.</p>
                    <p>Por favor, clique no botão abaixo para acessar o boleto e regularizar sua situação.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('empresa.pagamento.boleto') }}" class="btn btn-primary">Pagar Boleto</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="content container-fluid">

            @include('admin.empresas._partials.header')
            {{-- @include('admin.empresas._partials.modal') --}}
            <div class="row">
                @foreach ([['Total de Alunos', $numeroTotalDeAlunos, 'fe-users', 'primary'], ['Total Arrecadação', 'R$ ' . number_format($arrecadacao, 2, ',', '.'), 'fe-credit-card', 'success'], ['Número de Aulas', $numero_total_de_aulas, 'fe-money', 'danger'], ['Aulas Canceladas', $aulasCanceladas, 'fe-alert-circle', 'danger']] as $item)
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="dash-widget-header">
                                    <span class="dash-widget-icon text-{{ $item[3] }}">
                                        <i class="fe {{ $item[2] }}"></i>
                                    </span>
                                    <div class="dash-count">
                                        <h3>{{ $item[1] }}</h3>
                                    </div>
                                </div>
                                <div class="dash-widget-info">
                                    <h6 class="text-muted">{{ $item[0] }}</h6>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-{{ $item[3] }} w-50"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="row">
                <div class="col-md-12 col-lg-6">

                    <!-- Sales Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Arrecadação</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <!-- /Sales Chart -->

                </div>
                <div class="col-md-12 col-lg-6">

                    <!-- Invoice Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Alunos</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <!-- /Invoice Chart -->

                </div>
            </div>


        </div>
    </div>


    <script>
        $(document).ready(function() {
            var arrecadacaoDatas = {!! json_encode(array_keys($arrecadacaoPorDia->toArray())) !!};
            var arrecadacaoValores = {!! json_encode(array_values($arrecadacaoPorDia->toArray())) !!};

            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: arrecadacaoDatas,
                    datasets: [{
                        label: 'Arrecadação',
                        data: arrecadacaoValores,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var aulasCanceladas = {{ $aulasCanceladas }};
            var aulasRealizadas = {{ $realizadas }};

            var ctx2 = document.getElementById('statusChart').getContext('2d');
            var statusChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Aulas Feitas', 'Aulas Canceladas'],
                    datasets: [{
                        data: [aulasRealizadas, aulasCanceladas],
                        backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('empresa.verificarStatus', ['empresaId' => Auth::user()->empresa->id ?? '']) }}",
                method: "GET",
                success: function(response) {
                    if (response.status === 'inativo') {
                        // Exibir o modal
                        $('#empresaInativaModal').modal('show');
                        // Desabilitar a sidebar
                        $('#sidebar').addClass('sidebar-disabled');
                        // Desabilitar todos os links da sidebar
                        $('#sidebar a').each(function() {
                            $(this).attr('href', 'javascript:void(0);').css('cursor',
                                'not-allowed');
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Erro ao verificar status da empresa:', xhr.responseText);
                }
            });
        });
    </script>

    <!-- /Page Wrapper -->
    </x-layoutsadmin>
