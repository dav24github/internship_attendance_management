<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Control de Asistencia</title>
    <!-- General CSS Files -->

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/app.min.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/bootstrap-social/bootstrap-social.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/style.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/custom.css">

    <link rel='shortcut icon' type='image/x-icon' href='{{asset('backEnd')}}/assets/img/favicon.ico' />
    <style>
        .hover a:hover{
            background: #83bceb !important;
            color: white !important;
        }
    </style>
</head>

<body>
{{--<div class="loader"></div>--}}
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar sticky" id="topnav" style="display: none" v-show="$route.path === '/' || $route.path === '/register' ? false : true ">
            <div class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                    <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                            <i data-feather="maximize"></i>
                        </a>
                    </li>
                    <li>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<img alt="image" src="{{asset('backEnd')}}/assets/img/LOGO-TECNOLOGIA.png" class="img-thumbnail" width="60"/>
                        <strong>UMRPSFXCH FACULTAD DE TECNOLOGÍA</strong>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="side main-sidebar sidebar-style-2" v-show="$route.path === '/' || $route.path === '/register' ? false : true " style="display: none" id="sidebar">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <router-link :to="{name: 'home'}"> <img alt="image" src="{{asset('backEnd')}}/assets/img/icon.png" class="header-logo" /> <span
                            class="logo-name">Lab. Robótica</span>
                    </router-link>
                </div>
                <ul class="sidebar-menu">
                <li class="menu-header"><strong>Control de Asistencia</strong></li>
                    <li style="background-color:#83bceb" class="hover dropdown active">
                        <router-link :to="{name: 'home'}" class="nav-link"><i data-feather="home"></i><span>Inicio</span></router-link>
                    </li>

                    <li style="background-color:#83bceb" class="hover dropdown active">
                        <router-link :to="{name: 'puntoMarcaje'}" class="nav-link"><i data-feather="monitor"></i><span>Punto de Marcaje</span></router-link>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-user"></i><span>Practicantes</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'practicanteCreate'}">Nuevo</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'practicanteIndex'}">Lista</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'practicanteHistorial'}">Historial</router-link></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-clock"></i><span>Horarios</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'horarioCreate'}">Nuevo</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'horarioIndex'}">Lista</router-link></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-calendar-check"></i><span>Asistencias</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'asistenciaCreate'}">Nuevo</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'asistencias'}">Lista</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'searchAsistencia'}">Búsqueda</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'historialAsistencia'}">Historial</router-link></li>
                        </ul>
                    </li>

                    <li>
                        <router-link class="nav-link" :to="{name:'searchRetraso'}"><i class="far fa-hourglass"></i><span>Retrasos</span></router-link>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-times-circle"></i><span>Faltas</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'faltas'}">Lista</router-link></li>
                            <li><router-link class="nav-link" :to="{name: 'searchFalta'}">Búsqueda</router-link></li>
                            <li><router-link class="nav-link" :to="{name: 'historialFalta'}">Historial</router-link></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-plus-square"></i><span>Permisos</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'permisoCreate'}">Nuevo</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'permisoIndex'}">Lista</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'searchPerm'}">Búsqueda</router-link></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-calendar-times"></i><span>Inactividades</span></a>
                        <ul class="dropdown-menu">
                            <li><router-link class="nav-link" :to="{name: 'inactividadCreate'}">Nuevo</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'inactividadIndex'}">Lista</router-link></li>
                            <li><router-link class="nav-link" :to="{name:'searchInact'}">Búsqueda</router-link></li>
                        </ul>
                    </li>

                    <li>
                        <router-link class="nav-link" :to="{name:'generarPdf'}"><i class="far fa-file-pdf"></i><span>Informes</span></router-link>
                    </li>

                    <li>
                        <router-link class="nav-link" :to="{name: 'logout'}"><i class="fas fa-power-off"></i>Cerrar Sesión</router-link>
                    </li>
                </ul>
            </aside>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <router-view></router-view>
            </section>

        </div>

    </div>
</div>
<!-- General JS Scripts -->

<script src="{{asset('js/app.js')}}"></script>
<script>
    let token = localStorage.getItem('token');
    if(token){
        $("topnav").css('display','');
        $("sidebar").css('display','')
    }

</script>

<script src="{{asset('backEnd')}}/assets/js/app.min.js"></script>
<script src="{{asset('backEnd')}}/assets/bundles/select2/dist/js/select2.full.min.js"></script>
<script src="{{asset('backEnd')}}/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
<!-- JS Libraies -->
<script src="{{asset('backEnd')}}/assets/bundles/apexcharts/apexcharts.min.js"></script>
<!-- Page Specific JS File -->
<script src="{{asset('backEnd')}}/assets/js/page/index.js"></script>
<!-- Template JS File -->
<script src="{{asset('backEnd')}}/assets/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="{{asset('backEnd')}}/assets/js/custom.js"></script>



</body>
</html>
<style>
    .side{
        background: #e9e9f0;
    }
</style>
