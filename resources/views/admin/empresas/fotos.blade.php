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
                    <!-- Gallery -->
                  
                    <!-- Gallery -->
                    <!-- Upload Form -->
                    <form action="{{route('empresa.upload')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <input type="text" name="empresa_id" value="{{Auth::user()->id}}">
                        
                    
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image" required>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </div>                        
                     
                    </form>
                    <!-- End Upload Form -->
                </div>

                <div class="row p-5">
                    <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp" class="w-100 shadow-1-strong rounded mb-4" alt="Boat on Calm Water" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layoutsadmin>
