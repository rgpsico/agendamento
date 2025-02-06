@isset($model->imagem)
    <img class="" src="{{ asset('servico/' . $model->imagem ?? '') }}" width="100px" height="100px" alt="Imagem do serviço">
@endisset

<div class="form-group">
    <label for="">Imagem da Aula</label>
    <input type="file" name="imagem">
</div>

<input type="hidden" class="" name="empresa_id" value="{{ Auth::user()->empresa->id }}" />

<x-text-input name="titulo" size="30" label="Titulo" :value="$model->titulo ?? ''" />

<x-text-area name="descricao" label="Descrição" :model="$model ?? ''" />

<x-text-input name="preco" size="30" label="Preço" :value="$model->preco ?? ''" />

<x-text-input name="tempo_de_aula" size="30" label="Tempo de Aula" :value="$model->tempo_de_aula ?? ''" />

<!-- Adicionando o Tipo de Serviço -->
<div class="form-group">
    <label for="tipo_agendamento">Tipo de Serviço</label>
    <select name="tipo_agendamento" id="tipo_agendamento" class="form-control">
        <option value="DIA" {{ isset($model) && $model->tipo_agendamento == 'DIA' ? 'selected' : '' }}>Somente Dia</option>
        <option value="HORARIO" {{ isset($model) && $model->tipo_agendamento == 'HORARIO' ? 'selected' : '' }}>Dia e Horário</option>
    </select>
</div>
