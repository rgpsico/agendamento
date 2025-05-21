<x-public.layout title="HOME">
    <style>
        #spinner {
            position: fixed; 
            top: 50%; 
            left: 50%; 
            z-index: 9999; 
        }
    </style>
    <!-- Breadcrumb -->
    <x-home.breadcrumb title="TESTE"/>
    <!-- /Breadcrumb -->
    <div id="spinner" class="spinner-border text-primary" role="status" style="display:none;">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="content" style="transform: none; min-height: 172.906px;">
        <div class="container" style="transform: none;">
            <div class="row" style="transform: none;">
                <div class="col-md-7 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <x-alert/>
                            <!-- Checkout Form -->
                            <form action="{{ route('empresa.pagamento.asaas') }}" method="post" id="payment-form">
                                @csrf
                                <div class="info-widget">
                                    <div class="payment-widget">
                                        <h4 class="card-title">Confirmação de Agendamento</h4>

                                        @php 
                                            $usuario = Auth::user();
                                            $usuario->load('aluno');
                                        @endphp

                                        <input type="hidden" name="aluno_id" value="{{ Auth::user()->aluno->id ?? Auth::user()->professor->id }}">
                                        <input type="hidden" name="professor_id" value="{{ $professor->id }}">
                                        <input type="hidden" name="modalidade_id" value="{{ $professor->modalidade_id ?? 1 }}">
                                        <input type="hidden" id="data_aula" name="data_aula" value="" placeholder="data_aula">
                                        <input type="hidden" id="hora_aula" name="hora_aula" value="" placeholder="hora_aula">
                                        <input type="hidden" id="valor_aula" name="valor_aula" value="" placeholder="valor_aula">
                                        <input type="hidden" id="titulo" name="titulo" value="" placeholder="titulo">

                                        <!-- Exibir informações do agendamento -->
                                        <div class="payment-list">
                                            <p><strong>Data:</strong> <span id="display_data_aula"></span></p>
                                            <p><strong>Hora:</strong> <span id="display_hora_aula"></span></p>
                                            <p><strong>Serviço:</strong> <span id="display_titulo"></span></p>
                                            <p><strong>Valor:</strong> R$ <span id="display_valor_aula"></span></p>
                                        </div>

                                        <button type="submit" class="btn btn-success my-5">Confirmar e Pagar</button>
                                    </div>
                                </div>
                            </form>

                            <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
                            <script>
                                $(document).ready(function() {
                                    var diaDaSemana = localStorage.getItem('diaDaSemana');
                                    var data = localStorage.getItem('data');
                                    var horaDaAula = localStorage.getItem('horaDaAula');
                                    
                                    $('#data_aula').val(data);
                                    $('#hora_aula').val(horaDaAula);
                                    $('#display_data_aula').text(data);
                                    $('#display_hora_aula').text(horaDaAula);

                                    var servico = localStorage.getItem('servicos');
                                    if (servico) {
                                        var res = JSON.parse(servico);
                                        $('#valor_aula').val(res[0].preco);
                                        $('#titulo').val(res[0].titulo);
                                        $('#display_valor_aula').text(res[0].preco);
                                        $('#display_titulo').text(res[0].titulo);
                                    }

                                    $('#payment-form').on('submit', function(event) {
                                        var data_aula = $('#data_aula').val();
                                        var hora_aula = $('#hora_aula').val();
                                        var valor_aula = $('#valor_aula').val();
                                        var titulo = $('#titulo').val();

                                        if (!data_aula || !hora_aula || !valor_aula || !titulo) {
                                            alert('Todos os campos são obrigatórios!');
                                            event.preventDefault();
                                        } else {
                                            $('#spinner').show();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <x-detalhes-agendamento-confirm :model="$model"/>
            </div>
        </div>
    </div>
</x-public.layout>