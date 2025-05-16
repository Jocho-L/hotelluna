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
          <form>
            <div class="card mb-4">
              <div class="card-header">
                <h4>Detalles de la Habitación <strong><?= htmlspecialchars($habitacion['numero']) ?></strong> </h4>
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
                <select class="form-control" id="buscar_cliente" name="idcliente" style="width: 100%;">
                </select>
              </div>

              <div class="col-md-3 d-flex align-items-end mb-4"> <!-- Agregar Cliente -->
                <a href="../clientes/registrar.php" class="btn btn-success w-100">Agregar Cliente</a>
              </div>

              <div class="col-12 mb-3" id="cliente-info" style="display: none;"> <!-- Detalles Cliente -->
                <p><strong>Nombres:</strong> <span id="nombre_cliente"></span></p>
                <p><strong>Apellidos:</strong> <span id="apellido_cliente"></span></p>
                <input type="hidden" name="idcliente" id="idcliente">
              </div>

              <div class="col-md-9 mb-4">  <!-- Acompañante -->
                <label class="form-label">Acompañante</label>
                <!-- Botón para abrir modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
                  +
                </button>
              </div>

              <!-- Modal -->
              <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="content"> <!-- Contenido principal -->
                          <?php
                            include('../huespedes/registrar.php');
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

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

              <div class="col-12"> <!-- BTN Asignar Habitación -->
                <input type="hidden" name="idcliente" value="">
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
        url: '../../app/controllers/BuscarCliente.php',
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
                id: item.idcliente,
                text: item.dni + ' - ' + item.nombres + ' ' + item.apellidos,
                nombres: item.nombres,
                apellidos: item.apellidos
              };
            })
          };
        },
        cache: true
      }
    });

    $('#buscar_cliente').on('select2:select', function(e) {
      var data = e.params.data;
      $('#nombre_cliente').text(data.nombres);
      $('#apellido_cliente').text(data.apellidos);
      $('#idcliente').val(data.id);
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
  document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const maxDate = new Date(today);
    maxDate.setMonth(maxDate.getMonth() + 2);

    const formatDateTimeLocal = (date) => {
      const pad = (n) => n.toString().padStart(2, '0');
      return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    };

    // Establece la fecha actual como inicio
    const fechainicioInput = document.querySelector('input[name="fechainicio"]');
    fechainicioInput.value = formatDateTimeLocal(today);

    // Restringe la fecha de fin
    const fechafinInput = document.getElementById('fechafin');
    fechafinInput.min = formatDateTimeLocal(tomorrow);
    fechafinInput.max = formatDateTimeLocal(maxDate);
  });
</script>


<?php
include('../../includes/footer.php');
?>