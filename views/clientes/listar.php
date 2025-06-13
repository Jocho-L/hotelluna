<?php
require_once '../../app/config/Conexion.php';

// Obtenemos la instancia PDO
$conexion = Conexion::getConexion();

// La consulta SQL para obtener los clientes
$sql = "SELECT
          c.idcliente,
          p.tipodoc,
          p.numerodoc,
          p.apellidos,
          p.nombres,
          p.telefono,
          p.fechanac
        FROM clientes c
        JOIN personas p ON c.idpersona = p.idpersona";

try {
  // Ejecutar la consulta SQL
  $stmt = $conexion->prepare($sql);
  $stmt->execute();
  $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  // Si ocurre un error, mostramos un mensaje
  echo "Error al ejecutar la consulta: " . $e->getMessage();
  $clientes = [];
}

// Verificar si no hay clientes
if (empty($clientes)) {
  echo "<p>No se encontraron clientes.</p>";
}
?>

<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Clientes</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Clientes</a></li>
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
          <h2>Lista de Clientes</h2>

          <!-- Botón para agregar un nuevo cliente -->
          <!-- <a href="clientes/registrar.php" class="btn btn-success mb-3">Agregar Nuevo Cliente</a> -->

          <!-- Tabla de clientes -->
          <table id="tablaClientes" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Número de Documento</th>
                <th>Tipo de Documento</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Teléfono</th>
                <th>Fecha Nacimiento</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $fila): ?>
                  <tr>
                    <td><?= $fila['idcliente'] ?></td>
                    <td><?= $fila['numerodoc'] ?></td>
                    <td><?= $fila['tipodoc'] ?></td>
                    <td><?= $fila['apellidos'] ?></td>
                    <td><?= $fila['nombres'] ?></td>
                    <td><?= $fila['telefono'] ?></td>
                    <td><?= $fila['fechanac'] ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">No hay clientes registrados</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>