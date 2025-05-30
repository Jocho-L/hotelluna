<?php
require_once '../../app/config/Conexion.php';

// Obtenemos la instancia PDO
$conexion = Conexion::getConexion();

// Consulta SQL para obtener las personas
$sql = "SELECT idpersona, tipodoc, numerodoc, apellidos, nombres, genero, telefono, fechanac FROM personas";

try {
  $stmt = $conexion->prepare($sql);
  $stmt->execute();
  $personas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  echo "Error al ejecutar la consulta: " . $e->getMessage();
  $personas = [];
}
?>

<!-- Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Personas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Personas</a></li>
          <li class="breadcrumb-item active">Listar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div id="contenido" class="container" style="flex-grow: 1; padding: 20px;">
          <h2>Lista de Personas</h2>
           <a href="personas/registrar.php" class="btn btn-success mb-3">
            <i class="fas fa-user-plus"></i> Agregar Persona
          </a>
          <table id="tablaPersonas" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tipo Doc</th>
                <th>Número Doc</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Género</th>
                <th>Teléfono</th>
                <th>Fecha Nac.</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($personas)): ?>
                <?php foreach ($personas as $row): ?>
                  <tr>
                    <td><?= $row['idpersona'] ?></td>
                    <td><?= $row['tipodoc'] ?></td>
                    <td><?= $row['numerodoc'] ?></td>
                    <td><?= $row['apellidos'] ?></td>
                    <td><?= $row['nombres'] ?></td>
                    <td><?= $row['genero'] ?></td>
                    <td><?= $row['telefono'] ?></td>
                    <td><?= $row['fechanac'] ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center">No hay personas registradas</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
