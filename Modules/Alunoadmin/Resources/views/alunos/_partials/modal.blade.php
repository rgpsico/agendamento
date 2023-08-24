<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="aluno_id" value="{{Auth::user()->id}}">
                <div class="modal-body">
                    <form id="form-update-aluno">
                        <div class="row form-row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Primeiro Nome</label>
                                    <input type="text" class="form-control" id="primeiro_nome" value="{{Auth::user()->nome}}">
                                </div>
                            </div>
   
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control datetimepicker" id="data_nascimento" value="24-07-1983">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" value="{{Auth::user()->email ?? ''}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Celular</label>
                                    <input type="text" value="{{Auth::user()->telefone ?? ''}}" id="telefone" class="form-control">
                                </div>
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>

   

<div class="modal fade" id="editar_endereco" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Endereço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="aluno_id" value="{{Auth::user()->id}}">
                <div class="modal-body">
                    <form id="form-update-aluno">
                        <div class="row form-row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Primeiro Nome</label>
                                    <input type="text" class="form-control" id="primeiro_nome" value="{{Auth::user()->nome}}">
                                </div>
                            </div>
   
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control datetimepicker" id="data_nascimento" value="24-07-1983">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" value="{{Auth::user()->email ?? ''}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Celular</label>
                                    <input type="text" value="{{Auth::user()->telefone ?? ''}}" id="telefone" class="form-control">
                                </div>
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>


<script>
    $('#form-update-aluno').on('submit', function(e) {
    e.preventDefault();

    var alunoId = $('#aluno_id').val(); 

    var formData = $(this).serialize(); // Obtém os dados do formulário

    let dataBr = $('#data_nascimento').val();
let dataParts = dataBr.split("/");
let dataSql = new Date(+dataParts[2], dataParts[1] - 1, +dataParts[0]);
let finalDate = dataSql.toISOString().split('T')[0];

    $.ajax({
    url: '/api/aluno/' + alunoId + '/update',
    method: 'POST',
    data: {
        primeiro_nome: $('#primeiro_nome').val(),
        ultimo_nome: $('#ultimo_nome').val(),
        data_nascimento: finalDate,
        email: $('#email').val(),
        telefone: $('#telefone').val(),
        endereco: $('#endereco').val(),
        cidade: $('#cidade').val(),
        estado: $('#estado').val(),
        cep: $('#cep').val(),
        pais: $('#pais').val()
    },
    success: function(response, status) {
        alert(response.message);
      if(response.message == 'Dados atualizados com sucesso!'){
        $('.modal').modal('hide')
        window.location.reload();
        
      }
        
      
    } , error: function(response) {
        if (response.status == 422) { // verifica se é um erro de validação
            const errors = response.responseJSON.errors;
            // exibe os erros no formulário
            for (let field in errors) {
                $(`.${field}_error`).text(errors[field][0]);
            }
        } else {
            alert('Ocorreu um erro desconhecido!');
        }
    }
});

});


</script>