<x-public.layout title="HOME">
  <!-- Breadcrumb -->
  <x-home.breadcrumb title="TESTE"/>
  <!-- /Breadcrumb -->

  <link rel="stylesheet" type="text/css" href="{{ asset('css/booking.css') }}">
  <img src="" alt="">
  @include('admin.empresas._partials.modal')

  <input type="hidden" id="professor_id" value="{{ $professor_id }}">

  <div class="container">
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-body">
                      <div class="booking-doc-info d-flex flex-column flex-md-row align-items-center">
                          <a href="" class="booking-doc-img mb-3 mb-md-0">
                              @isset($model->avatar)
                                  <img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid usuario-img" alt="Usuario Image">
                              @endisset
                          </a>
                          <div class="booking-info text-center text-md-start">
                              <h4><a href="">{{ $model->nome ?? 'Sem Nome' }}</a></h4>
                              @isset($model)
                                  <x-avaliacao-home :model="$model" />
                              @endisset
                              <p class="text-muted mb-0">
                                  <i class="fas fa-map-marker-alt"></i> {{ $model->endereco->cidade ?? '' }}, {{ $model->endereco->pais ?? '' }}
                              </p>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row mb-4">
                  @isset($model->servicos)
                  @foreach ($model->servicos as $serv)
                  <div class="col-12 col-md-6 col-lg-4 mb-3">
                      <div class="selection-wrapper card_servicos" data-servico_preco="{{ $serv->preco }}" 
                          data-servico_id="{{ $serv->id }}" data-servico_titulo="{{ $serv->titulo }}">
                          <label for="selected-item-{{ $serv->id }}" class="selected-label">
                              <input type="radio" name="selected-item" id="selected-item-{{ $serv->id }}" class="servico-selecionado" data-servico_id="{{ $serv->id }}">
                              <span class="icon"></span>
                              <div class="selected-content">
                                  <img class="card-img-top img-fluid" src="{{ asset('servico/' . $serv->imagem ?? 'admin/img/doctors/Thumbs.db') }}" alt="{{ $serv->titulo }}">
                                  <h4>{{ $serv->titulo }}</h4>
                                  <h5>{{ $serv->descricao }}</h5>
                              </div>
                          </label>
                      </div>
                  </div>
              @endforeach
              
                  @endisset
              </div>

              <div class="row">
                  <div class="col-12 col-sm-6 col-md-8">
                      <h4 class="mb-1">{{ \Carbon\Carbon::now()->format('d F Y') }}</h4>
                      <p class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</p>
                  </div>
              </div>

              <input type="hidden" class="dia_da_semana">
              <input type="hidden" class="data">
              <input type="hidden" class="hora_da_aula">

              <!-- Schedule Widget -->
              <div id="spinner" class="spinner-border text-primary" role="status" style="display:none;">
                  <span class="sr-only">Loading...</span>
              </div>

              <div class="card booking-schedule schedule-widget">
                  <!-- Schedule Header -->
                  <div class="schedule-header" style="display:none;">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="day-slot">
                                  <ul>
                                      <li class="left-arrow">
                                          <a href=""><i class="fa fa-chevron-left"></i></a>
                                      </li>
                                      <li class="right-arrow">
                                          <a href=""><i class="fa fa-chevron-right"></i></a>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- /Schedule Header -->

                  <!-- Schedule Content -->
                  <div class="schedule-cont">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="time-slot">
                                  <ul class="clearfix"></ul>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- /Schedule Content -->
              </div>
              <!-- /Schedule Widget -->

              <!-- Submit Section -->
              <div class="submit-section proceed-btn text-end mt-4">
                @if(Auth::check())
                    <a href="{{ route('home.checkoutAuth', ['user_id' => $model->user_id]) }}" class="btn btn-primary submit-btn disabled">Agendar e Pagar</a>
                @else
                    <a href="{{ route('home.checkout', ['id' => $model->user_id]) }}" class="btn btn-primary submit-btn disabled">Agendar e Pagar</a>
                @endif
            </div>
              <!-- /Submit Section -->
          </div>
      </div>
  </div>

  <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
  <script src="{{ asset('js/booking.js') }}"></script>
  <script>

      $(document).ready(function() {
            $('.card-img-top').on('error', function() {
                $(this).attr('src', 'https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY');
            });

            $('.usuario-img').on('error', function() {
                $(this).attr('src', 'https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY');
            });
        });
  </script>
</x-public.layout>