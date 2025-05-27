<x-adminaluno.layout title="Agenda">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
        

            
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
                                    <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" 
                                                        src="{{asset('template/assets/img/doctors/doctor-thumb-02.jpg')}}"
                                                         alt="User Image"></a>
                                                    <a href="profile.html">Dr. Darren Elder</a>
                                                </h2>
                                            </td>
                                            <td>Dental</td>
                                            
                                            
                                            <td>5 Nov 2019 <span class="text-primary d-block">11.00 AM - 11.35 AM</span></td>
                                            <td>
                                                <div class="status-toggle">
                                                    <input type="checkbox" id="status_2" class="check" checked="">
                                                    <label for="status_2" class="checktoggle">checkbox</label>
                                                </div>
                                            </td>
                                            <td class="text-start">
                                                $300.00
                                            </td>
                                        </tr>
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