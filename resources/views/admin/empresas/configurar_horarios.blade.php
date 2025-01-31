<x-admin.layout title="Configurar Horários">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <x-header.titulo pageTitle="Horários para {{ $servico->nome }}"/>
            <x-alert/>

            <form action="{{ route('salvar.horarios', $servico->id) }}" method="POST">
                @csrf
                <input type="hidden" name="professor_id" value="{{ Auth::user()->professor->id }}">
                <input type="hidden" name="id_servico" value="{{ $servico->id }}">

                <div class="row">
                    @foreach($diaDaSemana as $dia)
                        <div class="col-lg-4 mb-4"> 
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ $dia->nome_dia }}</h6>
                                </div>
                                <div class="card-body">
                                    <div id="horarios-{{ $dia->id }}">
                                        @php
                                            $disponibilidadesDia = $disponibilidades->where('id_dia', $dia->id);
                                        @endphp

                                        @if($disponibilidadesDia->isEmpty())
                                            <div class="horario-item">
                                                <div class="form-group">
                                                    <label>Hora Início:</label>
                                                    <input type="time" name="start[{{ $dia->id }}][]" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Hora Fim:</label>
                                                    <input type="time" name="end[{{ $dia->id }}][]" class="form-control">
                                                </div>
                                            </div>
                                        @else
                                            @foreach($disponibilidadesDia as $disponibilidade)
                                                <div class="horario-item">
                                                    <div class="form-group">
                                                        <label>Hora Início:</label>
                                                        <input type="time" name="start[{{ $dia->id }}][]" class="form-control" value="{{ $disponibilidade->hora_inicio }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Hora Fim:</label>
                                                        <input type="time" name="end[{{ $dia->id }}][]" class="form-control" value="{{ $disponibilidade->hora_fim }}">
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm remove-horario">Remover</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm mt-2 add-horario" data-dia="{{ $dia->id }}">+ Adicionar horário</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>          
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.add-horario').forEach(function(button) {
                button.addEventListener('click', function() {
                    let diaId = this.getAttribute('data-dia');
                    let container = document.getElementById(`horarios-${diaId}`);

                    let div = document.createElement('div');
                    div.classList.add('horario-item');

                    div.innerHTML = `
                        <div class="form-group">
                            <label>Hora Início:</label>
                            <input type="time" name="start[${diaId}][]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Hora Fim:</label>
                            <input type="time" name="end[${diaId}][]" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-horario">Remover</button>
                    `;

                    container.appendChild(div);

                    div.querySelector('.remove-horario').addEventListener('click', function() {
                        this.parentElement.remove();
                    });
                });
            });

            document.querySelectorAll('.remove-horario').forEach(function(button) {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });
        });
    </script>
</x-admin.layout>
