<x-admin.layout title="Listar Alunos">

    @include('admin.modalidade._partials.modal')
    <x-modal-editar-usuario/>
    @include('components.modal-delete')
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           {{-- <x-header.titulo pageTitle="{{$pageTitle}}" btAdd="true" route="{{$route}}" /> --}}
            <!-- /Page Header -->

            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">Esporte</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="">Admin</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:(0);">Esporte </a>
                            </li>
                      
                        </ul>
                    </div>
                </div>
                </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nome</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model as $value )
                                        <tr class="linha_id-{{$value->id}}">
                                         <td>{{$value->id}}</td>
                                         <td>{{$value->nome}}</td>
                                         <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" 
                                                        data-data="{{$model}}"
                                                        data-nome="{{$value->nome}}"
                                                        data-id="{{$value->id}}"
                                                         id="editar_bt">
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>
                                                    <a data-bs-toggle="modal" href="#delete_modal" data-id="{{$value->id}}" class="btn btn-sm bg-danger-light bt_excluir">
                                                        <i class="fe fe-trash"></i> Excluir
                                                    </a>
                                                </div>
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
            </div>
            
        </div>			
    </div>
   
   
  
    <script src="{{asset('request.js')}}"></script>

   <script>

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


$(document).ready(function(){
    $(document).on("click", ".bt_excluir", function() {
        var id = $(this).data('id');
         
        $('.confirmar_exclusao').attr('data_id', id);
        $(".delete_modal").modal('show');
    });

    $(document).on("click", ".confirmar_exclusao", function() {
        var id = $(this).attr('data_id');
    
        $.ajax({
            url:'/api/modalidade/' + id+'/destroy',
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                $(".modal").modal('hide');
                $(".linha_id-" + id).fadeOut();
            },
            error: function(request,msg,error) {
                console.log(error);
            }
        });
    });
});



$(document).on("click", "#editar_bt", function() {
    $("#modal_editar").modal('show');
    $(".modal-title").text('Editar Modalidade');
    var nome = $(this).data('nome');
    var id = $(this).data('id');
    $("#nome_modalidade").val(nome);
    $("#modalidade_id").val(id);

});
$(document).on("click", "#salvar_modalidade", function() {
    var nome = $('#nome_modalidade').val();
    var id =  $('#modalidade_id').val()
 
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/modalidade/' + id+'/update', 
        method: 'POST',             
        data: {
            nome: nome,
            id:id
        },
        success: function(response) {
            // Trate a resposta aqui. Por exemplo:
            $('.linha_id-' + id).find('td:eq(1)').text(nome);
            alert('Modalidade atualizada com sucesso!');
            $('.modal').modal('hide')
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Trate os erros aqui. Por exemplo:
            alert('Erro ao atualizar a modalidade: ' + errorThrown);
        }
    });
});





    
   </script>

</x-layoutsadmin>