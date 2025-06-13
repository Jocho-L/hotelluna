<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->

<?php
include('../../includes/base.php');
include('../../app/controllers/PersonaController.php');

// Obtener datos de la persona a editar
$persona = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Conexión a la base de datos
    require_once '../../app/config/Conexion.php';
    $conexion = Conexion::getConexion();
    $sql = "SELECT * FROM personas WHERE idpersona = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $persona = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Registrar' ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Contenido principal -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title"><?= isset($subtitulo) ? htmlspecialchars($subtitulo) : 'Editar' ?></h3>
                    </div>
                    <div class="card-body">
                        <!-- Formulario solo para editar género, teléfono y fecha de nacimiento -->
                        <form id="formEditarPersona" method="POST" action="../../app/controllers/PersonaController.php">
                            <!-- Campo oculto para el ID de la persona -->
                            <input type="hidden" name="idpersona" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">

                            <!-- Mostrar datos no editables -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nombres</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= isset($persona) ? htmlspecialchars($persona['nombres']) : '' ?>" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Apellidos</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= isset($persona) ? htmlspecialchars($persona['apellidos']) : '' ?>" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Número Doc</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= isset($persona) ? htmlspecialchars($persona['numerodoc']) : '' ?>" disabled>
                                </div>
                            </div>

                            <!-- Género -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Género</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="genero" id="genero" required>
                                        <option value="">Seleccione</option>
                                        <option value="masculino" <?= (isset($persona) && $persona['genero'] == 'masculino') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="femenino" <?= (isset($persona) && $persona['genero'] == 'femenino') ? 'selected' : '' ?>>Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Fecha de Nacimiento</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="fechanac" id="fechanac" required value="<?= isset($persona) ? htmlspecialchars($persona['fechanac']) : '' ?>">
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Teléfono</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="telefono" id="telefono" value="<?= isset($persona) ? htmlspecialchars($persona['telefono']) : '' ?>">
                                </div>
                            </div>

                            <!-- Botón de Guardar -->
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  // Validar solo los campos editables
  function validarFormulario() {
    const telefono = document.getElementById('telefono').value.trim();
    const fechaNac = document.getElementById('fechanac').value;

    if (telefono !== "" && !/^\d{9}$/.test(telefono)) {
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

    return true;
  }

  document.getElementById('formEditarPersona').addEventListener('submit', function(e) {
    if (!validarFormulario()) {
      e.preventDefault();
    }
  });
</script>

<?php
include('../../includes/footer.php');
?>