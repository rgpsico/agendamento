<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registre-se</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="registerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="aluno-tab" data-bs-toggle="tab" data-bs-target="#aluno"
                            type="button" role="tab" aria-controls="aluno" aria-selected="true">Aluno</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="professor-tab" data-bs-toggle="tab" data-bs-target="#professor"
                            type="button" role="tab" aria-controls="professor"
                            aria-selected="false">Professor</button>
                    </li>
                </ul>
                <div class="tab-content" id="registerTabContent">
                    <!-- Aluno Form -->
                    <div class="tab-pane fade show active" id="aluno" role="tabpanel" aria-labelledby="aluno-tab">
                        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo_usuario" value="Aluno">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating @error('nome') is-invalid @enderror"
                                    id="alunoNome" name="nome" value="{{ old('nome') }}" required>
                                <label class="focus-label">Nome</label>
                                @error('nome')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-focus">
                                <input type="email" class="form-control floating @error('email') is-invalid @enderror"
                                    id="alunoEmail" name="email" value="{{ old('email') }}" required>
                                <label class="focus-label">E-mail</label>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-focus">
                                <input type="password"
                                    class="form-control floating @error('senha') is-invalid @enderror" id="alunoSenha"
                                    name="senha" required>
                                <label class="focus-label">Senha</label>
                                @error('senha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Cadastrar como Aluno</button>
                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">Ou</span>
                            </div>
                            <div class="row form-row social-login">
                                <div class="col-12 text-center">
                                    <a href="{{ route('aluno.googleAuth.redirect') }}" class="btn btn-google w-100">
                                        <i class="fab fa-google me-1"></i> Registrar com Google
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Professor Form -->
                    <div class="tab-pane fade" id="professor" role="tabpanel" aria-labelledby="professor-tab">
                        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo_usuario" value="Professor">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating @error('nome') is-invalid @enderror"
                                    id="professorNome" name="nome" value="{{ old('nome') }}" required>
                                <label class="focus-label">Nome</label>
                                @error('nome')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-focus">
                                <input type="email"
                                    class="form-control floating @error('email') is-invalid @enderror"
                                    id="professorEmail" name="email" value="{{ old('email') }}" required>
                                <label class="focus-label">E-mail</label>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-focus">
                                <input type="password"
                                    class="form-control floating @error('senha') is-invalid @enderror"
                                    id="professorSenha" name="senha" required>
                                <label class="focus-label">Senha</label>
                                @error('senha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-focus">
                                <label class="focus-label">Modalidade</label>
                                <select name="modalidade_id"
                                    class="form-control @error('modalidade_id') is-invalid @enderror"
                                    id="professorModalidade" required>
                                    @foreach ($modalidade as $value)
                                        <option value="{{ $value->id }}"
                                            {{ old('modalidade_id') == $value->id ? 'selected' : '' }}>
                                            {{ $value->nome }}</option>
                                    @endforeach
                                </select>
                                @error('modalidade_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Cadastrar como
                                Professor</button>
                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">Ou</span>
                            </div>
                            <div class="row form-row social-login">
                                <div class="col-12 text-center">
                                    <a href="{{ route('prof.handle.google') }}" class="btn btn-google w-100">
                                        <i class="fab fa-google me-1"></i> Registrar com Google
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
