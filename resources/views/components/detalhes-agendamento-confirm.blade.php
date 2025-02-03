<style>
    .booking-card {
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    background: #fff;
    padding: 20px;
    transition: all 0.3s ease-in-out;
}

.booking-card:hover {
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 2px solid #ddd;
    padding: 15px;
    font-weight: bold;
    font-size: 1.2rem;
    color: #333;
    border-radius: 12px 12px 0 0;
}

.booking-doc-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.booking-doc-img img {
    border-radius: 50%;
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid #007bff;
}

.booking-info h4 {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 5px;
}

.booking-info .doc-location {
    font-size: 0.9rem;
    color: #777;
}

.booking-summary {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.booking-item-wrap ul {
    padding: 0;
    list-style: none;
}

.booking-date li {
    font-size: 1rem;
    font-weight: 500;
    color: #555;
}

.booking-fee li {
    font-size: 1rem;
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}

.valor_total {
    color: #28a745;
    font-size: 1.1rem;
}

.booking-total {
    padding-top: 15px;
}

.booking-total-list li {
    font-size: 1.2rem;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    color: #007bff;
}

.total-cost {
    font-size: 1.5rem;
    font-weight: bold;
    color: #28a745;
}

</style>
<div class="col-md-5 col-lg-4 theiaStickySidebar" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
						
 
    <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none;">
        
        <div class="card booking-card">
            <div class="card-header">
                <h4 class="card-title">Detalhes do Agendamento</h4>
            </div>
            <div class="card-body">
                <div class="booking-doc-info">
                    <a href="#" class="booking-doc-img">
                        <img src="{{ asset('avatar/' . $model->avatar) }}" alt="Usuário">
                    </a>
                    <div class="booking-info">
                        <h4><a href="#">{{$model->nome}}</a></h4>
                        <div class="clinic-details">
                            <p class="doc-location">
                                <i class="fas fa-map-marker-alt"></i> {{$model->endereco->cidade ?? 'Rio de Janeiro'}}, {{$model->endereco->pais ?? 'Brasil'}}
                            </p>
                        </div>
                    </div>
                </div>
        
                <div class="booking-summary my-4">
                    <div class="booking-item-wrap">
                        <ul class="booking-date">
                            <li><b>Data:</b> 
                                <span id="savedDate"></span> 
                                <span id="savedHora"></span>
                            </li>
                        </ul>
        
                        <ul class="booking-fee">
                            <li>Produto 1 - Descrição <span class="valor_total">R$200</span></li>
                        </ul>
        
                        <div class="booking-total">
                            <ul class="booking-total-list">
                                <li><span>Total</span> <span class="total-cost">R$160</span></li>
                            </ul>
                        </div>
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

        function formatarData(dataString) {
            const [ano, mes, dia] = dataString.split('-');
            return `${dia}/${mes}/${ano}`;
        }

      var diaDaSemana = localStorage.getItem('diaDaSemana');
      var data = localStorage.getItem('data');
      var horaDaAula = localStorage.getItem('horaDaAula');
      $('#savedDate').text('    ' + diaDaSemana + '  ' + formatarData(data) + ' As ' + horaDaAula + ' Horas');
   
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