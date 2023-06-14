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
                        <x-text-input name="nome_escola" size="30" label="Nome Completo" />

                        
                        
                        <div class="form-group">
                            <label>Descrição da Escola Surf</label>
                            <textarea id="" cols="30" rows="10" name="descricao" class="form-control descricao">{{ old('descricao', $model->descricao ?? '') }}</textarea>
                            @error('descricao')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                        <x-text-input name="telefone" size="30" label="Telefone" />
                        
                       
                        <x-text-input name="cep" size="30" label="Cep" />

                        
                        <x-text-input name="rua" size="30" label="Rua" />
                    
                        <x-text-input name="numero" size="30" label="Numero" />
                       
                        

                        <div class="form-group">                         
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