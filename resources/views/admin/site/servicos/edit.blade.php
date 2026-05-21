<x-admin.layout title="Editar Serviço">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Editar Serviço / Aula</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.site.servicos.index') }}">Serviços</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Editando: {{ $servico->titulo }}</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            <form action="{{ route('admin.site.servicos.update', $servico->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Informações principais --}}
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="titulo">Título <span class="text-danger">*</span></label>
                                            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                                                   value="{{ old('titulo', $servico->titulo) }}" required>
                                            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nivel">Nível / Tag</label>
                                            <input type="text" name="nivel" class="form-control"
                                                   value="{{ old('nivel', $servico->nivel) }}"
                                                   placeholder="Ex: Iniciantes, Kids · 6-12 anos">
                                            <small class="text-muted">Aparece como badge no card</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descricao">Descrição <span class="text-danger">*</span></label>
                                    <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror"
                                              rows="3" required>{{ old('descricao', $servico->descricao) }}</textarea>
                                    @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Meta da aula --}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Duração</label>
                                            <input type="text" name="duracao" class="form-control"
                                                   value="{{ old('duracao', $servico->duracao) }}"
                                                   placeholder="Ex: 2h, 1h30, 8 sessões">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Capacidade</label>
                                            <input type="text" name="capacidade" class="form-control"
                                                   value="{{ old('capacidade', $servico->capacidade) }}"
                                                   placeholder="Ex: até 4 alunos, 1 aluno">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Info Extra</label>
                                            <input type="text" name="info_extra" class="form-control"
                                                   value="{{ old('info_extra', $servico->info_extra) }}"
                                                   placeholder="Ex: vídeo análise">
                                            <small class="text-muted">Aparece com 📌 no card</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Preço (R$)</label>
                                            <input type="number" step="0.01" name="preco" class="form-control"
                                                   value="{{ old('preco', $servico->preco) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Imagem</label>
                                            <input type="file" name="imagem" class="form-control" accept="image/*">
                                            @if($servico->imagem)
                                                <div class="mt-2 d-flex align-items-center gap-2">
                                                    <img src="{{ asset('storage/' . $servico->imagem) }}"
                                                         alt="Imagem atual" height="60" style="border-radius:6px;object-fit:cover">
                                                    <small class="text-muted">Imagem atual · envie nova para substituir</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="form-group mb-0">
                                            <div class="form-check mt-3">
                                                <input type="hidden" name="destaque" value="0">
                                                <input class="form-check-input" type="checkbox" name="destaque" value="1"
                                                       id="destaque" {{ old('destaque', $servico->destaque) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="destaque">
                                                    <strong>Card em destaque</strong>
                                                    <small class="d-block text-muted">Exibe com visual diferenciado no site</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end px-0">
                                    <a href="{{ route('admin.site.servicos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Atualizar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
