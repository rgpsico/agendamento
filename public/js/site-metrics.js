$(document).ready(function() {

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
