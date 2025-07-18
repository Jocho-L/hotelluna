<?php
session_start();
include('../../app/config/Conexion.php');
$conn = Conexion::getConexion();
$conn = null;

if (!isset($_SESSION['idusuario'])) {
  header("Location: ../../index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hotel Luna</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/hotelluna/plugins/fontawesome-free/css/all.min.css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/hotelluna/public/css/adminlte.min.css" />
  <!-- CSS de Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- CSS de DataTables -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                  aria-label="Search" />
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/hotelluna/views/index.php" class="brand-link">
        <img src="/hotelluna/public/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Hotel Luna</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="info">
            <a class="d-block" style="white-space: normal;">
              <span><?= $_SESSION['nombres'] ?></span><br>
              <span><?= $_SESSION['apellidos'] ?></span><br>
              <small>(<?= $_SESSION['rol'] ?>)</small>
            </a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2" id="navbar-options">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Módulos comunes -->
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Módulos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/habitaciones/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Habitaciones</p>
                  </a>
                </li>
                <!-- Solo para Administrador -->
                <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/usuarios/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Usuarios</p>
                  </a>
                </li>
                <!-- <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/empresas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Empresas</p>
                  </a>
                </li> -->
                <?php endif; ?>
                <!-- Otros módulos accesibles por ambos roles -->
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/clientes/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Clientes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/alquileres/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Alquileres</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/personas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Personas</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="/hotelluna/views/reservas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reservas</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" data-vista="/hotelluna/views/reportes_usuario/menu.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Reportes U</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" data-vista="/hotelluna/views/reportes/menu.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Reportes</p>
              </a>
            </li>
            <li>
              <p><a href="../logout.php">Cerrar sesión</a></p>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="contenido">
      <!-- Content Header (Page header) -->

      <!-- /.content-header -->

      <!-- Main content -->
      <!--
        Iconos para los paneles de HABITACIONES
        https://themeon.net/nifty/v2.9.1/icons-ionicons.html
        -->
      <div class="content">
        <div class="container-fluid">
          <!-- jQuery -->
<script src="/hotelluna/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/hotelluna/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/hotelluna/public/js/adminlte.min.js"></script>
<!-- DataTables v2 JS -->
<script src="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.js"></script>
<!-- cargar ajax de vistas del menú lateral y inicializar DataTables -->
<script src="/hotelluna/public/js/cargar_ajax.js"></script>
</body>
</html></div>