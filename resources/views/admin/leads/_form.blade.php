@php $lead = $lead ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nome <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
               value="{{ old('nome', $lead?->nome) }}" required>
        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Empresa</label>
        <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror"
               value="{{ old('empresa', $lead?->empresa) }}">
        @error('empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $lead?->email) }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror"
               value="{{ old('telefone', $lead?->telefone) }}">
        @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Origem <span class="text-danger">*</span></label>
        <select name="origem" class="form-control @error('origem') is-invalid @enderror" required>
            @foreach($origens as $key => $label)
                <option value="{{ $key }}" {{ old('origem', $lead?->origem) === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('origem') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
            @foreach($statusList as $key => $label)
                <option value="{{ $key }}" {{ old('status', $lead?->status ?? 'novo') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Interesse</label>
        <input type="text" name="interesse" class="form-control @error('interesse') is-invalid @enderror"
               placeholder="Ex: Agendamento, Plano mensal..."
               value="{{ old('interesse', $lead?->interesse) }}">
        @error('interesse') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Responsável</label>
        <select name="responsavel_id" class="form-control @error('responsavel_id') is-invalid @enderror">
            <option value="">Nenhum</option>
            @foreach($responsaveis as $usuario)
                <option value="{{ $usuario->id }}"
                    {{ old('responsavel_id', $lead?->responsavel_id) == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->nome }}
                </option>
            @endforeach
        </select>
        @error('responsavel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" class="form-control @error('observacoes') is-invalid @enderror"
                  rows="4">{{ old('observacoes', $lead?->observacoes) }}</textarea>
        @error('observacoes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
