<!-- resources/views/components/modal-editar-vhost.blade.php -->
<div class="modal fade editar_vhost_modal" id="editarVhostModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="form-editar-vhost">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Editar Virtual Host <small id="modal-filename" class="fw-bold"></small></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="file" id="vhost-file">

          <div class="mb-3">
            <label class="form-label">ServerName</label>
            <input type="text" name="servername" id="vhost-servername" class="form-control" placeholder="ex: www.example.com">
          </div>

          <div class="mb-3">
            <label class="form-label">ServerAlias</label>
            <input type="text" name="serveralias" id="vhost-serveralias" class="form-control" placeholder="separe por espaço, ex: example.com www.example.com">
            <small class="text-muted">Separe aliases por espaço</small>
          </div>

          <div class="mb-3">
            <label class="form-label">DocumentRoot</label>
            <input type="text" name="documentroot" id="vhost-documentroot" class="form-control" placeholder="/var/www/meuapp/public">
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="vhost-enable-ssl" name="enable_ssl">
            <label class="form-check-label" for="vhost-enable-ssl">Habilitar SSL (detecção automática)</label>
          </div>

          <div class="mb-3">
            <label class="form-label">Conteúdo do arquivo (.conf)</label>
            <textarea name="content" id="vhost-content" class="form-control" rows="12" style="font-family: monospace;"></textarea>
            <small class="text-muted">Você pode editar o arquivo diretamente aqui.</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-salvar-vhost">Salvar alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>
