<?php
include('../../includes/base.php');
include('../../app/controllers/AlquilerController.php');
include('../../app/controllers/LoginController.php');

$usuario_nombre = $_SESSION['nombres'] . ' ' . $_SESSION['apellidos'];

// Depuración temporal
error_log('usuario_nombre en postsalida: ' . print_r($_SESSION['usuario_nombre'] ?? 'NO DEFINIDO', true));
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
              <a href="../index.php" class="btn btn-secondary">Volver</a>
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
            <?php if ($alquiler['habitacion_estado'] === 'mantenimiento'): ?>
              <form method="POST" action="estado_posobservacion.php" class="mb-3" id="form-cambiar-estado">
                <div class="mb-3">
                  <strong>Posobservaciones:</strong>
                  <textarea id="posobservaciones" name="posobservaciones" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" name="idhabitacion" value="<?= $alquiler['idhabitacion'] ?>">
                <input type="hidden" name="idalquiler" value="<?= $alquiler['idalquiler'] ?>">
                <input type="hidden" name="nuevo_estado" value="disponible">
                <button type="submit" class="btn btn-warning">Guardar y Cambiar a Disponible</button>
              </form>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  const textarea = document.getElementById('posobservaciones');
                  if (textarea) {
                    const usuario = <?= json_encode($usuario_nombre) ?>;
                    const now = new Date();
                    const fecha = now.toISOString().slice(0, 10);
                    const hora = now.toTimeString().slice(0,5);
                    const encabezado = `[${usuario} - ${fecha} ${hora}]: Se cambió el estado de mantenimiento a disponible. `;
                    textarea.value = encabezado;
                    textarea.focus();
                    textarea.setSelectionRange(textarea.value.length, textarea.value.length);
                  }
                });
              </script>
            <?php else: ?>
              <div class="mb-3">
                <strong>Posobservaciones:</strong>
                <form method="POST" action="guardar_posobservacion.php">
                  <textarea id="posobservaciones" name="posobservaciones" class="form-control" rows="4" required></textarea>
                  <input type="hidden" name="idalquiler" value="<?= $alquiler['idalquiler'] ?>">
                  <button type="submit" class="btn btn-primary mt-2">Guardar Posobservación</button>
                </form>
              </div>
            <?php endif; ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
              const textarea = document.getElementById('posobservaciones');
              if (textarea) {
                const usuario = <?= json_encode($usuario_nombre) ?>;
                const now = new Date();
                const fecha = now.toISOString().slice(0, 10);
                const hora = now.toTimeString().slice(0,5);
                const encabezado = `[${usuario} - ${fecha} ${hora}]: `;
                textarea.value = encabezado;
                textarea.focus();
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);

                // Evita borrar o modificar el encabezado
                textarea.addEventListener('keydown', function(e) {
                  // Evita borrar, mover el cursor o seleccionar antes del encabezado
                  if (
                    (e.key === 'Backspace' && textarea.selectionStart <= encabezado.length) ||
                    (e.key === 'ArrowLeft' && textarea.selectionStart <= encabezado.length) ||
                    (e.key === 'Delete' && textarea.selectionStart < encabezado.length)
                  ) {
                    e.preventDefault();
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                  // Evita seleccionar el encabezado y reemplazarlo
                  if (
                    (e.key.length === 1 || e.key === 'Enter') &&
                    textarea.selectionStart < encabezado.length
                  ) {
                    e.preventDefault();
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                });

                textarea.addEventListener('paste', function(e) {
                  // Evita pegar antes o sobre el encabezado
                  if (textarea.selectionStart < encabezado.length) {
                    e.preventDefault();
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                });

                textarea.addEventListener('cut', function(e) {
                  // Evita cortar el encabezado
                  if (
                    textarea.selectionStart < encabezado.length ||
                    textarea.selectionEnd <= encabezado.length
                  ) {
                    e.preventDefault();
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                });

                textarea.addEventListener('select', function(e) {
                  // Evita seleccionar el encabezado
                  if (textarea.selectionStart < encabezado.length) {
                    textarea.setSelectionRange(encabezado.length, textarea.selectionEnd);
                  }
                });

                textarea.addEventListener('click', function(e) {
                  // Evita colocar el cursor antes del encabezado
                  if (textarea.selectionStart < encabezado.length) {
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                });

                textarea.addEventListener('blur', function() {
                  // Si el encabezado fue modificado, restaurarlo
                  if (!textarea.value.startsWith(encabezado)) {
                    textarea.value = encabezado;
                    textarea.setSelectionRange(encabezado.length, encabezado.length);
                  }
                });
              }
            });
            </script>
          <?php else: ?>
            <div class="alert alert-danger">No se encontró el alquiler.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
