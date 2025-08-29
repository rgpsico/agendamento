<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="password" class="form-control" {{ !isset($user) ? 'required' : '' }}>
            @if(isset($user))
                <small class="text-muted">Deixe em branco para não alterar</small>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Confirmar Senha</label>
            <input type="password" name="password_confirmation" class="form-control" {{ !isset($user) ? 'required' : '' }}>
        </div>
    </div>
</div>