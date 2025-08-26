<!DOCTYPE html>
<html lang="en">

<head>
    
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/font-awesome.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/feathericon.min.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Full Calander CSS -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fullcalendar/fullcalendar.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    <link href='https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.css' rel='stylesheet' />

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/jquery-ui.min.js') }}"></script> --}}



</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <div class="header">

            <!-- Logo -->
            @php
                $user = Auth::user(); // pega o usuário autenticado
                $empresa = $user->empresa ?? '';
                $avatar = $empresa->avatar ?? '';
            @endphp
            <div class="header-left">
                <a href="{{ route('home.index') }}" class="logo">
                    <x-logo-tipo imagem="{{ $avatar }}" largura="154" altura="80" />
                </a>
                <a href="{{ route('home.index') }}" class="logo logo-small">
                    <img src="{{ asset('admin/img/logo-small.png') }}" alt="Logo" width="30" height="30">
                </a>
            </div>
            <!-- /Logo -->

            <a href="javascript:void(0);" id="toggle_btn">
                <i class="fe fe-text-align-left"></i>
            </a>

            <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Buscar">
                    <button class="btn" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Toggle -->
            <a class="mobile_btn" id="mobile_btn">
                <i class="fa fa-bars"></i>
            </a>
            <!-- /Mobile Menu Toggle -->

            <!-- Header Right Menu -->
            <ul class="nav user-menu">

                <!-- Notifications -->
                <li class="nav-item dropdown noti-dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <i class="fe fe-bell"></i> <span class="badge rounded-pill notification-count">3</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notificação</span>
                            <a href="javascript:void(0)" class="clear-noti"> Limpar </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image"
                                                    src="{{ asset('admin/img/doctors/doctor-thumb-01.jpg') }}">
                                            </span>404 (No
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Dr. Ruby Perrin</span>
                                                    Schedule <span class="noti-title">her appointment</span></p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>


                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">Todas Notificações</a>
                        </div>
                    </div>
                </li>
                <!-- /Notifications -->

                <!-- User Menu -->
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle"
                                src="{{ asset('admin/img/profiles/avatar-01.jpg') }}" width="31"
                                alt="Ryan Taylor"></span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ asset('admin/img/profiles/avatar-01.jpg') }}" alt="User Image"
                                    class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>TESTE</h6>
                                <p class="text-muted mb-0">Administrator</p>
                            </div>
                        </div>

                        <a class="dropdown-item"
                            href="{{ route('empresa.configuracao', ['userId' => Auth::user()->id]) }}">Configurações</a>
                        <a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a>
                    </div>
                </li>
                <!-- /User Menu -->

            </ul>
            <!-- /Header Right Menu -->

        </div>
        <!-- /Header -->

        <script>
            $(document).ready(function() {
                function loadNotifications() {
                    $.get('/notificacoes/usuario', function(data) {
                        console.log(data)
                        let list = $(".notification-list");
                        let count = data.length;
                        list.empty();

                        if (count === 0) {
                            list.append(
                                '<li class="notification-message"><a href="#">Nenhuma nova notificação</a></li>'
                            );
                        } else {
                            $.each(data, function(index, notification) {
                                list.append(`
                        <li class="notification-message" data-id="${notification.id}">
                            <a href="#" class="notification-link" data-id="${notification.id}" data-url="${notification.url ?? '#'}">
                                <div class="media">
                                    <div class="media-body">
                                        <p class="noti-details"><strong>${notification.title}</strong><br>${notification.message}</p>
                                        <p class="noti-time">${notification.created_at}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    `);
                            });
                        }

                        $(".notification-count").text(count);
                    });
                }

                // Carrega as notificações ao carregar a página
                loadNotifications();

                // Atualiza a cada 30 segundos
                setInterval(loadNotifications, 30000);

                // Marcar como lida quando clicar
                $(document).on("click", ".notification-link", function(e) {
                    e.preventDefault();
                    let id = $(this).data("id");
                    let url = $(this).data("url");

                    console.log("Notificação clicada ID:", id);

                    if (id) {
                        $.post(`/notificacao/lida/${id}`, function() {
                            loadNotifications();
                            if (url !== "#") {
                                window.location.href = url;
                            }
                        });
                    }
                });
            });
        </script>

        <!-- Sidebar -->
        {{-- <x-admin.sidebar/> --}}
        <x-admin.sidebarescola />
        <!-- /Sidebar -->
