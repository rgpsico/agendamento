 <div class="filter-card">
            <div class="section-title">
                <span>🔍</span> Filtrar Período
            </div>
            <form method="GET" action="{{ route('admin.financeiro.dashboard') }}" class="filter-form">
                <div class="form-group">
                    <label for="data_inicio">De</label>
                    <input type="date" 
                        name="data_inicio" 
                        id="data_inicio" 
                        class="form-control"
                        value="{{ request('data_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label for="data_fim">Até</label>
                    <input type="date" 
                        name="data_fim" 
                        id="data_fim" 
                        class="form-control"
                        value="{{ request('data_fim', now()->endOfMonth()->format('Y-m-d')) }}">
                </div>

                <div class="form-group" style="flex: 100%;">
                    <label>Tipos</label>
                    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                        <label><input type="checkbox" name="tipo[]" value="receitas" {{ in_array('receitas', request('tipo', ['todos'])) ? 'checked' : '' }}> Receitas</label>
                        <label><input type="checkbox" name="tipo[]" value="despesas" {{ in_array('despesas', request('tipo', ['todos'])) ? 'checked' : '' }}> Despesas</label>
                        <label><input type="checkbox" name="tipo[]" value="receitas_recorrentes" {{ in_array('receitas_recorrentes', request('tipo', ['todos'])) ? 'checked' : '' }}> Receitas Recorrentes</label>
                        <label><input type="checkbox" name="tipo[]" value="despesas_recorrentes" {{ in_array('despesas_recorrentes', request('tipo', ['todos'])) ? 'checked' : '' }}> Despesas Recorrentes</label>
                    </div>
                </div>

                <button type="submit" class="btn-filter">
                    <span>📊</span> Filtrar
                </button>
            </form>
        </div>