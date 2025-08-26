<x-admin.layout title="Configurar Horários">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <!-- Page Header -->
            <x-header.titulo pageTitle="Configuração de Horários" />
            <!-- /Page Header -->

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Defina os horários de trabalho</h4>
                </div>

                <div class="card-body">
                    <x-alert />

                    <form method="POST" action="{{ route('horario.gerar') }}">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Serviço</label>
                                <select name="servico" class="form-control" required>
                                    <option value="">Selecione o serviço</option>
                                    @foreach ($servicos as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->titulo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-3">
                                <label>Início do expediente</label>
                                <input type="time" name="inicio" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Fim do expediente</label>
                                <input type="time" name="fim" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Duração de cada atendimento (min)</label>
                                <input type="number" name="duracao" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Intervalo entre atendimentos (min)</label>
                                <input type="number" name="intervalo" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Início do almoço</label>
                                <input type="time" name="almoco_inicio" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Fim do almoço</label>
                                <input type="time" name="almoco_fim" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Considerar feriados</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="feriados" value="1">
                                    <label class="form-check-label">Sim</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label>Dias de folga:</label>
                            <div class="row">
                                @foreach ($dias_da_semana as $dia)
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="folga[]"
                                                value="{{ $dia->id }}" id="dia_{{ $dia->id }}">
                                            <label class="form-check-label"
                                                for="dia_{{ $dia->id }}">{{ $dia->nome_dia }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Gerar Horários</button>
                            <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
