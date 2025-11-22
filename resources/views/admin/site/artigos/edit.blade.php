<x-admin.layout title="Editar Artigo">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Editar Artigo</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.site.artigos.index') }}">Blog</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-alert />

            <form action="{{ route('admin.site.artigos.update', $artigo) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.site.artigos.form', ['artigo' => $artigo, 'statuses' => $statuses])
            </form>
        </div>
    </div>
</x-admin.layout>
