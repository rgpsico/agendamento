@props(['name', 'size', 'label', 'value' => '', 'type' => 'text'])

<div class="form-group">
    <label>{{ $label }}</label>

    <input type="{{ $type }}" id="{{ $name }}"
        class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}" name="{{ $name }}"
        size="{{ $size }}" value="{{ old($name, $value) }}">

    @if ($errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
