$(document).ready(function() {


    $(document).ready(function() {

 
        $(document).on('click', '#enviar-email', function(e) {
        e.preventDefault(); // evita recarregar a página
        let submitBtn = $(this);
        let form = submitBtn.closest('form'); // pega o formulário pai do botão
        let site_id = submitBtn.data('siteid');
        let url = '/contato/enviar/' + site_id;

        submitBtn.prop('disabled', true).text('Enviando...');

        $.ajax({
            type: 'POST',
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: form.serialize(), // agora pega os campos corretos
            success: function(response) {
                alert('Mensagem enviada com sucesso!');
                form[0].reset();
                submitBtn.prop('disabled', false).text('Enviar Mensagem');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                if(errors){
                    let msg = Object.values(errors).map(e => e.join(', ')).join('\n');
                    alert('Erro:\n' + msg);
                } else {
                    alert('Ocorreu um erro ao enviar a mensagem.');
                }
                submitBtn.prop('disabled', false).text('Enviar Mensagem');
            }
        });
    });


});


    // Função para registrar clique no WhatsApp
    function registrarCliquesWhatsapp(selector, empresaSiteId, whatsappNumero) {
        $(document).on('click', selector, function(e) {
            $.ajax({
                url: '/api/clique-whatsapp',
                type: 'POST',
                data: { empresa_site_id: empresaSiteId },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    console.log('Clique registrado!', response);
                    window.location.href = 'https://wa.me/' + whatsappNumero.replace(/\D/g,'');
                },
                error: function(err) {
                    console.error('Erro ao registrar clique', err);
                }
            });
        });
    }

    // Função para registrar visitante
    function registrarVisitante(empresaSiteId) {
        $.ajax({
            url: '/api/visitante',
            type: 'POST',
            data: { empresa_site_id: empresaSiteId },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                console.log('Visitante registrado', response);
            },
            error: function(err) {
                console.error('Erro ao registrar visitante', err);
            }
        });
    }

    // Função para registrar visualização
    function registrarVisualizacao(empresaSiteId) {
        $.ajax({
            url: '/api/visualizacao',
            type: 'POST',
            data: { empresa_site_id: empresaSiteId },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                console.log('Visualização registrada', response);
            },
            error: function(err) {
                console.error('Erro ao registrar visualização', err);
            }
        });
    }

    // Inicializa
    const empresaSiteId = window.SITE_ID || 1;
    const whatsappNumero = window.WHATSAPP || '1199999888';

    registrarVisitante(empresaSiteId);
    registrarVisualizacao(empresaSiteId);
    registrarCliquesWhatsapp('.fa-whatsapp', empresaSiteId, whatsappNumero);

    

});
