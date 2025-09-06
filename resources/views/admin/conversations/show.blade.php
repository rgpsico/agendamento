<x-admin.layout title="Conversa #{{ $conversa->id }}">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <x-header.titulo pageTitle="Conversa com {{ $conversa->user?->name ?? 'Anônimo' }}"/>
            <x-alert/>

            <div class="card shadow mb-4">
                <div class="card-body" style="height:400px; overflow-y:auto;">
                    @foreach($conversa->where('id', $conversa->id)->get() as $msg)
                        <div class="mb-2">
                            <strong>{{ $msg->tipo === 'user' ? $msg->user?->name ?? 'Cliente' : 'Bot' }}:</strong> 
                            {{ $msg->mensagem }}
                            <small class="text-muted">({{ $msg->created_at->format('H:i') }})</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</x-admin.layout>
