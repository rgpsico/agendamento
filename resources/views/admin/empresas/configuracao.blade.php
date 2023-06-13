<x-admin.layout title="Listar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}" />
            <!-- /Page Header -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Geral </h4>
                </div>
                <div class="card-body">
                    <form action="{{route('empresa.update',['id' => Auth::user()->id])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Nome da Escola de Surf</label>
                            <input type="text" class="form-control" name="nome_escola" value="{{ old('nome_escola', $model->name ?? '') }}">
                            @error('nome_escola')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Descrição da Escola Surf</label>
                            <textarea id="" cols="30" rows="10" name="descricao" class="form-control descricao">{{ old('descricao', $model->descricao ?? '') }}</textarea>
                            @error('descricao')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" class="form-control" name="telefone" value="{{ old('telefone', $model->telefone ?? '') }}">
                            @error('telefone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror  
                        </div>
                        
                        <div class="form-group">
                            <label>Cep</label>
                            <input type="text" class="form-control" name="cep" value="{{ old('cep', $model->endereco->cep ?? '') }}">
                            @error('cep')
                            <span class="text-danger">{{$message}}</span>
                            @enderror  
                        </div>
                        
                        <div class="form-group">
                            <label>Rua</label>
                            <input type="text" class="form-control" name="rua" value="{{ old('rua', $model->endereco->rua ?? '') }}">
                            @error('rua')
                            <span class="text-danger">{{$message}}</span>
                            @enderror    
                        </div>
                        
                        <div class="form-group">
                            <label>Numero</label>
                            <input type="text" class="form-control" name="numero" value="{{ old('numero', $model->endereco->numero ?? '') }}">
                            @error('numero')
                            <span class="text-danger">{{$message}}</span>
                            @enderror    
                        </div>
                        

                        <div class="form-group">
                          
                        
                            <!-- Aqui é onde você coloca a imagem -->
                            <div class="mb-3">
                                <img src="" width="150" height="150" alt="Logo da Escola de Surf">
                            </div>

                            <label>Logo da Escola de surf </label>
                            <input type="file" class="form-control" name="logo">
                            <small class="text-secondary">Tamanho recomendado <b>150px x 150px</b></small>
                        </div>
                      
                        
                  
                </div>
                <div class="card-footer d-flex">
                    <button class="btn btn-success justify-content-right" >Salvar</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>