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
                    <form action="{{route('empresa.update_endereco',['userId' => Auth::user()->id ])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <x-text-input name="cep" size="30" label="Cep" :value="$model->endereco ?? '' " />
                           
                        <x-text-input name="estado" size="30" label="Estado" :value="$model->endereco ?? '' " />
                        
                        <x-text-input name="uf" size="30" label="Uf" :value="$model->endereco ?? ''" />
                    
                        <x-text-input name="pais" size="30" label="Pais" :value="$model->endereco ?? '' " />

                        <x-text-input name="cidade" size="30" label="Cidade" :value="$model->endereco ?? ''" />
        
                        <x-text-input name="endereco" size="30" label="Endereco" :value="$model->endereco ?? ''" />
                     
                    </div>
                    <input type="hidden"  name="empresa_id" value="{{$model->id}}" />   
                        <div class="card-footer d-flex">
                    <button class="btn btn-success justify-content-right" >Salvar</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    <script>
        document.getElementById('cep').addEventListener('blur', function() {
            let cep = this.value;
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('endereco').value = data.logradouro;
                document.getElementById('cidade').value = data.localidade;
                document.getElementById('estado').value = data.uf;  // ViaCEP não fornece o nome completo do estado, somente a sigla
                document.getElementById('uf').value = data.uf;
                document.getElementById('pais').value = 'Brasil';  // ViaCEP é um serviço brasileiro, então podemos assumir que o país é Brasil
            })
            .catch(error => console.error('Error:', error));
        });
        </script>
    <!-- /Page Wrapper -->
</x-layoutsadmin>