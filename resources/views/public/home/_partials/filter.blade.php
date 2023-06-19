<style>
    .input-icon {
    position: relative;
}

.input-icon > i {
    position: absolute;
    right: 10px;
    top: 15px;
}
.input-icon > .form-control {
    padding-left: 30px;  /* Ajuste esse valor conforme necessário */
}

</style>
<x-filter-home/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', ".buscar_empresa", function() {
        $('.listar_empresas').empty();
        
        // busca todos os checkboxes que foram marcados
        var tipos_empresa = [];
        $('.filter_empresa:checked').each(function() {
            tipos_empresa.push($(this).data('type'));
        });
  
        var nome_empresa = $("#nome_empresa").val(); // obtenha o valor do campo de entrada


        
    fetch('api/search/empresa?tipo='+tipos_empresa+'&nome_empresa='+nome_empresa)
        .then(response => response.json())
        .then(dadosApi => {
            dadosApi.forEach(function(empresa) {

                // Calcule a média das avaliações\
                let sum = 0;
                empresa.avaliacao.forEach(function(avaliacao) {

                    sum += avaliacao.avaliacao;
                });
                let rating = sum / empresa.avaliacao.length;

                // Crie as estrelas de avaliação
                let ratingHTML = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        ratingHTML += '<i class="fas fa-star filled"></i>';
                    } else {
                        ratingHTML += '<i class="fas fa-star"></i>';
                    }
                }
                ratingHTML += '<span class="d-inline-block average-rating">(' + rating.toFixed(1) + ')</span>';

                // Crie a galeria de imagens
                let galleryHTML = '';
                empresa.galeria.forEach(function(image) {
                    galleryHTML += `<li>
                                        <a href="galeria_escola/${image.image}" data-fancybox="gallery">
                                            <img src="galeria_escola/${image.image}" alt="Feature">
                                        </a>
                                    </li>`;
                });

                var row = `<div class="card">
                                <div class="card-body">
                                    <div class="doctor-widget">
                                        <div class="doc-info-left">
                                            <div class="doctor-img">
                                                <a href="">
                                                    <img src="avatar/${empresa.avatar}" class="img-fluid" alt="${empresa.nome}">
                                                </a>
                                            </div>
                                            <div class="doc-info-cont">
                                                <h4 class="doc-name">
                                                    <a href="${empresa.user_id}/empresa">${empresa.nome}</a>
                                                </h4>
                                                <div class="rating">
                                                    ${ratingHTML}
                                                </div>
                                                <div class="clinic-details">
                                                    <p class="doc-location">RJ, 22071-060</p>
                                                    <ul class="clinic-gallery">
                                                        ${galleryHTML}
                                                    </ul>
                                                </div>
                                                <span class="badge bg-primary" style="font-size: 1.2em; text-transform: capitalize;">${empresa.modalidade}</span>
                                            </div>
                                        </div>
                                        <div class="doc-info-right">
                                            <div class="clini-infos">
                                                <ul>
                                                    <li><i class="fas fa-map-marker-alt"></i> RJ, BR</li>
                                                    <li><i class="far fa-money-bill-alt"></i> R$ ${empresa.valor_aula_de} - R$ ${empresa.valor_aula_ate}
                                                        <i class="fas fa-info-circle" data-bs-toggle="tooltip"
                                                            aria-label="${empresa.nome}" data-bs-original-title="${empresa.nome}"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="clinic-booking"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $('.listar_empresas').append(row);
            });
        });
});
  

</script>