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
                                            <th>Empresa</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th class="text-center">Status</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td>{{$value->id ?? '10'}}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{asset('admin/img/patients/patient15.jpg')}}" alt="User Image"></a>
                                                    <a href="profile.html">{{$value->nome ?? 'Escola de Surf'}}</a>
                                                </h2>
                                            </td>
                                   
                                            <td>{{$value->email ?? 'rgyr2010@hotmail.com'}}</td>
                                            <td>{{$value->telefone ?? '21 990271287'}}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-success inv-badge">Ativo</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-success-light" data-bs-toggle="modal" href="#edit_specialities_details">
                                                        <i class="fe fe-pencil"></i> Edit
                                                    </a>
                                                    <a class="btn btn-sm bg-info-light"  href="{{route($route.'.show',['id' => 1])}}">
                                                        <i class="fe fe-eye p-1"></i> Ver
                                                    </a>
                                                    <a data-bs-toggle="modal" href="#delete_modal" class="btn btn-sm bg-danger-light">
                                                        <i class="fe fe-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
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