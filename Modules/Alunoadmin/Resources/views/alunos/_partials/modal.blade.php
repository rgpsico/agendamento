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
   
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control datetimepicker" id="data_nascimento" value="24-07-1983">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" value="johndoe@example.com">
                                </div>
                            </div> --}}
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Celular</label>
                                    <input type="text" value="+21 990271287" id="telefone" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <h5 class="form-title"><span>Endereço</span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" class="form-control"  id="endereco" value="4663 Agriculture Lane">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" class="form-control" id="cidade" value="Miami">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" class="form-control" id="estado" value="Florida">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>CEP</label>
                                    <input type="text" class="form-control" id="cep" value="22434">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>País</label>
                                    <input type="text" class="form-control" id="pais" value="United States">
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
        
      
    }
});

});


</script>