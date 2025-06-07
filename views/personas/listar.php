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

// Definir títulos para la vista
$titulo = "Personas";
$subtitulo = "Lista de Personas";
?>

<!-- Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Título de la página' ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item active"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Actual' ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- Contenido principal -->
        <div class="card shadow">
          <div class="card-header">
            <h3 class="card-title"><?= isset($subtitulo) ? htmlspecialchars($subtitulo) : 'Subtítulo' ?></h3>
            <a href="/hotelluna/views/personas/registrar.php" class="btn btn-success float-right">
              <i class="fas fa-user-plus"></i> Agregar Persona
            </a>
          </div>
          <div class="card-body">
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
                      <td><?= htmlspecialchars($row['idpersona']) ?></td>
                      <td><?= htmlspecialchars($row['tipodoc']) ?></td>
                      <td><?= htmlspecialchars($row['numerodoc']) ?></td>
                      <td><?= htmlspecialchars($row['apellidos']) ?></td>
                      <td><?= htmlspecialchars($row['nombres']) ?></td>
                      <td><?= htmlspecialchars($row['genero']) ?></td>
                      <td><?= htmlspecialchars($row['telefono']) ?></td>
                      <td><?= htmlspecialchars($row['fechanac']) ?></td>
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
</div>
