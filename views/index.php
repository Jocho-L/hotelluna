<?php
include('../app/config/Conexion.php');
$conn = Conexion::getConexion();
$conn = null;
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
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/adminlte.min.css" />
  <!-- DataTables v2 CSS -->
  <link href="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.css" rel="stylesheet" />


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
      <a href="index.php" class="brand-link">
        <img src="../public/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Hotel Luna</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../public/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" />
          </div>
          <div class="info">
            <a href="#" class="d-block">Alexander Pierce</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2" id="navbar-options">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
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
                  <a href="#" data-vista="habitaciones/listar.php" class="nav-link">
                    <!-- la clase active, diferente el elemento -->
                    <i class="far fa-circle nav-icon"></i>
                    <p>Habitaciones</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="clientes/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Clientes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="reservas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reservas</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="colaboradores/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Colaboradores</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="empresas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Empresas</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Reportes</p>
              </a>
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
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Inicio</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Starter Page</li>
              </ol>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <!-- 
        Iconos para los paneles de HABITACIONES
        https://themeon.net/nifty/v2.9.1/icons-ionicons.html
        -->
      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">
              <div class="row" id="habitaciones-container">
                <!-- Las habitaciones se insertarán aquí -->
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar (DERECHO PANEL OCULTO) -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        Desarrollado por José Hernandez
      </div>
      <!-- Default to the left -->
      <strong>Todos los derechos reservados
        <a href="https://adminlte.io">Hotel Luna</a>.</strong>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../public/js/adminlte.min.js"></script>
  <script src="../public/js/cliente.js"></script>
  <!-- DataTables v2 JS -->
  <script src="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      fetch("menu/habitaciones.php")
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById("habitaciones-container");
          container.innerHTML = "";

          data.forEach(h => {
            const box = `
          <div class="col-lg-3 col-6">
            <div class="small-box ${h.estado === 'Disponible' ? 'bg-success' : h.estado === 'Mantenimiento' ? 'bg-warning' : 'bg-secondary'}">
              <div class="inner">
                <h3 style="font-size: 40px;">${h.numero}</h3>
                <p style="font-size: 18px; margin-bottom: 5px;">Tipo: ${h.tipo}</p>
                <p style="font-size: 18px; margin-bottom: 5px;">Precio: <strong style="font-size: 20px;">S/. ${h.precio}</strong></p>
                <p style="font-size: 18px;">N° Camas: <em>No definido</em></p> <!-- Aquí podrías agregar si tienes ese dato -->
              </div>
              <div class="icon">
                <i class="fa fa-bed" style="font-size: 60px;"></i>
              </div>
              <a href="${h.estado === 'Disponible' ? `alquileres/registrar.php?idhabitacion=${h.idhabitacion}` : h.estado === 'Ocupado' ? `views/editar.php?idhabitacion=${h.idhabitacion}` : '#'}" class="small-box-footer" style="font-size: 18px;">
                ${h.estado === 'Disponible' ? 'Alquilar' : h.estado === 'Mantenimiento' ? 'Mantenimiento' : 'Consultar'} <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>`;
            container.innerHTML += box;
          });
        })
        .catch(err => {
          console.error("Error al cargar habitaciones:", err);
        });
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const navbarOptions = document.querySelectorAll("#navbar-options .nav-link");

      navbarOptions.forEach(enlace => {
        enlace.addEventListener("click", function (event) {
          const destino = event.target.closest("a").getAttribute("data-vista");

          if (destino) {
            fetch(destino, {
              method: 'GET'
            })
              .then(response => response.text())
              .then(data => {
                document.querySelector("#contenido").innerHTML = data;

                // Esperamos un poco para asegurarnos que la tabla ya se renderizó
                setTimeout(() => {
                  const tabla = document.querySelector("#tablaClientes");
                  if (tabla) {
                    new DataTable(tabla, {
                      language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        paginate: {
                          first: "Primero",
                          last: "Último",
                          next: "Siguiente",
                          previous: "Anterior"
                        },
                        zeroRecords: "No se encontraron registros coincidentes",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        infoFiltered: "(filtrado de _MAX_ registros en total)"
                      }
                    });
                  }
                }, 0); // Tiempo mínimo de espera
              })
              .catch(error => console.error("Error al cargar la vista:", error));
          }
        });
      });
    });
  </script>

  <script>
    new DataTable('#tablaClientes', {
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'csvHtml5',
          text: 'Exportar a CSV',
          title: 'clientes'
        }
      ],
      language: {
        url: "//cdn.datatables.net/plug-ins/2.0.3/i18n/es-ES.json"
      }
    });
  </script>


</body>

</html>