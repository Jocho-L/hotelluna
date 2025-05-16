<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hotel Luna</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/hotelluna/plugins/fontawesome-free/css/all.min.css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/hotelluna/public/css/adminlte.min.css" />
  <!-- CSS de Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- CSS de DataTables -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


</head>

<body>
  <div>
    <div style="padding: 40px;">
      <div class="content">
        <div class="container-fluid">

          <?php include('../../app/controllers/ClienteController.php'); ?>

          <!-- Zona: Contenido -->
          <h2 class="mb-4">Registrar Acompañante</h2>

          <!-- Formulario de Registro -->
          <form id="formRegistrarCliente" method="POST" action="../../app/controllers/ClienteController.php">
            <div class="row mb-3 align-items-center">
              <label for="tipodoc" class="col-sm-2 col-form-label">Tipo de Documento</label>
              <div class="col-sm-10">
                <select id="tipodoc" name="tipodoc" class="form-control" required>
                  <option value="DNI" selected>DNI</option>
                  <option value="Pasaporte">Pasaporte</option>
                </select>
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <label for="numerodoc" class="col-sm-2 col-form-label">Número de Documento</label>
              <div class="col-sm-7">
                <input type="text" id="numerodoc" name="numerodoc" class="form-control" required>
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-secondary" onclick="buscarDni()">VALIDAR</button>
              </div>
            </div>

            <div class="row mb-3 justify-content-center" id="spinner" style="display: none;">
              <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
              </div>
            </div>

            <div id="datosCliente" style="display: none;">
              <div class="row mb-3 align-items-center">
                <label for="apellidos" class="col-sm-2 col-form-label">Apellidos</label>
                <div class="col-sm-10">
                  <input type="text" id="apellidos" name="apellidos" class="form-control" readonly required>
                </div>
              </div>

              <div class="row mb-3 align-items-center">
                <label for="nombres" class="col-sm-2 col-form-label">Nombres</label>
                <div class="col-sm-10">
                  <input type="text" id="nombres" name="nombres" class="form-control" readonly required>
                </div>
              </div>

              <div class="row mb-3 align-items-center">
                <label for="fechanac" class="col-sm-2 col-form-label">Fecha de Nacimiento</label>
                <div class="col-sm-10">
                  <input type="date" id="fechanac" name="fechanac" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar
              </button>
            </div>
          </form>
  
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
          /*Validar edad */
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

        <script>
          /*Oculta valores */
          $(document).ready(function() {
            // Ocultar los campos y botón al inicio
            $('#datosCliente').hide();
            $('#spinner').hide();
          });
        </script>

        <script>
          /*Obtiene datos */
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
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  </div>