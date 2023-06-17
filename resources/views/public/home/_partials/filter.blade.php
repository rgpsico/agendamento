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
    $(document).on('click', ".teste2",function(){
       $('.listar_empresas').empty()
var empresas = ['Empresa 1', 'Empresa 2', 'Empresa 3']; // Exemplo de dados do loop

var container = document.querySelector('.listar_empresas'); // Seleciona o elemento com a classe "listar_empresas"
fetch('api/empresas')
    .then(response => response.json())
    .then(dadosApi => {
        dadosApi.forEach(function (empresa) {
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
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="d-inline-block average-rating">(8)</span>
                                    </div>
                                    <div class="clinic-details">
                                        <p class="doc-location">RJ, 22071-060</p>
                                        <ul class="clinic-gallery">
                                            <li>
                                                <a href="http://127.0.0.1:8000/template/assets/img/features/feature-01.jpg"
                                                    data-fancybox="gallery">
                                                    <img
                                                        src="http://127.0.0.1:8000/template/assets/img/features/feature-01.jpg"
                                                        alt="Feature">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://127.0.0.1:8000/template/assets/img/features/feature-02.jpg"
                                                    data-fancybox="gallery">
                                                    <img
                                                        src="http://127.0.0.1:8000/template/assets/img/features/feature-02.jpg"
                                                        alt="Feature">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://127.0.0.1:8000/template/assets/img/features/feature-03.jpg"
                                                    data-fancybox="gallery">
                                                    <img
                                                        src="http://127.0.0.1:8000/template/assets/img/features/feature-03.jpg"
                                                        alt="Feature">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://127.0.0.1:8000/template/assets/img/features/feature-04.jpg"
                                                    data-fancybox="gallery">
                                                    <img
                                                        src="http://127.0.0.1:8000/template/assets/img/features/feature-04.jpg"
                                                        alt="Feature">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="doc-info-right">
                                <div class="clini-infos">
                                    <ul>
                                        <li><i class="fas fa-map

	
	-marker-alt"></i> RJ, BR</li>
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
    container.innerHTML += row; // Adiciona a template string ao conteúdo do elemento selecionado
});

    })

    });
</script>