@php 
$img = 'admin/img/surfbread2.png';
@endphp

@isset($model)
   @php $img = ($model->banners == '') ? 'admin/img/surfbread2.png' : 'banner/'.$model->banners; @endphp
@endisset

<style>
.breadcrumb-bar-modern {
    position: relative;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.55)), 
                url("{{ asset($img) }}") no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    min-height: 450px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 1;
}

.breadcrumb-bar-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, 
        rgba(59, 130, 246, 0.15) 0%, 
        rgba(147, 51, 234, 0.15) 100%);
    z-index: 1;
    animation: shimmer 4s linear infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.breadcrumb-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    padding: 80px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.breadcrumb-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1.2rem;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.6);
    letter-spacing: -0.03em;
    animation: slideInDown 1s ease-out;
    background: linear-gradient(45deg, #ffffff, #e0f7fa);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.breadcrumb-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    opacity: 0.85;
    font-weight: 400;
    line-height: 1.6;
    animation: slideInUp 1s ease-out 0.3s both;
}

.page-breadcrumb {
    animation: fadeIn 1.2s ease-out 0.5s both;
}

.breadcrumb {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(12px);
    border-radius: 50px;
    padding: 14px 28px;
    margin: 0 auto;
    display: inline-flex;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.breadcrumb:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
}

.breadcrumb-item {
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 20px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.breadcrumb-item a:hover {
    color: #fff;
    background: rgba(59, 130, 246, 0.3);
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.breadcrumb-item.active {
    color: #fff;
    font-weight: 700;
    background: rgba(59, 130, 246, 0.2);
    padding: 6px 12px;
    border-radius: 20px;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "❯";
    color: rgba(255, 255, 255, 0.7);
    margin: 0 10px;
    font-size: 1.2rem;
    font-weight: bold;
}

/* Animações */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Responsividade */
@media (max-width: 992px) {
    .breadcrumb-bar-modern {
        min-height: 350px;
        background-attachment: scroll;
    }
    
    .breadcrumb-title {
        font-size: 3rem;
    }
    
    .breadcrumb-subtitle {
        font-size: 1.1rem;
    }
    
    .breadcrumb-content {
        padding: 60px 15px;
    }
    
    .breadcrumb {
        padding: 12px 24px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .breadcrumb-bar-modern {
        min-height: 280px;
    }
    
    .breadcrumb-title {
        font-size: 2.2rem;
    }
    
    .breadcrumb-subtitle {
        font-size: 0.95rem;
    }
    
    .breadcrumb {
        padding: 10px 20px;
        font-size: 0.85rem;
    }
    
    .breadcrumb-item a, .breadcrumb-item.active {
        padding: 5px 10px;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        margin: 0 8px;
    }
}

/* Efeito parallax suave */
@media (min-width: 993px) {
    .breadcrumb-bar-modern {
        background-attachment: fixed;
    }
}
</style>

<div class="breadcrumb-bar-modern">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-title">{{ $title ?? 'Home' }}</h1>
                    
                    @if(isset($subtitle))
                        <p class="breadcrumb-subtitle">{{ $subtitle }}</p>
                    @endif
                    
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">
                                    <i class="fas fa-home me-1"></i> Home
                                </a>
                            </li>
                            
                            @if(isset($breadcrumbs) && is_array($breadcrumbs))
                                @foreach($breadcrumbs as $breadcrumb)
                                    @if($loop->last)
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{ $breadcrumb['title'] }}
                                        </li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $title ?? 'Página Atual' }}
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>