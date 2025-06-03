<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Habitaciones</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Habitaciones</a></li>
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
          <h2>Lista de Habitaciones</h2>

          <!-- Botón para agregar un nuevo cliente -->
          <a href="habitaciones/registrar.php" class="btn btn-success mb-3" data-vista="#">Agregar habitación</a>

          <!-- Tabla de habitaciones -->
          <table class="table table-striped table-striped">
            <thead class="thead">
              <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Numero</th>
                <th>Piso</th>
                <th>Numero de camas</th>
                <th>Precio regular</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Incluir el archivo de conexión
              require_once(__DIR__ . '/../../app/config/Conexion.php');
              require_once(__DIR__ . '/../../app/models/Habitaciones.php');

              // Obtener la conexión desde la clase Conexion
              $conn = Conexion::getConexion();

              // Consulta SQL para obtener las habitaciones
              $sql = "SELECT h.idhabitacion, t.tipohabitacion, h.numero, h.piso, h.numcamas, h.precioregular
                      FROM habitaciones h
                      JOIN tipohabitaciones t ON h.idtipohabitacion = t.idtipohabitacion";
              $result = $conn->query($sql);

              // Verificamos si la consulta fue exitosa y si hay resultados
              if ($result && $result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                      echo "<tr>";
                      echo "<td>" . $row['idhabitacion'] . "</td>";
                      echo "<td>" . $row['tipohabitacion'] . "</td>";
                      echo "<td>" . $row['numero'] . "</td>";
                      echo "<td>" . $row['piso'] . "</td>";
                      echo "<td>" . $row['numcamas'] . "</td>";
                      echo "<td>" . $row['precioregular'] . "</td>";
                      echo "<td>";
                      echo "<a href='habitaciones/editar.php?idhabitacion=" . $row['idhabitacion'] . "' class='btn btn-warning btn-sm mr-3'>Editar</a>";
                      echo "<a href='../controllers/HabitacionController.php?action=eliminar&idhabitacion=" . $row['idhabitacion'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar esta habitación?\");'>Eliminar</a>";
                      echo "</td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='7'>No se encontraron habitaciones.</td></tr>";
              }

              // Cerrar la conexión
              $conn = null;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
