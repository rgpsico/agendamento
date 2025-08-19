<x-admin.layout :title="$pageTitle">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">{{ $pageTitle }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('site-templates.index') }}">Templates</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">	
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informações do Template</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            @isset($model->id)
                                <form action="{{ route('site-templates.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                            @else 
                                <form action="{{ route('site-templates.store') }}" method="POST" enctype="multipart/form-data">
                            @endisset

                                @csrf
                                @include('admin.sitetemplate._partials.form')  

                                <div class="card-footer d-flex">
                                    <button class="btn btn-success justify-content-right">
                                        {{ isset($model->id) ? 'Atualizar' : 'Salvar' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>	
        </div>			
    </div>
</x-admin.layout>
