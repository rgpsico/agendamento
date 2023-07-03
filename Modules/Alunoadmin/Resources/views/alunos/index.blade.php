@extends('alunoadmin::layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div class="page-wrapper" style="min-height: 239px;">
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{$title}}"/>
        <!-- /Page Header -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row"><div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                <label>Ver<select name="DataTables_Table_0_length" 
                                    aria-controls="DataTables_Table_0" 
                                    class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
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
                                <th>Professor</th>
                                <th>Modalidade</th>
                                <th>Data da Aula</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                       <tbody>
                        @foreach($agendamentos as $agendamento)
                            <tr role="row" class="odd">
                                <td class="sorting_1">
                                    <h2 class="table-avatar">
                                        <a href="" class="avatar avatar-sm me-2">
                                            <img class="avatar-img rounded-circle" 
                                            src="{{asset('admin/img/doctors/doctor-02.jpg')}}"
                                             alt="User Image"></a>
                                        <a href="">{{ $agendamento->professor->usuario->nome
                                            ?? 'AQUi'}}</a>
                                    </h2>
                                </td>
                                <td>{{ $agendamento->modalidade->nome}}</td>
                                <td>
                                    <span class="text-primary d-block">{{ date('d/m/Y', strtotime($agendamento->data_da_aula)) }}</span>
                                </td>

                                <td class='badge rounded-pill bg-success inv-badge my-4'>{{ $agendamento->status}} </td>
                                <td class='text-success font-weight-bold'>R$ {{ number_format($agendamento->preco, 2, ',', '.') }}</td>
                                <td>
                                    <a href="" class="btn btn-primary">Avaliar</a>
                                    <a href="" class="btn btn-secondary">Mensagem</a>
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
@endsection
