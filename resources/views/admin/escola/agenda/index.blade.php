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

            <div class="col-2 my-4">
             
                <a href="{{route('agenda.create')}}"  class="btn btn-success" >
                    <i class="icon icon-plus">Adicionar Agenda</i>
                </a>
                
        </div>
            <!-- /Page Header -->

            
            <div class="row">
                <div class="col-md-12">
                
                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                               </div>
                                                </div>
                                                    <div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table class="datatable table table-hover table-center mb-0 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 100.859px;">Nome</th>
                                            <th style="width: 91.2188px;">Email</th>
                                           
                                            <th style="width: 155.047px;">Data da aula</th>
                                            <th style="width: 61.6406px;">Status</th>
                                            <th style="width: 74.9062px;">Pagamento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model->agendamentos as $agendamento)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">
                                                    <h2 class="table-avatar">
                                                        <a href="" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" 
                                                            src="{{asset('template/assets/img/doctors/doctor-thumb-02.jpg')}}"
                                                             alt="User Image"></a>
                                                        <a href="">{{$agendamento->aluno->nome}}</a>
                                                    </h2>
                                                </td>
                                                <td>{{$agendamento->modalidade->nome}}</td>
                                                <td>{{$agendamento->data_da_aula}}</td>
                                                <td>
                                                     <span class="btn btn-success">{{$agendamento->status}}</span>    
                                                </td>
                                                <td class="text-start">
                                                    {{$agendamento->valor_aula}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        
                                    </div>
                                    <div class="col-sm-12 col-md-7">

                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>			
    </div>
 
    
<script>
   $(document).ready(function(){
   
   $("#AdicionarAgenda").click(function(e){
       e.stopPropagation();
       $('.modal-body').empty();
       $(".modal").modal('show');
       $.get('/admin/escola/agenda/form', function(data){
           $(".modal-body").html(data);
       });
   });

   // Use o seletor de atributo para selecionar todos os formulários com o atributo 'data-route'.
   $(document).on('submit', 'form[data-route]', function(e){
       e.preventDefault(); // Previne o comportamento padrão do envio do formulário.

       var route = $(this).data('route'); // Pega a rota do atributo 'data-route'.
       
       // Supondo que você esteja enviando os dados como JSON.
       var data = $(this).serializeArray();

       $.post(route, data, function(response){
           // Trate a resposta aqui.
       });
   });
});

   </script>
</x-layoutsadmin>