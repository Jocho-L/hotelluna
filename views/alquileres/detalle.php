<?php
include('../../includes/base.php');
include('../../app/controllers/AlquilerController.php');

// Obtener el idhabitacion desde GET
$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : null;
$alquiler = null;

// Debes tener una función que obtenga el alquiler por idhabitacion
if ($idhabitacion) {
    // Ejemplo: ajusta el nombre de la función según tu controlador
    global $conexion; // Si la conexión está definida como global en el controlador
    $alquiler = obtenerDetalleAlquilerPorHabitacion($conexion, $idhabitacion);
}

// Si no existe, $alquiler será null y la vista mostrará el mensaje de error
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detalle de Alquiler</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Detalle Alquiler</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card border border-secondary shadow rounded p-4">
          <?php if ($alquiler): ?>
            <div class="mb-3">
              <a href="index.php" class="btn btn-secondary">Volver</a>
              <a href="pdf.php?idalquiler=<?= $alquiler['idalquiler'] ?>" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Generar PDF
              </a>
             <?php if ($alquiler['habitacion_estado'] === 'ocupada'): ?>
                <form method="POST" action="salida.php" class="d-inline">
                  <input type="hidden" name="idalquiler" value="<?= $alquiler['idalquiler'] ?>">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-door-open"></i> Check-out
                  </button>
                </form>
              <?php else: ?>
                <span class="badge badge-success">Finalizado</span>
              <?php endif; ?>
            </div>
            <div class="card mb-4">
              <div class="card-header">
                <h4>Habitación <strong><?= htmlspecialchars($alquiler['habitacion_numero']) ?></strong></h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <p><strong>ID Habitación:</strong> <?= $alquiler['idhabitacion'] ?></p>
                    <p><strong>Piso:</strong> <?= htmlspecialchars($alquiler['habitacion_piso']) ?></p>
                    <p><strong>Número de Camas:</strong> <?= htmlspecialchars($alquiler['habitacion_numcamas']) ?></p>
                  </div>
                  <div class="col-md-6 mb-3">
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($alquiler['habitacion_tipo']) ?></p>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($alquiler['habitacion_estado']) ?></p>
                    <p><strong>Precio Regular:</strong> S/. <?= number_format($alquiler['habitacion_precio'], 2) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="card mb-4">
              <div class="card-header">
                <h4>Cliente Principal</h4>
              </div>
              <div class="card-body">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($alquiler['cliente_nombres'] . ' ' . $alquiler['cliente_apellidos']) ?></p>
                <p><strong>DNI:</strong> <?= htmlspecialchars($alquiler['cliente_numerodoc']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($alquiler['cliente_telefono']) ?></p>
              </div>
            </div>
            <div class="card mb-4">
              <div class="card-header">
                <h4>Acompañantes</h4>
              </div>
              <div class="card-body">
                <?php if (!empty($alquiler['acompanantes'])): ?>
                  <ul>
                    <?php foreach ($alquiler['acompanantes'] as $a): ?>
                      <li><?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?> (<?= htmlspecialchars($a['numerodoc']) ?>)</li>
                    <?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <p>No hay acompañantes registrados.</p>
                <?php endif; ?>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <p><strong>Fecha de Inicio:</strong> <?= htmlspecialchars($alquiler['fechahorainicio']) ?></p>
                <p><strong>Fecha de Fin:</strong> <?= htmlspecialchars($alquiler['fechahorafin']) ?></p>
                <p><strong>Lugar de Procedencia:</strong> <?= htmlspecialchars($alquiler['lugarprocedencia']) ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Modalidad de Pago:</strong> <?= htmlspecialchars($alquiler['modalidadpago']) ?></p>
                <p><strong>Total Pagado:</strong> S/. <?= number_format($alquiler['valoralquiler'], 2) ?></p>
                <p><strong>Incluye desayuno:</strong> <?= $alquiler['incluyedesayuno'] ? 'Sí' : 'No' ?></p>
              </div>
            </div>
            <div class="mb-3">
              <strong>Observaciones:</strong>
              <p><?= nl2br(htmlspecialchars($alquiler['observaciones'])) ?></p>
            </div>
          <?php else: ?>
            <div class="alert alert-danger">No se encontró el alquiler.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('../../includes/footer.php'); ?>