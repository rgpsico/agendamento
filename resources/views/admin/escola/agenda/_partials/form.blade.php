<div class="row form-row">
    <div class="col-12 col-sm-6">
        @if (isset($model))
            <input type="hidden" name="professor_id" value="{{ $model->professor_id }}">
            <x-alunos-component :professorId="$model->professor_id" />
        @else
            <input type="hidden" name="professor_id" value="{{ Auth::user()->professor->id }}">
            <x-alunos-component :professorId="Auth::user()->professor->id" />
        @endif
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label>Valor Aula</label>
            <input type="text" class="form-control" name="valor_aula" value="{{ isset($model) ? $model->valor_aula : old('valor_aula') }}" placeholder="100,00">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Data da Aula</label>
            <div class="cal-icon">
                <input type="text" class="form-control datetimepicker" name="data_da_aula" value="{{ isset($model) && $model->data_da_aula ? \Carbon\Carbon::parse($model->data_da_aula)->format('d/m/Y') : old('data_da_aula') }}">
            </div>
        </div>
    </div>
    
    
    <div class="col-6">
        <div class="form-group">
            <label>Horário</label>
            <div class="cal-icon">
                <input type="time" class="form-control timepicker" name="horario_aula" value="{{ isset($model) && $model->horario_aula ? \Carbon\Carbon::parse($model->horario_aula)->format('H:i') : old('horario_aula') }}">
            </div>
        </div>
    </div>
    
</div>

    <x-modalidadeselect/>
    
    
                  

  

