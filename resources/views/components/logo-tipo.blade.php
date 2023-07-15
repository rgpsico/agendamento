@isset($imagem)
<img src="{{ asset('avatar/' . $imagem) }}" 
width="150" 
height="150" 
alt="Logo da Escola de Surf"
onerror="this.onerror=null; this.src='{{ asset('admin/img/logo.png') }}';">

@endisset