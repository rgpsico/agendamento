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
                    {{-- <p class="doc-speciality">{{$value->nome}}</p> --}}
                    {{-- <h5 class="doc-department">
                        <img src="{{asset('template/assets/img/specialities/specialities-05.png')}}" class="img-fluid" alt="Professor">
                    </h5> --}}
                    <x-avaliacao-home/>

                    <x-gallery-home :value="$value->endereco" />

                    
                </div>
            </div>
          <x-right-card-home :value="$value->uuid"/>
        </div>
    </div>
</div>