<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->

			<style>
				.selected-date {
					background-color: #42c0fb;
    				border: 1px solid #42c0fb;
    				color: #ffffff;
            cursor: pointer;
				}

				.card-selected {
   				 background-color: #007bff;
    			color: #fff;
				}

        .card_servicos {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    overflow: hidden; /* para garantir que as imagens fiquem contidas no card */
}

.card_servicos img {
    width: 100%;
    height: 80px; /* Ajuste esse valor para o tamanho desejado */
    object-fit: cover; /* Faz com que as imagens se ajustem ao tamanho especificado sem distorção */
}

.card_servicos .card-body {
    padding: 15px;
}

.card_servicos .card-title {
    font-weight: bold; 
    font-size: 18px;
}

.card_servicos .card-text {
    color: #777; 
    font-size: 14px;
}

.card_servicos .btn-primary {
    background-color: #007bff;
    border: none;
    color: white; 
    padding: 10px 24px; 
    text-align: center;
    text-decoration: none; 
    display: inline-block; 
    font-size: 16px; 
    margin: 4px 2px;
    cursor: pointer;
}

        

			</style>

			@include('admin.empresas._partials.modal')
			<div class="container">
				<div class="row">
					<div class="col-12">
					
						<div class="card">
							<div class="card-body">
								<div class="booking-doc-info">
									<a href="" class="booking-doc-img">
                    @isset($model->avatar)
										  <img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid" alt="Usuario Image">
                    @endisset
                    </a>
									<div class="booking-info">
										<h4><a href="">{{$model->nome ?? 'sEM nOME'}}</a></h4>
									
                    @isset($model)                              
                      <x-avaliacao-home :model="$model" />
                    @endisset
										<p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> {{$model->endereco->cidade ?? ''}}, {{$model->endereco->pais ?? ''}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
              @isset($model->servicos)              
              @foreach ($model->servicos as $serv )                        
                  <div class="col-md-2">            
                      <div class="card card_servicos" data-servico_preco="{{$serv->preco}}" data-servico_id="{{$serv->id}}" data-servico_titulo="{{$serv->titulo}}">
                          <img class="img-fluid" src="{{ asset('servico/' . $serv->imagem ?? 'admin/img/doctors/Thumbs.db') }}" alt="Imagem do serviço">
                          <div class="card-body">
                              <h5 class="card-title">{{$serv->titulo}}</h5>
                              <p class="card-text">{{$serv->descricao}}</p>
                              <p class="card-text" style="color: #333; font-size: 16px;">{{$serv->preco}}</p>
                              <a href="#" class="btn btn-primary">Escolher</a>
                          </div>
                      </div>
                  </div>
              @endforeach 
          @endisset
            </div>
						<div class="row">
							<div class="col-12 col-sm-4 col-md-6">
								<h4 class="mb-1">{{ \Carbon\Carbon::now()->format('d F Y') }}</h4>
								<p class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</p>
							</div>						
						</div>
						<input type="hidden" class="dia_da_semana" >
						<input type="hidden" class="data">
						<input type="hidden" class="hora_da_aula">
						<!-- Schedule Widget -->
						<div class="card booking-schedule schedule-widget">
					  	<!-- Schedule Header -->
							<div class="schedule-header" style="display:none;">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Day Slot -->
										<div class="day-slot">
											<ul>
												<li class="left-arrow">
													<a href="">
														<i class="fa fa-chevron-left"></i>
													</a>
												</li>
												
												<li class="right-arrow">
													<a href="">
														<i class="fa fa-chevron-right"></i>
													</a>
												</li>
											</ul>
										</div>
										<!-- /Day Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Header -->
							
							<!-- Schedule Content -->
							<div class="schedule-cont">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Time Slot -->
										<div class="time-slot">
											<ul class="clearfix">
											
											</ul>
										</div>
										<!-- /Time Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Content -->
							
						</div>
						<!-- /Schedule Widget -->
						
						<!-- Submit Section -->
						<div class="submit-section proceed-btn text-end">
							@if(Auth::check())
								<!-- Se o usuário estiver autenticado, redireciona para a rota desejada -->
								<a href="{{ route('home.checkoutAuth',['user_id' =>$model->user_id]) }}" class="btn btn-primary submit-btn">Agendar e Pagar</a>
							@else
								<!-- Se o usuário não estiver autenticado, redireciona para a rota de checkout -->
								<a href="{{ route('home.checkout', ['id' =>$model->user_id]) }}" class="btn btn-primary submit-btn">Agendar e Pagar</a>
							@endif
						</div>
						<!-- /Submit Section -->
						
					</div>
				</div>
					
					<!-- /Submit Section -->
					
				</div>
			</div>
		</div>
			<!-- /Page Content -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

				<script>
					var currentStartIndex = 0;
const dates = getNext29Days();

function getNext29Days() {
  const dates = [];
  for (let i = 0; i < 29; i++) {
    const date = new Date();
    date.setDate(date.getDate() + i);
    dates.push({
      day: date.toLocaleString('default', { weekday: 'short' }),
      date: date.getDate(),
      month: date.toLocaleString('default', { month: 'short' }),
      year: date.getFullYear()
    });
  }
  return dates;
}

function renderDates() {
  const $daySlot = $('.day-slot ul');
  $daySlot.empty();
  $daySlot.append('<li class="left-arrow"><a href=""><i class="fa fa-chevron-left"></i></a></li>');
  
  for (let i = currentStartIndex; i < currentStartIndex + 7; i++) {
    const dateObj = dates[i];
    const $li = $(`
      <li style='cursor:pointer'>
        <span>${dateObj.day}</span>
        <span class="slot-date">${dateObj.date} ${dateObj.month} <small class="slot-year">${dateObj.year}</small></span>
      </li>
    `);
    $daySlot.append($li);
  }

  $daySlot.append('<li class="right-arrow"><a href=""><i class="fa fa-chevron-right"></i></a></li>');
}

$(document).ready(function() {
  // Render inicial das datas
  renderDates();

  // Manipuladores de eventos para as setas
  $(document).on('click', '.day-slot .left-arrow', function(e) {
    e.preventDefault();
    if (currentStartIndex > 0) {
      currentStartIndex--;
      renderDates();
    }
  });

  $(document).on('click', '.day-slot .right-arrow', function(e) {
    e.preventDefault();
    if (currentStartIndex < dates.length - 7) {
      currentStartIndex++;
      renderDates();
    }
  });
});

$(document).on('click', '.day-slot li', function(e) {
  e.preventDefault();
  
  // Verifique se o item clicado não é uma seta
  if (!$(this).hasClass('left-arrow') && !$(this).hasClass('right-arrow')) {
    // Remove a classe 'selected-date' de qualquer li que a possua
    $('.day-slot li').removeClass('selected-date');

    // Adiciona a classe 'selected-date' ao li clicado
    $(this).addClass('selected-date');

    const dayOfWeek = $(this).find('span:first').text();
    const date = $(this).find('.slot-date').text();

	$('.dia_da_semana').val(dayOfWeek)
	$('.data').val(date)
    
	console.log('Dia da semana:', dayOfWeek);
    console.log('Data:', date);
  }
});


$(document).on('click', '.day-slot li', function(e) {
  e.preventDefault();
  
  if (!$(this).hasClass('left-arrow') && !$(this).hasClass('right-arrow')) {
    $('.day-slot li').removeClass('selected-date');
    $(this).addClass('selected-date');

    const dayOfWeek = $(this).find('span:first').text();
    const dayMapping = {
      'seg.': 1,
      'ter.': 2,
      'qua.': 3,
      'qui.': 4,
      'sex.': 5,
      'sáb.': 6,
      'dom.': 7
    };

    const dayNumber = dayMapping[dayOfWeek];
	console.log(dayNumber)
    $.ajax({
      url: '/api/disponibilidade', 
      method: 'GET',
      data: {
        day: dayNumber // enviar o número do dia
      },
      success: function(response) {
        $('.time-slot ul').html(''); 

		
        response.forEach(function(time) {
    const timeElement = `<li>
      <a class="timing" href="#">
        <span>${time}</span>
      </a>
    </li>`;
    $('.time-slot ul').append(timeElement);
  });

      }
    });
  }
});


$(document).on('click', '.timing', function(e) {
  e.preventDefault();
  
  // Remove a classe "selected" de qualquer outra marcação de tempo
  $('.timing').removeClass('selected');
  
  // Adiciona a classe "selected" ao elemento clicado
  $(this).addClass('selected');
  
  // Pega a hora do elemento clicado
  const time = $(this).find('span').text();
  
  // Coloca a hora no input
  $('.hora_da_aula').val(time);
});


$('.submit-btn').on('click', function(e) {
  // Previne o evento padrão (navegação) até que os dados sejam salvos
  e.preventDefault();

  // Obtém os valores dos inputs e os armazena no localStorage
  var diaDaSemana = $('.dia_da_semana').val();
  var data = $('.data').val();
  var horaDaAula = $('.hora_da_aula').val();
  
  // Verifica se a data e a horaDaAula estão preenchidas
  if (data && horaDaAula) {
    localStorage.setItem('diaDaSemana', diaDaSemana);
    localStorage.setItem('data', data);
    localStorage.setItem('horaDaAula', horaDaAula);

    // Permite que o evento de clique prossiga (navegação)
    window.location.href = $(this).attr('href');
  } else {
    // Exibe uma mensagem de erro ou realiza alguma ação para indicar que os campos estão vazios
    alert('Por favor, preencha a data e a hora da aula.');
  }
});


$(document).ready(function() {
    $('.card_servicos').on('click', function() {
        $(this).toggleClass('card-selected');
        
        const servico = {
            id: $(this).data('servico_id'),
            titulo: $(this).data('servico_titulo'),
            preco: $(this).data('servico_preco')
        };

        $(".schedule-header").show()
        toggleServico(servico);
    });
});

function calcularPrecoTotal() {
    let servicos = localStorage.getItem('servicos');
    
    if (servicos) {
        servicos = JSON.parse(servicos);
    } else {
        servicos = [];
    }

    let total = 0;

    for (let servico of servicos) {
        total += servico.preco;
    }

    return total;
}


function toggleServico(servico) {
    // Pega a lista de serviços do localStorage
    let servicos = localStorage.getItem('servicos');

    // Se não tem nada no localStorage, inicializa uma lista vazia
    if (!servicos) {
        servicos = [];
    } else {
        // Converte a string de volta em uma lista
        servicos = JSON.parse(servicos);
    }

    // Procura pelo serviço na lista
    const index = servicos.findIndex(s => s.id === servico.id);

    if (index === -1) {
        // Se o serviço não está na lista, adiciona
        servicos.push(servico);
    } else {
        // Se o serviço está na lista, remove
        servicos.splice(index, 1);
    }

    // Salva a lista atualizada no localStorage
    localStorage.setItem('servicos', JSON.stringify(servicos));
}



				</script>
</x-layoutsadmin>