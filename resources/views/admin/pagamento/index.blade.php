<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}"/>
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Empresa Gateway</th>
                                            <th>Tokem</th>
                                            <th class="text-center">Status</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                           @foreach ($model as $value )                                                                                                  
                                        <tr>
                                            <td>{{$value->id ?? '10'}}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{asset('admin/img/patients/patient15.jpg')}}" alt="User Image"></a>
                                                    <a href="profile.html">{{$value->name ?? 'Escola de Surf'}}</a>
                                                </h2>
                                            </td>
                                   
                                            <td>{{$value->api_key ?? '21 990271287'}}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-success inv-badge">Ativo</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-success-light" href="{{ route('empresa.pagamento.edit', $value->id) }}">
                                                        <i class="fe fe-pencil"></i> Edit
                                                    </a>
                                                    <!-- Trigger/Link to open the delete confirmation modal -->
                                                    <a href="javascript:void(0);" class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="#delete_modal_{{ $value->id }}">
                                                        <i class="fe fe-trash"></i> Delete
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
    <!-- /Page Wrapper -->
</x-layoutsadmin>