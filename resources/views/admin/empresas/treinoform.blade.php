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
                    <form action="{{route('empresa.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="" name="user_id" value="{{Auth::user()->id}}" />
                                      
                      
                        <x-text-input name="nome" size="30" label="Nome Completo" :value="$model"/>

                        <x-select-modalidade  label="Modalidade" />
                                                                        
                        <x-text-input name="cnpj" size="30" label="Cnpj" :value="$model" />

                        <x-text-area name="descricao" label="Descrição" :model="$model" />

                        <x-text-input name="telefone" size="30" label="Telefone" :value="$model" />                
                
                        <x-text-input name="cep" size="30" label="Cep" :value="$model->endereco ?? '' " />
                           
                        <x-text-input name="estado" size="30" label="Estado" :value="$model->endereco ?? '' " />
                        
                        <x-text-input name="uf" size="30" label="Uf" :value="$model->endereco ?? ''" />
                    
                        <x-text-input name="pais" size="30" label="Pais" :value="$model->endereco ?? '' " />

                        <x-text-input name="cidade" size="30" label="Cidade" :value="$model->endereco ?? ''" />
        
                        <x-text-input name="endereco" size="30" label="Endereco" :value="$model->endereco ?? ''" />
                       
                        <x-text-input name="valor_aula_de" size="30" label="Preço Minimo aula" :value="$model->valor_aula_de ?? ''" placeholder="Valor Aula" />
                       
                        <x-text-input name="valor_aula_ate" size="30" label="Preço Maximo aula" :value="$model->valor_aula_ate ?? ''" placeholder="Valor Aula" />
                       
                        <x-avatar-component label="Logo da Escola " :model="$model"/>
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