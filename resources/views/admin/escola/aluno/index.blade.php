<x-admin.layout title="{{$pageTitle}}">

    <x-modal-delete/>
    <x-modal-editar-usuario/>
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">{{$pageTitle ?? ''}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="">Admin</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:(0);">Alunos </a>
                            </li>
                      
                        </ul>
                    </div>
                </div>
        
                <div class="row">
                   <div class="col-2 my-4">
                            <a href="{{route('alunos.create')}}" class="btn btn-success Adicionar{{$pageTitle}}">
                                <i class="icon icon-plus">Adicionar {{$pageTitle}}</i>
                            </a>
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
                                            <th>Telefone</th>
                                            <th>Endereço</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                        @foreach ($model as $aluno)
                                      
                                  
                                        <tr class="linha_{{$aluno->id}}">
                                            <td>{{$aluno->usuario->id}}</td>                           
                                            <td>{{$aluno->usuario->nome}}</td>
                                            <td>{{$aluno->usuario->telefone}}</td>
                                            <td>{{$aluno->usuario->email}}</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" 
                                                        href="{{route('aluno.edit',['id' => $aluno->usuario->id])}}"
                                                        data-data="{{$aluno}}"
                                                        >
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>
                                                    <a data-bs-toggle="modal" href="#delete_modal" data-id="{{$aluno->id}}" data-professor_id='{{Auth::user()->professor->id}}' class="btn btn-sm bg-danger-light bt_excluir">
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
        var professor_id = $(this).data('professor_id');

         $('.confirmar_exclusao').data('id', id);
         $('.confirmar_exclusao').data('professor_id', professor_id);
         $(".delete_modal").modal('show');

    });

    $(document).on("click", ".confirmar_exclusao", function() {
     
        var id = $(this).data('id');
        var professor_id = $(this).data('professor_id');
        var token = $('meta[name="csrf-token"]').attr('content');
     
   
        $.ajax({
    url: '/api/aluno/' + id + '/destroy/' + professor_id,
    type: 'DELETE',
    docType:"json",
    headers: {
        'Authorization': 'Bearer ' + token,
    },
    success: function(result, textStatus, xhr) {  // note que acrescentamos parâmetros adicionais aqui
        if (xhr.status == 200) {  // Verifique o status da resposta
            alert('Excluído com sucesso');
            $('.modal').modal('hide');
            $(".linha_" + id).fadeOut();
        }
    },
    error: function(request, msg, error) {
        console.log(error);
    }
});


 });


   


})
    
   </script>

</x-layoutsadmin>