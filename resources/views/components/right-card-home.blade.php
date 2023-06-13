
  
  <div class="doc-info-right">
                <div class="clini-infos">
                    <ul>
                        {{-- <li><i class="far fa-thumbs-up"></i> 98%</li>
                        <li><i class="far fa-comment"></i> 17 Feedback</li> --}}
                        <li><i class="fas fa-map-marker-alt"></i> {{$cidade ?? 'RJ'}}, {{$nacionalidade ?? 'BR'}}</li>
                        <li><i class="far fa-money-bill-alt"></i> $300 - $1000 
                            <i class="fas fa-info-circle" data-bs-toggle="tooltip"
                             title="Lorem Ipsum"></i> 
                            </li>
                    </ul>
                </div>
                <div class="clinic-booking">
                    @isset($value)                       
                    <a class="view-pro-btn" href="{{route('home.show',['id' => $value])}}">Ver Escola</a>
                    <a class="apt-btn" href="{{route('home.booking',['id' => $value])}}">Agendar Aula</a>
                    @endisset
                </div>
            </div>