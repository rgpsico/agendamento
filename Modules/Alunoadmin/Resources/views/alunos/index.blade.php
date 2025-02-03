@extends('alunoadmin::layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
    .star-rating {
    font-size: 2rem;
    cursor: pointer;
    color: #ccc;
}

.star-rating i {
    transition: color 0.3s;
}

.star-rating i.text-warning {
    color: #ffc107;
}

</style>

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
                                <th>Professora</th>
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
                                    <button class="btn btn-primary atualizar-btn" data-id="{{ $agendamento->id }}" data-status="{{ $agendamento->status }}">
                                        Atualizar Status
                                    </button>
                                
                                    <button class="btn btn-warning avaliar-btn" data-id="{{ $agendamento->id }}" data-professor="{{ $agendamento->professor->usuario->nome ?? 'AQUI' }}">
                                        Avaliar Aula ⭐
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
<div class="modal fade" id="avaliacaoAulaModal" tabindex="-1" aria-labelledby="avaliacaoAulaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avaliacaoAulaModalLabel">Avaliar Aula de <span id="professorNome"></span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="avaliacaoAulaForm" action="{{ route('empresa.avaliacao.store') }}" method="POST">
                @csrf
                <input type="hidden" name="agendamento_id" id="avaliacao_agendamento_id">
                
                <div class="modal-body">
                    <div class="form-group text-center">
                        <label class="mb-2">Dê sua nota:</label>
                        <div class="star-rating">
                            <i class="fas fa-star" data-rating="1"></i>
                            <i class="fas fa-star" data-rating="2"></i>
                            <i class="fas fa-star" data-rating="3"></i>
                            <i class="fas fa-star" data-rating="4"></i>
                            <i class="fas fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="nota" id="avaliacao_nota" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Comentários (Opcional)</label>
                        <textarea name="comentario" id="avaliacao_comentario" class="form-control" rows="3" placeholder="Escreva um comentário sobre a aula..."></textarea>
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


<!-- Modal de Avaliação -->
<div class="modal fade" id="atualizarStatusModal" tabindex="-1" aria-labelledby="atualizarStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="atualizarStatusModalLabel">Avaliar Aula</h5>
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
    
        $(document).on("click", ".atualizar-btn", function(){
       
            var agendamentoId = $(this).data("id");
            var statusAtual = $(this).data("status");

            console.log("Agendamento ID:", agendamentoId);
            console.log("Status Atual:", statusAtual);

            // Preenche os campos do modal
            $("#agendamento_id").val(agendamentoId);
            $("#status").val(statusAtual);

            // Abre o modal
            $("#atualizarStatusModal").modal("show");
        });
    });
</script>



<script>
   $(document).ready(function () {
    $(".avaliar-btn").on("click", function () {
        let agendamentoId = $(this).data("id");
        let professorNome = $(this).data("professor");

        $("#avaliacao_agendamento_id").val(agendamentoId);
        $("#professorNome").text(professorNome);
        $(".star-rating i").removeClass("text-warning");

        // Buscar avaliação já existente
        $.ajax({
            type: "GET",
            url: `/avaliacao/${agendamentoId}`,
            success: function (response) {
                if (response.avaliacao) {
                    $("#avaliacao_nota").val(response.avaliacao.nota);
                    $("#avaliacao_comentario").val(response.avaliacao.comentario);

                    // Preenche as estrelas conforme a nota já salva
                    $(".star-rating i").removeClass("text-warning");
                    for (let i = 1; i <= response.avaliacao.nota; i++) {
                        $(".star-rating i[data-rating='" + i + "']").addClass("text-warning");
                    }
                }
                $("#avaliacaoAulaModal").modal("show");
            }
        });
    });

    // Marcar estrelas ao clicar
    $(".star-rating i").on("click", function () {
        let rating = $(this).data("rating");
        $("#avaliacao_nota").val(rating);
        $(".star-rating i").removeClass("text-warning");

        for (let i = 1; i <= rating; i++) {
            $(".star-rating i[data-rating='" + i + "']").addClass("text-warning");
        }
    });

    // Enviar avaliação
    $("#avaliacaoAulaForm").on("submit", function (e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "{{ route('avaliacao.store') }}",
            data: formData,
            success: function (response) {
                alert(response.message);
                $("#avaliacaoAulaModal").modal("hide");
            },
            error: function (xhr) {
                alert("Erro ao enviar avaliação. Tente novamente.");
            }
        });
    });
});


</script>

@endsection
