<x-admin.layout title="Editar Depoimento">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <h3 class="page-title">Editar Depoimento</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-alert/>

                    <form action="{{ route('admin.site.depoimentos.update', $depoimento->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" class="form-control" value="{{ $depoimento->nome }}" required>
                        </div>

                        <div class="form-group">
                            <label for="nota">Nota</label>
                            <select name="nota" class="form-control">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $depoimento->nota == $i ? 'selected' : '' }}>
                                        {{ $i }} estrela{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comentario">Comentário</label>
                            <textarea name="comentario" rows="4" class="form-control" required>{{ $depoimento->comentario }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" class="form-control">
                            @if($depoimento->foto)
                                <img src="{{ asset('storage/' . $depoimento->foto) }}" alt="Foto atual" width="100" class="mt-2">
                            @endif
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Atualizar</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
