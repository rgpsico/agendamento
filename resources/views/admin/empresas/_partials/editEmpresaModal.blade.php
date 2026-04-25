<div id="editEmpresaModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form id="editEmpresaForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="empresa_id" name="empresa_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="text-muted text-uppercase small mb-3">Dados gerais</h6>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <label for="nome_empresa" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome_empresa" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label for="email_empresa" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_empresa" name="email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="cnpj" class="form-label">CPF/CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="data_vencimento_empresa" class="form-label">Vencimento</label>
                                    <input type="date" class="form-control" id="data_vencimento_empresa" name="data_vencimento">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <label for="site_url" class="form-label">Site URL</label>
                                    <input type="url" class="form-control" id="site_url" name="site_url">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label for="modalidade_id" class="form-label">Modalidade</label>
                                    <select class="form-control" id="modalidade_id" name="modalidade_id" required>
                                        <option value="">Selecione</option>
                                        @foreach ($modalidades as $modalidade)
                                            <option value="{{ $modalidade->id }}"
                                                {{ old('modalidade_id', $empresa->modalidade_id ?? '') == $modalidade->id ? 'selected' : '' }}>
                                                {{ $modalidade->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2" required></textarea>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <h6 class="text-muted text-uppercase small mb-3">Valores</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3 mb-md-0">
                                    <label for="valor_aula_de" class="form-label">Valor aula de</label>
                                    <input type="text" class="form-control" id="valor_aula_de" name="valor_aula_de" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="valor_aula_ate" class="form-label">Valor aula ate</label>
                                    <input type="text" class="form-control" id="valor_aula_ate" name="valor_aula_ate" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <h6 class="text-muted text-uppercase small mb-3">Endereco</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" required>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <label for="endereco" class="form-label">Endereco</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="numero" class="form-label">Numero</label>
                                    <input type="text" class="form-control" id="numero" name="numero">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3 mb-md-0">
                                    <label for="uf" class="form-label">UF</label>
                                    <input type="text" class="form-control" id="uf" name="uf" required maxlength="2">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group mb-0">
                                    <label for="pais" class="form-label">Pais</label>
                                    <input type="text" class="form-control" id="pais" name="pais" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div>
                        <h6 class="text-muted text-uppercase small mb-3">Imagens</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3 mb-md-0">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="banner" class="form-label">Banner</label>
                                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
                </div>
            </form>
        </div>
    </div>
</div>
