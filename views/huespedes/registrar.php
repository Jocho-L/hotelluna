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

          <h5>Acompañantes agregados</h5>
          <table class="table table-bordered" id="tablaAcompanantes">
            <thead>
              <tr>
                <th>Tipo Doc</th>
                <th>Número</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>F. Nac</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>


          <!-- Formulario de Registro -->
          <form id="formRegistrarCliente" enctype="multipart/form-data" method="POST" action="../../app/controllers/ClienteController.php">
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
                <input type="text" id="numerodoc" name="numerodoc" class="form-control">
                <small class="form-text text-muted">(Opcional para acompañantes)</small>
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
                  <input type="date" id="fechanac" name="fechanac" class="form-control">
                </div>
              </div>
              <div class="row mb-3 align-items-center">
                <label for="esFamiliar" class="col-sm-2 col-form-label">¿Es familiar?</label>
                <div class="col-sm-10">
                  <select id="esFamiliar" name="esFamiliar" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3 align-items-center" id="campoFamiliarDirecto" style="display:none;">
                <label for="esDirecto" class="col-sm-2 col-form-label">¿Es familiar directo?</label>
                <div class="col-sm-10">
                  <select id="esDirecto" name="esDirecto" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3 align-items-center" id="campoApoderado" style="display:none;">
                <label for="apoderado" class="col-sm-2 col-form-label">Apoderado</label>
                <div class="col-sm-10">
                  <input type="text" id="apoderado" name="apoderado" class="form-control">
                </div>
              </div>

              <div class="row mb-3 align-items-center" id="campoCartaPoder" style="display:none;">
                <label for="cartaPoder" class="col-sm-2 col-form-label">Carta Poder</label>
                <div class="col-sm-10">
                  <input type="file" id="cartaPoder" name="cartaPoder" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                  <small class="form-text text-muted">Suba la carta poder en formato PDF o imagen.</small>
                </div>
              </div>
            </div>

            <div class="text-end mb-3">
              <button type="button" class="btn btn-secondary" onclick="limpiarFormulario(); $('.bd-example-modal-lg').modal('hide');">
                <i class="fas fa-times"></i> Cancelar
              </button>
              <button type="button" class="btn btn-primary" onclick="agregarAcompanante()" id="btnAgregar" style="display:none;">
                <i class="fas fa-plus-circle"></i> Agregar Acompañante
              </button>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success" id="btnGuardar" style="display:none;">
                <i class="fas fa-save"></i> Guardar Todos
              </button>
            </div>

          </form>

          <!-- ********* SCRIPTS ********* -->

          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

          <script>
            $(document).ready(function() {
              $('#formRegistrarCliente').on('submit', function(e) {
                e.preventDefault();

                if (acompanantes.length === 0) {
                  if (!confirm('No ha agregado ningún acompañante. ¿Desea guardar sin acompañantes?')) {
                    return;
                  }
                }

                // Validar campos requeridos para menores de edad
                if (!validarFormulario()) {
                  return;
                }

                this.submit();
              });
            });
          </script>

          <script>
            /* actualizarEstadoBotones */
            function actualizarEstadoBotones() {
              const btnAgregar = $('#btnAgregar');
              const btnGuardar = $('#btnGuardar');

              if ($('#datosCliente').is(':visible')) {
                btnAgregar.show();
              } else {
                btnAgregar.hide();
              }

              if (acompanantes.length > 0) {
                btnGuardar.show();
              } else {
                btnGuardar.hide();
              }
            }
          </script>

          <script>
            /* guardarAcompanante */
            function guardarAcompanante() {
              const dni = document.getElementById('dni').value;
              const nombres = document.getElementById('nombres').value;
              const apellidos = document.getElementById('apellidos').value;

              if (!dni || !nombres || !apellidos) {
                alert("Completa todos los campos del acompañante.");
                return;
              }

              agregarAcompanante({
                dni,
                nombres,
                apellidos
              });

              // Limpiar formulario y cerrar modal
              document.getElementById('dni').value = '';
              document.getElementById('nombres').value = '';
              document.getElementById('apellidos').value = '';
              $('.bd-example-modal-lg').modal('hide');
            }
          </script>

          <script>
            /* agregarAcompanante */
            let acompanantes = [];

            function agregarAcompanante() {
              const tipodoc = $('#tipodoc').val();
              const numerodoc = $('#numerodoc').val();
              const apellidos = $('#apellidos').val();
              const nombres = $('#nombres').val();
              const fechanac = $('#fechanac').val();

              // Validaciones mínimas
              if (!nombres || !apellidos || !fechanac) {
                alert("Complete al menos nombres, apellidos y fecha de nacimiento.");
                return;
              }

              const nuevo = {
                tipodoc,
                numerodoc: numerodoc || 'Sin documento',
                apellidos,
                nombres,
                fechanac,
                esFamiliar: $('#esFamiliar').val(),
                esDirecto: $('#esDirecto').val(),
                apoderado: $('#apoderado').val()
              };

              // Verificar si ya existe (solo si tiene número de documento)
              if (numerodoc && acompanantes.some(a => a.numerodoc === numerodoc)) {
                alert("Este acompañante ya ha sido agregado.");
                return;
              }

              acompanantes.push(nuevo);
              actualizarTablaAcompanantes();
              limpiarFormulario();
              actualizarEstadoBotones();
            }

            function eliminarAcompanante(index) {
              acompanantes.splice(index, 1);
              actualizarTablaAcompanantes();
            }

            function actualizarTablaAcompanantes() {
              const tabla = $('#tablaAcompanantes tbody');
              tabla.empty();

              acompanantes.forEach((a, index) => {
                tabla.append(`
        <tr>
          <td>${a.tipodoc}</td>
          <td>${a.numerodoc}</td>
          <td>${a.apellidos}</td>
          <td>${a.nombres}</td>
          <td>${a.fechanac}</td>
          <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAcompanante(${index})">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      `);
              });
            }

            function limpiarFormulario() {
              if ($('#datosCliente').is(':visible') &&
                ($('#nombres').val() || $('#apellidos').val())) {
                if (!confirm('¿Está seguro que desea descartar los datos del acompañante actual?')) {
                  return;
                }
              }
              $('#numerodoc').val('');
              $('#nombres').val('');
              $('#apellidos').val('');
              $('#fechanac').val('');
              $('#esFamiliar').val('');
              $('#esDirecto').val('');
              $('#apoderado').val('');
              $('#cartaPoder').val('');
              $('#datosCliente').hide();
            }

            // Exportar acompañantes al enviar alquiler
            function obtenerAcompanantesJSON() {
              return JSON.stringify(acompanantes);
            }
          </script>

          <script>
            /* manejarCamposPorEdad */
            function manejarCamposPorEdad() {
              const fechaNac = new Date($('#fechanac').val());
              if (!fechaNac) return;

              const hoy = new Date();
              let edad = hoy.getFullYear() - fechaNac.getFullYear();
              const m = hoy.getMonth() - fechaNac.getMonth();
              if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
              }

              // Mostrar/ocultar campos según edad
              if (edad < 18) {
                $('#campoApoderado').slideDown();
                $('#esFamiliar').closest('.row').slideDown();

                // Mostrar carta poder solo si es necesario
                const esFamiliar = $('#esFamiliar').val();
                const esDirecto = $('#esDirecto').val();
                if (esFamiliar === 'no' || (esFamiliar === 'si' && esDirecto === 'no')) {
                  $('#campoCartaPoder').slideDown();
                } else {
                  $('#campoCartaPoder').slideUp();
                }
              } else {
                $('#campoApoderado').slideUp();
                $('#campoFamiliarDirecto').slideUp();
                $('#campoCartaPoder').slideUp();
                $('#esFamiliar').closest('.row').slideUp();
              }
            }

            // Asignar eventos una sola vez
            $(document).ready(function() {
              $('#fechanac, #esFamiliar, #esDirecto').on('change', manejarCamposPorEdad);
            });
          </script>

          <script>
            /* verificarCartaPoder */
            function verificarCartaPoder() {
              const fechaNac = new Date($('#fechanac').val());
              const hoy = new Date();
              let edad = hoy.getFullYear() - fechaNac.getFullYear();
              const m = hoy.getMonth() - fechaNac.getMonth();
              if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
              }

              const esFamiliar = $('#esFamiliar').val();
              const esDirecto = $('#esDirecto').val();

              // Mostrar carta poder solo si es menor de edad y NO es familiar directo
              if (edad < 18 && (esFamiliar === 'no' || (esFamiliar === 'si' && esDirecto === 'no'))) {
                $('#campoCartaPoder').slideDown();
              } else {
                $('#campoCartaPoder').slideUp();
                $('#cartaPoder').val('');
              }
            }

            $('#fechanac, #esFamiliar, #esDirecto').on('change', verificarCartaPoder);
          </script>


          <script>
            /* Mostrar/ocultar campos según respuestas */
            // Mostrar/ocultar campos según respuestas
            $('#esFamiliar').on('change', function() {
              if (this.value === 'si') {
                $('#campoFamiliarDirecto').slideDown();
              } else {
                $('#campoFamiliarDirecto').slideUp();
                $('#esDirecto').val('');
              }
            });

            // Verificar si menor de edad y pedir apoderado
            $('#fechanac').on('change', function() {
              const fechaNac = new Date(this.value);
              const hoy = new Date();
              let edad = hoy.getFullYear() - fechaNac.getFullYear();

              const m = hoy.getMonth() - fechaNac.getMonth();
              if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
              }

              if (edad < 18) {
                $('#campoApoderado').slideDown();
              } else {
                $('#campoApoderado').slideUp();
                $('#apoderado').val('');
              }
            });
          </script>


          <script>
            /* validarFormulario */
            /*Validar edad */
            function validarFormulario() {
              const telefono = document.getElementById('telefono')?.value?.trim();
              const fechaNac = document.getElementById('fechanac').value;

              if (telefono && !/^\d{9}$/.test(telefono)) {
                alert("El teléfono debe tener exactamente 9 dígitos numéricos.");
                return false;
              }

              if (!fechaNac) {
                alert("Debe ingresar una fecha de nacimiento válida.");
                return false;
              }

              const fechaNacimiento = new Date(fechaNac);
              const hoy = new Date();
              let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
              const mesActual = hoy.getMonth();
              const diaActual = hoy.getDate();
              const mesNacimiento = fechaNacimiento.getMonth();
              const diaNacimiento = fechaNacimiento.getDate();

              if (mesActual < mesNacimiento || (mesActual === mesNacimiento && diaActual < diaNacimiento)) {
                edad--;
              }

              if (edad > 120) {
                alert("La edad no puede ser mayor a 120 años.");
                return false;
              }

              // Validaciones adicionales
              const esFamiliar = document.getElementById('esFamiliar').value;
              const esDirecto = document.getElementById('esDirecto').value;
              const apoderado = document.getElementById('apoderado').value.trim();

              if (!esFamiliar) {
                alert("Debe indicar si el huésped es familiar del cliente.");
                return false;
              }

              $('#esFamiliar').on('change', function() {
                if (this.value === 'si') {
                  $('#campoFamiliarDirecto').slideDown();
                } else {
                  $('#campoFamiliarDirecto').slideUp();
                  $('#esDirecto').val('');
                }
              });

              if (esFamiliar === "si" && !esDirecto) {
                alert("Debe indicar si es un familiar directo.");
                return false;
              }

              if (edad < 18 && apoderado === "") {
                alert("Debe ingresar el nombre del apoderado para menores de edad.");
                return false;
              }

              if (edad < 18 && (esFamiliar === 'no' || (esFamiliar === 'si' && esDirecto === 'no'))) {
                const cartaPoder = document.getElementById('cartaPoder').files[0];
                if (!cartaPoder) {
                  alert("Debe subir la carta poder si el acompañante es menor de edad y no es familiar directo.");
                  return false;
                }
              }


              return true; // Todo está OK
            }
          </script>

          <script>
            /* Oculta valores */
            /*Oculta valores */
            $(document).ready(function() {
              $('#datosCliente').hide();
              $('#spinner').hide();

              // Oculta campos hasta saber si es menor
              $('#campoApoderado').hide();
              $('#campoFamiliarDirecto').hide();
              $('#campoCartaPoder').hide();
              $('#esFamiliar').closest('.row').hide();
            });
          </script>

          <script>
            /* buscarDni */
            /*Obtiene datos */
            function buscarDni() {
              const dni = $('#numerodoc').val();
              const tipodoc = $('#tipodoc').val();

              // Hacer la validación completamente opcional
              if (!dni) {
                if (confirm('¿Desea ingresar los datos manualmente sin validar documento?')) {
                  $('#nombres').val('').removeAttr('readonly');
                  $('#apellidos').val('').removeAttr('readonly');
                  $('#datosCliente').fadeIn();
                  $('#btnAgregar').show();
                }
                return;
              }

              // Validaciones solo si se ingresa documento
              if (tipodoc === 'DNI' && dni.length !== 8) {
                alert('Ingrese un DNI válido de 8 dígitos');
                return;
              }

              if (tipodoc === 'Pasaporte' && dni.length < 4) {
                alert('Ingrese un número de pasaporte válido (mínimo 4 caracteres)');
                return;
              }

              $('#spinner').show();
              $('#datosCliente').hide();

              $.ajax({
                url: '/hotelluna/app/controllers/ClienteController.php?action=buscarDni&dni=' + dni + '&tipodoc=' + tipodoc,
                method: 'GET',
                success: function(response) {
                  $('#spinner').hide();

                  if (response.success) {
                    $('#nombres').val(response.nombres).attr('readonly', true);
                    $('#apellidos').val(response.apellidos).attr('readonly', true);
                    $('#datosCliente').fadeIn();
                    $('#btnAgregar').show();
                  } else {
                    if (confirm('Documento no encontrado. ¿Desea ingresar los datos manualmente?')) {
                      $('#nombres').val('').removeAttr('readonly');
                      $('#apellidos').val('').removeAttr('readonly');
                      $('#datosCliente').fadeIn();
                      $('#btnAgregar').show();
                    }
                  }
                },
                error: function(err) {
                  $('#spinner').hide();
                  console.error('Error AJAX', err);
                  alert('Error en la solicitud. ¿Desea ingresar los datos manualmente?');
                  $('#nombres').val('').removeAttr('readonly');
                  $('#apellidos').val('').removeAttr('readonly');
                  $('#datosCliente').fadeIn();
                  $('#btnAgregar').show();
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