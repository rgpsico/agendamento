<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-lg-6 col-md-12 mb-2 mb-lg-0">
            <h3 class="page-title">Bem-vindo <b class="text-capitalize">{{ Auth::user()->empresa->nome ?? 'Usuário' }}</b>
            </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="">Admin</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Alunos</a>
                </li>
            </ul>
        </div>

        <!-- Formulário de Filtro Responsivo -->
        <div class="col-lg-6 col-md-12">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="date-filter d-flex">
                <input type="date" class="form-control" id="start-date" name="data_inicial"
                    value="{{ request('data_inicial') }}">
                <input type="date" class="form-control mx-2" id="end-date" name="data_final"
                    value="{{ request('data_final') }}">
                <button type="submit" id="filter-button" class="btn btn-success">Filtrar</button>
            </form>
        </div>
    </div>
</div>
<!-- /Page Header -->
