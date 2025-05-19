
@isset($empresa)
    

<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('empresa.update',['id' =>$empresa->id ]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="user_id" value="{{ $empresa->user_id }}">
                    
                    <div class="row form-row">
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="nome" value="{{ $empresa->nome }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $empresa->user->email }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Data de cadastro</label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" name="data_created" value="{{ $empresa->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="{{ $empresa->telefone }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" name="cnpj" class="form-control" value="{{ $empresa->cnpj ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="form-control" name="descricao">{{ $empresa->descricao ?? '' }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Valor Aula De</label>
                                <input type="text" name="valor_aula_de" class="form-control" value="{{ $empresa->valor_aula_de ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Valor Aula Até</label>
                                <input type="text" name="valor_aula_ate" class="form-control" value="{{ $empresa->valor_aula_ate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label>Modalidade</label>
                                <select class="form-control" name="modalidade_id">
                                    <!-- Aqui você deve listar as modalidades disponíveis -->
                                    <option value="{{ $empresa->modalidade_id }}" selected>Atual</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Avatar</label>
                                <input type="file" class="form-control" name="avatar">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Banner</label>
                                <input type="file" class="form-control" name="banner">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <h5 class="form-title"><span>Endereço</span></h5>
                        </div>                       
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cep</label>
                                <input type="text" class="form-control" name="cep" id="cep" value="{{ $empresa->endereco->cep ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" class="form-control" name="endereco" id="endereco" value="{{ $empresa->endereco->endereco ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" value="{{ $empresa->endereco->cidade ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control" name="estado" id="estado" value="{{ $empresa->endereco->estado ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Uf</label>
                                <input type="text" class="form-control" name="uf" id="uf" value="{{ $empresa->endereco->uf ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Pais</label>
                                <input type="text" class="form-control" name="pais" id="pais" value="{{ $empresa->endereco->pais ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endisset