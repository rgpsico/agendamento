<div class="modal fade" id="modal_editar" tabindex="-1" aria-labelledby="modalEditarPermissao" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Permissão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="permission_id">
        <div class="form-group">
          <label for="nome_permission">Nome</label>
          <input type="text" id="nome_permission" class="form-control">
        </div>
        <div class="form-group mt-2">
          <label for="guard_permission">Guard</label>
          <input type="text" id="guard_permission" class="form-control" value="web">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="salvar_permission" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
