<x-admin.layout title="WhatsApp com IA">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="WhatsApp com IA"/>
            <x-alert/>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-body">
                    <p class="mb-2">
                        Quando um cliente manda mensagem para o número de WhatsApp do estúdio, a IA responde: explica os serviços e valores,
                        tira dúvidas, cadastra o cliente e agenda. As conversas aparecem em
                        <a href="{{ route('admin.conversations.index') }}">Conversas</a>.
                    </p>
                    <p class="mb-0 text-muted">
                        Para assumir uma conversa, responda pelo painel: a IA para de responder naquela conversa até você reativá-la.
                    </p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Números</h5></div>
                <div class="card-body">
                    @forelse($canais as $canal)
                        <form action="{{ route('admin.bot.whatsapp.update', $canal->id) }}" method="POST" class="border rounded p-3 mb-3">
                            @csrf
                            @method('PUT')
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                <div>
                                    <h5 class="mb-1">{{ $canal->numero }}</h5>
                                    @if($master)<small class="text-muted">{{ $canal->empresa->nome ?? 'Empresa #' . $canal->empresa_id }}</small>@endif
                                </div>
                                <div>
                                    @if(! $canal->ativo)
                                        <span class="badge bg-secondary">Número desativado</span>
                                    @elseif($canal->ia_ativa)
                                        <span class="badge bg-success">IA respondendo</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Só a equipe responde</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Bot que atende</label>
                                    <select name="bot_id" class="form-control">
                                        <option value="">Primeiro bot ativo da empresa</option>
                                        @foreach($bots->where('empresa_id', $canal->empresa_id) as $bot)
                                            <option value="{{ $bot->id }}" {{ (int) $canal->bot_id === (int) $bot->id ? 'selected' : '' }}>{{ $bot->nome }}{{ $bot->status ? '' : ' (inativo)' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="ia_ativa" value="0">
                                        <input class="form-check-input" type="checkbox" name="ia_ativa" value="1" id="ia_{{ $canal->id }}" {{ $canal->ia_ativa ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ia_{{ $canal->id }}">IA responde automaticamente</label>
                                    </div>
                                    @if($master)
                                        <div class="form-check form-switch mt-1">
                                            <input type="hidden" name="ativo" value="0">
                                            <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo_{{ $canal->id }}" {{ $canal->ativo ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ativo_{{ $canal->id }}">Número ativo</label>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </div>
                        </form>
                        @if($master)
                            <form action="{{ route('admin.bot.whatsapp.destroy', $canal->id) }}" method="POST" class="text-end mb-4"
                                  onsubmit="return confirm('Desligar {{ $canal->numero }} desta empresa? As conversas continuam salvas.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Desligar número da empresa</button>
                            </form>
                        @endif
                    @empty
                        <p class="mb-0">
                            Nenhum número de WhatsApp ligado
                            @if(! $master)
                                ao seu estúdio ainda. Fale com o suporte do PilatesGestão para ativar o atendimento com IA no WhatsApp.
                            @else
                                ainda. Ligue um abaixo.
                            @endif
                        </p>
                    @endforelse
                </div>
            </div>

            @if($master)
                <div class="card shadow mb-4">
                    <div class="card-header"><h5 class="card-title mb-0">Ligar número a uma empresa (administrador)</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.bot.whatsapp.store') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label">Empresa</label>
                                <select name="empresa_id" class="form-control" required>
                                    <option value="">Selecione</option>
                                    @foreach($empresasDisponiveis as $empresa)
                                        <option value="{{ $empresa->id }}" {{ (string) old('empresa_id') === (string) $empresa->id ? 'selected' : '' }}>{{ $empresa->nome }} (#{{ $empresa->id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Número no Twilio</label>
                                <input type="text" name="numero" class="form-control" placeholder="+5521999999999" value="{{ old('numero') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Bot (opcional)</label>
                                <select name="bot_id" class="form-control">
                                    <option value="">Primeiro bot ativo</option>
                                    @foreach($bots as $bot)
                                        <option value="{{ $bot->id }}">{{ $bot->nome }} — empresa #{{ $bot->empresa_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-success">Ligar</button>
                            </div>
                        </form>

                        <hr>
                        <h6>No Twilio</h6>
                        <ol class="mb-0">
                            <li>O número precisa estar ativo como <strong>WhatsApp Sender</strong> na conta Twilio da plataforma
                                (para testes, dá para usar o número do Sandbox).</li>
                            <li>Em <em>"When a message comes in"</em> do sender (ou do Sandbox), use o endereço
                                <code>{{ $webhookUrl }}</code> com o método <strong>POST</strong>.</li>
                            <li>O número sai do app WhatsApp do celular: no Twilio, o número fica só na API.</li>
                        </ol>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-admin.layout>
