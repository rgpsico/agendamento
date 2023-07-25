<div class="row form-row">
    <div class="col-12 col-sm-6">
        <input type="hidden" name="professor_id" value="{{Auth::user()->professor->id}}">
        <x-alunos-component :professorId="Auth::user()->professor->id" />


    </div><div class="col-12 col-sm-6">
        <div class="form-group">
            <label>Valor Aula</label>
            <input type="text" class="form-control" name="valor_aula" value="{{ old('valor_aula') }}" placeholder="100,00">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Data da Aula</label>
            <div class="cal-icon">
                <input type="text" class="form-control datetimepicker" name="data_da_aula" value="{{ old('data_da_aula') }}">
            </div>
        </div>
    </div>
    
    <div class="col-6">
        <div class="form-group">
            <label>Horário</label>
            <div class="cal-icon">
                <input type="text" class="form-control datetimepicker" name="horario_aula" value="{{ old('horario_aula') }}">
            </div>
        </div>
    </div>


    <x-modalidadeselect/>
    
    
                  

  
</form>
