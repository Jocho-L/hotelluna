<?php
include('../../includes/base.php');
include('../../app/controllers/AlquilerController.php');

$idalquiler = isset($_GET['idalquiler']) ? intval($_GET['idalquiler']) : null;
$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : null;
$alquiler = null;

global $conexion;

if ($idalquiler) {
    $alquiler = obtenerDetalleAlquilerPorId($conexion, $idalquiler);
} elseif ($idhabitacion) {
    $alquiler = obtenerDetalleAlquilerPorHabitacion($conexion, $idhabitacion);
}

// Obtener acompañantes directamente desde la base de datos
$acompanantes = [];
if ($idalquiler) {
    $sql = "SELECT
                p.nombres,
                p.apellidos,
                p.numerodoc,
                p.tipodoc,
                p.genero,
                p.fechanac,
                h.tipohuesped,
                h.parentesco,
                h.observaciones,
                h.cartapoder,
                CONCAT(rp.nombres, ' ', rp.apellidos) AS nombre_responsable
            FROM huespedes h
            INNER JOIN personas p ON h.idpersona = p.idpersona
            LEFT JOIN personas rp ON h.idresponsable = rp.idpersona
            WHERE h.idalquiler = :idalquiler";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idalquiler', $idalquiler, PDO::PARAM_INT);
    $stmt->execute();
    $acompanantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
            <div class="mb-3 d-flex justify-content-between align-items-center">
              <div>
                <a href="../index.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="pdf.php?idalquiler=<?= $alquiler['idalquiler'] ?>" class="btn btn-danger" target="_blank">
                  <i class="fas fa-file-pdf"></i> PDF
                </a>
                <?php if ($alquiler['habitacion_estado'] === 'ocupada'): ?>
                  <form method="POST" action="salida.php" class="d-inline">
                    <input type="hidden" name="idalquiler" value="<?= $alquiler['idalquiler'] ?>">
                    <button type="submit" class="btn btn-success">
                      <i class="fas fa-door-open"></i> Check-out
                    </button>
                  </form>
                <?php else: ?>
                  <span class="badge bg-success fs-6">Finalizado</span>
                <?php endif; ?>
              </div>
              <span class="badge bg-info text-dark fs-5">
                Estado: <?= htmlspecialchars(ucfirst($alquiler['habitacion_estado'])) ?>
              </span>
            </div>

            <div class="row g-4 align-items-stretch mb-4">
              <div class="col-md-6 d-flex">
                <div class="card border-primary h-100 w-100 mb-0">
                  <div class="card-header bg-primary text-white">
                    <i class="fas fa-bed"></i> Habitación <?= htmlspecialchars($alquiler['habitacion_numero']) ?>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6">
                        <p class="mb-2"><strong>ID:</strong> <?= $alquiler['idhabitacion'] ?></p>
                        <p class="mb-2"><strong>Piso:</strong> <?= htmlspecialchars($alquiler['habitacion_piso']) ?></p>
                        <p class="mb-2"><strong>Camas:</strong> <?= htmlspecialchars($alquiler['habitacion_numcamas']) ?></p>
                      </div>
                      <div class="col-6">
                        <p class="mb-2"><strong>Tipo:</strong> <?= htmlspecialchars($alquiler['habitacion_tipo']) ?></p>
                        <p class="mb-2"><strong>Precio:</strong> <span class="badge bg-warning text-dark">S/. <?= number_format($alquiler['habitacion_precio'], 2) ?></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 d-flex">
                <div class="card border-success h-100 w-100 mb-0">
                  <div class="card-header bg-success text-white">
                    <i class="fas fa-user"></i> Cliente Principal
                  </div>
                  <div class="card-body">
                    <?php if (!empty($alquiler['cliente_nombres'])): ?>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($alquiler['cliente_nombres'] . ' ' . $alquiler['cliente_apellidos']) ?></li>
                        <li class="list-group-item"><strong>DNI:</strong> <?= htmlspecialchars($alquiler['cliente_numerodoc']) ?></li>
                        <li class="list-group-item"><strong>Teléfono:</strong> <?= htmlspecialchars($alquiler['cliente_telefono']) ?></li>
                      </ul>
                    <?php else: ?>
                      <div class="alert alert-warning mb-0">No se encontraron datos del cliente principal.</div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="card mb-4">
              <div class="card-header">
                <h4>Acompañantes</h4>
              </div>
              <div class="card-body">
                <?php if (!empty($acompanantes)): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                      <thead class="table-light">
                        <tr>
                          <th>Nombre</th>
                          <th>DNI</th>
                          <th>Tipo</th>
                          <th>Género</th>
                          <th>Fecha Nac.</th>
                          <th>Parentesco</th>
                          <th>Responsable</th> <!-- Nueva columna -->
                          <th>Observaciones</th>
                          <th>Carta Poder</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($acompanantes as $a): ?>
                          <tr>
                            <td><?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?></td>
                            <td><?= htmlspecialchars($a['numerodoc']) ?></td>
                            <td><?= htmlspecialchars($a['tipohuesped']) ?></td>
                            <td><?= htmlspecialchars($a['genero']) ?></td>
                            <td><?= htmlspecialchars($a['fechanac']) ?></td>
                            <td><?= htmlspecialchars($a['parentesco']) ?></td>
                            <td><?= htmlspecialchars($a['nombre_responsable'] ?? '-') ?></td> <!-- Mostrar responsable -->
                            <td><?= htmlspecialchars($a['observaciones']) ?></td>
                            <td>
                              <?php if (!empty($a['cartapoder'])): ?>
                                <a href="/hotelluna/public/img/cartapoder/<?= urlencode($a['cartapoder']) ?>" target="_blank">Ver</a>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <p>No hay acompañantes registrados.</p>
                <?php endif; ?>
              </div>
            </div>
            <!-- Nueva card para detalles adicionales -->
            <div class="card mb-4">
              <div class="card-header bg-secondary text-white">
                <i class="fas fa-info-circle"></i> Detalles del Alquiler
              </div>
              <div class="card-body">
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
                <div>
                  <strong>Observaciones:</strong>
                  <p><?= nl2br(htmlspecialchars($alquiler['observaciones'])) ?></p>
                </div>
              </div>
            </div>
            <!-- Fin de la nueva card -->
          <?php else: ?>
            <div class="alert alert-danger">No se encontró el alquiler.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('../../includes/footer.php'); ?>