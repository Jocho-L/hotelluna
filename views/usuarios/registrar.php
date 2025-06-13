<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->

<?php
include('../../includes/base.php');
include('../../app/controllers/UsuarioController.php'); // Cambia según tu estructura
?>

<!-- Agrega los estilos de Select2 en el head o aquí -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Registrar Usuario</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Usuarioes</a></li>
          <li class="breadcrumb-item active">Registrar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Registrar Usuario</h3>
          </div>
          <form action="../../app/controllers/UsuarioController.php" method="POST">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-10">
                  <label>Buscar Persona</label>
                  <select name="idpersona" id="idpersona" class="form-control select2" required>
                    <option value="">Seleccione una persona...</option>
                    <?php
                    // Suponiendo que tienes una función para obtener personas
                    $personas = obtenerPersonas(); // Implementa esta función en tu controlador
                    foreach ($personas as $persona) {
                      echo "<option value='{$persona['idpersona']}'>
                        {$persona['apellidos']} {$persona['nombres']} - {$persona['numerodoc']}
                      </option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                  <a href="../personas/registrar.php" target="_blank" class="btn btn-success btn-block">Nueva Persona</a>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Rol</label>
                  <select name="idrol" class="form-control" required>
                    <?php
                    $roles = obtenerRoles();
                    foreach ($roles as $rol) {
                      echo "<option value='{$rol['idrol']}'>{$rol['rol']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>Usuario</label>
                  <input type="text" name="username" class="form-control" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Contraseña</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                  <label>Estado</label>
                  <select name="estado" class="form-control" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" name="registrar_usuario" class="btn btn-primary">Registrar</button>
              <a href="/hotelluna/views/index.php" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts de Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: "Seleccione una persona...",
      allowClear: true
    });
  });
</script>

<?php
include('../../includes/footer.php');
?>