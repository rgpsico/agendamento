<x-text-input name="nome" size="30" label="Nome Completo" value="{{ $model->nome ?? '' }}" />
<x-text-input name="email" size="30" label="Email" value="{{ $model->email ?? '' }}" />
<label for="">Data de Nacimento </label>

@php
    $data_nascimento = '';

@endphp
@isset($model->data_nascimento)
    @php $data_nascimento = \Carbon\Carbon::parse($model->data_nascimento)->format('d/m/Y');  @endphp
@endisset
<input type="text" name="data_nascimento" class='form-control mb-2' id="data_nascimento"
    value="{{ $data_nascimento }}" />
<x-text-input name="telefone" size="30" label="Telefone" value="{{ $model->telefone ?? '' }}" />

</div>
