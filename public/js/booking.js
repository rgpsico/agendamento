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
    
	
  }
});


$(document).on('click', '.day-slot li', function(e) {
  e.preventDefault();
  
  if (!$(this).hasClass('left-arrow') && !$(this).hasClass('right-arrow')) {
    $('.day-slot li').removeClass('selected-date');
    $(this).addClass('selected-date');

    const dayOfWeek = $(this).find('span:first').text();
    
    // Extract the day, month, and year
    const day = $(this).find('span.slot-date').text().trim();
    const month = $(this).find('span:not(.slot-date)').text().trim();
    const year = $(this).find('small.slot-year').text().trim();

    // Combine to get the full date
    const fullDate = day + ' ' + month + ' ' + year;

    let data_selecionada = converterData(fullDate)
    console.log( converterData(fullDate)); // You can see the full date in the console for now

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
$('#spinner').show()
    $.ajax({
      url: '/api/disponibilidade', 
      method: 'GET',
      data: {
        day: dayNumber,
        data_select:data_selecionada,
        professor_id:$('#professor_id').val()
      },
      success: function(response) {
        $('.time-slot ul').html(''); 
        $('#spinner').hide()
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

function converterData(dateString) {
    // Define month mapping
    const monthMapping = {
        'jan.': '01',
        'fev.': '02',
        'mar.': '03',
        'abr.': '04',
        'mai.': '05',
        'jun.': '06',
        'jul.': '07',
        'ago.': '08',
        'set.': '09',
        'out.': '10',
        'nov.': '11',
        'dez.': '12'
    };

    // Split the date string by spaces
    const parts = dateString.split(' ');

    // Extract day, month, and year
    const day = parts[0].padStart(2, '0'); // Ensure day is two digits
    const month = monthMapping[parts[1]];
    const year = parts[2];

    // Return in the format "YYYY-MM-DD"
    return year + '-' + month + '-' + day;
}


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
       // servicos.splice(index, 1);
    }

    // Salva a lista atualizada no localStorage
    localStorage.setItem('servicos', JSON.stringify(servicos));
}
