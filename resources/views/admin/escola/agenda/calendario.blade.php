<x-admin.layout title="Agendar de Alunos">
   
   <!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Adicionar Evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="eventForm">
          <div class="form-group">
            <label for="eventTitle">Título do Evento</label>
            <input type="text" class="form-control" id="eventTitle">
          </div>
          <div class="form-group">
            <label for="eventStart">Data Inicial</label>
            <input type="date" class="form-control" id="eventStart">
          </div>
          <div class="form-group">
            <label for="eventEnd">Data Final</label>
            <input type="date" class="form-control" id="eventEnd">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="saveEvent">Salvar</button>
      </div>
    </div>
  </div>
</div>

   
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="row">
                <div class="col-10">
                    <h3 class="page-title">Agenda</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Admin</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:(0);">Alunos </a>
                        </li>
                  
                    </ul>
                </div>
            </div>

          
            <!-- /Page Header -->

            
            <div class="row">
                <div class="col-md-12">
                    <div id='calendar1'></div>
                </div>
         </div>			
    </div>

    <script>
        $(document).ready(function() {
            $('#calendar1').fullCalendar({
                locale: 'pt-br',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                select: function(start, end, allDay) {
                    // Mostra o modal quando um dia é selecionado
                    $('#eventModal').modal('show');
    
                    // Quando o botão "Salvar" no modal é clicado
                    $('#saveEvent').on('click', function() {
                        var title = $('#eventTitle').val();
                        var start = $('#eventStart').val();
                        var end = $('#eventEnd').val();
    
                        if (title) {
                            // Adiciona o evento ao calendário
                            $('#calendar1').fullCalendar('renderEvent',
                                {
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                },
                                true
                            );
                        }
                        
                        $('#eventModal').modal('hide');
                        $('#eventForm')[0].reset();
                    });
                },
                events: [
                    {
                        title  : 'Evento 1',
                        start  : '2023-09-13'
                    },
                    {
                        title  : 'Evento 2',
                        start  : '2023-09-14',
                        end    : '2023-09-16'
                    }
                ]
            });
        });
    </script>
    
    
    
</x-layoutsadmin>