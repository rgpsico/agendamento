
<style>
/* Estilos personalizados para o filtro */
.search-filter {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.search-filter .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    border: none;
    padding: 15px 20px;
}

.filter-widget {
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.custom_check {
    position: relative;
    padding-left: 25px;
    cursor: pointer;
    font-size: 14px;
    user-select: none;
    transition: all 0.2s ease;
}

.custom_check:hover {
    color: #667eea;
    transform: translateX(2px);
}

.custom_check input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: #eee;
    border-radius: 4px;
    border: 2px solid #ddd;
    transition: all 0.2s ease;
}

.custom_check:hover input ~ .checkmark {
    background-color: #f0f0f0;
    border-color: #667eea;
}

.custom_check input:checked ~ .checkmark {
    background-color: #667eea;
    border-color: #667eea;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.custom_check input:checked ~ .checkmark:after {
    display: block;
}

.custom_check .checkmark:after {
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.modalidades-container {
    background: #fafafa;
}

.modalidades-container::-webkit-scrollbar {
    width: 6px;
}

.modalidades-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modalidades-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modalidades-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>


                    <!-- /Search Filter -->
                </div>

                <div class="col-md-12 col-lg-8 col-xl-9 listar_empresas">
                    @foreach ($model as $value)
                        <x-home.cardprofissional :value="$value" />
                    @endforeach
                    <div class="load-more text-center">
                        <a class="btn btn-primary btn-sm prime-btn" href="javascript:void(0);">Carregar Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
$(document).off('click', '#buscar_empresa').on('click', '#buscar_empresa', function () {
    $('.listar_empresas').empty();

    // Coletar filtros
    var nome_empresa = $('#nome_empresa').val();
    var bairro = $('#bairro_filtro').val();
    var avaliacao = $('#avaliacao_filtro').val();
    var preco = $('#preco_filtro').val();

    // Coletar modalidades selecionadas
    var modalidades = [];
    $('.filter_modalidade:checked').each(function () {
        modalidades.push($(this).val());
    });

    // Montar query string
    const query = new URLSearchParams({
        nome_empresa: nome_empresa,
        bairro: bairro,
        avaliacao_minima: avaliacao,
        faixa_preco: preco,
        modalidades: modalidades.join(','), // envia separado por vírgula
    }).toString();

    // Requisição
    fetch(`api/search/empresa?${query}`)
        .then((response) => {
            if (!response.ok) throw new Error('Erro na resposta da API');
            return response.json();
        })
        .then((dadosApi) => {
            if (dadosApi.length === 0) {
                $('.listar_empresas').append(
                    '<p class="text-center">Nenhuma empresa encontrada.</p>'
                );
                return;
            }

            dadosApi.forEach(function (empresa) {
                // Avatar
                const isValidAvatar =
                    empresa.avatar &&
                    !empresa.avatar.includes('/tmp/') &&
                    !empresa.avatar.includes('AppData\\Local\\Temp');
                const avatarSrc = isValidAvatar
                    ? `/avatar/${empresa.avatar}`
                    : 'https://picsum.photos/200/200';

                // Avaliação
                let ratingHTML = '';
                if (empresa.avaliacao && empresa.avaliacao.length > 0) {
                    let sum = 0;
                    empresa.avaliacao.forEach(function (avaliacao) {
                        sum += parseFloat(avaliacao.avaliacao) || 0;
                    });
                    let rating = sum / empresa.avaliacao.length;
                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += `<i class="fas fa-star${
                            i <= Math.round(rating) ? ' filled' : ''
                        }"></i>`;
                    }
                    ratingHTML += `<span class="d-inline-block average-rating">(${rating.toFixed(
                        1
                    )})</span>`;
                } else {
                    ratingHTML =
                        '<span class="d-inline-block average-rating">Sem avaliações</span>';
                    for (let i = 1; i <= 5; i++) {
                        ratingHTML += '<i class="fas fa-star"></i>';
                    }
                }

                // Galeria
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

                // Endereço
                const endereco = empresa.endereco
                    ? `${empresa.endereco.cidade}, ${empresa.endereco.cep}`
                    : 'RJ, 22071-060';

                // Card
                var row = `
                    <div class="card">
                        <div class="card-body">
                            <div class="doctor-widget">
                                <div class="doc-info-left">
                                    <div class="doctor-img">
                                        <a href="${empresa.user_id}/empresa">
                                            <img src="${avatarSrc}" class="img-fluid" onerror="this.src='https://picsum.photos/200/200'" alt="${
                    empresa.nome
                }">
                                        </a>
                                    </div>
                                    <div class="doc-info-cont">
                                        <h4 class="doc-name">
                                            <a href="${empresa.user_id}/empresa">${empresa.nome}</a>
                                        </h4>
                                        <div class="rating">${ratingHTML}</div>
                                        <div class="clinic-details">
                                            <p class="doc-location">${endereco}</p>
                                            <ul class="clinic-gallery">${galleryHTML}</ul>
                                        </div>
                                        ${
                                            empresa.modalidade
                                                ? `<span class="badge bg-primary">${empresa.modalidade.nome}</span>`
                                                : ''
                                        }
                                    </div>
                                </div>
                                <div class="doc-info-right">
                                    <div class="clini-infos">
                                        <ul>
                                            <li><i class="fas fa-map-marker-alt"></i> RJ, BR</li>
                                            <li><i class="far fa-money-bill-alt"></i> 
                                                R$ ${parseFloat(empresa.valor_aula_de).toFixed(
                                                    2
                                                )} - R$ ${parseFloat(
                    empresa.valor_aula_ate
                ).toFixed(2)}
                                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" aria-label="${
                                                    empresa.nome
                                                }" data-bs-original-title="${empresa.nome}"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="clinic-booking">
                                        <a class="view-pro-btn" href="${
                                            empresa.user_id
                                        }/empresa">Ver Escola</a>
                                        <a class="apt-btn" href="${
                                            empresa.user_id
                                        }/bokking">Agendar Aula</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('.listar_empresas').append(row);
            });

            // Reinit tooltips e fancybox
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('[data-fancybox="gallery"]').fancybox();
        })
        .catch((error) => {
            console.error('Erro ao buscar empresas:', error);
            $('.listar_empresas').append(
                '<p class="text-center text-danger">Erro ao carregar empresas.</p>'
            );
        });
});

    </script>