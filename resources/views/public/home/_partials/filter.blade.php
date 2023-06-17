<div class="card search-filter">
    <div class="card-header">
        <h4 class="card-title mb-0">Filtro</h4>
    </div>
    <div class="card-body">
    <div class="filter-widget">
        <div class="cal-icon">
            <input type="text" class="form-control" placeholder="Buscar Escola">
        </div>			
    </div>
    <div class="filter-widget">
        <h4>Modalidade</h4>
        <div>
            <label class="custom_check">
                <input type="checkbox" name="gender_type" checked class="teste2">
                <span class="checkmark"></span> Surf
            </label>
        </div>
        <div>
            <label class="custom_check">
                <input type="checkbox" name="gender_type">
                <span class="checkmark"></span> Female Doctor
            </label>
        </div>
    </div>
   
        <div class="btn-search">
            <button type="button" class="btn w-100">Buscar</button>
        </div>	
    </div>
</div>
<!-- /Search Filter -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   $(document).on('click', ".teste2", function() {
    $('.listar_empresas').empty()

    fetch('api/empresas')
        .then(response => response.json())
        .then(dadosApi => {
            dadosApi.forEach(function(empresa) {

                // Calcule a média das avaliações
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
                                                    <a href="${empresa.id}/profissional">${empresa.nome}</a>
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