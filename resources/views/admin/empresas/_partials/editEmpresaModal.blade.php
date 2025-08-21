<!-- Modal de Edição -->
<div id="editEmpresaModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="editEmpresaForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="empresa_id" name="empresa_id">

                <div class="modal-body">
                    <!-- Informações Gerais -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nome_empresa" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome_empresa" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email_empresa" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_empresa" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="site_url" class="form-label">Site URL</label>
                                <input type="url" class="form-control" id="site_url" name="site_url">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="cnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="data_vencimento" class="form-label">Data Vencimento</label>
                                <input type="text" class="form-control" id="data_vencimento_empresa" name="data_vencimento" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="valor_aula_de" class="form-label">Valor Aula (De)</label>
                                <input type="text" class="form-control" id="valor_aula_de" name="valor_aula_de" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="valor_aula_ate" class="form-label">Valor Aula (Até)</label>
                                <input type="text" class="form-control" id="valor_aula_ate" name="valor_aula_ate" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="modalidade_id" class="form-label">Modalidade</label>
                        <select class="form-control" id="modalidade_id" name="modalidade_id" required>
                            <option value="">Selecione uma modalidade</option>
                            @foreach ($modalidades as $modalidade)
                                <option value="{{ $modalidade->id }}"
                                    {{ old('modalidade_id', $empresa->modalidade_id ?? '') == $modalidade->id ? 'selected' : '' }}>
                                    {{ $modalidade->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Endereço -->
                    <h5 class="mt-4">Endereço</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="uf" class="form-label">UF</label>
                                <input type="text" class="form-control" id="uf" name="uf" required maxlength="2">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" required>
                            </div>
                        </div>
                    </div>

                    <!-- Uploads -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="banner" class="form-label">Banner</label>
                                <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>