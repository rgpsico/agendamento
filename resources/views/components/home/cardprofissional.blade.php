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

                    <x-gallery-home :value="$value"  />
                   
                </div>
            </div>
          <x-right-card-home :value="$value"/>
        </div>
    </div>
</div>