<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{$pageTitle}}" />
            <!-- /Page Header -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Geral</h4>
                </div>
                <div class="card-body">
                    <x-alert/>
                    <form action="{{route('empresa.upload')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="empresa_id" value="{{Auth::user()->id}}">
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image[]" multiple required>        </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </div>                        
                     
                    </form>
                    <!-- End Upload Form -->
                </div>

                <div class="row p-5">
                    @foreach ($model as $value)
                    <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                        <img src="{{ asset('galeria_escola/' . $value->image) }}" class="w-100 shadow-1-strong rounded mb-4" alt="Imagem" />
                        <form method="POST" action="{{ route('gallery.destroy', $value->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                    @endforeach
                </div>
                
                
            </div>
        </div>
    </div>
</x-layoutsadmin>
