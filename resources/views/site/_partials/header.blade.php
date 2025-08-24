<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site->titulo }} - {{ $site->descricao ?? 'Escola de Surf' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="empresa-id" content="{{ $site->empresa_id ?? 1 }}">

    @if(!empty($site->favicon))
        <link rel="icon" href="{{ asset('storage/' . $site->favicon) }}" type="image/png">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-4ZMP2C63TR">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-4ZMP2C63TR');
</script>
    @php
        $corPrimaria = $site->cores['primaria'] ?? '#667eea';
        $corSecundaria = $site->cores['secundaria'] ?? '#764ba2';
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * { font-family: 'Poppins', sans-serif; }

        .hero-gradient {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="40%"><stop offset="0%" style="stop-color:rgb(255,255,255);stop-opacity:0.3"/><stop offset="100%" style="stop-color:rgb(255,255,255);stop-opacity:0"/></radialGradient></defs><ellipse fill="url(%23a)" cx="50" cy="10" rx="50" ry="10"/></svg>') repeat-x;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .wave-animation {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="%23ffffff"/></svg>') no-repeat center bottom;
            background-size: cover;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .floating { animation: float 3s ease-in-out infinite; }

        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .text-glow { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5); }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-15px) scale(1.02);
        }

        .diagonal-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .diagonal-whatsapp:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(37, 211, 102, 0.6);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 50%;
            background: linear-gradient(90deg, {{ $corPrimaria }}, {{ $corSecundaria }});
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover::before { width: 100%; }

        .service-icon {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before { left: 100%; }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }

        .wave-loader {
            width: 100px;
            height: 100px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stagger-item {
            opacity: 0;
            transform: translateY(50px) !important;
            
        }

        .testimonial-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-left: 4px solid transparent;
            background-clip: padding-box;
            position: relative;
        }

        .testimonial-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            border-radius: 0 2px 2px 0;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, {{ $corPrimaria }} 50%, transparent 100%);
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
    #home {
        padding-top: 100px; /* ajuste o valor conforme a altura do menu */
    }
}
    </style>
</head>