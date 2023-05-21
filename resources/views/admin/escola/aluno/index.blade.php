<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}" btAdd="true" route="{{$route}}" />
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
                                            <th>Aluno</th>
                                            <th>Email</th>
                                            <th>Idade</th>
                                            <th>Telefone</th>
                                           <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td>#PT015</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{asset('admin/img/patients/patient15.jpg')}}" alt="User Image"></a>
                                                    <a href="profile.html">Jessica Garza</a>
                                                </h2>
                                            </td>
                                   
                                            <td>4672  Rose Street, Schaumburg, Illinois, 60173</td>
                                            <td>7082788201</td>
                                            <td>6 Nov 2019</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <a class="btn btn-sm bg-info-light" data-bs-toggle="modal" href="#edit_specialities_details">
                                                        <i class="fe fe-pencil"></i> Edit
                                                    </a>

                                                    <a class="btn btn-sm bg-info" href="{{route('alunos.show',['id' => 1])}}">
                                                        <i class="fe fe-eye"></i> Ver

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