<?php
include('../../includes/base.php');
include('../../app/controllers/AlquilerController.php');
require_once('../../app/config/Conexion.php');
$conexion = Conexion::getConexion();

$idalquiler = isset($_GET['idalquiler']) ? intval($_GET['idalquiler']) : null;
if (!$idalquiler) {
    // Si solo tienes idhabitacion, busca el alquiler activo
    $idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : null;
    if ($idhabitacion) {
        $stmt = $conexion->prepare("SELECT idalquiler FROM alquileres WHERE idhabitacion = ? AND fechahorafin IS NULL ORDER BY idalquiler DESC LIMIT 1");
        $stmt->execute([$idhabitacion]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idalquiler = $row ? $row['idalquiler'] : null;
    }
}

$huespedes = [];
if ($idalquiler) {
    $stmt = $conexion->prepare("
        SELECT h.*, p.nombres, p.apellidos, p.numerodoc, p.fechanac
        FROM huespedes h
        JOIN personas p ON h.idpersona = p.idpersona
        WHERE h.idalquiler = ?
    ");
    $stmt->execute([$idalquiler]);
    $huespedes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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
                h.idhuesped,
                h.fechasalida,
                h.fechaentrada,
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
                <?php
                // Calcula si hay huéspedes sin fechasalida
                $puede_checkout_general = false;
                foreach ($acompanantes as $a) {
                    if (empty($a['fechasalida'])) {
                        $puede_checkout_general = true;
                        break;
                    }
                }
                ?>
                <?php if ($alquiler['habitacion_estado'] === 'ocupada' && $puede_checkout_general): ?>
                  <form id="form-checkout-todos" method="POST" class="d-inline">
                    <input type="hidden" name="accion" value="checkout_todos">
                    <input type="hidden" name="idalquiler" value="<?= $alquiler['idalquiler'] ?>">
                    <button type="submit" class="btn btn-success">
                      <i class="fas fa-door-open"></i> Check-out
                    </button>
                  </form>
                <?php elseif ($alquiler['habitacion_estado'] !== 'ocupada'): ?>
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
                          <th>Responsable</th>
                          <th>Observaciones</th>
                          <th>Carta Poder</th>
                          <th><strong>Fecha Entrada</strong></th> <!-- Nueva columna -->
                          <th><strong>Fecha Salida</strong></th>  <!-- Nueva columna -->
                          <th>Acciones</th>
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
                            <td><?= htmlspecialchars($a['nombre_responsable'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($a['observaciones']) ?></td>
                            <td>
                              <?php if (!empty($a['cartapoder'])): ?>
                                <a href="/hotelluna/public/img/cartapoder/<?= urlencode($a['cartapoder']) ?>" target="_blank">Ver</a>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($a['fechaentrada'] ?? '-') ?></td> <!-- Mostrar fechaentrada -->
                            <td><?= htmlspecialchars($a['fechasalida'] ?? '-') ?></td>   <!-- Mostrar fechasalida -->
                            <td>
                              <?php if (empty($a['fechasalida'])): ?>
                                <form method="POST" class="form-checkout-huesped" style="display:inline;">
                                  <input type="hidden" name="accion" value="checkout_huesped">
                                  <input type="hidden" name="idhuesped" value="<?= $a['idhuesped'] ?>">
                                  <input type="hidden" name="idalquiler" value="<?= $idalquiler ?>">
                                  <button type="submit" class="btn btn-sm btn-outline-success">Check-out</button>
                                </form>
                              <?php else: ?>
                                <span class="badge bg-secondary">Check-out: <?= htmlspecialchars($a['fechasalida']) ?></span>
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
            <!-- Zona de Acompañantes -->
            <div class="card mb-4">
              <div class="card-header bg-info text-white">
                <strong>Acompañantes registrados</strong>
              </div>
              <div class="card-body">
                <form id="formAcompanantes" method="POST" enctype="multipart/form-data" action="detalle.php?idalquiler=<?= $idalquiler ?>">
                  <input type="hidden" name="idalquiler" value="<?= $idalquiler ?>">
                  <div id="acompanantes-container"></div>
                  <button type="button" class="btn btn-outline-primary mt-2" id="agregar-acompanante" title="Agregar acompañante">
                    <i class="fas fa-user-friends"></i> Añadir Acompañante
                  </button>
                  <input type="hidden" name="acompanantes_json" id="acompanantes_json">
                  <button type="submit" class="btn btn-success mt-2">Guardar cambios</button>
                </form>
              </div>
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

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let index = 0;

function obtenerHuespedesAdultos(excludeId = null) {
  let responsables = [];
  let idsIncluidos = {};

  // Usa todos los adultos de la habitación, no solo los del alquiler actual
  if (typeof HUESPEDES_ADULTOS_TODOS !== 'undefined') {
    HUESPEDES_ADULTOS_TODOS.forEach(function(a) {
      if (a.id && a.id !== excludeId) {
        responsables.push({ id: a.id, text: a.text });
        idsIncluidos[a.id] = true;
      }
    });
  }

  // Cliente principal del formulario (si no está ya)
  let clienteId = $('#idcliente').val();
  let clienteText = $('#cliente-nombre').val() || '';
  if (clienteId && !idsIncluidos[clienteId] && clienteId !== excludeId) {
    responsables.push({ id: clienteId, text: clienteText });
    idsIncluidos[clienteId] = true;
  }

  // Acompañantes seleccionados en el formulario (si no están ya)
  $('.acompanante-select-row').each(function() {
    let select = $(this).find('.select2-acompanante');
    let val = select.val();
    let text = '';
    if (select.data('select2')) {
      text = select.select2('data')[0]?.text || '';
    }
    if (val && text && !idsIncluidos[val] && val !== excludeId) {
      responsables.push({ id: val, text: text });
      idsIncluidos[val] = true;
    }
  });

  return responsables;
}

function crearSelectAcompanante(index) {
  return `
    <div class="input-group mb-2 acompanante-select-row" data-index="${index}">
      <select class="form-control select2-acompanante" name="acompanante[]" style="width: 90%;" required></select>
      <div class="input-group-append">
        <button class="btn btn-danger btn-quitar-acompanante" type="button" title="Quitar"><span>&times;</span></button>
      </div>
    </div>
    <div class="row mb-3 cuestionario-acompanante" data-index="${index}">
      <div class="col-md-4 parentesco-container"></div>
      <div class="col-md-4">
        <label>Tipo de Huésped</label>
        <select class="form-control" name="tipohuesped_acompanante[]" required>
          <option value="">Seleccione</option>
          <option value="Adulto">Adulto</option>
          <option value="Menor de edad">Menor de edad</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Observaciones</label>
        <input type="text" class="form-control" name="observaciones_acompanante[]" maxlength="50" placeholder="Observaciones">
      </div>
    </div>
  `;
}

function inicializarSelect2Acompanante($select) {
  $select.select2({
    theme: "classic",
    placeholder: 'Busca por nombre o DNI...',
    ajax: {
      url: '../../app/controllers/BuscarPersona.php',
      type: 'POST',
      dataType: 'json',
      delay: 250,
      data: function(params) {
        return { searchTerm: params.term };
      },
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
            return {
              id: item.idpersona,
              text: item.numerodoc + ' - ' + item.nombres + ' ' + item.apellidos,
              fechanac: item.fechanac
            };
          })
        };
      },
      cache: true
    }
  });

  $select.on('select2:select', function(e) {
    let data = e.params.data;
    let $row = $(this).closest('.acompanante-select-row');
    let idx = $row.data('index');
    let $cuestionario = $('.cuestionario-acompanante[data-index="' + idx + '"]');
    let $tipoHuesped = $cuestionario.find('select[name="tipohuesped_acompanante[]"]');

    if (data.fechanac) {
      let fechaNacimiento = new Date(data.fechanac);
      let hoy = new Date();
      let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
      let m = hoy.getMonth() - fechaNacimiento.getMonth();
      if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
      }
      if (edad < 18) {
        $tipoHuesped.val('Menor de edad').prop('disabled', true);
        setTimeout(function() {
          $tipoHuesped.trigger('change');
        }, 0);
      } else {
        $tipoHuesped.val('Adulto').prop('disabled', true);
        setTimeout(function() {
          $tipoHuesped.trigger('change');
        }, 0);
      }
    } else {
      $tipoHuesped.val('').prop('disabled', false);
    }
  });

  $select.on('select2:clear', function() {
    let $row = $(this).closest('.acompanante-select-row');
    let idx = $row.data('index');
    let $cuestionario = $('.cuestionario-acompanante[data-index="' + idx + '"]');
    let $tipoHuesped = $cuestionario.find('select[name="tipohuesped_acompanante[]"]');
    $tipoHuesped.val('').prop('disabled', false).trigger('change');
  });
}

function cambiarParentescoAMenor($row, index) {
  let acompananteId = $(`.acompanante-select-row[data-index="${index}"] .select2-acompanante`).val();
  let adultos = obtenerHuespedesAdultos(acompananteId);
  let options = adultos.map(a => `<option value="${a.id}">${a.text}</option>`).join('');
  let parentescoHtml = `
    <label>Parentesco</label>
    <select class="form-control parentesco-tipo-select" name="parentesco_tipo_acompanante[]" required data-index="${index}">
      <option value="">Seleccione parentesco</option>
      <option value="directo">Familiar directo</option>
      <option value="indirecto">Familiar indirecto</option>
    </select>
    <div class="responsable-container mt-2"></div>
    <div class="cartapoder-container mt-2"></div>
  `;
  $row.find('.parentesco-container').html(parentescoHtml);
}

$(document).on('change', '.parentesco-tipo-select', function() {
  let tipo = $(this).val();
  let index = $(this).data('index');
  let $row = $(this).closest('.cuestionario-acompanante');
  let acompananteId = $(`.acompanante-select-row[data-index="${index}"] .select2-acompanante`).val();
  let adultos = obtenerHuespedesAdultos(acompananteId);
  let options = adultos.map(a => `<option value="${a.id}">${a.text}</option>`).join('');
  let responsableHtml = `
    <label>Responsable</label>
    <select class="form-control" name="parentesco_acompanante[]" required>
      <option value="">Seleccione responsable</option>
      ${options}
    </select>
  `;
  if (tipo === 'directo') {
    $row.find('.responsable-container').html(responsableHtml);
    $row.find('.cartapoder-container').html('');
  } else if (tipo === 'indirecto') {
    $row.find('.responsable-container').html(responsableHtml);
    $row.find('.cartapoder-container').html(`
      <label> Autorización firmada por los padres (PDF/JPG)</label>
      <input type="file" class="form-control" name="cartapoder_acompanante[]" accept=".pdf,.jpg,.jpeg,.png" required>
    `);
  } else {
    $row.find('.responsable-container').html('');
    $row.find('.cartapoder-container').html('');
  }
});

$(document).on('change', 'select[name="tipohuesped_acompanante[]"]', function() {
  let tipo = $(this).val();
  let $row = $(this).closest('.cuestionario-acompanante');
  let index = $row.data('index');
  if (tipo === 'Menor de edad') {
    cambiarParentescoAMenor($row, index);
  } else {
    $row.find('.parentesco-container').html('');
  }
});

$(document).ready(function() {
  // Agrega el primer select solo si lo deseas, o deja vacío
  // agregarNuevoAcompanante();

  $('#agregar-acompanante').click(function() {
    $('#acompanantes-container').append(crearSelectAcompanante(index));
    let $nuevoSelect = $('#acompanantes-container .acompanante-select-row:last .select2-acompanante');
    inicializarSelect2Acompanante($nuevoSelect);
    index++;
  });

  // Quitar acompañante
  $('#acompanantes-container').on('click', '.btn-quitar-acompanante', function() {
    const idx = $(this).closest('.acompanante-select-row').data('index');
    $(this).closest('.acompanante-select-row').remove();
    $(`.cuestionario-acompanante[data-index="${idx}"]`).remove();
  });

  $('#formAcompanantes').on('submit', function(e) {
    let acompanantesData = [];
    $('.acompanante-select-row').each(function() {
      let idx = $(this).data('index');
      let val = $(this).find('.select2-acompanante').val();
      let $cuestionario = $('.cuestionario-acompanante[data-index="' + idx + '"]');
      let tipohuesped = $cuestionario.find('select[name="tipohuesped_acompanante[]"]').val();
      let observaciones = $cuestionario.find('input[name="observaciones_acompanante[]"]').val();
      let parentesco_tipo = null;
      let parentesco_responsable = null;
      if (tipohuesped === 'Menor de edad') {
        parentesco_tipo = $cuestionario.find('select[name="parentesco_tipo_acompanante[]"]').val() || null;
        parentesco_responsable = $cuestionario.find('select[name="parentesco_acompanante[]"]').val() || null;
      }
      acompanantesData.push({
        idpersona: val,
        tipohuesped: tipohuesped,
        observaciones: observaciones,
        parentesco_tipo: parentesco_tipo,
        parentesco_responsable: parentesco_responsable
      });
    });
    $('#acompanantes_json').val(JSON.stringify(acompanantesData));
    $('button[type="submit"]').prop('disabled', true);
  });
});

$(document).on('submit', '#form-checkout-todos', function(e) {
    e.preventDefault();
    if (!confirm('¿Seguro que deseas hacer check-out de TODOS los huéspedes que aún no han salido?')) return;

    var $form = $(this);
    $.post(window.location.href, $form.serialize(), function() {
        // Recarga solo la parte de la tabla y el estado
        location.reload(); // O puedes usar AJAX para recargar solo la tabla si prefieres
    });
});

$(document).on('submit', '.form-checkout-huesped', function(e) {
    e.preventDefault();
    if (!confirm('¿Seguro que desea hacer check-out de este huésped?')) return;

    var $form = $(this);
    $.post(window.location.href, $form.serialize(), function() {
        location.reload();
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'checkout_huesped') {
    $idhuesped = intval($_POST['idhuesped']);
    $stmt = $conexion->prepare("UPDATE huespedes SET fechasalida = NOW() WHERE idhuesped = ?");
    $stmt->execute([$idhuesped]);
    $idalquiler = isset($_POST['idalquiler']) ? intval($_POST['idalquiler']) : 0;
    header("Location: detalle.php?idalquiler=$idalquiler");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'checkout_todos') {
    $idalquiler = intval($_POST['idalquiler']);
    // Actualiza huéspedes sin fechasalida
    $stmt = $conexion->prepare("UPDATE huespedes SET fechasalida = NOW() WHERE idalquiler = ? AND fechasalida IS NULL");
    $stmt->execute([$idalquiler]);
    // Cambia el estado de la habitación a 'mantenimiento'
    $stmt = $conexion->prepare("SELECT idhabitacion FROM alquileres WHERE idalquiler = ?");
    $stmt->execute([$idalquiler]);
    $idhabitacion = $stmt->fetchColumn();
    if ($idhabitacion) {
        $stmt = $conexion->prepare("UPDATE habitaciones SET estado = 'mantenimiento' WHERE idhabitacion = ?");
        $stmt->execute([$idhabitacion]);
    }
    header("Location: detalle.php?idalquiler=$idalquiler");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idalquiler'])) {
    $idalquiler = intval($_POST['idalquiler']);
    $acompanantes = $_POST['acompanante'] ?? [];
    $tipos = $_POST['tipohuesped_acompanante'] ?? [];
    $parentescos = $_POST['parentesco_acompanante'] ?? [];
    $observs = $_POST['observaciones_acompanante'] ?? [];

    foreach ($acompanantes as $i => $idpersona) {
        // Verifica si ya existe ese acompañante para este alquiler
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM huespedes WHERE idalquiler = ? AND idpersona = ?");
        $stmt->execute([$idalquiler, $idpersona]);
        if ($stmt->fetchColumn() == 0) {
            $tipo = $tipos[$i] ?? 'Adulto';
            $obs = $observs[$i] ?? '';
            $parentesco = $parentescos[$i] ?? null;
            $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped, observaciones, parentesco, fechaentrada) VALUES (?, ?, ?, ?, ?, NOW())")
                ->execute([$idalquiler, $idpersona, $tipo, $obs, $parentesco]);
        }
    }
    // Recarga la página para ver los cambios
    header("Location: detalle.php?idalquiler=$idalquiler&actualizado=1");
    exit;
}
?>
<input type="hidden" id="idcliente" value="<?= $alquiler['idcliente'] ?? '' ?>">
<input type="hidden" id="cliente-nombre" value="<?= htmlspecialchars(($alquiler['cliente_nombres'] ?? '') . ' ' . ($alquiler['cliente_apellidos'] ?? '')) ?><?php
// Prepara un array de huéspedes adultos ya registrados
$huespedes_adultos = [];
foreach ($huespedes as $h) {
    // Considera "Adulto" o "cliente" como adultos
    if (in_array($h['tipohuesped'], ['Adulto', 'cliente'])) {
        $huespedes_adultos[] = [
            'id' => $h['idpersona'],
            'text' => $h['nombres'] . ' ' . $h['apellidos'] . ' (' . $h['numerodoc'] . ')'
        ];
    }
}
?>
<script>
const HUESPEDES_ADULTOS_REGISTRADOS = <?= json_encode($huespedes_adultos) ?>;
</script>

<?php
// Obtener todos los huéspedes adultos de la habitación (de todos los alquileres de esa habitación)
$huespedes_adultos = [];
if (isset($alquiler['idhabitacion'])) {
    $stmt = $conexion->prepare("
        SELECT DISTINCT p.idpersona, p.nombres, p.apellidos, p.numerodoc
        FROM alquileres a
        INNER JOIN huespedes h ON a.idalquiler = h.idalquiler
        INNER JOIN personas p ON h.idpersona = p.idpersona
        WHERE a.idhabitacion = :idhabitacion
        AND h.tipohuesped IN ('Adulto', 'cliente')
    ");
    $stmt->bindParam(':idhabitacion', $alquiler['idhabitacion'], PDO::PARAM_INT);
    $stmt->execute();
    $huespedes_adultos = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $h) {
        $huespedes_adultos[] = [
            'id' => $h['idpersona'],
            'text' => $h['nombres'] . ' ' . $h['apellidos'] . ' (' . $h['numerodoc'] . ')'
        ];
    }
}
?>
<script>
const HUESPEDES_ADULTOS_TODOS = <?= json_encode($huespedes_adultos) ?>;
</script>