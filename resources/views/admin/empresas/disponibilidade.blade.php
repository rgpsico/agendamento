<x-admin.layout title="Disponibilidade">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{$pageTitle}}"/>
            <!-- /Page Header -->
            <x-alert/>

            <div class="card shadow mb-5">
                <div class="card-header">
                    <h5 class="card-title mb-0">Definir disponibilidade para todos os dias</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="marcarTodos" {{ $mesmoHorario ? 'checked' : '' }}>
                        <label class="form-check-label" for="marcarTodos">Marcar Todos</label>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="startTodos">Hora Início para Todos:</label>
                                <input type="time" id="startTodos" class="form-control" value="{{ $horaInicio }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="endTodos">Hora Fim para Todos:</label>
                                <input type="time" id="endTodos" class="form-control" value="{{ $horaFim }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{route('empresa.disponibilidade.store')}}" method="POST">
            <div class="row">
                @csrf
                <input type="hidden" name="professor_id" value="{{Auth::user()->professor->id}}">
                @foreach($diaDaSemana as $dia)
                    <div class="col-lg-3 mb-4"> 
                        @php
                        $disponibilidade = $disponibilidades->firstWhere('id_dia', $dia->dia);
                        @endphp
                        <div class="card shadow">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $dia->id }}" id="dia{{ $dia->id }}" name="dias[]" {{ $disponibilidade ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dia{{ $dia->id }}">
                                        {{ $dia->nome_dia }}                               
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="start{{ $dia->id }}">Hora Início:</label>
                                    <input type="time" id="start{{ $dia->id }}" name="start[]" class="form-control" value="{{ $disponibilidade ? $disponibilidade->hora_inicio : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="end{{ $dia->id }}">Hora Fim:</label>
                                    <input type="time" id="end{{ $dia->id }}" name="end[]" class="form-control" value="{{ $disponibilidade ? $disponibilidade->hora_fim : '' }}">
                                </div>
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
    // Seu código JS aqui...
    </script>
</x-layoutsadmin>
