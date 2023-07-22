<x-admin.layout title="Criar Aluno">
    <div class="page-wrapper">
        <div class="content container-fluid">
                   <!-- Page Header -->
           <x-header.titulo pageTitle="{{$pageTitle}}" />
            <!-- /Page Header -->
           
            @include('admin.empresas._partials.modal')
            <div class="row">
                <form action="{{route('empresa.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden"  name="user_id" value="{{Auth::user()->id}}" />

                    <x-text-input name="nome" size="30" label="Nome Completo" :value="$model"/>

                    <x-select-modalidade  label="Modalidade"  :model="$model" :modalidades="$modalidades"/>  
                                                                  
                    <x-text-input name="cnpj" size="30" label="Cnpj" :value="$model" />

                    <x-text-area name="descricao" label="Descrição" :model="$model" />

                    <x-text-input name="telefone" size="30" label="Telefone" :value="$model" />                
            
                    
                    <x-text-input name="valor_aula_de" size="30" label="Preço Minimo aula" :value="$model->valor_aula_de ?? ''" placeholder="Valor Aula" />
                   
                    <x-text-input name="valor_aula_ate" size="30" label="Preço Maximo aula" :value="$model->valor_aula_ate ?? ''" placeholder="Valor Aula" />
                   
                    <x-avatar-component label="Logo da Escola " :model="$model"/>
                            
            
        </div>			
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>