<div class="doc-info-right">
    <div class="clini-infos">
        <ul>
            <li><i class="fas fa-map-marker-alt"></i> {{$cidade ?? 'RJ'}}, {{$nacionalidade ?? 'BR'}}</li>
            <li>
                <i class="far fa-money-bill-alt"></i> R$ {{$value->valor_aula_de}}
            </li>
        </ul>
    </div>
    <div class="clinic-booking">
        @isset($value)                       
            <a class="view-pro-btn" href="{{route('home.show',['id' => $value->user_id])}}">Ver Escola</a>
            
            @if($value->tipoAgendamento === 'horarios')
                <a class="apt-btn" href="{{route('home.booking',['id' => $value->user_id])}}">Agendar Aula</a>
            @elseif($value->tipoAgendamento === 'whatsapp' && !empty($value->whatsappNumero))
                <!-- Botão que ativa o modal -->
                <a class="apt-btn btn-success" data-bs-toggle="modal" data-bs-target="#passeioModal-{{$value->user_id}}">
                    Agendar via WhatsApp
                </a>
            @else
                <span class="text-muted">Agendamento indisponível</span>
            @endif
        @endisset
    </div>
</div>

<!-- Modal Dinâmico (Somente se for tipoAgendamento == whatsapp) -->
@if($value->tipoAgendamento === 'whatsapp' && !empty($value->whatsappNumero))
<div class="modal fade" id="passeioModal-{{$value->user_id}}" tabindex="-1" aria-labelledby="passeioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passeioModalLabel">Detalhes do Passeio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p><strong>📍 Local:</strong> {{$cidade ?? 'RJ'}}, {{$nacionalidade ?? 'BR'}}</p>
                <p><strong>💰 Valor:</strong> R$ {{$value->valor_aula_de}}</p>
                <p><strong>📄 Descrição:</strong> {{$value->descricao ?? 'Sem descrição disponível.'}}</p>
                <p><strong>📅 Dias Disponíveis:</strong> {{$value->dias_disponiveis ?? 'Consultar'}}</p>
                <p><strong>⏰ Horário de saída:</strong> {{$value->horario ?? '08:00'}}</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" href="https://wa.me/{{$value->whatsappNumero}}" target="_blank">
                    <i class="fab fa-whatsapp"></i> Agendar via WhatsApp
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endif
