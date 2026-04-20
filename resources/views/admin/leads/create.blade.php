<x-admin.layout title="Novo Lead">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="page-title">Novo Lead</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">Leads</a></li>
                            <li class="breadcrumb-item active">Novo</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.leads.store') }}" method="POST">
                                @csrf

                                @include('admin.leads._form')

                                <div class="mt-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-save"></i> Salvar Lead
                                    </button>
                                    <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
