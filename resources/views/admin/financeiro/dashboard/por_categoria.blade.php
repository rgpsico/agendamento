{{-- Faturamento do período por categoria de receita (ex.: Pilates x Fisioterapia) --}}
<div class="metrics-section">
    <div class="section-title">
        <span>🏷️</span> Faturamento por categoria
    </div>

    @if($faturamentoPorCategoria->isEmpty())
        <p class="text-muted mb-0">Nenhuma receita no período.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Categoria</th>
                        <th class="text-end">Faturamento</th>
                        <th class="text-end">Recebido</th>
                        <th class="text-end">A receber</th>
                        <th style="min-width: 160px;">% do total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faturamentoPorCategoria as $linha)
                        <tr>
                            <td>
                                @if($linha['nome'] === 'Sem categoria')
                                    <span class="text-muted">Sem categoria</span>
                                @else
                                    {{ $linha['nome'] }}
                                @endif
                            </td>
                            <td class="text-end fw-semibold">R$ {{ number_format($linha['total'], 2, ',', '.') }}</td>
                            <td class="text-end text-success">R$ {{ number_format($linha['recebido'], 2, ',', '.') }}</td>
                            <td class="text-end text-warning">R$ {{ number_format($linha['pendente'], 2, ',', '.') }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $linha['percentual'] }}%"
                                             aria-valuenow="{{ $linha['percentual'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($linha['percentual'], 1, ',', '.') }}%</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td>Total</td>
                        <td class="text-end">R$ {{ number_format($faturamentoPorCategoria->sum('total'), 2, ',', '.') }}</td>
                        <td class="text-end">R$ {{ number_format($faturamentoPorCategoria->sum('recebido'), 2, ',', '.') }}</td>
                        <td class="text-end">R$ {{ number_format($faturamentoPorCategoria->sum('pendente'), 2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if($faturamentoPorCategoria->firstWhere('nome', 'Sem categoria'))
            <small class="text-muted d-block mt-2">Receitas "sem categoria" podem ser classificadas em
                <a href="{{ route('financeiro.receitas.index') }}">Receitas</a> → Editar.</small>
        @endif
    @endif
</div>
