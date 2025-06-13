<?php
if (!isset($habitaciones)) {
    require_once __DIR__ . '/../../app/models/Habitaciones.php';
    $habitaciones = Habitacion::obtenerOcupadasYMantenimiento();
    $titulo = "Reportes de Usuario";
    $subtitulo = "Habitaciones Ocupadas y en Mantenimiento";
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Título de la página' ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Actual' ?></li>
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
                        <h3 class="card-title"><?= isset($subtitulo) ? htmlspecialchars($subtitulo) : 'Subtítulo' ?></h3>
                        <!-- Puedes agregar aquí otros elementos como filtros, etc. -->
                    </div>
                    <div class="card-body">
                        <!-- Botones de acciones -->
                        <div class="mb-3">
                            <a href="/hotelluna/views/reportes_usuario/gastos.php" class="btn btn-primary">Registrar Gasto</a>
                            <a href="/hotelluna/views/reportes_usuario/desayunos.php" class="btn btn-success">Reporte de Desayunos</a>
                            <a href="/hotelluna/views/reportes_usuario/ingresos.php" class="btn btn-info">Reporte de Ingresos</a>
                        </div>

                        <!-- Tabla de habitaciones ocupadas y en mantenimiento -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablaHabitaciones">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($habitaciones) && is_array($habitaciones) && count($habitaciones) > 0): ?>
                                        <?php foreach ($habitaciones as $hab): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($hab['numero']) ?></td>
                                                <td><?= htmlspecialchars($hab['tipohabitacion']) ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Ocupada</span>
                                                </td>
                                                <td>
                                                    <a href="/hotelluna/views/alquileres/detalle.php?idalquiler=<?= urlencode($hab['idalquiler']) ?>" class="btn btn-sm btn-outline-info">
                                                        Ver Detalle
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">No hay habitaciones ocupadas.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>