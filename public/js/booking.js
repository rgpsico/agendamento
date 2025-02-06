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
  $daySlot.append('<li class="left-arrow"><a href="#"><i class="fa fa-chevron-left"></i></a></li>');

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

  $daySlot.append('<li class="right-arrow"><a href="#"><i class="fa fa-chevron-right"></i></a></li>');
}

$(document).ready(function() {
  renderDates();

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

  $(document).on('click', '.day-slot li', function(e) {
    e.preventDefault();
    $('.submit-btn').addClass('disabled');

    if (!$(this).hasClass('left-arrow') && !$(this).hasClass('right-arrow')) {
        $('.day-slot li').removeClass('selected-date');
        $(this).addClass('selected-date');

        const dayOfWeek = $(this).find('span:first').text();
        const fullDate = $(this).find('.slot-date').text() + ' ' + $(this).find('.slot-year').text();
        let data_selecionada = converterData(fullDate);

        $('.dia_da_semana').val(dayOfWeek);
        $('.data').val(data_selecionada);

        const servicoSelecionado = $('.card-selected').data('tipo_agendamento');
        const servicoId = $('.card-selected').data('servico_id');

        if (servicoSelecionado === 'DIA') {
            buscarDisponibilidade(servicoId, data_selecionada);
        } else {
            buscarHorarios(dayOfWeek, data_selecionada);
        }
    }
});



  $(document).on('click', '.timing', function(e) {
    e.preventDefault();

    $('.timing').removeClass('selected');
    $(this).addClass('selected');

    const time = $(this).find('span').text();
    $('.hora_da_aula').val(time);
    $('.submit-btn').removeClass('disabled');
  });

  $('.submit-btn').on('click', function(e) {
    e.preventDefault();

    var diaDaSemana = $('.dia_da_semana').val();
    var data = $('.data').val();
    var horaDaAula = $('.hora_da_aula').val();
    var tipoAgendamento = $('.card-selected').data('tipo_agendamento');

    if (tipoAgendamento === 'DIA' && data) {
        // Apenas armazena a data e segue para pagamento
        localStorage.setItem('diaDaSemana', diaDaSemana);
        localStorage.setItem('data', data);

        window.location.href = $(this).attr('href');
    } 
    else if (tipoAgendamento === 'HORARIO' && data && horaDaAula) {
        // Serviço com horário precisa da hora também
        localStorage.setItem('diaDaSemana', diaDaSemana);
        localStorage.setItem('data', data);
        localStorage.setItem('horaDaAula', horaDaAula);

        window.location.href = $(this).attr('href');
    } 
    else {
        alert('Por favor, preencha a data e o horário corretamente.');
    }
});


  // Corrigindo a seleção do serviço
  $('.card_servicos').on('click', function() {
    localStorage.removeItem('servicos');
    $('.time-slot ul').empty();
    $('.submit-btn').addClass('disabled');
    $('.day-slot li').removeClass('selected-date');

    $('.card_servicos').removeClass('card-selected');
    $(this).addClass('card-selected');

    const servico = {
        id: $(this).data('servico_id'),
        titulo: $(this).data('servico_titulo'),
        preco: $(this).data('servico_preco'),
        tipo_agendamento: $(this).data('tipo_agendamento')
    };

    console.log(servico);
    $(".schedule-header").show(); // Mantém a seleção de datas visível

    toggleServico(servico);

    // Ajusta a interface conforme o tipo de serviço
    if (servico.tipo_agendamento === 'DIA') {
        $('.time-slot ul').html(`
            <li class="text-info">
                <strong>Este serviço não possui horários específicos.</strong>
            </li>
        `);
        $('.submit-btn').addClass('disabled'); // Botão desativado até que o usuário escolha um dia
    } else {
        $('.time-slot ul').empty(); // Limpa horários anteriores
    }
});





function buscarDisponibilidade(servicoId, dataSelecionada) {
  $.ajax({
      url: '/api/disponibilidadedia',
      method: 'GET',
      data: {
          servico_id: servicoId,
          data: dataSelecionada
      },
      success: function(response) {
          $('.time-slot ul').html('');

          if (response.vagas_disponiveis > 0) {
              $('.time-slot ul').html(`
                  <li class="text-success">
                      <strong>Vagas Disponíveis: ${response.vagas_disponiveis}</strong>
                  </li>
                  <li class="text-info">
                      <strong>Este serviço não possui horários específicos.</strong>
                  </li>
              `);
              $('.submit-btn').removeClass('disabled');
          } else {
              $('.time-slot ul').html(`
                  <li class="text-danger">
                      <strong>Nenhuma vaga disponível para esta data.</strong>
                  </li>
              `);
              $('.submit-btn').addClass('disabled');
          }
      },
      error: function() {
          $('.time-slot ul').html(`
              <li class="text-danger">
                  <strong>Erro ao carregar disponibilidade.</strong>
              </li>
          `);
          $('.submit-btn').addClass('disabled');
      }
  });
}




function buscarHorarios(dayOfWeek, data_selecionada) {
  const dayMapping = {
    'seg.': 1, 'ter.': 2, 'qua.': 3, 'qui.': 4,
    'sex.': 5, 'sáb.': 6, 'dom.': 7
  };

  const dayNumber = dayMapping[dayOfWeek];
  const professorId = $('#professor_id').val();
  const servicoSelecionado = $('.card-selected').data('servico_id'); // Pega corretamente o ID do serviço selecionado

  if (!servicoSelecionado) {
    alert("Selecione um serviço antes de escolher o horário.");
    return;
  }

  console.log("Serviço Selecionado ID:", servicoSelecionado);

  $('#spinner').show();
  
  $.ajax({
    url: '/api/disponibilidade',
    method: 'GET',
    data: {
      day: dayNumber,
      data_select: data_selecionada,
      professor_id: professorId,
      servico_id: servicoSelecionado
    },
    success: function(response) {
      $('.time-slot ul').html('');
      $('#spinner').hide();

      if (response.length === 0) {
        $('.time-slot ul').append('<li class="text-danger">Nenhum horário disponível</li>');
      } else {
        response.forEach(function(time) {
          const timeElement = `<li>
            <a class="timing" href="#">
              <span>${time}</span>
            </a>
          </li>`;
          $('.time-slot ul').append(timeElement);
        });
      }
    },
    error: function() {
      $('#spinner').hide();
      $('.time-slot ul').html('<li class="text-danger">Erro ao carregar horários</li>');
    }
  });
}

function converterData(dateString) {
  const monthMapping = {
    'jan.': '01', 'fev.': '02', 'mar.': '03', 'abr.': '04',
    'mai.': '05', 'jun.': '06', 'jul.': '07', 'ago.': '08',
    'set.': '09', 'out.': '10', 'nov.': '11', 'dez.': '12'
  };

  const parts = dateString.split(' ');
  const day = parts[0].padStart(2, '0');
  const month = monthMapping[parts[1]];
  const year = parts[2];

  return year + '-' + month + '-' + day;
}

function toggleServico(servico) {
  let servicos = localStorage.getItem('servicos');

  if (!servicos) {
    servicos = [];
  } else {
    servicos = JSON.parse(servicos);
  }

  const index = servicos.findIndex(s => s.id === servico.id);

  if (index === -1) {
    servicos.push(servico);
  } else {
    servicos.splice(index, 1);
  }

  localStorage.setItem('servicos', JSON.stringify(servicos));
}
});