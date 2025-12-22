<div class="metrics-section">
                <div class="section-title">
                    <span>📈</span> Visão Geral
                </div>
                <div class="row">
                    <!-- Total de Receitas -->
                        @if(in_array('todos', $tipoSelecionado) || in_array('receitas', $tipoSelecionado))
                        <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                            <div class="metric-card success">
                                <div class="card-header">
                                    <div>
                                        <h5 class="card-title">Receitas Totais</h5>
                                        <p class="card-value">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="card-icon">💰</div>
                                </div>
                            </div>
                        </div>
                        @endif

                    <!-- Total de Despesas -->
                         @if(in_array('todos', $tipoSelecionado) || in_array('despesas', $tipoSelecionado))
                        <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                            <div class="metric-card danger">
                                <div class="card-header">
                                    <div>
                                        <h5 class="card-title">Despesas Totais</h5>
                                        <p class="card-value">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="card-icon">💸</div>
                                </div>
                            </div>
                        </div>
                     @endif

                    <!-- Despesas Recorrentes -->
                 <!-- Despesas Recorrentes -->
                @if(in_array('todos', $tipoSelecionado) || in_array('despesas_recorrentes', $tipoSelecionado))
                <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                    <div class="metric-card danger">
                        <div class="card-header">
                            <div>
                                <h5 class="card-title">Despesas Recorrentes</h5>
                                <p class="card-value">R$ {{ number_format($totalDespesasRecorrentes, 2, ',', '.') }}</p>
                                <p class="card-subtitle">Total do período</p>
                            </div>
                            <div class="card-icon">🔄</div>
                        </div>
                    </div>
                </div>
                @endif

                    <!-- Saldo Atual -->
                       @if(in_array('todos', $tipoSelecionado))
                        <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                            <div class="metric-card {{ ($totalReceitas - $totalDespesas) >= 0 ? 'success' : 'danger' }}">
                                <div class="card-header">
                                    <div>
                                        <h5 class="card-title">Saldo Atual</h5>
                                        <p class="card-value">R$ {{ number_format($totalReceitas - $totalDespesas, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="card-icon">💳</div>
                                </div>
                            </div>
                        </div>
                        @endif

                    <!-- Resultado do Mês -->
                    <div class="col-xl-3 col-lg-4 col-md-6 card-indicador">
                        <div class="metric-card {{ $resultadoMes >= 0 ? 'success' : 'danger' }}">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title">Resultado do Mês</h5>
                                    <p class="card-value">R$ {{ number_format($resultadoMes, 2, ',', '.') }}</p>
                                </div>
                                <div class="card-icon">📊</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
