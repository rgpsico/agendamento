<div class="form-group">
    <label>{{$label}}</label>
    <textarea id="" cols="30" rows="10" name="{{$name}}" class="form-control descricao">{{ old($name, $model->$name ?? '') }}</textarea>
    @error($name)
    <span class="text-danger">{{$message ?? ''}}</span>
    @enderror
</div>