<x-admin.layout title="Listar Alunos">

    <x-modal-delete/>
    <x-modal-editar-usuario/>
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           {{-- <x-header.titulo pageTitle="{{$pageTitle}}" btAdd="true" route="{{$route}}" /> --}}
            <!-- /Page Header -->
            
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
    $(document).ready(function(){
        $(document).on("click", ".bt_excluir", function() {
        var id = $(this).data('id');
      
         $('.confirmar_exclusao').attr('data_id', id);
         $(".delete_modal").modal('show');
    });

    $(document).on("click", ".confirmar_exclusao", function() {
        var id = $(this).attr('data_id');

        var token = ""; // substitua com o seu token
    deleteUser( '/api/modalidade/','.linha_id-'+id, id, token);
    });


    $(document).on("click", "#editar_bt", function() {
    $("#modal_editar").modal('show');
    var data = $(this).data('data')[0];
    $("#nome").val(data.nome);

});



})
    
   </script>

</x-layoutsadmin>