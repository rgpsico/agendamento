
$(document).ready(function () {
  // Monitorando mudanças no checkbox
  $('#terms_accept').change(function () {
      if ($(this).is(':checked')) {
          $('#confirmarPagamento').prop('disabled', false); // Habilita o botão
      } else {
          $('#confirmarPagamento').prop('disabled', true); // Desabilita o botão
      }
  });
});


$("#paymentForm").on('submit', function(e) {

e.preventDefault(); 

$('#spinner').show();
var formData = $(this).serializeArray(); 


var servicos = localStorage.getItem('servicos');
var servicosArray = JSON.parse(servicos); 	 
var total = 0;

for (var i = 0; i < servicosArray.length; i++) {
total += parseFloat(servicosArray[i].preco);
}

var data = localStorage.getItem('data');
var horaAula = localStorage.getItem('horaDaAula');

formData.push({ name: "servicos", value: servicosArray });
formData.push({ name: "total", value: total });
formData.push({ name: "data_aula", value: data });
formData.push({ name: "hora_aula", value: horaAula });

var data = {};

$(formData).each(function(index, obj){
  data[obj.name] = obj.value;
});

// Faz a solicitação POST para a API
$.ajax({
  url: '/pagamento', // A URL da API que você está chamando
  type: 'post', // O tipo de solicitação que você está fazendo (GET, POST, etc.)
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Para proteção contra falsificação de solicitação entre sites (CSRF)
  },
  data: data, // Os dados que você está enviando para a API
  success: function (response) {
if (response.error) {
  alert('Erro: ' + response.error); // Mostra o erro em um alerta
 
} else if (response && response.content && response.content.id) {
  const baseUrl = "{{ route('home.checkoutsucesso', ['id' => 'USER_ID']) }}";
  const redirectTo = baseUrl.replace('USER_ID', response.content.id);
  window.location.href = redirectTo;
}


  },
  error: function(response) {
try {
  console.log(response.responseJSON)
  let errors = response.responseJSON.errors;
  alert(response.responseJSON.error);
  $('#spinner').hide();
$('.error').empty()	
$.each(errors, function(key, values) {
  $('#' + key ).removeClass(); 	
});

$.each(errors, function(key, values) {
let errorMessages = '';

  $('#' + key + '_erro').empty(); 			



      $.each(values, function(index, value) {
          errorMessages += '<span class="error">' + value + '</span><br>';
      });

$('#' + key).addClass('is-invalid');
$('#' + key+'_erro').show()
      $('#' + key+'_erro').append(errorMessages);
  });
  
} catch (error) {
  
}


}
});
});

// Limpa a mensagem de erro ao corrigir o campo


// Configure sua chave pública do Stripe
var stripe = Stripe('pk_test_51JDFv2BOmvZWJe0xeu2cwxUHl3Fw92cGWXoDlUpLQfJlY8K2yhk6LKs0GNtDP7GBmRgSs8aOySLTFlkAJJ7hb1Yr00q73EhugI');
          

// Selecione o formulário de pagamento
var form = document.getElementById('payment-form');

// Adicione um manipulador de eventos para o envio do formulário
form.addEventListener('submit', function(event) {
event.preventDefault();

// Crie o token do Stripe ao enviar o formulário
stripe.createToken('card', {
name: document.getElementById('card_name').value
// Outros detalhes do cartão, se necessário
}).then(function(result) {
if (result.error) {
// Se houver um erro ao criar o token, exiba a mensagem de erro
console.log(result.error.message);
} else {

var token = result.token.id;


form.submit();
}
});
});