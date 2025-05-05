
<div class="tab-pane fade hide active" id="todosAlunos">  
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length mb-4" id="DataTables_Table_0_length">
                                        <label>Ver Registros
                                            <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> 
                                        </label>
                                    </div>
                                </div>
                                        <div class="col-sm-12 col-md-6">                                            
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                
                                                <table class="datatable table table-hover table-center mb-0 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 150.578px;">#</th>
                                    <th style="width: 378.891px;">Nome</th>
                                    <th style="width: 372.531px;">E-mail</th>
                                    <th style="width: 372.531px;">Telefone</th>
                                    <th style="width: 372.531px;" class="actions">Ações</th>
                                </tr>
                            </thead>
                            <tbody>                              
                                 <tr role="row" class="odd">
                                    <td class="sorting_1">{{$value->id ?? ''}}</td>                                    
                                    <td class="sorting_1">{{$value->nome ?? ''}}</td>   
                                    <td class="sorting_1">{{$value->email ?? ''}}</td>
                                    <td class="sorting_1">{{$value->telefone ?? ''}}</td>
                                    <td class="text-start">
                                        <div class="actions">
                                            <a class="btn btn-sm bg-success-light" data-bs-toggle="modal" href="#edit_specialities_details">
                                                <i class="fe fe-pencil"></i> Edit
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
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- /Personal Details -->
</div>