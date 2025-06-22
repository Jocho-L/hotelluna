<?php
session_start();
include('../app/config/Conexion.php');
$conn = Conexion::getConexion();
$conn = null;

if (!isset($_SESSION['idusuario'])) {
  header("Location: ../index.php");
  exit;
}

// Definir roles permitidos para esta vista
$roles_permitidos = ['Administrador', 'Recepcionista']; // Cambia según la vista

if (!in_array($_SESSION['rol'], $roles_permitidos)) {
  // Si el rol no está permitido, redirige o muestra mensaje
  header("Location: ../acceso_denegado.php");
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
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/adminlte.min.css" />
  <!-- DataTables v2 CSS -->
  <link href="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.css" rel="stylesheet" />
  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
  <style>
    canvas {
      min-height: 300px !important;
      max-width: 100%;
    }
  </style>

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
          <a href="index.php" class="nav-link">Home</a>
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
                  <a href="#" data-vista="habitaciones/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Habitaciones</p>
                  </a>
                </li>
                <!-- Solo para Administrador -->
                <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                  <li class="nav-item">
                    <a href="#" data-vista="usuarios/listar.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Usuarios</p>
                    </a>
                  </li>
                  <!-- <li class="nav-item">
                    <a href="#" data-vista="empresas/listar.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Empresas</p>
                    </a>
                  </li> -->
                <?php endif; ?>
                <!-- Otros módulos accesibles por ambos roles -->
                <!-- <li class="nav-item">
                  <a href="#" data-vista="clientes/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Clientes</p>
                  </a>
                </li> -->
                <li class="nav-item">
                  <a href="#" data-vista="alquileres/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Alquileres</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="calendario/menu.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Calendario</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-vista="personas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Personas</p>
                  </a>
                </li>
                <!-- <li class="nav-item">
                  <a href="#" data-vista="reservas/listar.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reservas</p>
                  </a>
                </li> -->
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" data-vista="reportes_usuario/menu.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Reportes U</p>
              </a>
            </li>
            <?php if ($_SESSION['rol'] === 'Administrador'): ?>
            <li class="nav-item">
              <a href="#" data-vista="reportes/menu.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Reportes</p>
              </a>
            </li>
            <?php endif; ?>
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

          <!-- Filtros de habitaciones -->
          <div class="row mb-3">
            <div class="col-md-4">
              <div class="form-group">
                <label for="filtro-piso">Filtrar por piso:</label>
                <select class="form-control" id="filtro-piso">
                  <option value="todos">Todos los pisos</option>
                  <!-- Las opciones se llenarán dinámicamente -->
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="filtro-estado">Filtrar por estado:</label>
                <select class="form-control" id="filtro-estado">
                  <option value="todos">Todos los estados</option>
                  <option value="Disponible" selected>Disponible</option>
                  <option value="Ocupado">Ocupado</option>
                  <option value="Mantenimiento">Mantenimiento</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="filtro-tipo">Filtrar por tipo:</label>
                <select class="form-control" id="filtro-tipo">
                  <option value="todos">Todos los tipos</option>
                  <!-- Las opciones se llenarán dinámicamente -->
                </select>
              </div>
            </div>
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

  <!-- DataTables por tabla -->
  <script src="../public/js/datatables/clientes.js"></script>
  <script src="../public/js/datatables/personas.js"></script>
  <script src="../public/js/datatables/alquileres.js"></script>

  <!-- cargar ajax de vistas del menú lateral y inicializar DataTables -->
  <script src="../public/js/cargar_ajax.js"></script>
  <!-- graficos -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../public/js/graficos.js"></script>

  <!-- /* Filtros de habitaciones */ -->
  <script>
    /* Filtros de habitaciones */
    document.addEventListener("DOMContentLoaded", () => {
      let todasHabitaciones = []; // Almacenar todas las habitaciones

      // Cargar habitaciones al inicio
      cargarHabitaciones();

      // Event listeners para los filtros
      document.getElementById('filtro-piso').addEventListener('change', filtrarHabitaciones);
      document.getElementById('filtro-estado').addEventListener('change', filtrarHabitaciones);
      document.getElementById('filtro-tipo').addEventListener('change', filtrarHabitaciones);

      function cargarHabitaciones() {
        fetch("menu/habitaciones.php")
          .then(response => response.json())
          .then(data => {
            todasHabitaciones = data;
            llenarOpcionesFiltros(data);
            filtrarHabitaciones();
          })
          .catch(err => {
            console.error("Error al cargar habitaciones:", err);
            mostrarError();
          });
      }

      function llenarOpcionesFiltros(habitaciones) {
        // Llenar filtro de pisos
        const pisosUnicos = [...new Set(habitaciones.map(h => h.piso))].sort();
        const filtroPiso = document.getElementById('filtro-piso');

        pisosUnicos.forEach(piso => {
          const option = document.createElement('option');
          option.value = piso;
          option.textContent = `Piso ${piso}`;
          filtroPiso.appendChild(option);
        });

        // Llenar filtro de tipos
        const tiposUnicos = [...new Set(habitaciones.map(h => h.tipo))].sort();
        const filtroTipo = document.getElementById('filtro-tipo');

        tiposUnicos.forEach(tipo => {
          const option = document.createElement('option');
          option.value = tipo;
          option.textContent = tipo;
          filtroTipo.appendChild(option);
        });
      }

      function filtrarHabitaciones() {
        const filtroPiso = document.getElementById('filtro-piso').value;
        const filtroEstado = document.getElementById('filtro-estado').value;
        const filtroTipo = document.getElementById('filtro-tipo').value;

        // Función para mapear estado de BD a visual
        function mapEstado(estado) {
          if (!estado) return 'Desconocido';
          switch (estado.toLowerCase()) {
            case 'ocupada':
              return 'Ocupado';
            case 'disponible':
              return 'Disponible';
            case 'mantenimiento':
              return 'Mantenimiento';
            default:
              return 'Desconocido';
          }
        }

        // Filtrar habitaciones según los criterios seleccionados
        const habitacionesFiltradas = todasHabitaciones.filter(h => {
          const cumplePiso = filtroPiso === 'todos' || h.piso == filtroPiso;
          const estadoVisual = mapEstado(h.estado);
          const cumpleEstado = filtroEstado === 'todos' || estadoVisual === filtroEstado;
          const cumpleTipo = filtroTipo === 'todos' || h.tipo === filtroTipo;
          return cumplePiso && cumpleEstado && cumpleTipo;
        });

        mostrarHabitaciones(habitacionesFiltradas);
      }

      function mostrarHabitaciones(habitaciones) {
        const container = document.getElementById("habitaciones-container");
        container.innerHTML = "";

        if (habitaciones.length === 0) {
          container.innerHTML = `
            <div class="col-12">
              <div class="alert alert-info">
                No se encontraron habitaciones con los filtros seleccionados.
              </div>
            </div>`;
          return;
        }

        const filtroPiso = document.getElementById('filtro-piso').value;

        if (filtroPiso === 'todos') {
          // Mostrar todas las habitaciones juntas, sin agrupar por piso
          habitaciones.forEach(h => {
            // Mapear estado de la BD a estado mostrado
            let estado = h.estado ? h.estado : 'Desconocido';
            if (estado.toLowerCase() === 'ocupada') estado = 'Ocupado';
            if (estado.toLowerCase() === 'disponible') estado = 'Disponible';
            if (estado.toLowerCase() === 'mantenimiento') estado = 'Mantenimiento';

            const box = `
              <div class="col-lg-3 col-6">
                <div class="small-box ${getColorEstado(estado)}">
                  <div class="inner">
                    <h3 style="font-size: 40px;">${h.numero}</h3>
                    <p style="font-size: 18px; margin-bottom: 5px;">${h.tipo}</p>
                    <p style="font-size: 18px; margin-bottom: 5px;">Precio: <strong>S/. ${h.precio}</strong></p>
                    <p style="font-size: 18px;">Estado: <strong>${estado}</strong></p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-bed" style="font-size: 60px;"></i>
                  </div>
                  <a href="${getEnlaceAccion({...h, estado})}" class="small-box-footer" style="font-size: 18px;">
                    ${getTextoAccion(estado)} <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>`;
            container.innerHTML += box;
          });
        } else {
          // Agrupar por piso solo si se selecciona un piso específico
          const habitacionesPorPiso = {};
          habitaciones.forEach(h => {
            if (!habitacionesPorPiso[h.piso]) {
              habitacionesPorPiso[h.piso] = [];
            }
            habitacionesPorPiso[h.piso].push(h);
          });

          for (const piso in habitacionesPorPiso) {
            // Encabezado del piso
            container.innerHTML += `
              <div class="col-12">
                <h4 class="mt-4 mb-3">Piso ${piso}</h4>
                <hr>
              </div>`;

            habitacionesPorPiso[piso].forEach(h => {
              let estado = h.estado ? h.estado : 'Desconocido';
              if (estado.toLowerCase() === 'ocupada') estado = 'Ocupado';
              if (estado.toLowerCase() === 'disponible') estado = 'Disponible';
              if (estado.toLowerCase() === 'mantenimiento') estado = 'Mantenimiento';

              const box = `
                <div class="col-lg-3 col-6">
                  <div class="small-box ${getColorEstado(estado)}">
                    <div class="inner">
                      <h3 style="font-size: 40px;">${h.numero}</h3>
                      <p style="font-size: 18px; margin-bottom: 5px;">${h.tipo}</p>
                      <p style="font-size: 18px; margin-bottom: 5px;">Precio: <strong>S/. ${h.precio}</strong></p>
                      <p style="font-size: 18px;">Estado: <strong>${estado}</strong></p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-bed" style="font-size: 60px;"></i>
                    </div>
                    <a href="${getEnlaceAccion({...h, estado})}" class="small-box-footer" style="font-size: 18px;">
                      ${getTextoAccion(estado)} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>`;
              container.innerHTML += box;
            });
          }
        }
      }

      function getColorEstado(estado) {
        switch (estado.toLowerCase()) {
          case 'disponible':
            return 'bg-success';
          case 'mantenimiento':
            return 'bg-warning';
          case 'ocupado':
            return 'bg-dark';
          default:
            return 'bg-secondary';
        }
      }

      function getEnlaceAccion(habitacion) {
        switch (habitacion.estado.toLowerCase()) {
          case 'disponible':
            return `alquileres/registrar.php?idhabitacion=${habitacion.idhabitacion}`;
          case 'ocupado':
            return `alquileres/detalle.php?idalquiler=${habitacion.idalquiler}`;
          case 'mantenimiento':
            return `alquileres/postsalida.php?idhabitacion=${habitacion.idhabitacion}`;
          default:
            return '#';
        }
      }

      function getTextoAccion(estado) {
        switch (estado.toLowerCase()) {
          case 'disponible':
            return 'Asignar habitación';
          case 'ocupado':
            return 'Ver detalle';
          case 'mantenimiento':
            return 'En mantenimiento';
          default:
            return 'Consultar';
        }
      }

      function mostrarError() {
        const container = document.getElementById("habitaciones-container");
        container.innerHTML = `
        <div class="col-12">
          <div class="alert alert-danger">
            Error al cargar las habitaciones. Por favor, intente nuevamente.
          </div>
        </div>`;
      }
    });
  </script>

  <!-- Modal Detalle Habitación (detalle estilo detalle.php) -->
  <div class="modal fade" id="modalDetalleHabitacion" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalleLabel">Detalle de Habitación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="detalle-habitacion-body">
          <!-- Aquí se cargará el detalle completo por JS -->
          <div class="text-center">
            <span class="spinner-border text-primary"></span>
            <p>Cargando detalle...</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Modal Detalle Habitación -->

  <script>
    function mostrarDetalleHabitacion(idhabitacion) {
      const body = document.getElementById('detalle-habitacion-body');
      body.innerHTML = `
      <div class="text-center">
        <span class="spinner-border text-primary"></span>
        <p>Cargando detalle...</p>
      </div>
    `;

      fetch(`menu/detalle_habitacion.php?idhabitacion=${idhabitacion}`)
        .then(response => response.json())
        .then(data => {
          if (!data || data.error) {
            body.innerHTML = `<div class="alert alert-danger">No se pudo obtener el detalle de la habitación.</div>`;
            return;
          }

          // Renderizar detalle estilo detalle.php
          body.innerHTML = `
          <div class="card border border-secondary shadow rounded p-3">
            <div class="mb-3">
              <span class="badge badge-info">ID Habitación: ${data.idhabitacion ?? '-'}</span>
              <span class="badge badge-secondary ml-2">Piso: ${data.habitacion_piso ?? '-'}</span>
              <span class="badge badge-primary ml-2">Tipo: ${data.habitacion_tipo ?? '-'}</span>
              <span class="badge badge-dark ml-2">Estado: ${data.habitacion_estado ?? '-'}</span>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <h5>Habitación <strong>${data.habitacion_numero ?? '-'}</strong></h5>
                <p><strong>Número de Camas:</strong> ${data.habitacion_numcamas ?? '-'}</p>
                <p><strong>Precio Regular:</strong> S/. ${data.habitacion_precio ? Number(data.habitacion_precio).toFixed(2) : '-'}</p>
              </div>
              <div class="col-md-6 mb-3">
                <h5>Cliente Principal</h5>
                <p><strong>Nombre:</strong> ${data.cliente_nombres ?? ''} ${data.cliente_apellidos ?? ''}</p>
                <p><strong>DNI:</strong> ${data.cliente_numerodoc ?? '-'}</p>
                <p><strong>Teléfono:</strong> ${data.cliente_telefono ?? '-'}</p>
              </div>
            </div>
            <div class="mb-3">
              <h5>Acompañantes</h5>
              ${
                Array.isArray(data.acompanantes) && data.acompanantes.length > 0
                  ? `<ul>${data.acompanantes.map(a => `<li>${a.nombres ?? ''} ${a.apellidos ?? ''} (${a.numerodoc ?? '-'})</li>`).join('')}</ul>`
                  : '<p>No hay acompañantes registrados.</p>'
              }
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <p><strong>Fecha de Inicio:</strong> ${data.fechahorainicio ?? '-'}</p>
                <p><strong>Fecha de Fin:</strong> ${data.fechahorafin ?? '-'}</p>
                <p><strong>Lugar de Procedencia:</strong> ${data.lugarprocedencia ?? '-'}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Modalidad de Pago:</strong> ${data.modalidadpago ?? '-'}</p>
                <p><strong>Total Pagado:</strong> S/. ${data.valoralquiler ? Number(data.valoralquiler).toFixed(2) : '-'}</p>
                <p><strong>Incluye desayuno:</strong> ${data.incluyedesayuno ? 'Sí' : 'No'}</p>
              </div>
            </div>
            <div class="mb-3">
              <strong>Observaciones:</strong>
              <p>${data.observaciones ? data.observaciones.replace(/\n/g, '<br>') : '-'}</p>
            </div>
          </div>
        `;
        })
        .catch(() => {
          body.innerHTML = `<div class="alert alert-danger">No se pudo obtener el detalle de la habitación.</div>`;
        });

      $('#modalDetalleHabitacion').modal('show');
    }
  </script>

  <!-- Modal para mostrar huéspedes (debe estar fuera de #contenido y de cualquier vista AJAX) -->
  <div class="modal fade" id="modalHuespedes" tabindex="-1" role="dialog" aria-labelledby="modalHuespedesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalHuespedesLabel">Huéspedes del alquiler</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="huespedes-lista">
            <!-- Aquí se cargan los huéspedes -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Mostrar Huespedes Alquiler -->
  <script>
    function mostrarHuespedesAlquiler(idalquiler) {
      // Limpia la lista de huéspedes
      document.getElementById('huespedes-lista').innerHTML = '';

      fetch(`alquileres/huespedes_ajax.php?idalquiler=${idalquiler}`)
        .then(response => response.json())
        .then(data => {
          const lista = document.getElementById('huespedes-lista');
          if (data.length === 0) {
            lista.innerHTML = '<p>No hay huéspedes registrados para este alquiler.</p>';
            return;
          }
          lista.innerHTML = '';
          data.forEach(huesped => {
            const div = document.createElement('div');
            div.classList.add('huesped-item');
            div.innerHTML = `
              <p><strong>${huesped.nombres} ${huesped.apellidos}</strong></p>
              <p>Tipo huésped: ${huesped.tipohuesped}</p>
              <p>Parentesco: ${huesped.parentesco ?? '-'}</p>
              <p>Observaciones: ${huesped.observaciones ?? '-'}</p>
              <hr>
            `;
            lista.appendChild(div);
          });
        })
        .catch(() => {
          document.getElementById('huespedes-lista').innerHTML = '<p>Error al cargar los huéspedes.</p>';
        });

      $('#modalHuespedes').modal('show');
    }
  </script>
  <!-- Delegación global para el botón "Ver huéspedes" -->
  <script>
    // Delegación global para el botón "Ver huéspedes"
    document.body.addEventListener('click', function(e) {
      if (e.target.classList.contains('ver-huespedes')) {
        const idalquiler = e.target.getAttribute('data-idalquiler');
        if (typeof mostrarHuespedesAlquiler === 'function') {
          mostrarHuespedesAlquiler(idalquiler);
        } else {
          alert('No se puede mostrar el modal de huéspedes. Recargue la página.');
        }
      }
    });
  </script>

  <!-- Modal para mostrar credenciales -->
  <div class="modal fade" id="modalCredenciales" tabindex="-1" role="dialog" aria-labelledby="modalCredencialesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCredencialesLabel">Credenciales del Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><strong>Usuario:</strong> <span id="credencialUsuario"></span></p>
          <p><strong>Contraseña:</strong> <span class="text-danger">No disponible por seguridad</span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function mostrarCredenciales(username) {
      document.getElementById('credencialUsuario').textContent = username;
      $('#modalCredenciales').modal('show');
    }
  </script>


</body>

</html>