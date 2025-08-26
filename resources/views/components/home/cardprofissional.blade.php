<style>
/* Card Styling */
.card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 25px;
}

/* Doctor Widget Styling */
.doctor-widget {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

/* Doctor Info Left - Container principal */
.doc-info-left {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
    min-width: 0; /* Importante para flexbox */
}

/* Doctor Image Styling */
.doctor-img {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease;
    flex-shrink: 0; /* Impede que a imagem encolha */
}

.doctor-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease, filter 0.3s ease;
}

.doctor-img:hover img {
    transform: scale(1.1);
    filter: brightness(1.1);
}

.doctor-img::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.3));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.doctor-img:hover::after {
    opacity: 1;
}

/* Doctor Info Styling */
.doc-info-cont {
    flex: 1;
    min-width: 200px;
}

.doc-name {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #003b4d;
    text-transform: capitalize;
}

.doc-name a {
    color: #003b4d;
    text-decoration: none;
    transition: color 0.3s ease;
}

.doc-name a:hover {
    color: #00c4e0;
}

.badge.bg-primary {
    background: linear-gradient(45deg, #00c4e0, #007a99) !important;
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: capitalize;
    display: inline-block;
    margin-top: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.badge.bg-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 196, 224, 0.4);
}

/* Right Card Styling */
.right-card-home {
    flex: 1;
    min-width: 200px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: background 0.3s ease;
}

.right-card-home:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Responsive Design - MELHORADO */
@media (max-width: 768px) {
    .doctor-widget {
        flex-direction: column;
        align-items: center; /* Centraliza no mobile */
        gap: 20px;
        text-align: center; /* Centraliza o texto */
    }

    .doc-info-left {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 15px;
        width: 100%;
    }

    .doctor-img {
        width: 100px;
        height: 100px;
        margin: 0 auto; /* Garante centralização */
    }

    .doc-info-cont {
        text-align: center;
        width: 100%;
    }

    .doc-name {
        font-size: 1.4rem;
        text-align: center;
    }

    .badge.bg-primary {
        font-size: 1rem;
        padding: 6px 12px;
        display: inline-block;
        margin: 10px auto 0;
    }

    .card-body {
        padding: 20px;
        text-align: center;
    }

    .right-card-home {
        width: 100%;
        max-width: 300px; /* Limita a largura no mobile */
        margin: 0 auto;
    }
}

@media (max-width: 576px) {
    .doctor-widget {
        gap: 15px;
    }

    .doc-info-left {
        gap: 12px;
    }

    .doctor-img {
        width: 80px;
        height: 80px;
    }

    .doc-name {
        font-size: 1.2rem;
    }

    .badge.bg-primary {
        font-size: 0.9rem;
        padding: 5px 10px;
    }

    .card-body {
        padding: 15px;
    }

    .right-card-home {
        max-width: 280px;
        padding: 12px;
    }
}

/* Animações */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card {
    animation: slideInRight 0.5s ease-out;
}

/* Classes auxiliares para centralização */
.mobile-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Se você quiser uma versão ainda mais centralizada */
@media (max-width: 768px) {
    .card {
        margin: 0 auto 30px;
        max-width: 400px; /* Opcional: limita largura máxima */
    }
}
</style>

<div class="card">
    <div class="card-body">
        <div class="doctor-widget">
            <div class="doc-info-left">
                <div class="doctor-img">
                    <a href="{{ route('home.show', ['id' => $value->user_id]) }}">                        
                        <img src="{{ asset('avatar/' . $value->avatar) }}" class="img-fluid" onerror="handleImageError(this)" alt="Usuario Image">
                    </a>
                </div>              
                <div class="doc-info-cont">
                    <h4 class="doc-name"><a href="{{ route('home.show', ['id' => $value->user_id]) }}">{{ $value->nome }}</a></h4>
                    <x-avaliacao-home :model="$value" />
                    <x-gallery-home :model="$value" /> 
                    <span class="badge bg-primary">{{ $value->modalidade->nome }}</span>
                </div>
            </div>
            <x-right-card-home :value="$value"/>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script>
    function handleImageError(imageElement) {
        var defaultImage = 'https://picsum.photos/536/354';
       // $(imageElement).attr('src', defaultImage).css('opacity', '0').fadeTo(300, 1);
    }
</script>