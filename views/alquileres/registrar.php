<?php
include('../../includes/base.php');
include('../../app/controllers/AlquilerController.php');
/* include('../../controllers/BuscarCliente.php'); */
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
          <form id="formAlquiler" method="POST" action="controller.php?idhabitacion=<?= $habitacion['idhabitacion'] ?>">
            <div class="card mb-4">
              <div class="card-header">
                <h4>Detalles de la Habitación <strong>
                    <?= isset($habitacion) && $habitacion ? htmlspecialchars($habitacion['numero']) : 'No seleccionada' ?>
                  </strong></h4>
              </div>
              <div class="card-body"> <!-- Detalles habitacion -->
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
                      <p><strong>Precio Regular:</strong> S/. <?= number_format($habitacion['precioregular'], 2) ?></p>
                    </div>
                  </div>
                <?php else: ?>
                  <p class="text-danger">Habitación no encontrada.</p>
                <?php endif; ?>
              </div>

            </div>


            <div class="row">

              <div class="col-md-9 mb-4"> <!-- Buscar Cliente -->
                <label class="form-label">Buscar Cliente</label>
                <select class="form-control" id="buscar_cliente" style="width: 100%;"></select>
                <input type="hidden" id="idcliente" name="idcliente" />
              </div>

              <div class="col-md-3 d-flex align-items-end mb-4"> <!-- Agregar Cliente -->
                <a href="../clientes/registrar.php" class="btn btn-success w-100">Agregar Cliente</a>
              </div>

              <div class="col-md-9 mb-4">
                <label class="form-label">Acompañantes</label>
                <div id="acompanantes-container">
                  <!-- Aquí se agregan los selects dinámicamente -->
                </div>
                <button type="button" class="btn btn-primary mt-2" id="agregar-acompanante">+</button>
              </div>
              <input type="hidden" name="acompanantes_json" id="acompanantes_json">

              <div class="col-md-6 mb-3"> <!-- Fecha de Inicio -->
                <label class="form-label">Fecha de Inicio</label>
                <input type="text" class="form-control" name="fechainicio" readonly>
              </div>

              <div class="col-md-6 mb-3"> <!-- Fecha de Fin -->
                <label class="form-label">Fecha de Fin</label>
                <input type="datetime-local" class="form-control" name="fechafin" id="fechafin" required>
              </div>

              <div class="col-md-6 mb-3"> <!-- Lugar de Procedencia -->
                <label class="form-label">Lugar de Procedencia</label>
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

              <div class="col-md-6 mb-3"> <!-- Modalidad de Pago -->
                <label class="form-label">Modalidad de Pago</label>
                <select class="form-control" name="modalidadpago" required>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Tarjeta">Tarjeta</option>
                  <option value="Transferencia">Transferencia</option>
                </select>
              </div>

              <div class="col-md-12 mb-3"> <!-- Observaciones -->
                <label class="form-label">Observaciones</label>
                <textarea class="form-control" name="observaciones" rows="4"
                  placeholder="Escribe cualquier observación aquí..."></textarea>
              </div>

              <div class="col-md-6 mb-3"> <!-- Total a Pagar -->
                <label class="form-label">Total a Pagar</label>
                <input type="text" class="form-control" name="total" id="total" readonly>
              </div>

              <!-- Incluye desayuno -->
              <div class="col-md-6 mb-3 d-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="incluyedesayuno" id="incluyedesayuno" value="1">
                  <label class="form-check-label" for="incluyedesayuno">
                    Incluye desayuno
                  </label>
                </div>
              </div>

              <div class="col-12"> <!-- BTN Asignar Habitación -->
                <button type="submit" class="btn btn-primary">Asignar Habitación</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

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
  function calcularTotal() {
    const inicio = new Date(document.querySelector('input[name="fechainicio"]').value);
    const fin = new Date(document.getElementById('fechafin').value);
    const dias = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24));
    const precio = <?= $habitacion['precioregular'] ?>;
    if (dias > 0) {
      document.getElementById('total').value = (precio * dias).toFixed(2);
    }
  }

  document.getElementById('fechafin').addEventListener('change', calcularTotal);
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
    $(this).closest('.acompanante-select-row').remove();
  });

  // Al enviar el formulario, recolecta los idpersona seleccionados
  $('#formAlquiler').on('submit', function(e) {
    // Validar cliente seleccionado
    if (!$('#idcliente').val()) {
      alert("Por favor seleccione un cliente.");
      e.preventDefault();
      return;
    }
    let ids = [];
    let idcliente = $('#idcliente').val();
    $('.select2-acompanante').each(function() {
      let val = $(this).val();
      // Evita agregar el cliente principal como acompañante
      if (val && val !== idcliente) ids.push(val);
    });
    $('#acompanantes_json').val(JSON.stringify(ids));
    $('button[type="submit"]').prop('disabled', true); // Desactivar el botón
  });
});
</script>

<?php
$idpersona = isset($_POST['idcliente']) ? intval($_POST['idcliente']) : null;
include('../../includes/footer.php');
?>