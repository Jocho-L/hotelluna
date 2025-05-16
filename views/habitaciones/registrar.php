<?php
include('../../includes/base.php');
?>

<?php
include('../../app/config/Conexion.php');
$conn = Conexion::getConexion();

$sql = "SELECT idtipohabitacion, tipohabitacion FROM tipohabitaciones";
$stmt = $conn->query($sql);
$tiposHabitacion = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$conn = null;
?>

<!-- HTML -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Registrar Habitación</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item active">Registrar</li>
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
            <h3 class="card-title mb-0">Formulario de Habitaciones</h3>
          </div>
          <div class="card-body">

          <form id="formRegistrarHabitacion" method="POST" action="../../app/controllers/HabitacionController.php">
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="idtipohabitacion">Tipo de Habitación</label>
                  <select class="form-control" id="idtipohabitacion" name="idtipohabitacion" required>
                    <option value="">Seleccione un tipo</option>
                    <?php foreach ($tiposHabitacion as $tipo): ?>
                      <option value="<?= $tipo['idtipohabitacion'] ?>"><?= $tipo['tipohabitacion'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="piso">Piso</label>
                  <input type="number" class="form-control" id="piso" name="piso" required>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label for="numero">Número de Habitación</label>
                  <input type="text" class="form-control" id="numero" name="numero" maxlength="10" required>
                </div>
                <div class="col-md-6">
                  <label for="precioregular">Precio Regular (S/.)</label>
                  <input type="number" step="0.01" class="form-control" id="precioregular" name="precioregular" required>
                </div>
              </div>

              <div class="form-group">
                <label for="numcamas">Número de Camas</label>
                <input type="number" class="form-control" id="numcamas" name="numcamas" required>
              </div>

              <input type="hidden" name="estado" value="Disponible">

              <div class="text-right">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-save"></i> Guardar
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include('../../includes/footer.php');
?>