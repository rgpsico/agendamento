$(document).on('click', ".buscar_empresa", function () {
    $('.listar_empresas').empty();

    // Collect checked checkboxes
    var tipos_empresa = [];
    $('.filter_empresa:checked').each(function () {
        tipos_empresa.push($(this).data('type'));
    });

    var nome_empresa = $("#nome_empresa").val();

    // Encode query parameters
    const query = new URLSearchParams({
        tipo: tipos_empresa.join(','),
        nome_empresa: nome_empresa
    }).toString();

    fetch(`api/search/empresa?${query}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(dadosApi => {
            if (dadosApi.length === 0) {
                $('.listar_empresas').append('<p class="text-center">Nenhuma empresa encontrada.</p>');
                return;
            }

            dadosApi.forEach(function (empresa) {
                // Validate avatar
                const isValidAvatar = empresa.avatar && !empresa.avatar.includes('/tmp/') && !empresa.avatar.includes('AppData\\Local\\Temp');
                const avatarSrc = isValidAvatar ? `/avatar/${empresa.avatar}` : 'https://picsum.photos/200/200';

                // Calculate rating
                let rating = 0;
                let ratingHTML = '';
                if (empresa.avaliacao && empresa.avaliacao.length > 0) {
                    let sum = 0;
                    empresa.avaliacao.forEach(function (avaliacao) {
                        sum += parseFloat(avaliacao.avaliacao) || 0;
                    });
                    rating = sum / empresa.avaliacao.length;
                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += `<i class="fas fa-star${i <= Math.round(rating) ? ' filled' : ''}"></i>`;
                    }
                    ratingHTML += `<span class="d-inline-block average-rating">(${rating.toFixed(1)})</span>`;
                } else {
                    ratingHTML = '<span class="d-inline-block average-rating">Sem avaliações</span>';
                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += '<i class="fas fa-star"></i>';
                    }
                }

                // Build gallery
                let galleryHTML = '';
                if (empresa.galeria && empresa.galeria.length > 0) {
                    empresa.galeria.forEach(function (image) {
                        galleryHTML += `<li>
                            <a href="galeria_escola/${image.image}" data-fancybox="gallery">
                                <img src="galeria_escola/${image.image}" alt="Feature">
                            </a>
                        </li>`;
                    });
                } else {
                    galleryHTML = '<li><span>Sem imagens</span></li>';
                }

                // Mock address
                const endereco = empresa.endereco ? `${empresa.endereco.cidade}, ${empresa.endereco.cep}` : 'RJ, 22071-060';

                // Build card HTML
                var row = `
                    <div class="card">
                        <div class="card-body">
                            <div class="doctor-widget">
                                <div class="doc-info-left">
                                    <div class="doctor-img">
                                        <a href="${empresa.user_id}/empresa">
                                            <img src="${avatarSrc}" class="img-fluid" onerror="this.src='https://picsum.photos/200/200'" alt="${empresa.nome}">
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
                                            <p class="doc-location">${endereco}</p>
                                            <ul class="clinic-gallery">
                                                ${galleryHTML}
                                            </ul>
                                        </div>
                                        <span class="badge bg-primary">${empresa.modalidade.nome}</span>
                                    </div>
                                </div>
                                <div class="doc-info-right">
                                    <div class="clini-infos">
                                        <ul>
                                            <li><i class="fas fa-map-marker-alt"></i> RJ, BR</li>
                                            <li><i class="far fa-money-bill-alt"></i> R$ ${parseFloat(empresa.valor_aula_de).toFixed(2)} - R$ ${parseFloat(empresa.valor_aula_ate).toFixed(2)}
                                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" aria-label="${empresa.nome}" data-bs-original-title="${empresa.nome}"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="clinic-booking">
                                        <a class="view-pro-btn" href="${empresa.user_id}/empresa">Ver Escola</a>
                                        <a class="apt-btn" href="${empresa.user_id}/bokking">Agendar Aula</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                $('.listar_empresas').append(row);
            });

            // Reinitialize tooltips and Fancybox
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('[data-fancybox="gallery"]').fancybox();
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            $('.listar_empresas').append('<p class="text-center text-danger">Erro ao carregar empresas.</p>');
        });
});