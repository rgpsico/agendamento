<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}"/>
            <!-- /Page Header -->
            <x-alert/>
            <div class="row">
                <form action="{{route('empresa.disponibilidade.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="professor_id" value="{{Auth::user()->professor->id}}">
                    @foreach($diaDaSemana as $dia)
                    <div class="col-12 col-sm-6 col-lg-3">
                        @php
                         $disponibilidade = $disponibilidades->firstWhere('id_dia', $dia->dia);
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $dia->id }}" id="dia{{ $dia->id }}" name="dias[]" {{ $disponibilidade ? 'checked' : '' }}>
                            <label class="form-check-label" for="dia{{ $dia->id }}">
                                {{ $dia->nome_dia }}                               
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="start{{ $dia->id }}">Hora Início:</label>
                            <input type="time" id="start{{ $dia->id }}" name="start[]" class="form-control" value="{{ $disponibilidade ? $disponibilidade->hora_inicio : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="end{{ $dia->id }}">Hora Fim:</label>
                            <input type="time" id="end{{ $dia->id }}" name="end[]" class="form-control" value="{{ $disponibilidade ? $disponibilidade->hora_fim : '' }}">
                        </div>
                    </div>
                    @endforeach
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
            
            
            
            
        </div>			
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>