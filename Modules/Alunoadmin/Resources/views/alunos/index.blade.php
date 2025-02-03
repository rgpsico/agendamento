@extends('alunoadmin::layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


<div class="page-wrapper" style="min-height: 239px;">
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{$title}}"  />
        <!-- /Page Header -->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="datatable table table-hover table-center mb-0 dataTable">
                        <thead>
                            <tr>
                                <th>Professoraaa</th>
                                <th>Modalidade</th>
                                <th>Data da Aula</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($agendamentos as $agendamento)
                            <tr>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="" class="avatar avatar-sm me-2">
                                            <img class="avatar-img rounded-circle" src="{{ asset('admin/img/doctors/doctor-02.jpg') }}" alt="User Image">
                                        </a>
                                        <a href="">{{ $agendamento->professor->usuario->nome ?? 'AQUI' }}</a>
                                    </h2>
                                </td>
                                <td>{{ $agendamento->modalidade->nome }}</td>
                                <td>
                                    <span class="text-primary d-block">{{ date('d/m/Y', strtotime($agendamento->data_da_aula)) }}</span>
                                </td>

                               
                                <td>
                                    @php
                                        // Define as cores com base no status
                                        $statusColors = [
                                            'Aula Realizada' => 'bg-success text-white',
                                            'Aula Cancelada' => 'bg-danger text-white',
                                            'Aula Adiada pelo Professor' => 'bg-warning text-dark',
                                            'Aula Adiada pelo Aluno' => 'bg-info text-dark',
                                        ];
                                
                                        // Obtém a classe correspondente ao status
                                        $badgeClass = $statusColors[$agendamento->status] ?? 'bg-secondary text-white';
                                    @endphp
                                
                                    <span class="badge rounded-pill {{ $badgeClass }} p-2">
                                        {{ $agendamento->status }}
                                    </span>
                                </td>

                                <td class='text-success font-weight-bold'>R$ {{ number_format($agendamento->preco, 2, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-primary avaliar-btn" 
                                            data-id="{{ $agendamento->id }}" 
                                            data-status="{{ $agendamento->status }}">
                                        Avaliar
                                    </button>
                                    <a href="" class="btn btn-secondary">Mensagem</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>  

<!-- Modal de Avaliação -->
<div class="modal fade" id="avaliacaoModal" tabindex="-1" aria-labelledby="avaliacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avaliacaoModalLabel">Avaliar Aula</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="avaliacaoForm" action="{{ route('empresa.avaliacao.store') }}" method="POST">
                @csrf
                <input type="hidden" name="agendamento_id" id="agendamento_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status da Aula</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Aula Realizada">Aula Realizada</option>
                            <option value="Aula Cancelada">Aula Cancelada</option>
                            <option value="Aula Adiada pelo Professor">Aula Adiada pelo Professor</option>
                            <option value="Aula Adiada pelo Aluno">Aula Adiada pelo Aluno</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comentários (Opcional)</label>
                        <textarea name="comentario" id="comentario" class="form-control" rows="3" placeholder="Escreva um comentário sobre a aula..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar Avaliação</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
    $(document).ready(function () {
    
        $(document).on("click", ".avaliar-btn", function(){
       
            var agendamentoId = $(this).data("id");
            var statusAtual = $(this).data("status");

            console.log("Agendamento ID:", agendamentoId);
            console.log("Status Atual:", statusAtual);

            // Preenche os campos do modal
            $("#agendamento_id").val(agendamentoId);
            $("#status").val(statusAtual);

            // Abre o modal
            $("#avaliacaoModal").modal("show");
        });
    });
</script>
@endsection
