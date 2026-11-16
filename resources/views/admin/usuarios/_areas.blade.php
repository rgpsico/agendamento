{{--
    Áreas do painel liberadas para o funcionário (App\Support\Modulos).
    Variáveis: $marcadas (array de chaves já liberadas), $acessoTotal (bool: o usuário editado é dono/master).
--}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">O que este usuário pode acessar</h5>
        <small class="text-muted">Marque as áreas do sistema que o funcionário pode ver e usar. O que não estiver marcado fica escondido e bloqueado.</small>
    </div>
    <div class="card-body">
        @if($acessoTotal ?? false)
            <p class="mb-0"><strong>Acesso total.</strong> Este usuário é o responsável pela empresa e acessa todas as áreas.</p>
        @else
            <div class="row">
                @foreach(\App\Support\Modulos::AREAS as $chave => [$rotulo, $descricao])
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="modulos[]" value="{{ $chave }}"
                                   id="modulo_{{ $chave }}" {{ in_array($chave, old('modulos', $marcadas), true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="modulo_{{ $chave }}">
                                <strong>{{ $rotulo }}</strong><br>
                                <small class="text-muted">{{ $descricao }}</small>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
