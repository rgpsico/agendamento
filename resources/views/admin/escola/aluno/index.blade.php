<x-admin.layout title="Listar Alunos">

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
                        <a class="btn btn-success AdicionarAluno">
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
                                          
                                            <th>Aluno</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model as $value )
                                            
                                    
                                        <tr class="linha_-{{$value->id}}">
                                          
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="{{route('alunos.show',['id' => $value->id ])}}" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{asset('admin/img/patients/patient15.jpg')}}" alt="User Image"></a>
                                                    <a href="{{route('alunos.show',['id' => $value->id ])}}">{{$value->nome}}</a>
                                                </h2>
                                            </td>
                                   
                                            <td>{{$value->usuario->email ?? ''}}</td>
                                            <td>{{$value->usuario->telefone ?? ''}}</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" 
                                                        data-data="{{$model}}"
                                                         id="editar_aluno">
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>

                                                    <a class="btn btn-sm bg-info " href="{{route('alunos.show',['id' => $value->id])}}">
                                                        <i class="fe fe-eye"></i> Ver

                                                    </a>
                                                    <a data-bs-toggle="modal" href="#delete_modal" data-id="{{$value->id}}" class="btn btn-sm bg-danger-light bt_excluir_aluno">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="{{asset('request.js')}}"></script>

   <script>
$(document).ready(function(){

    function getNewRowHTML(aluno) {
    return `<tr class="linha_-${aluno.id}">
        <td>
            <h2 class="table-avatar">
                <a href="/alunos/${aluno.id}" class="avatar avatar-sm me-2">
                    <img class="avatar-img rounded-circle" src="/admin/img/patients/patient15.jpg" alt="User Image">
                </a>
                <a href="/alunos/${aluno.id}">${aluno.nome}</a>
            </h2>
        </td>
        <td>${aluno.email || ''}</td>
        <td>${aluno.telefone || ''}</td>
        <td class="text-center">
            <div class="actions">
                <a class="btn btn-sm bg-info-light" id="editar_aluno">
                    <i class="fe fe-pencil"></i> Editar
                </a>
                <a class="btn btn-sm bg-info " href="/alunos/${aluno.id}">
                    <i class="fe fe-eye"></i> Ver
                </a>
                <a data-bs-toggle="modal" href="#delete_modal" data-id="${aluno.id}" class="btn btn-sm bg-danger-light bt_excluir_aluno">
                    <i class="fe fe-trash"></i> Excluir
                </a>
            </div>
        </td>
    </tr>`;
}



    $(document).on("click", ".AdicionarAluno", function() {
        $('.modal_editar').modal('show')  


    });

    $(document).on("click", ".salvar_aluno", function(e) {
    e.preventDefault();

    // Remova quaisquer mensagens de erro existentes antes de fazer uma nova requisição.
    $('.form-group .text-danger').remove();

    var id = $("#id").val(); // Supondo que você tem um campo de entrada oculto com o ID.

        var url, method;
        if (id) {
            // Se o ID existe, atualize o aluno.
            url = '/api/aluno/' + id + '/update';
            method = 'POST';
        } else {
            // Se o ID não existe, insira um novo aluno.
            url = '/api/aluno/store';
            method = 'POST';
        }

    var nome = $("#nome").val();
    var sobreNome = $("#sobreNome").val();

    var email = $("#email").val();
    var cep = $("#cep").val();
    var rua = $("#rua").val();
    var cidade = $("#cidade").val();
    var estado = $("#estado").val();
    var numero = $("#numero").val();

    var nascimento = $("#nascimento").val();

  

    // Usa moment.js para converter a string de data para o formato 'YYYY-MM-DD'.
    var nascimentoFormatado = moment(nascimento, "DD-MM-YYYY").format("YYYY-MM-DD");


    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,  // Substitua '/api/aluno' com a rota correta para a sua API.
        method: method,             
        data: {
            nome: nome,
            sobreNome: sobreNome,
            nascimento: nascimentoFormatado,
            email: email,
            endereco:rua+' '+cidade+''+numero,
            cep: cep,
            rua: rua,
            password:'12',
            cidade: cidade,
            estado: estado,
            numero: numero
        },
        success: function(response) {
            alert(response.message); // Dados inseridos/atualizados com sucesso!
            $(".modal_editar").modal('hide');
            let newRowHTML = getNewRowHTML(response.aluno);
            $('table tbody').append(newRowHTML);
            if(id){
                window.location.reload()
            }
                
            // Aqui, você pode adicionar qualquer lógica que queira executar após o sucesso da requisição.
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON.errors) {
                Object.keys(jqXHR.responseJSON.errors).forEach(function(key) {
                    var msg = jqXHR.responseJSON.errors[key];
                    $('#' + key).parent().append('<span class="text-danger">' + msg + '</span>');
                });
            } else {
                alert('Erro ao salvar o aluno: ' + errorThrown);
            }
        }
    });
});



    
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


        
    
      
        $("#id").val(data.id);
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