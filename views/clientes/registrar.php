<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->

<?php
include('../../includes/base.php');
include('../../app/controllers/ClienteController.php');
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Clientes</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Clientes</a></li>
          <li class="breadcrumb-item active">Registrar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="content">
          <!-- Contenido principal -->
          <div class="container content" style="flex-grow: 1; padding: 20px;">
            <h2>Registrar Cliente</h2>
            <!-- Formulario de Registro -->
            <form id="formRegistrarCliente" method="POST" action="../../app/controllers/ClienteController.php">
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Tipo de Documento</label>
                <div class="col-sm-10">
                  <select class="form-control" name="tipodoc" required>
                    <option value="DNI" selected>DNI</option>
                    <option value="Pasaporte">Pasaporte</option>
                  </select>
                </div>
              </div>

              <!-- Número de Documento -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Número de Documento</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="numerodoc" id="numerodoc" required>
                </div>
                <div class="col-sm-3">
                  <button type="button" class="btn btn-secondary" onclick="buscarDni()">VALIDAR</button>
                </div>
              </div>

              <div class="col-sm-12 text-center mt-2">
                <div id="spinner" style="display: none;">
                  <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                  </div>
                </div>
              </div>


              <div id="datosCliente" style="display: none;">
                <!-- Apellidos -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Apellidos</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="apellidos" id="apellidos" readonly required>
                  </div>
                </div>

                <!-- Nombres -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Nombres</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nombres" id="nombres" readonly required>
                  </div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Fecha de Nacimiento</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="fechanac" id="fechanac" required>
                  </div>
                </div>

                <!-- Teléfono -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Teléfono</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="telefono" id="telefono" required>
                  </div>
                </div>
              </div>


              <!-- Botón de Registrar -->
              <div class="text-right">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-save"></i> Guardar
                </button>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script> /*Validar edad */
  function validarFormulario() {
    const telefono = document.getElementById('telefono').value.trim();
    const fechaNac = document.getElementById('fechanac').value;

    // Validar teléfono solo números y longitud 9
    if (!/^\d{9}$/.test(telefono)) {
      alert("El teléfono debe tener exactamente 9 dígitos numéricos.");
      return false;
    }

    // Validar fecha de nacimiento no vacía
    if (!fechaNac) {
      alert("Debe ingresar una fecha de nacimiento válida.");
      return false;
    }

    const fechaNacimiento = new Date(fechaNac);
    const hoy = new Date();

    // Calcular la edad en años
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();

    // Ajustar edad si el mes y día actuales son anteriores a la fecha de nacimiento
    const mesActual = hoy.getMonth();
    const diaActual = hoy.getDate();
    const mesNacimiento = fechaNacimiento.getMonth();
    const diaNacimiento = fechaNacimiento.getDate();

    if (mesActual < mesNacimiento || (mesActual === mesNacimiento && diaActual < diaNacimiento)) {
      edad--;
    }

    // Validar edad mínima y máxima
    if (edad < 18) {
      alert("Debe ser mayor o igual a 18 años para registrarse.");
      return false;
    }

    if (edad > 120) {
      alert("La edad no puede ser mayor a 120 años.");
      return false;
    }

    return true; // Si todo está OK, permite enviar el formulario
  }

  // Asociar la función validarFormulario al submit del formulario
  document.getElementById('formRegistrarCliente').addEventListener('submit', function(e) {
    if (!validarFormulario()) {
      e.preventDefault(); // Evita que se envíe el formulario si falla validación
    }
  });
</script>

<script> /*Oculta valores */
  $(document).ready(function() {
    // Ocultar los campos y botón al inicio
    $('#datosCliente').hide();
    $('#spinner').hide();
  });
</script>

<script> /*Obtiene datos */
  function buscarDni() {
    const dni = $('#numerodoc').val();

    if (dni.length !== 8) {
      alert('Ingrese un DNI válido de 8 dígitos');
      return;
    }

    // Mostrar el spinner y ocultar los datos previos
    $('#spinner').show();
    $('#datosCliente').hide();

    $.ajax({
      url: '/hotelluna/app/controllers/ClienteController.php?action=buscarDni&dni=' + dni,
      method: 'GET',
      success: function(response) {
        $('#spinner').hide();

        if (response.success) {
          $('#nombres').val(response.nombres);
          $('#apellidos').val(response.apellidos);
          $('#datosCliente').fadeIn();
        } else {
          alert(response.error || 'DNI no encontrado');
        }
      },
      error: function(err) {
        $('#spinner').hide();
        console.error('Error AJAX', err);
        alert('Error en la solicitud');
      }
    });
  }
</script>


<?php
include('../../includes/footer.php');
?>