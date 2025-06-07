<?php require_once '../../app/controllers/UsuarioController.php'; ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes</li>
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
                        <h3 class="card-title">Panel de reportes</h3>
                    </div>
                    <div class="card-body">
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <canvas id="ocupacionChart"></canvas>
                                    <div class="text-center mt-2">
                                        <strong>Ingresos Semanales</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <canvas id="ingresosChart"></canvas>
                                    <div class="text-center mt-2">
                                        <strong>Ingresos Mensuales</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col text-center">
                                    <a href="reporte_ocupacion.php" class="btn btn-primary m-2">Reporte de Ocupación</a>
                                    <a href="/hotelluna/views/reportes/ingresos.php" class="btn btn-success m-2">Reporte de Ingresos</a>
                                    <a href="reporte_clientes.php" class="btn btn-info m-2">Reporte de Clientes</a>
                                    <a href="reporte_mantenimientos.php" class="btn btn-warning m-2">Reporte de Mantenimientos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>