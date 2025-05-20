<style>
    .profile-pic-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.pic-holder {
    text-align: center;
    position: relative;
    border-radius: 50%;
    width: 150px;
    height: 150px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.pic-holder .pic {
    height: 100%;
    width: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center;
}

.pic-holder .upload-file-block {
    cursor: pointer;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: all 0.2s;
}

.pic-holder .upload-file-block:hover {
    opacity: 1;
}
    </style>
<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-update-aluno" enctype="multipart/form-data">
                    <input type="hidden" id="aluno_id" name="aluno_id" value="{{ Auth::user()->id }}">
                    <div class="row form-row">
                        <!-- Campo para upload de imagem -->
                        <div class="col-12 text-center mb-3">
                            <div class="form-group">
                                <div class="profile-pic-wrapper">
                                    <div class="pic-holder">
                                        <img id="profilePic" class="pic" 
                                             src="{{ Auth::user()->aluno && Auth::user()->aluno->avatar ? asset('storage/' . Auth::user()->aluno->avatar) : asset('assets/img/user-default.png') }}">
                                        <label for="newProfilePhoto" class="upload-file-block">
                                            <div class="text-center">
                                                <div class="mb-2">
                                                    <i class="fa fa-camera fa-2x"></i>
                                                </div>
                                                <div class="text-uppercase">
                                                    Atualizar Foto
                                                </div>
                                            </div>
                                        </label>
                                        <input type="file" name="profile_image" id="newProfilePhoto" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Primeiro Nome</label>
                                <input type="text" class="form-control" id="primeiro_nome" name="primeiro_nome" 
                                       value="{{ explode(' ', Auth::user()->nome)[0] ?? '' }}">
                                <span class="primeiro_nome_error text-danger"></span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Data de Nascimento</label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" id="data_nascimento" name="data_nascimento" 
                                           value="{{ Auth::user()->data_nascimento ? date('d-m-Y', strtotime(Auth::user()->data_nascimento)) : '' }}">
                                    <span class="data_nascimento_error text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ Auth::user()->email ?? '' }}">
                                <span class="email_error text-danger"></span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" 
                                       value="{{ Auth::user()->telefone ?? '' }}">
                                <span class="telefone_error text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script>
  // Script para mostrar prévia da imagem
$(document).ready(function() {

    

    // Quando um novo arquivo é selecionado
    $("#newProfilePhoto").change(function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Atualiza a imagem de prévia
                $("#profilePic").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Modificando o script de submit para incluir upload de imagem
    $('#form-update-aluno').on('submit', function(e) {
        e.preventDefault();

        var alunoId = $('#aluno_id').val();
        var formData = new FormData(this);

        // Fix para formatação de data
        let dataBr = $('#data_nascimento').val();
        let dataParts = dataBr.split('-');
        
        if (dataParts.length !== 3) {
            alert('Formato de data inválido. Use DD-MM-AAAA.');
            return;
        }

        let dataSql = new Date(+dataParts[2], +dataParts[1] - 1, +dataParts[0]);
        if (isNaN(dataSql.getTime())) {
            alert('Data inválida.');
            return;
        }

        // Substitui a data no FormData
        formData.set('data_nascimento', dataSql.toISOString().split('T')[0]);

        $.ajax({
            url: '/api/aluno/' + alunoId + '/update',
            method: 'POST',
            data: formData,
            processData: false, // Necessário para enviar arquivos
            contentType: false, // Necessário para enviar arquivos
            success: function(response) {
                alert(response.message);
                if (response.message === 'Dados atualizados com sucesso!') {
                    $('.modal').modal('hide');
                    window.location.reload();
                }
            },
            error: function(response) {
                if (response.status === 422) {
                    const errors = response.responseJSON.errors;
                    // Limpa erros anteriores
                    $('.text-danger').text('');
                    // Exibe novos erros
                    for (let field in errors) {
                        $(`.${field}_error`).text(errors[field][0]);
                    }
                } else if (response.status === 404) {
                    alert(response.responseJSON.message || 'Aluno ou usuário não encontrado!');
                } else {
                    alert('Ocorreu um erro desconhecido!');
                }
            }
        });
    });
});



$(document).ready(function() {

    function buscarCepPreenchido() {

// Seleciona o campo de CEP

const $cepInput = $('#cep');



// Verifica se o campo existe e tem um valor

if ($cepInput.length && $cepInput.val()) {

    // Limpa o CEP, deixando apenas os números

    const cep = $cepInput.val().replace(/\D/g, '');

    

    // Verifica se o CEP tem o tamanho correto (8 dígitos)

    if (cep.length === 8) {

        const $form = $cepInput.closest('form');

        $form.find('.cep_error').text('Buscando CEP...');

        

        // Faz a requisição à API ViaCEP

        $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {

            $form.find('.cep_error').text('');

            

            if (!data.erro) {

                $('#endereco').val(data.logradouro || '');

                $('#cidade').val(data.localidade || '');

                $('#estado').val(data.uf || '');

                $('#pais').val('Brasil');

            } else {

                $form.find('.cep_error').text('CEP não encontrado.');

            }

        }).fail(function() {

            $form.find('.cep_error').text('Erro ao buscar CEP. Tente novamente.');

        });

    }

}

}
    // Apply input mask to CEP field
    $('#cep').inputmask('99999-999');

    // CEP Lookup with event delegation
    $(document).on('blur change', '#cep', function() {
        const cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
            const $cepInput = $(this);
            $cepInput.prop('disabled', true);
            const $form = $cepInput.closest('form');
            $form.find('.cep_error').text('Buscando CEP...');

            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                $cepInput.prop('disabled', false);
                $form.find('.cep_error').text('');

                if (!data.erro) {
                    $('#endereco').val(data.logradouro || '');
                    $('#cidade').val(data.localidade || '');
                    $('#estado').val(data.uf || '');
                    $('#pais').val('Brasil');
                } else {
                    $form.find('.cep_error').text('CEP não encontrado.');
                }
            }).fail(function() {
                $cepInput.prop('disabled', false);
                $form.find('.cep_error').text('Erro ao buscar CEP. Tente novamente.');
            });
        } else if (cep.length > 0) {
            $(this).closest('form').find('.cep_error').text('CEP deve ter 8 dígitos.');
        }
    });

    // Clear error when user starts typing
    $(document).on('input', '#cep', function() {
        $(this).closest('form').find('.cep_error').text('');
    });

    // Generic AJAX form submission handler
    function submitForm(formId, url, isFileUpload = false, successMessage = 'Dados atualizados com sucesso!') {
        $(`#${formId}`).on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            let formData = isFileUpload ? new FormData(this) : form.serialize();
            const processData = !isFileUpload;
            const contentType = isFileUpload ? false : 'application/x-www-form-urlencoded; charset=UTF-8';

            // Special handling for form-update-aluno (date formatting)
            if (formId === 'form-update-aluno') {
                const dataBr = $('#data_nascimento').val();
                const dataParts = dataBr.split('-');

                if (dataParts.length !== 3) {
                    alert('Formato de data inválido. Use DD-MM-AAAA.');
                    return;
                }

                const dataSql = new Date(+dataParts[2], +dataParts[1] - 1, +dataParts[0]);
                if (isNaN(dataSql.getTime())) {
                    alert('Data inválida.');
                    return;
                }

                formData.set('data_nascimento', dataSql.toISOString().split('T')[0]);
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: processData,
                contentType: contentType,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message || successMessage);
                    if (response.message === successMessage) {
                        $('.modal').modal('hide');
                        window.location.reload();
                    }
                },
                error: function(response) {
                    if (response.status === 422) {
                        $('.text-danger.error-text').text('');
                        const errors = response.responseJSON.errors;
                        for (let field in errors) {
                            $(`.${field}_error`).text(errors[field][0]);
                        }
                    } else if (response.status === 401) {
                        alert('Não autorizado. Faça login novamente.');
                    } else if (response.status === 404) {
                        alert(response.responseJSON.message || 'Aluno ou usuário não encontrado!');
                    } else {
                        alert('Ocorreu um erro desconhecido: ' + response.status);
                    }
                }
            });
        });
    }
    buscarCepPreenchido()
    // Initialize form submissions
    submitForm('form-update-aluno', '/api/aluno/{{ Auth::user()->id }}/update', true, 'Dados atualizados com sucesso!');
    submitForm('update-endereco-form', '/api/aluno/{{ Auth::user()->id }}/update-endereco', false, 'Endereço atualizado com sucesso!');
    submitForm('update-password-form', '/api/aluno/update-password', false, 'Senha atualizada com sucesso!');
});
</script>



</script>