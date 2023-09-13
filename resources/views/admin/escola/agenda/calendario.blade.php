<x-admin.layout title="Agendar de Alunos">
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
                    // Adicione mais eventos conforme necessário.
                ]
            });
        });
    </script>
    
    
</x-layoutsadmin>