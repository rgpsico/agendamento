<div class="card">
    <div class="card-body">
        <div class="doctor-widget">
            <div class="doc-info-left">
                <div class="doctor-img">
                    <a href="{{route('home.show',['id' => $value->uuid])}}">
                        <img src="{{asset('template/assets/img/doctors/doctor-thumb-01.jpg')}}" class="img-fluid" alt="User Image">
                    </a>
                </div>
                <div class="doc-info-cont">
                    <h4 class="doc-name"><a href="{{route('home.show',['id' =>$value->uuid])}}">{{$value->nome}}</a></h4>
                    <p class="doc-speciality">{{$value->descricao}}</p>
                    <h5 class="doc-department"><img src="{{asset('template/assets/img/specialities/specialities-05.png')}}" class="img-fluid" alt="Speciality">Dentist</h5>
                    <x-avaliacao-home/>

                    <x-gallery-home/>

                    
                </div>
            </div>
          <x-right-card-home :value="$value->uuid"/>
        </div>
    </div>
</div>