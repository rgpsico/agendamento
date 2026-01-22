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
                            <label>Selecoes rapidas:</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input js-folga-preset" type="checkbox"
                                        id="preset_todos" data-preset="todos">
                                    <label class="form-check-label" for="preset_todos">Todos os dias</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-folga-preset" type="checkbox"
                                        id="preset_seg_sex" data-preset="seg_sex">
                                    <label class="form-check-label" for="preset_seg_sex">Segunda a sexta</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-folga-preset" type="checkbox"
                                        id="preset_seg_sab" data-preset="seg_sab">
                                    <label class="form-check-label" for="preset_seg_sab">Segunda a sabado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-folga-preset" type="checkbox"
                                        id="preset_ter_sab" data-preset="ter_sab">
                                    <label class="form-check-label" for="preset_ter_sab">Terca a sabado (padrao sala)</label>
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
                                                value="{{ $dia->id }}" id="dia_{{ $dia->id }}"
                                                data-day="{{ strtolower($dia->nome_dia) }}">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var presetChecks = Array.prototype.slice.call(document.querySelectorAll('.js-folga-preset'));
            var folgaChecks = Array.prototype.slice.call(document.querySelectorAll('input[name="folga[]"]'));

            function normalizeDay(raw) {
                if (!raw) {
                    return '';
                }
                return raw
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');
            }

            function getDayKey(input) {
                var normalized = normalizeDay(input.getAttribute('data-day'));
                if (normalized.indexOf('domingo') !== -1) return 'sun';
                if (normalized.indexOf('segunda') !== -1) return 'mon';
                if (normalized.indexOf('terca') !== -1) return 'tue';
                if (normalized.indexOf('quarta') !== -1) return 'wed';
                if (normalized.indexOf('quinta') !== -1) return 'thu';
                if (normalized.indexOf('sexta') !== -1) return 'fri';
                if (normalized.indexOf('sabado') !== -1) return 'sat';
                return '';
            }

            function applyWorkingDays(workingDays) {
                folgaChecks.forEach(function (checkbox) {
                    var key = getDayKey(checkbox);
                    if (!key) {
                        return;
                    }
                    checkbox.checked = !workingDays.has(key);
                });
            }

            function applyPreset(preset) {
                if (preset === 'todos') {
                    applyWorkingDays(new Set(['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']));
                } else if (preset === 'seg_sex') {
                    applyWorkingDays(new Set(['mon', 'tue', 'wed', 'thu', 'fri']));
                } else if (preset === 'seg_sab') {
                    applyWorkingDays(new Set(['mon', 'tue', 'wed', 'thu', 'fri', 'sat']));
                } else if (preset === 'ter_sab') {
                    applyWorkingDays(new Set(['tue', 'wed', 'thu', 'fri', 'sat']));
                }
            }

            presetChecks.forEach(function (checkbox) {
                checkbox.addEventListener('change', function (event) {
                    if (!event.target.checked) {
                        return;
                    }
                    presetChecks.forEach(function (other) {
                        if (other !== event.target) {
                            other.checked = false;
                        }
                    });
                    applyPreset(event.target.getAttribute('data-preset'));
                });
            });
        });
    </script>
</x-admin.layout>
