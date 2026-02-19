<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ AppSetting('title') }} - Sistema de Citas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clinic Dr. Panameño - Sistema de Gestión de Hospitales y Clínicas desarrollado por Jaime Aleman">
    <meta name="keywords" content="Clinic Dr. Panameño - Sistema de Gestión de Hospitales y Clínicas desarrollado por Jaime Aleman">
    <meta name="author" content="Themesbrand">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/')."/". AppSetting('favicon') }}">
    @include('layouts.head')

    <style>
        /* ─── SIDEBAR PREMIUM DEEP BLUE THEME ─── */
        body[data-sidebar="dark"] .vertical-menu {
            /* Azul imperial profundo a azul nocturno */
            background: linear-gradient(180deg, #10306c 0%, #081736 100%) !important;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        
        /* Logo Area */
        body[data-sidebar="dark"] .navbar-brand-box {
            background: #10306c !important;
            box-shadow: 0 1px 0 rgba(255,255,255,0.05);
        }

        /* Menu Items Container */
        body[data-sidebar="dark"] #sidebar-menu ul li {
            margin-bottom: 2px;
        }

        /* Menu Links */
        body[data-sidebar="dark"] #sidebar-menu ul li a {
            color: rgba(255,255,255,0.80) !important;
            font-size: 15.5px !important; /* Fuente más grande */
            padding: 12px 20px !important; /* Más espaciado vertical */
            display: flex !important;
            align-items: center;
            transition: all 0.3s;
            font-weight: 400;
        }
        
        /* Icons */
        body[data-sidebar="dark"] #sidebar-menu ul li a i {
            color: rgba(255,255,255,0.85) !important;
            font-size: 22px !important; /* Iconos grandes */
            min-width: 35px !important; /* Espacio reservado para icono */
            display: inline-block;
            text-align: center;
            margin-right: 5px;
        }
        
        /* Hover State */
        body[data-sidebar="dark"] #sidebar-menu ul li a:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.06);
            padding-left: 25px !important; /* Efecto sutil de movimiento */
        }
        
        body[data-sidebar="dark"] #sidebar-menu ul li a:hover i {
            color: #fff !important;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        /* Active State */
        body[data-sidebar="dark"] #sidebar-menu ul li.mm-active > a {
            color: #fff !important;
            background: rgba(255,255,255,0.1) !important;
            border-left: 4px solid #64B5F6; /* Borde de acento más grueso */
            font-weight: 500;
        }
        
        body[data-sidebar="dark"] #sidebar-menu ul li.mm-active > a i {
            color: #64B5F6 !important; /* Icono activo en celeste */
        }

        /* Submenu */
        body[data-sidebar="dark"] #sidebar-menu ul ul.sub-menu {
            background: rgba(0,0,0,0.15) !important; 
            padding-left: 0 !important;
        }
        
        body[data-sidebar="dark"] #sidebar-menu ul ul.sub-menu li a {
            padding: 10px 20px 10px 58px !important; /* Indentación correcta para submenú */
            font-size: 14.5px !important;
            color: rgba(255,255,255,0.65) !important;
        }
        
        body[data-sidebar="dark"] #sidebar-menu ul ul.sub-menu li a:hover {
            background: transparent !important;
            color: #fff !important;
            padding-left: 62px !important;
        }

        /* Metismenu Arrow */
        body[data-sidebar="dark"] #sidebar-menu ul li a .has-arrow:after {
            font-size: 1.2rem; 
        }

        /* Menu Titles */
        body[data-sidebar="dark"] #sidebar-menu .menu-title {
            color: rgba(255,255,255,0.4) !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 15px 20px 10px !important;
            margin-top: 5px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        
        /* Scrollbar */
        .simplebar-track.simplebar-vertical .simplebar-scrollbar:before {
            background: rgba(255,255,255,0.2);
        }
    </style>

<body data-sidebar="dark" data-topbar="light" data-layout="vertical">

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner-chase">
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
        </div>
    </div>
</div>
<!-- Begin page -->

<div id="layout-wrapper">
    @include('layouts.top-hor')
    @include('layouts.sidebar')
    
    {{-- @include('layouts.hor-menu') --}}
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <!-- Start content -->
            <div class="container-fluid">
                @yield('content')
            </div> <!-- content -->
        </div>
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
</div>
<!-- END wrapper -->

<!-- Right Sidebar -->
@include('layouts.right-sidebar')
<!-- END Right Sidebar -->

@include('layouts.footer-script')
</body>

</html>
