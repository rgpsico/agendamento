<x-admin.layout title="{{$pageTitle}}">

    <x-modal-delete/>
    <x-modal-editar-usuario/>
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
        
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

                    <!-- Botão de cadastrar serviço -->
                    <div class="col-2 d-flex align-items-center justify-content-end">
                        <a href="{{ route('admin.servico.create') }}" class="btn btn-primary gsap-btn">
                            <i class="icon icon-plus"></i> Cadastrar Serviço
                        </a>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card gsap-card">
                        <div class="card-body">
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
                                        <tr class="linha_{{$value->id}} gsap-row">
                                            <td>{{$value->id}}</td>                           
                                            <td>{{$value->titulo}}</td>
                                            <td>{{$value->descricao}}</td>
                                            <td>{{$value->preco}}</td>
                                            <td>{{$value->tempo_de_aula}}</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" 
                                                        href="{{route('admin.servico.edit',['id' => $value->id])}}">
                                                        <i class="fe fe-pencil"></i> Editar
                                                    </a>
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
  
    <script src="{{asset('request.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

   <script>
    $(document).ready(function(){

        // Animações GSAP
        gsap.from(".gsap-btn", { 
            duration: 1, 
            y: -50, 
            opacity: 0, 
            ease: "bounce" 
        });

        gsap.from(".gsap-card", { 
            duration: 1, 
            y: 100, 
            opacity: 0, 
            ease: "power3.out" 
        });

        gsap.from(".gsap-row", { 
            duration: 0.6, 
            x: -50, 
            opacity: 0, 
            stagger: 0.1, 
            ease: "power2.out" 
        });

        // Excluir
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
    });
   </script>

</x-admin.layout>
