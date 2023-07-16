<x-admin.layout title="{{$pageTitle}}">

    <x-modal-delete/>
    <x-modal-editar-usuario/>
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <div class="page-header">
                <div class="row">
                    <div class="col-10">
                        <h3 class="page-title">{{$pageTitle ?? ''}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="">Admin</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:(0);">Serviços </a>
                            </li>
                      
                        </ul>
                    </div>
                </div>
                @if(isset($btAdd) && $btAdd ==  'true')
                <div class="row">
                    <div class="col-2 my-4">
                        @if(!isset($modal))
                            <button class="btn btn-success Adicionar{{$pageTitle}}">
                                <i class="icon icon-plus">Adicionar {{$pageTitle}}</i>
                            </button>
                        @else 
                            <button  class="btn btn-success" id="Adicionar{{$pageTitle}}">
                                <i class="icon icon-plus">Adicionar {{$pageTitle}}</i>
                            </button>
                        @endif    
                    </div>
                </div>
                @endif
            </div>
            
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Titulo</th>
                                            <th>Descricao</th>
                                            <th>Preço</th>
                                            <th>Tempo de Aula</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model as $value )
                                            
                                    
                                        <tr class="linha_{{$value->id}}">
                                          
                                            <td>{{$value->id}}</td>                           
                                            <td>{{$value->titulo}}</td>
                                            <td>{{$value->descricao}}</td>
                                            <td>{{$value->preco}}</td>
                                            <td>{{$value->tempo_de_aula}}</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" 
                                                        href="{{route('admin.servico.edit',['id' => $value->id])}}"
                                                        data-data="{{$model}}"
                                                        >
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>

                                                    {{-- <a class="btn btn-sm bg-info " href="{{route($route.'.show',['id' => $value->id])}}">
                                                        <i class="fe fe-eye"></i> Ver

                                                    </a> --}}
                                                    <a data-bs-toggle="modal" href="#delete_modal" data-id="{{$value->id}}" class="btn btn-sm bg-danger-light bt_excluir">
                                                        <i class="fe fe-trash"></i> Excluir
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>			
            </div>
            
        </div>			
    </div>
  
    <script src="{{asset('request.js')}}"></script>

   <script>
    $(document).ready(function(){
        $(document).on("click", ".bt_excluir", function() {
        var id = $(this).data('id');
         $('.confirmar_exclusao').data('id', id);
         $(".delete_modal").modal('show');
    });

    $(document).on("click", ".confirmar_exclusao", function() {
        var id = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');
     
   

    $.ajax({
        url: '/api/servicos/' + id,
        type: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + token,
        },
        success: function(result) {
            alert('Excluido com sucesso')
            $('.modal').modal('hide');
            $(".linha_"+id).fadeOut();
        },
        error: function(request,msg,error) {
            console.log(error);
        }
    });
 });


   


})
    
   </script>

</x-layoutsadmin>