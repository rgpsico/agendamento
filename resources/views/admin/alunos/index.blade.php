<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.breadcumbs pageTitle="{{$pageTitle}}"/>
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
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Last Visit</th>
                                            <th class="text-end">Paid</th>
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
                                            <td>10</td>
                                            <td>4672  Rose Street, Schaumburg, Illinois, 60173</td>
                                            <td>7082788201</td>
                                            <td>6 Nov 2019</td>
                                            <td class="text-end">$310.00</td>
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