@isset($imagem)
<img src="{{ asset('avatar/' . $imagem) }}" 
width="150" 
height="150" 
alt="Logo da Escola de Surf"
onerror="this.onerror=null; this.src='https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png';">

@endisset