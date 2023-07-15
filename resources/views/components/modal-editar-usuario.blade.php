<div class="modal fade show modal_editar" id="modal_editar" role="dialog" aria-modal="true" style="display:none; padding-left: 0px;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Aluno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="id" value="">
                                <label>Nome</label>
                                <input type="text" class="form-control" id="nome" value="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Sobre Nome</label>
                                <input type="text" class="form-control" id="sobreNome" value="Doe">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Data de Nacimento</label>
                                <div class="cal-icon">
                                    <input type="text" id="nascimento" class="form-control datetimepicker" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" id="telefone" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control" value="">
                            </div>
                        </div>
                     
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cep</label>
                                <input type="text" id="cep" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Rua</label>
                                <input type="text" id="rua" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" id="cidade" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text"  id="estado" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Numero</label>
                                <input type="text" id="numero" class="form-control" value="">
                            </div>
                        </div>
                       
                      
                    </div>
                    <button type="submit" class="btn btn-primary w-100 salvar_aluno" id="salvar_aluno">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>