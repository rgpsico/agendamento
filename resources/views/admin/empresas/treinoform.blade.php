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
                    <x-alert/>
                    <form action="{{route('empresa.update',['id' => Auth::user()->id ])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden"  name="user_id" value="{{Auth::user()->id}}" />

                        <x-text-input name="nome" size="30" label="Nome Completo" :value="$model->nome ?? '' "/>

                        <x-select-modalidade  label="Modalidade"  :model="$model" :modalidades="$modalidades"/>  
                                                                      
                        <x-text-input name="cnpj" size="30" label="Cnpj" :value="$model->cnpj ?? '' " />

                        <x-text-area name="descricao" label="Descrição" :model="$model" />

                        <x-text-input name="telefone" size="30" label="Telefone" :value="$model->telefone ?? '' " />                
                
                        
                        <x-text-input name="valor_aula_de" size="30" label="Preço Minimo aula" :value="$model->valor_aula_de ?? ''" placeholder="Valor Aula" />
                       
                        <x-text-input name="valor_aula_ate" size="30" label="Preço Maximo aula" :value="$model->valor_aula_ate ?? ''" placeholder="Valor Aula" />
                       
                        <x-avatar-component label="Logo da Escola " :model="$model"/>

                        <div class="form-group">                         
                            <div class="mb-3">      
                                @isset($model->banners)
                                    <img src="{{ asset('banner/' . $model->banners) }}" width="150" height="150" alt="Logo da Escola de Surf">
                                @endisset
                            </div>
                            <label>{{$label ?? 'Banners da Empresa'}}</label>
                            <input type="file" class="form-control" name="banner">
                            <small class="text-secondary">Tamanho recomendado <b>1116px x 400px</b></small>
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