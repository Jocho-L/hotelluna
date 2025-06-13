<?php
date_default_timezone_set('America/Lima'); // Establece la zona horaria de Lima
include('../../includes/base.php');
require_once('../../app/config/Conexion.php');
require_once('../../app/models/Alquiler.php');
include('../../app/controllers/AlquilerController.php');

$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : null;
$habitacion = null;

if ($idhabitacion) {
  $conexion = Conexion::getConexion();
  $habitacion = obtenerHabitacionPorId($conexion, $idhabitacion);
}

if (!$idhabitacion && $habitacion) {
  $idhabitacion = $habitacion['idhabitacion'];
}

// Obtén la fecha y hora actual del servidor en formato compatible con input[type=datetime-local]
$now = date('Y-m-d\TH:i');
?>

<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Registrar</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Starter Page</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card border border-secondary shadow rounded p-4">
          <form id="formAlquiler" method="POST" enctype="multipart/form-data" action="controller.php?idhabitacion=<?= $habitacion['idhabitacion'] ?>">
            <!-- Detalles de la Habitación -->
            <div class="card mb-4 bg-light">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                  <i class="fas fa-bed"></i>
                  Detalles de la Habitación <strong>
                    <?= isset($habitacion) && $habitacion ? htmlspecialchars($habitacion['numero']) : 'No seleccionada' ?>
                  </strong>
                </h4>
              </div>
              <div class="card-body">
                <?php if ($habitacion): ?>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <p><strong>ID Habitación:</strong> <?= $habitacion['idhabitacion'] ?></p>
                      <p><strong>Piso:</strong> <?= htmlspecialchars($habitacion['piso']) ?></p>
                      <p><strong>Número de Camas:</strong> <?= htmlspecialchars($habitacion['numcamas']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <p><strong>Tipo de Habitación:</strong> <?= htmlspecialchars($habitacion['tipohabitacion']) ?></p>
                      <p><strong>Estado:</strong> <?= htmlspecialchars($habitacion['estado']) ?></p>
                      <p><strong>Precio Regular:</strong> <span class="badge badge-success">S/. <?= number_format($habitacion['precioregular'], 2) ?></span></p>
                    </div>
                  </div>
                <?php else: ?>
                  <p class="text-danger">Habitación no encontrada.</p>
                <?php endif; ?>
              </div>
            </div>

            <!-- Cliente y Acompañantes -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row align-items-end">
                  <div class="col-md-9 mb-3">
                    <label class="form-label font-weight-bold">Buscar Cliente</label>
                    <select class="form-control" id="buscar_cliente" style="width: 100%;" required></select>
                    <input type="hidden" id="idcliente" name="idcliente" />
                  </div>
                  <div class="col-md-3 mb-3">
                    <a href="/hotelluna/views/personas/registrar.php" class="btn btn-success btn-block" title="Agregar nueva persona">
                      <i class="fas fa-user-plus"></i> Agregar Persona
                    </a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label class="form-label font-weight-bold">Acompañantes</label>
                    <div id="acompanantes-container"></div>
                    <button type="button" class="btn btn-outline-primary mt-2" id="agregar-acompanante" title="Agregar acompañante">
                      <i class="fas fa-user-friends"></i> Añadir Acompañante
                    </button>
                  </div>
                </div>
                <input type="hidden" name="acompanantes_json" id="acompanantes_json">
              </div>
            </div>

            <!-- Fechas y Lugar -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Fecha de Inicio</label>
                    <input type="datetime-local" class="form-control" name="fechainicio" id="fechainicio" required readonly value="<?= $now ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Fecha de Fin</label>
                    <input type="datetime-local" class="form-control" name="fechafin" id="fechafin" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Lugar de Procedencia</label>
                    <select class="form-control" id="lugarprocedencia" name="lugarprocedencia" required>
                      <option value="">Seleccione una región</option>
                      <option value="Amazonas">Amazonas</option>
                      <option value="Áncash">Áncash</option>
                      <option value="Apurímac">Apurímac</option>
                      <option value="Arequipa">Arequipa</option>
                      <option value="Ayacucho">Ayacucho</option>
                      <option value="Cajamarca">Cajamarca</option>
                      <option value="Callao">Callao</option>
                      <option value="Cusco">Cusco</option>
                      <option value="Huancavelica">Huancavelica</option>
                      <option value="Huánuco">Huánuco</option>
                      <option value="Ica">Ica</option>
                      <option value="Junín">Junín</option>
                      <option value="La Libertad">La Libertad</option>
                      <option value="Lambayeque">Lambayeque</option>
                      <option value="Lima">Lima</option>
                      <option value="Loreto">Loreto</option>
                      <option value="Madre de Dios">Madre de Dios</option>
                      <option value="Moquegua">Moquegua</option>
                      <option value="Pasco">Pasco</option>
                      <option value="Piura">Piura</option>
                      <option value="Puno">Puno</option>
                      <option value="San Martín">San Martín</option>
                      <option value="Tacna">Tacna</option>
                      <option value="Tumbes">Tumbes</option>
                      <option value="Ucayali">Ucayali</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Modalidad de Pago</label>
                    <select class="form-control" name="modalidadpago" required>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Yape">Yape</option>
                      <option value="Plin">Plin</option>
                      <option value="Culqi">Culqi</option>
                      <option value="Deposito">Deposito</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Observaciones y Extras -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label class="form-label font-weight-bold">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3" placeholder="Escribe cualquier observación aquí..."></textarea>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Placa (opcional)</label>
                    <input type="text" class="form-control" name="placa" maxlength="30" placeholder="Ej: ABC-123">
                  </div>
                  <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="incluyedesayuno" id="incluyedesayuno" value="1">
                      <label class="form-check-label" for="incluyedesayuno">
                        <i class="fas fa-coffee"></i> Incluye desayuno
                      </label>
                    </div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Total a Pagar</label>
                    <input type="text" class="form-control" name="total" id="total" readonly>
                  </div>
                  <!-- Apartado para modificar el precio -->
                  <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">Modificar Precio</label>
                    <div class="input-group">
                      <input type="number" step="0.01" class="form-control" id="modificador_valor" placeholder="Cantidad o porcentaje">
                      <div class="input-group-append">
                        <select class="form-control" id="modificador_tipo">
                          <option value="fijo">S/. Fijo</option>
                          <option value="porcentaje">% Porcentaje</option>
                        </select>
                      </div>
                    </div>
                    <small class="form-text text-muted">Ingrese un monto fijo o porcentaje para aumentar o descontar.</small>
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" id="modificador_descuento">
                      <label class="form-check-label" for="modificador_descuento">
                        Aplicar como descuento
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botón de Envío -->
            <div class="text-center">
              <button type="submit" class="btn btn-lg btn-primary px-5">
                <i class="fas fa-check-circle"></i> Asignar Habitación
              </button>
            </div>
            <input type="hidden" name="idhabitacion" value="<?php echo htmlspecialchars($idhabitacion); ?>">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FontAwesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jQuery (requerido por Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
  $(document).ready(function() {
    $('#buscar_cliente').select2({
      theme: "classic",
      placeholder: 'Busca por nombre o DNI...',
      ajax: {
        url: '../../app/controllers/BuscarPersona.php', // Cambiado a personas
        type: 'POST',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            searchTerm: params.term // lo que se escribe en el input
          };
        },
        processResults: function(data) {
          return {
            results: $.map(data, function(item) {
              return {
                id: item.idpersona,
                text: item.numerodoc + ' - ' + item.nombres + ' ' + item.apellidos,
                nombres: item.nombres,
                apellidos: item.apellidos,
                numerodoc: item.numerodoc
              };
            })
          };
        },
        cache: true
      }
    });

    $('#buscar_cliente').on('select2:select', function(e) {
      var data = e.params.data;
      $('#idcliente').val(data.id); // Ahora guarda idpersona
      $('#cliente-info').show();

      // Consultar la fecha de nacimiento del cliente seleccionado
      $.ajax({
        url: '../../app/controllers/BuscarPersona.php',
        type: 'POST',
        dataType: 'json',
        data: { idpersona: data.id },
        success: function(res) {
          if (res && res.length > 0 && res[0].fechanacimiento) {
            let fechaNacimiento = new Date(res[0].fechanacimiento);
            let hoy = new Date();
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            let m = hoy.getMonth() - fechaNacimiento.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
              edad--;
            }
            if (edad < 18) {
              alert('El cliente seleccionado es menor de edad y no puede registrarse.');
              $('#idcliente').val('');
              $('#buscar_cliente').val(null).trigger('change');
            }
          }
        }
      });
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('#lugarprocedencia').select2({
      theme: "classic",
      placeholder: "Seleccione una región",
      allowClear: true
    });
  });
</script>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    const fechainicio = document.getElementById('fechainicio');
    const local = fechainicio.value;
    const now = new Date(local);

    // Calcular el inicio del día siguiente (00:00)
    const tomorrow = new Date(now);
    tomorrow.setDate(now.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0); // <-- Esto es clave
    const tomorrowStr = tomorrow.toISOString().slice(0, 16);

    // Máximo dos meses desde la fecha de inicio
    function addMonths(date, months) {
      let d = new Date(date);
      d.setMonth(d.getMonth() + months);
      return d;
    }
    const maxDate = addMonths(now, 2).toISOString().slice(0, 16);

    fechainicio.min = local;
    fechainicio.max = local;
    fechainicio.readOnly = true;

    // Fecha de fin: mínimo mañana a las 00:00, máximo dos meses
    const fechafin = document.getElementById('fechafin');
    fechafin.min = tomorrowStr;
    fechafin.max = maxDate;
    fechafin.value = tomorrowStr;

    // --- Calcular el total al cargar la página ---
    calcularTotal();
    // Y también si el usuario cambia la fecha de fin
    fechafin.addEventListener('change', calcularTotal);

    // --- Modificador de precio ---
    function aplicarModificador(subtotal) {
      let valor = parseFloat($('#modificador_valor').val());
      let tipo = $('#modificador_tipo').val();
      let esDescuento = $('#modificador_descuento').is(':checked');
      if (isNaN(valor) || valor === 0) return subtotal;
      let modificado = subtotal;
      if (tipo === 'fijo') {
        modificado = esDescuento ? subtotal - valor : subtotal + valor;
      } else if (tipo === 'porcentaje') {
        let monto = subtotal * (valor / 100);
        modificado = esDescuento ? subtotal - monto : subtotal + monto;
      }
      return modificado > 0 ? modificado : 0;
    }

    function calcularTotal() {
      const inicio = new Date(fechainicio.value);
      const fin = new Date(fechafin.value);
      const precio = <?= $habitacion['precioregular'] ?>;
      if (!isNaN(inicio) && !isNaN(fin) && fin > inicio) {
        const dias = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24));
        if (dias > 0) {
          let subtotal = precio * dias;
          let total = aplicarModificador(subtotal);
          document.getElementById('total').value = total.toFixed(2);
        } else {
          document.getElementById('total').value = '';
        }
      } else {
        document.getElementById('total').value = '';
      }
    }

    // Escucha cambios en el modificador
    $('#modificador_valor, #modificador_tipo, #modificador_descuento').on('input change', calcularTotal);
  });
</script>

<script>
  $(document).ready(function() {
    $('#fechafin').on('change', function() {
      const inicio = new Date($('#fechainicio').val());
      const fin = new Date($(this).val());
      const precio = <?= $habitacion['precioregular'] ?>;
      if (!isNaN(inicio) && !isNaN(fin) && fin > inicio) {
        const dias = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24));
        if (dias > 0) {
          $('#total').val((precio * dias).toFixed(2));
        } else {
          $('#total').val('');
        }
      } else {
        $('#total').val('');
      }
    });
  });
</script>

<script>
  let acompanantes = [];

  function crearSelectAcompanante(index) {
    return `
    <div class="input-group mb-2 acompanante-select-row" data-index="${index}">
      <select class="form-control select2-acompanante" name="acompanante[]" style="width: 90%;" required></select>
      <div class="input-group-append">
        <button class="btn btn-danger btn-quitar-acompanante" type="button" title="Quitar"><span>&times;</span></button>
      </div>
    </div>
    <div class="row mb-3 cuestionario-acompanante" data-index="${index}">
      <!-- Parentesco solo se mostrará si es menor de edad -->
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

  $(document).ready(function() {
    // Agrega el primer select al cargar
    let index = 0;

    function agregarNuevoAcompanante() {
      $('#acompanantes-container').append(crearSelectAcompanante(index));
      let $nuevoSelect = $('#acompanantes-container .acompanante-select-row:last .select2-acompanante');
      inicializarSelect2Acompanante($nuevoSelect);
      index++;
    }

    agregarNuevoAcompanante();

    $('#agregar-acompanante').click(function() {
      agregarNuevoAcompanante();
    });

    // Quitar acompañante
    $('#acompanantes-container').on('click', '.btn-quitar-acompanante', function() {
      const index = $(this).closest('.acompanante-select-row').data('index');
      // Elimina el select y el cuestionario con el mismo data-index
      $(this).closest('.acompanante-select-row').remove();
      $(`.cuestionario-acompanante[data-index="${index}"]`).remove();
    });

    // Al enviar el formulario, recolecta los datos completos de los acompañantes
    $('#formAlquiler').on('submit', function(e) {
      // Habilita todos los selects de tipo de huésped antes de enviar
      $('select[name="tipohuesped_acompanante[]"]').prop('disabled', false);

      // Validar cliente seleccionado
      if (!$('#idcliente').val()) {
        alert("Por favor seleccione un cliente.");
        e.preventDefault();
        return;
      }
      let acompanantesData = [];
      let idcliente = $('#idcliente').val();
      $('.acompanante-select-row').each(function() {
        let idx = $(this).data('index');
        let val = $(this).find('.select2-acompanante').val();
        if (val && val !== idcliente) {
          // Busca el cuestionario correspondiente
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
        }
      });
      $('#acompanantes_json').val(JSON.stringify(acompanantesData));
      $('button[type="submit"]').prop('disabled', true); // Desactivar el botón
    });
  });

  function obtenerHuespedesAdultos(excludeId = null) {
    let responsables = [];
    // Cliente principal
    let clienteId = $('#idcliente').val();
    let clienteText = $('#buscar_cliente').select2('data')[0]?.text || '';
    if (clienteId && clienteId !== excludeId) responsables.push({ id: clienteId, text: clienteText });
    // Todos los acompañantes seleccionados (no solo adultos)
    $('.acompanante-select-row').each(function() {
      let idx = $(this).data('index');
      let select = $(this).find('.select2-acompanante');
      let val = select.val();
      let text = select.select2('data')[0]?.text || '';
      if (val && text && val !== excludeId) {
        responsables.push({ id: val, text: text });
      }
    });
    return responsables;
  }

  // Cambia el campo parentesco a select de tipo de parentesco y muestra responsable/carta poder según corresponda
  function cambiarParentescoAMenor($row, index) {
    // Obtener el id del acompañante actual para excluirlo
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

  // Maneja el cambio de tipo de parentesco para menores
  $(document).on('change', '.parentesco-tipo-select', function() {
    let tipo = $(this).val();
    let index = $(this).data('index');
    let $row = $(this).closest('.cuestionario-acompanante');
    // Obtener el id del acompañante actual para excluirlo
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
        <label>Carta Poder (PDF/JPG)</label>
        <input type="file" class="form-control" name="cartapoder_acompanante[]" accept=".pdf,.jpg,.jpeg,.png" required>
      `);
    } else {
      $row.find('.responsable-container').html('');
      $row.find('.cartapoder-container').html('');
    }
  });

  // Inicializa select2 para acompañantes (debe estar definida)
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
                fechanac: item.fechanac // <-- importante
              };
            })
          };
        },
        cache: true
      }
    });

    // Cuando se selecciona un acompañante, calcular edad y setear tipo de huésped automáticamente
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

    // Cuando se limpia el select2, habilitar el select de tipo de huésped
    $select.on('select2:clear', function() {
      let $row = $(this).closest('.acompanante-select-row');
      let idx = $row.data('index');
      let $cuestionario = $('.cuestionario-acompanante[data-index="' + idx + '"]');
      let $tipoHuesped = $cuestionario.find('select[name="tipohuesped_acompanante[]"]');
      $tipoHuesped.val('').prop('disabled', false).trigger('change');
    });
  }

  // Detecta cambio en tipo de huésped y ajusta el campo parentesco
  $(document).on('change', 'select[name="tipohuesped_acompanante[]"]', function() {
    let tipo = $(this).val();
    let $row = $(this).closest('.cuestionario-acompanante');
    let index = $row.data('index');
    if (tipo === 'Menor de edad') {
      cambiarParentescoAMenor($row, index);
    } else {
      // Oculta el campo parentesco para adultos
      $row.find('.parentesco-container').html('');
    }
  });

  // Al agregar acompañante, inicializa select2
  function agregarNuevoAcompanante() {
    $('#acompanantes-container').append(crearSelectAcompanante(index));
    let $nuevoSelect = $('#acompanantes-container .acompanante-select-row:last .select2-acompanante');
    inicializarSelect2Acompanante($nuevoSelect);
    index++;
  }
</script>

<?php
$idpersona = isset($_POST['idcliente']) ? intval($_POST['idcliente']) : null;
include('../../includes/footer.php');
?>