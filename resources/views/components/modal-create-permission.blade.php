<div class="modal fade" id="modal_create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar Nova Permissão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nome da Permissão</label>
                    <input type="text" id="create_nome_permission" class="form-control" placeholder="Ex: editar posts">
                </div>
                <div class="form-group mt-2">
                    <label>Guard</label>
                    <input type="text" id="create_guard_permission" class="form-control" value="web">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="criar_permission">Salvar</button>
            </div>
        </div>
    </div>
</div>
