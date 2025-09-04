<x-admin.layout title="Agendar Aluno">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">{{ $pageTitle }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="">Admin</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Aluno</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            {{-- Definição da rota e método --}}
                            @if(isset($model))
                                <form action="{{ route('empresa.agenda.update', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form action="{{ route('empresa.agenda.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                                @csrf

                                @include('admin.escola.agenda._partials.form')
                                <div class="form-group">
                            <label for="selectModalidades">Selecione uma modalidade:</label>
                            <select id="selectModalidades" class="form-control" name="modalidade_id">
                                <option value="">Selecione</option>
                                @foreach ($modalidades as $modalidade)
                                    <option value="{{ $modalidade->id }}" 
                                        {{ old('modalidade_id', $selectedModalidade) == $modalidade->id ? 'selected' : '' }}>
                                        {{ $modalidade->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                                <div class="card-footer d-flex">
                                    <button class="btn btn-success" type="submit">
                                        {{ isset($model) ? 'Atualizar' : 'Salvar' }}
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>			
    </div>
</x-admin.layout>
