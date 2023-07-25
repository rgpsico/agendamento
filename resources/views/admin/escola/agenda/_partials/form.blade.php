<div class="row form-row">
    <div class="col-12 col-sm-6">
        <input type="hidden" name="professor_id" value="{{Auth::user()->professor->id}}">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" value="">
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" class="form-control" value="">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Data da Aula</label>
            <div class="cal-icon">
                <input type="text" class="form-control datetimepicker">
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label>Horário</label>
            <div class="cal-icon">
                <input type="text" class="form-control datetimepicker">
            </div>
        </div>
    </div>

  
</form>
