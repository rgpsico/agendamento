<x-admin.layout title="Listar Alunos">

    <x-modal-delete/>
    <x-modal-editar-usuario/>
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}" btAdd="true" route="{{$route}}" />
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
                                          
                                            <th>Aluno</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($model as $value)
                                            <tr class="linha_-{{$value->id}}">
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="{{route('alunos.show',['id' => $value->id ])}}" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{asset('admin/img/patients/patient15.jpg')}}" alt="User Image">
                                                        </a>
                                                        <a href="{{route('alunos.show',['id' => $value->id ])}}">{{$value->usuario->nome}}</a>
                                                    </h2>
                                                </td>
                                                <td>{{$value->usuario->email ?? ''}}</td>
                                                <td>{{$value->usuario->telefone ?? ''}}</td>
                                                <!-- ... rest of the code ... -->
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
    
        $(document).on("click", ".bt_excluir_aluno", function() {
        var id = $(this).data('id');
         $('.confirmar_exclusao').data('id_aluno', id);
         $(".delete_modal").modal('show');
    });

    $(document).on("click", ".confirmar_exclusao", function() {
        var id_aluno = $(this).data('id_aluno');
        var token = "seu_token_aqui"; // substitua com o seu token
    deleteUser( '/api/users/','.linha_-'+id_aluno, id_aluno, token);
    });


    $(document).on("click", "#editar_aluno", function() {
    $("#modal_editar").modal('show');
    var data = $(this).data('data')[0];
        console.log(data)
    // Preencher os valores dos inputs com os dados obtidos
    $("#nome").val(data.nome);
    $("#sobreNome").val(data.sobreNome);
    $("#nascimento").val(data.nascimento);
    $("#email").val(data.email);
    $("#cep").val(data.cep);
    $("#rua").val(data.rua);
    $("#cidade").val(data.cidade);
    $("#estado").val(data.estado);
    $("#numero").val(data.numero);
});



})
    
   </script>

</x-layoutsadmin>