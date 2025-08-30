<link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">

<style>
    .breadcrumb-bar-modern {
        position: relative;
        background: linear-gradient(135deg, rgba(0,0,0,0.75), rgba(0,0,0,0.55)),
            url('{{ $banner }}') no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        min-height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        z-index: 1;
    }
</style>

<div class="breadcrumb-bar-modern">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-title">{{ $title }}</h1>
                    
                    @if($subtitle)
                        <p class="breadcrumb-subtitle">{{ $subtitle }}</p>
                    @endif
                    
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">
                                    <i class="fas fa-home me-1"></i> Home
                                </a>
                            </li>
                            
                            @if($breadcrumbs && is_array($breadcrumbs))
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
                                    {{ $title }}
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
