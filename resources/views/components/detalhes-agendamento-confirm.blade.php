<div class="col-md-5 col-lg-4 theiaStickySidebar" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
						
 
    <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none;"><div class="card booking-card">
        <div class="card-header">
            <h4 class="card-title">Detalhes do Agendamento</h4>
        </div>
        <div class="card-body">       
            <div class="booking-doc-info">
                <a href="doctor-profile.html" class="booking-doc-img">
                    <img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid" alt="Usuario Image">
                      </a>
                <div class="booking-info">
                    <h4>
                        <a href="doctor-profile.html">{{$model->nome}}</a></h4>
                    
                    <div class="clinic-details">
                        <p class="doc-location">
                            <i class="fas fa-map-marker-alt">
                                </i> {{$model->endereco->cidade ?? 'Rio de Janeiro'}}, {{$model->endereco->pais ?? 'Brasil'}}
                            </p>
                    </div>
                </div>
            </div>
            <!-- Booking Doctor Info -->
            
            <div class="booking-summary my-4">
                <div class="booking-item-wrap">
                    <ul class="booking-date">
                        <li class="mr-4">
                          <b>Data: </b> 
                          <span id="savedDate"></span>
                          <span id="savedHora"></span>
                        </li>                     
                      </ul>
                      
                    <ul class="booking-fee">
                        <li>Produto 1 
                            Descricao
                            
                            <span class="valor_total">R$200</span>
                        </li>
                    </ul>
                    
                    <div class="booking-total">
                        <ul class="booking-total-list">
                            <li>
                                <span>Total</span>
                                <span class="total-cost">R$160</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><div class="resize-sensor" style="position: absolute; inset: 0px; overflow: hidden; z-index: -1; visibility: hidden;"><div class="resize-sensor-expand" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;"><div style="position: absolute; left: 0px; top: 0px; transition: all 0s ease 0s; width: 390px; height: 752px;"></div></div><div class="resize-sensor-shrink" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;"><div style="position: absolute; left: 0; top: 0; transition: 0s; width: 200%; height: 200%"></div></div></div></div></div>
</div>
<script src="{{asset('admin/js/jquery-3.6.3.min.js')}}"></script>
<script>
    // Use jQuery para preencher o span com o valor armazenado no localStorage
    $(document).ready(function() {
      var diaDaSemana = localStorage.getItem('diaDaSemana');
      var data = localStorage.getItem('data');
      var horaDaAula = localStorage.getItem('horaDaAula');
      $('#savedDate').text('   ' + diaDaSemana + '  ' + data + ' As ' + horaDaAula + ' Horas');
   
    });

    function calcularPrecoTotal() {
    // Acessa os serviços armazenados no LocalStorage
    let servicos = localStorage.getItem('servicos');
        console.log(servicos)
    // Converte para uma lista, se houver algo armazenado
    if (servicos) {
        servicos = JSON.parse(servicos);
    } else {
        servicos = [];
    }

    // Inicia o total com 0
    let total = 0;

    // Adiciona o preço de cada serviço ao total
    for (let servico of servicos) {
        // Certifique-se de que o preço é tratado como um número
        total += parseFloat(servico.preco);
    }

    // Retorna o total
    return total;
}

// Calcula o preço total dos serviços
let total = calcularPrecoTotal();

// Acessa o elemento HTML onde o total será exibido
let totalCost = document.querySelector('.total-cost');

// Exibe o total no elemento HTML
totalCost.textContent = 'R$' + total.toFixed(2); // Aqui também adicionamos o método toFixed() para garantir que o total seja sempre exibido com duas casas decimais


// Calcula o preço total dos serviços


// $('.valor_total').text(calcularPrecoTotal() )


// Acessa os serviços armazenados no LocalStorage
let servicos = localStorage.getItem('servicos');

// Converte para uma lista, se houver algo armazenado
if (servicos) {
    servicos = JSON.parse(servicos);
} else {
    servicos = [];
}

// Acessa o elemento HTML onde os serviços serão listados
let bookingFee = document.querySelector('.booking-fee');

// Limpa qualquer conteúdo prévio
bookingFee.innerHTML = '';

// Itera sobre a lista de serviços
for (let servico of servicos) {
    // Cria um novo item de lista para cada serviço
    let li = document.createElement('li');

    // Define o conteúdo do item de lista
    li.innerHTML = `
        ${servico.titulo}
        <span class="valor_total">R$${servico.preco}</span>
    `;

    // Adiciona o item de lista à lista de serviços
    bookingFee.appendChild(li);

  
}

  </script>