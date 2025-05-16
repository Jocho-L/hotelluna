<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Reservas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Reservas</a></li>
          <li class="breadcrumb-item active">Listar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <!-- Contenido principal -->
        <div id="contenido" class="container" style="flex-grow: 1; padding: 20px;">
          <h2>Lista de Reservas</h2>

          <!-- Botón para agregar una nueva reserva -->
          <a href="#" id="btnRegistrar" class="btn btn-success mb-3" data-vista="reservas/registrar.php">Agregar Nueva Reserva</a>

          <!-- Tabla de reservas -->
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID Reserva</th>
                <th>Cliente</th>
                <th>Habitación</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Colaborador</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Aquí, la lógica del backend debe reemplazar las siguientes filas con los datos dinámicos -->
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <a href="editar.php?idalquiler=1" class="btn btn-warning btn-sm">Editar</a>
                  <a href="eliminar_reserva.php?idalquiler=1" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');">Eliminar</a>
                </td>
              </tr>
              <!-- Puedes agregar más filas de reservas aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
