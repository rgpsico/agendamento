<div class="metrics-section">
            <div class="section-title">
                <span>💵</span> Receitas
            </div>
            <div class="row">
                <!-- Receitas Recebidas -->
                @if(in_array('todos', $tipoSelecionado) || in_array('receitas', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card info">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Receitas Recebidas</h5>
                                <p class="card-value">R$ {{ number_format($totalReceitasRecebidas, 2, ',', '.') }}</p>
                                <p class="card-subtitle">{{ $receitasRecebidas }} registros</p>
                            </div>
                            <div class="card-icon">✅</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Receitas Recorrentes -->
                @if(in_array('todos', $tipoSelecionado) || in_array('receitas_recorrentes', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card success">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Receitas Recorrentes</h5>
                                <p class="card-value">R$ {{ number_format($totalReceitasRecorrentes, 2, ',', '.') }}</p>
                                <p class="card-subtitle">Total do período</p>
                            </div>
                            <div class="card-icon">🔄</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Receitas Pendentes -->
                @if(in_array('todos', $tipoSelecionado) || in_array('receitas', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card warning">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Receitas Pendentes</h5>
                                <p class="card-value">R$ {{ number_format($totalReceitasPendentes, 2, ',', '.') }}</p>
                                <p class="card-subtitle">{{ $receitasPendentes }} registros</p>
                            </div>
                            <div class="card-icon">⏳</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Despesas Pendentes -->
                @if(in_array('todos', $tipoSelecionado) || in_array('despesas', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card danger">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Despesas Pendentes</h5>
                                <p class="card-value">R$ {{ number_format($totalDespesasPendentes, 2, ',', '.') }}</p>
                                <p class="card-subtitle">{{ $despesasPendentes }} registros</p>
                            </div>
                            <div class="card-icon">⚠️</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Taxa de Recebimento -->
                @if(in_array('todos', $tipoSelecionado) || in_array('receitas', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card info">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Taxa de Recebimento</h5>
                                <p class="card-value">
                                    @php
                                        $totalReceitasCount = $receitasPendentes + $receitasRecebidas;
                                        $percentual = $totalReceitasCount > 0 ? ($receitasRecebidas / $totalReceitasCount) * 100 : 0;
                                    @endphp
                                    {{ number_format($percentual, 1) }}%
                                </p>
                                <p class="card-subtitle">Performance</p>
                            </div>
                            <div class="card-icon">📈</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>