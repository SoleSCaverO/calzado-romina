<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de calzado | @yield('title')</title>

    <!-- Bootstrap -->
    <link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('gentelella/css/custom.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link rel="stylesheet" href="{{asset('datatable/css/dataTables.bootstrap.min.css')}}"/>

    <!-- SweetAlert -->
    <link rel="stylesheet" href="{{asset('sweetalert/css/sweetalert.css')}}"/>

    <link rel="shortcut icon" href="{{asset('gentelella/production/images/Logo_Romina.png')}}" />
    <style>
        .beside_check{
            padding-top:10px;
        }
        .hidden_it{
            display: none;
        }
        label.error{
            color: red;
        }

        @media print {
            .noprint {display:none;}
        }
    </style>
    @yield('styles')
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ route('home') }}" class="site_title"><i class="fa fa-envira"></i> <span>Sistema calzado</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="{{ asset('gentelella/production/images/img.jpg') }}" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Bienvenido</span>
                        <h2>{{ Auth()->user()->name }}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-user"></i>CONTROL USUARIOS<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="index.html">Dashboard</a></li>
                                    <li><a href="index2.html">Dashboard2</a></li>
                                    <li><a href="index3.html">Dashboard3</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-desktop"></i>MANTENIMIENTO <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('models') }}">MODELO</a></li>
                                    <li><a href="{{ route('areas') }}">ÁREA Y SUB AREAS</a></li>
                                    <li><a href="{{ route('areas.subareas.subareas_menores.areas') }}">SUB AREAS MENORES</a></li>
                                    <li><a href="{{ route('trabajadores') }}">TRABAJADORES</a></li>
                                    <li><a href="{{ route('precio_area') }}">PRECIOS POR AREA</a></li>
                                    <li><a href="{{ route('modelo_tipo') }}">MODELO - DESCRIPCION</a></li>
                                    <li><a href="{{ route('precio.referencial') }}">PRECIOS REFERENCIALES</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-truck"></i>PRODUCCIÓN<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('programacion') }}">PROGRAMACIÓN</a></li>
                                    <li><a href="{{ route('inicio_trabajo') }}">INICIO DE TRABAJO</a></li>
                                    <li><a href="{{ route('termino_trabajo') }}">TÉRMINO DE TRABAJO</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-table"></i>PLANILLA <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('planillas') }}">Inicio - Término</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-table"></i>FICHA TECNICA <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('ficha.tecnica') }}">DISEÑO</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i>REPORTES<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="chartjs.html">Chart JS</a></li>
                                    <li><a href="chartjs2.html">Chart JS2</a></li>
                                    <li><a href="morisjs.html">Moris JS</a></li>
                                    <li><a href="echarts.html">ECharts</a></li>
                                    <li><a href="other_charts.html">Other Charts</a></li>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-table"></i>PRUEBAS <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('precios') }}">Precios</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu noprint">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('gentelella/production/images/img.jpg') }}" alt="">{{ Auth()->user()->name }}
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="row">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="text-center">
                Sistema de calzado - 2017
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>

<!-- Access denied -->
<script src="{{ asset('gentelella/js/user/access.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('gentelella/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('gentelella/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('gentelella/vendors/nprogress/nprogress.js') }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('gentelella/js/custom.js') }}"></script>

<!-- SweetAlert -->
<script src="{{ asset('sweetalert/js/sweetalert.min.js') }}"></script>

<!-- Notify -->
<script src="{{asset('js/global/notify.min.js')}}"></script>


<script src="{{asset('js/global/jquery.validate.min.js')}}"></script>

<!-- Datatable -->
<script src="{{ asset('datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatable/js/jquery.dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('validate/localization/messages_es_PE.js') }}"></script>

<!-- Custom functions -->
<script src="{{ asset('js/global/functions.js') }}"></script>

@yield('modals')

@yield('scripts')
</body>
</html>
