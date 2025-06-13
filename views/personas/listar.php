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
                  <th>Acciones</th> <!-- Nueva columna -->
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
                      <td>
                        <a href="/hotelluna/views/personas/editar.php?id=<?= urlencode($row['idpersona']) ?>" class="btn btn-primary btn-sm">
                          <i class="fas fa-edit"></i> Editar
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="text-center">No hay personas registradas</td>
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

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function() {
    $('#tablaPersonas').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
      }
    });
  });
</script>
