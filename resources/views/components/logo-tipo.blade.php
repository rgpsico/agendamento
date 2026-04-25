@php
    $logoArquivo = isset($imagem) && $imagem ? public_path('avatar/' . $imagem) : null;
    $logoUrl = $logoArquivo && is_file($logoArquivo)
        ? asset('avatar/' . $imagem)
        : asset('images/placeholder-image.svg');
@endphp

<img src="{{ $logoUrl }}"
    width="150"
    height="150"
    alt="Logo da Empresa">
