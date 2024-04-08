<x-admin.layout title="Meios de Pagamentos">
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
                    <form action="{{ isset($model) ? route('empresa.pagamento.update', $model->id) : route('empresa.pagamento.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($model))
                            @method('PUT') <!-- Ou @method('PATCH') se sua rota de update esperar PATCH -->
                        @endif
                    
                        <input type="hidden" name="empresa_id" value="{{Auth::user()->empresa->id}}">
                        <input type="hidden" name="id" value="{{ $model->id ?? '' }}" />
                    
                        <x-text-input name="name" size="30" label="Nome" :value="$model->name ?? '' " />
                        <x-text-input name="api_key" size="30" label="Key" :value="$model->api_key ?? ''" />
                    
                        <!-- Fechamento incorreto da div removido -->
                    
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ (isset($model) && $model->status == '1') ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ (isset($model) && $model->status == '0') ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                        
                        <div class="card-footer d-flex">
                            <button class="btn btn-success justify-content-right">Salvar</button>
                        </div>
                    </form>
                    
        </div>
    </div>
  
    <!-- /Page Wrapper -->
</x-layoutsadmin>