<div class="card">
    <div class="card-body">
        <div class="doctor-widget">
            <div class="doc-info-left">
                <div class="doctor-img">
                    <a href="{{route('home.show',['id' =>$value->user_id])}}">                        
                        <img src="{{ asset('avatar/' . $value->avatar) }}" class="img-fluid" onerror="handleImageError(this)" alt="Usuario Image">
                    </a>
                </div>              
                <div class="doc-info-cont">
                    <h4 class="doc-name"><a href="{{route('home.show',['id' =>$value->user_id])}}">{{$value->nome}}</a></h4>
                       
                    <x-avaliacao-home :model="$value" />

                    <x-gallery-home :model="$value"  /> 
                                 
                        <span class="badge bg-primary" style="font-size: 1.2em; text-transform: capitalize;">{{$value->modalidade->nome}}</span>
               
                   
                </div>
            </div>
          <x-right-card-home :value="$value"/>
        </div>
    </div>
</div>
<script>
    function handleImageError(imageElement) {
    // Define o caminho para a imagem padrão.
    var defaultImage = 'https://picsum.photos/536/354';
    $(imageElement).attr('src', defaultImage);
}
</script>