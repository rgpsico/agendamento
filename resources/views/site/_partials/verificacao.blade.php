{{--
  Partial: site/_partials/verificacao.blade.php
  Meta tags de verificação de propriedade (Google Search Console), cadastradas na tela
  de rastreamento do site com a plataforma "google_site_verification".
  Precisa ficar dentro do <head>. Requer: $site (EmpresaSite) com trackingCodes carregados.
--}}
@if(!empty($site) && $site->relationLoaded('trackingCodes'))
  @foreach($site->trackingCodes->where('status', true)->where('provider', 'google_site_verification') as $tc)
    <meta name="google-site-verification" content="{{ $tc->code }}">
  @endforeach
@endif
