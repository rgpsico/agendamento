 <div class="modal fade" id="modal_editar_permission" tabindex="-1" aria-labelledby="modalEditarPermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPermissionLabel">Editar Permissão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editar_permission_form">
                        <input type="hidden" id="permission_id">
                        <div class="mb-3">
                            <label for="nome_permission" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome_permission" required>
                        </div>
                        <div class="mb-3">
                            <label for="guard_permission" class="form-label">Guard</label>
                            <input type="text" class="form-control" id="guard_permission" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="salvar_editar_permission">Salvar</button>
                </div>
            </div>
        </div>
    </div>
