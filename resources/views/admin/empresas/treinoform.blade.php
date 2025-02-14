<x-admin.layout title="{{ isset($model) ? 'Editar Empresa' : 'Criar Empresa' }}">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{ isset($model) ? 'Editar Empresa' : 'Criar Empresa' }}" />
            <!-- /Page Header -->
           
            @include('admin.empresas._partials.modal')

            <div class="row">
                <form 
                    action="{{ isset($model) ? route('empresa.update', ['id' => $model->id]) : route('empresa.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if(isset($model))
                        @method('POST') <!-- Laravel aceita PUT/PATCH, mas como está configurado como POST, mantendo assim -->
                    @endif

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

                    <x-text-input name="nome" size="30" label="Nome Completo" :value="$model->nome ?? ''"/>

                    <x-select-modalidade label="Modalidade" :model="$model ?? null" :modalidades="$modalidades"/>  
                                                                  
                    <x-text-input name="cnpj" size="30" label="CNPJ" :value="$model->cnpj ?? ''" />

                    <x-text-area name="descricao" label="Descrição" :model="$model ?? null" />

                    <x-text-input name="telefone" size="30" label="Telefone" :value="$model->telefone ?? ''" />                
            
                    <x-text-input name="valor_aula_de" size="30" label="Preço Mínimo Aula" :value="$model->valor_aula_de ?? ''" placeholder="Valor Aula" />
                   
                    <x-text-input name="valor_aula_ate" size="30" label="Preço Máximo Aula" :value="$model->valor_aula_ate ?? ''" placeholder="Valor Aula" />
                   
                    <x-avatar-component label="Logo da Escola" :model="$model ?? null"/>

                    <button type="submit" class="btn btn-primary mt-3">
                        {{ isset($model) ? 'Atualizar' : 'Criar' }}
                    </button>
                </form>
            </div>			
        </div>
    </div>
    <!-- /Page Wrapper -->
</x-admin.layout>
