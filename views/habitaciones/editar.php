<?php
include('../../includes/base.php');
?>

<?php
// Incluir el archivo de conexión y obtener los tipos de habitación
include('../../app/config/Conexion.php');
$conn = Conexion::getConexion();

// Obtener los tipos de habitación
$sql = "SELECT idtipohabitacion, tipohabitacion FROM tipohabitaciones";
$stmt = $conn->query($sql);
$tiposHabitacion = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Obtener los datos de la habitación a editar
$idhabitacion = $_GET['idhabitacion'] ?? null;
$habitacion = null;

if ($idhabitacion) {
  $sql = "SELECT * FROM habitaciones WHERE idhabitacion = :idhabitacion";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':idhabitacion' => $idhabitacion]);
  $habitacion = $stmt->fetch(PDO::FETCH_ASSOC);
}

$conn = null;
?>

<!-- HTML -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Editar Habitación</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item"><a href="listar.php">Habitaciones</a></li>
          <li class="breadcrumb-item active">Editar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Formulario de Edición de Habitación</h3>
          </div>
          <div class="card-body">

            <?php if ($habitacion): ?>
              <form id="formEditarHabitacion" method="POST" action="../../controllers/HabitacionController.php">
                <input type="hidden" name="action" value="actualizar">
                <input type="hidden" name="idhabitacion" value="<?= $habitacion['idhabitacion'] ?>">

                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="idtipohabitacion">Tipo de Habitación</label>
                    <select class="form-control" id="idtipohabitacion" name="idtipohabitacion" required>
                      <option value="">Seleccione un tipo</option>
                      <?php foreach ($tiposHabitacion as $tipo): ?>
                        <option value="<?= $tipo['idtipohabitacion'] ?>"
                          <?= $habitacion['idtipohabitacion'] == $tipo['idtipohabitacion'] ? 'selected' : '' ?>>
                          <?= $tipo['tipohabitacion'] ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label for="piso">Piso</label>
                    <input type="number" class="form-control" id="piso" name="piso" value="<?= $habitacion['piso'] ?>"
                      required>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="numero">Número de Habitación</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="<?= $habitacion['numero'] ?>"
                      maxlength="10" required>
                  </div>
                  <div class="col-md-6">
                    <label for="precioregular">Precio Regular (S/.)</label>
                    <input type="number" step="0.01" class="form-control" id="precioregular" name="precioregular"
                      value="<?= $habitacion['precioregular'] ?>" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="numcamas">Número de Camas</label>
                  <input type="number" class="form-control" id="numcamas" name="numcamas"
                    value="<?= $habitacion['numcamas'] ?>" required>
                </div>

                <input type="hidden" name="estado" value="Disponible">

                <div class="text-right">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                  </button>
                </div>
              </form>
            <?php else: ?>
              <p>Habitación no encontrada.</p>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include('../../includes/footer.php');
?>