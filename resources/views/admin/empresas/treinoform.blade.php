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
                    <form action="{{route('empresa.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="" name="user_id" value="{{Auth::user()->id}}" />
                                      
                        <x-text-input name="nome" size="30" label="Nome Completo" :value="$model"/>
                                                
                        <x-text-input name="cnpj" size="30" label="Cnpj" :value="$model" />

                        <div class="form-group">
                            <label>Descrição da Escola Surf</label>
                            <textarea id="" cols="30" rows="10" name="descricao" class="form-control descricao">{{ old('descricao', $model->descricao ?? '') }}</textarea>
                            @error('descricao')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                        <x-text-input name="telefone" size="30" label="Telefone" :value="$model" />
                        
                       
                        <x-text-input name="cep" size="30" label="Cep" :value="$model->endereco ?? '' " />
                           
                        <x-text-input name="estado" size="30" label="Estado" :value="$model->endereco ?? '' " />
                        
                        <x-text-input name="uf" size="30" label="Uf" :value="$model->endereco ?? ''" />
                    
                        <x-text-input name="pais" size="30" label="Pais" :value="$model->endereco ?? '' " />

                        <x-text-input name="cidade" size="30" label="Cidade" :value="$model->endereco ?? ''" />
        
                            <x-text-input name="endereco" size="30" label="Endereco" :value="$model->endereco ?? ''" />
                        <div class="form-group">                         
                               <div class="mb-3">
                                @isset($model->avatar)
                                    <img src="{{ asset('avatar/' . $model->avatar) }}" width="150" height="150" alt="Logo da Escola de Surf">
                                @endisset
                                </div>
                                <label>Logo da Escola de surf </label>
                                <input type="file" class="form-control" name="avatar">
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