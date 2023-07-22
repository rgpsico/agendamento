<x-admin.layout title="Agendar de Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{$pageTitle}}" modal="true" btAdd="true" route="{{$route}}" />
            <!-- /Page Header -->

            
            <div class="row">
                <div class="col-md-12">
                
                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">   
                                                                 
                                <x-datateste :model="$model" :headers="['id', 'user.name', 'modalidade_id', 'avatar', 'nome', 'descricao']" :actions="['edit' => 'modalidade.index', 'delete' => 'modalidade.index', 'ver' => 'modalidade.show']"  />
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