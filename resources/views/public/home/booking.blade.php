<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->

			<style>
				.selected-date {
					background-color: #42c0fb;
    				border: 1px solid #42c0fb;
    				color: #ffffff;
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
										  <img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid" alt="Usuario Image">
									</a>
									<div class="booking-info">
										<h4><a href="">{{$model->nome}}</a></h4>
										<x-avaliacao-home :model="$model" />
										<p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> {{$model->endereco->cidade}}, {{$model->endereco->pais}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-4 col-md-6">
								<h4 class="mb-1">{{ \Carbon\Carbon::now()->format('d F Y') }}</h4>
								<p class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</p>
							</div>
							
							
						</div>
						<input type="text" class="dia_da_semana">
						<input type="text" class="data">
						<input type="text" class="hora_da_aula">
						<!-- Schedule Widget -->
						<div class="card booking-schedule schedule-widget">
						
							<!-- Schedule Header -->
							<div class="schedule-header">
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
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing selected" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
												<li>
													<a class="timing" href="#">
														<span>9:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>10:00</span> <span>AM</span>
													</a>
													<a class="timing" href="#">
														<span>11:00</span> <span>AM</span>
													</a>
												</li>
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
      <li>
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
  localStorage.setItem('diaDaSemana', diaDaSemana);
  localStorage.setItem('data', data);
  localStorage.setItem('horaDaAula', horaDaAula);

  // Permite que o evento de clique prossiga (navegação)
  window.location.href = $(this).attr('href');
});

				</script>
</x-layoutsadmin>