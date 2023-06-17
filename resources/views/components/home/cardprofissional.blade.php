<div class="card">
    <div class="card-body">
        <div class="doctor-widget">
            <div class="doc-info-left">
                <div class="doctor-img">
                    <a href="">                        
                        <img src="{{ asset('avatar/' . $value->avatar) }}" class="img-fluid" alt="Usuario Image">
                    </a>
                </div>              
                <div class="doc-info-cont">
                    <h4 class="doc-name"><a href="{{route('home.show',['id' =>$value->user_id])}}">{{$value->nome}}</a></h4>
                     <x-avaliacao-home/>

                     <div class="clinic-details">
                        <p class="doc-location">
                            {{$value->endereco->uf ?? ''}}, {{$value->endereco->cep ?? ''}}</p>
                          
                             
                    {{$value->galeria}}
                        <ul class="clinic-gallery">
                            @foreach ($value->galeria as $gal)
                            <li>
                                <a href="{{ asset('galeria_escola/' . $gal->image) }}" data-fancybox="gallery">
                                    <img src="{{ asset('galeria_escola/' . $gal->image) }}" alt="Feature">
                                </a>
                            </li>
                            @endforeach  
                        </ul>
                    </div>
                   
                </div>
            </div>
          <x-right-card-home :value="$value->uuid"/>
        </div>
    </div>
</div>