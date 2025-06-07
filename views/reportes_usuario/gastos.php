<?php
date_default_timezone_set('America/Lima');
include('../../includes/base.php');
$titulo = "Registrar Gasto";
$subtitulo = "Nuevo egreso";
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= htmlspecialchars($titulo) ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($titulo) ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title"><?= htmlspecialchars($subtitulo) ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="/hotelluna/app/controllers/EgresosController.php" method="POST" id="formGasto">
                            <!-- <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required value="<?= date('Y-m-d') ?>">
                            </div> -->
                            <input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto (S/)</label>
                                <input type="number" class="form-control" id="monto" name="monto" min="0.01" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar Gasto</button>
                            <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>