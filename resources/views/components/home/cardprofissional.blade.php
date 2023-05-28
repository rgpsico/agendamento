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
                    <div class="rating">
                        <i class="fas fa-star filled"></i>
                        <i class="fas fa-star filled"></i>
                        <i class="fas fa-star filled"></i>
                        <i class="fas fa-star filled"></i>
                        <i class="fas fa-star"></i>
                        <span class="d-inline-block average-rating">(17)</span>
                    </div>
                    <div class="clinic-details">
                        <p class="doc-location"><i class="fas fa-map-marker-alt">
                            </i> {{$value->endereco->pais ?? ''}}, {{$value->endereco->cidade ?? ''}}</p>
                        <ul class="clinic-gallery">
                            <li>
                                <a href="{{asset('template/assets/img/features/feature-01.jpg')}}" data-fancybox="gallery">
                                    <img src="{{asset('template/assets/img/features/feature-01.jpg')}}" alt="Feature">
                                </a>
                            </li>
                            <li>
                                <a href="{{asset('template/assets/img/features/feature-02.jpg')}}" data-fancybox="gallery">
                                    <img  src="{{asset('template/assets/img/features/feature-02.jpg')}}" alt="Feature">
                                </a>
                            </li>
                            <li>
                                <a href="{{asset('template/assets/img/features/feature-03.jpg')}}" data-fancybox="gallery">
                                    <img src="{{asset('template/assets/img/features/feature-03.jpg')}}" alt="Feature">
                                </a>
                            </li>
                            <li>
                                <a href="{{asset('template/assets/img/features/feature-04.jpg')}}" data-fancybox="gallery">
                                    <img src="{{asset('template/assets/img/features/feature-04.jpg')}}" alt="Feature">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clinic-services">
                        <span>Dental Fillings</span>
                        <span> Whitneing</span>
                    </div>
                </div>
            </div>
            <div class="doc-info-right">
                <div class="clini-infos">
                    <ul>
                        <li><i class="far fa-thumbs-up"></i> 98%</li>
                        <li><i class="far fa-comment"></i> 17 Feedback</li>
                        <li><i class="fas fa-map-marker-alt"></i> Florida, USA</li>
                        <li><i class="far fa-money-bill-alt"></i> $300 - $1000 <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="Lorem Ipsum"></i> </li>
                    </ul>
                </div>
                <div class="clinic-booking">
                    <a class="view-pro-btn" href="{{route('home.show',['id' => $value->uuid])}}">Ver Escola</a>
                    <a class="apt-btn" href="{{route('home.booking',['id' => $value->uuid])}}">Agendar Aula</a>
                </div>
            </div>
        </div>
    </div>
</div>