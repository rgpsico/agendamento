<x-admin.layout title="Editar Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Editar Bot"/>
            <!-- /Page Header -->

            <x-alert/>

            <form action="{{ route('admin.bot.update', $bot->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dados do Bot</h5>
                    </div>
                    <input type="hidden" id="empresa_id" name="empresa_id" class="form-control" value="{{ $bot->empresa_id }}" required>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Bot</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome', $bot->nome) }}" required>
                        </div>

                       <div class="mb-3">
                                <label for="prompt" class="form-label">Prompt / Missão do Bot</label>
                                <textarea id="prompt" name="prompt" class="form-control" rows="4" placeholder="Ex: Você é um assistente especializado em aulas de surf, sempre amigável e motivador.">{{ old('prompt', $bot->prompt) }}</textarea>
                            </div>


                        <div class="mb-3">
                            <label for="segmento" class="form-label">Segmento</label>
                            <input type="text" id="segmento" name="segmento" class="form-control" value="{{ old('segmento', $bot->segmento) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tom" class="form-label">Tom do Bot</label>
                            <input type="text" id="tom" name="tom" class="form-control" value="{{ old('tom', $bot->tom) }}">
                        </div>

                        <div class="mb-3">
                            <label for="token_deepseek" class="form-label">Token DeepSeek</label>
                            <input type="text" id="token_deepseek" name="token_deepseek" class="form-control" value="{{ old('token_deepseek', $bot->token_deepseek) }}">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="status" name="status" class="form-check-input" value="1" {{ old('status', $bot->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Ativo</label>
                        </div>

                        <div class="form-group mb-3">
                            <label>Serviços que o Bot vai gerenciar</label>
                            <div class="row">
                                @foreach($services as $service)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->id }}" id="service{{ $service->id }}"
                                                {{ $bot->services->contains($service->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service{{ $service->id }}">
                                                {{ $service->titulo }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.bot.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Atualizar Bot</button>
                </div>
            </form>

        </div>
    </div>
</x-admin.layout>