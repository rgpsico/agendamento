<div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Permissões</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="permissionsForm">
                    @csrf
                    <input type="hidden" name="user_id" id="modalUserId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Papéis (Roles)</h6>
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" 
                                           name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Permissões Diretas</h6>
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                           name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="savePermissions">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Abrir modal e carregar dados
    $(document).on('click', '.bt-permissions', function() {
        var userId = $(this).data('id');
        $('#modalUserId').val(userId);
        
        // Limpar checkboxes
        $('.role-checkbox, .permission-checkbox').prop('checked', false);
        
        // Carregar permissões do usuário via AJAX
        $.get('/admin/usuarios/' + userId + '/permissions', function(data) {
            // Marcar roles
            data.roles.forEach(function(role) {
                $('#role_' + role.id).prop('checked', true);
            });
            
            // Marcar permissões diretas
            data.directPermissions.forEach(function(permission) {
                $('#perm_' + permission.id).prop('checked', true);
            });
            
            $('#permissionsModal').modal('show');
        });
    });
    
    // Salvar permissões
    $('#savePermissions').click(function() {
        var userId = $('#modalUserId').val();
        var formData = $('#permissionsForm').serialize();
        
        $.post('/admin/usuarios/' + userId + '/permissions', formData, function() {
            alert('Permissões atualizadas com sucesso!');
            $('#permissionsModal').modal('hide');
            location.reload(); // Ou atualizar apenas a linha da tabela
        }).fail(function() {
            alert('Erro ao salvar permissões');
        });
    });
});
</script>