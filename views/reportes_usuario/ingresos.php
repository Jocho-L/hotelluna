<?php
include('../../includes/base.php');
include('../../app/controllers/ClienteController.php');

// Establecer zona horaria correcta
date_default_timezone_set('America/Lima'); // O 'America/Bogota', 'America/Mexico_City', etc.
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($titulo) ? htmlspecialchars($titulo) : 'Reporte de Ingresos' ?></h1>
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
                        <h3 class="card-title">
                            <?php
                                $fecha_mostrar = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
                                // Mostrar en formato d/m/Y
                                $fecha_mostrar = date('d/m/Y', strtotime($fecha_mostrar));
                                echo $fecha_mostrar;
                            ?>
                        </h3>
                        <!-- Filtro por fecha única -->
                        <form method="get" class="form-inline float-right">
                            <label for="fecha" class="mr-2">Fecha:</label>
                            <input type="date" id="fecha" name="fecha" class="form-control mr-2"
                                value="<?= isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : date('Y-m-d') ?>">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <?php
                        // Conexión a la base de datos
                        $mysqli = new mysqli("localhost", "root", "", "hotel");
                        if ($mysqli->connect_errno) {
                            echo "Error de conexión: " . $mysqli->connect_error;
                            exit();
                        }

                        // Obtener la fecha del filtro o usar la fecha actual por defecto
                        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

                        // Consulta para ingresos y egresos filtrados por una sola fecha
                        $sql = "
                        SELECT
                            a.idalquiler AS id_movimiento,
                            'Ingreso' AS tipo,
                            h.numero AS habitacion,
                            a.placa,
                            a.modalidadpago,
                            a.valoralquiler AS monto,
                            IF(a.incluyedesayuno, 'Sí', 'No') AS desayuno,
                            NULL AS descripcion_egreso,
                            CONCAT(pu.nombres, ' ', pu.apellidos) AS usuario,
                            a.fechahorainicio AS fecha
                        FROM alquileres a
                        JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
                        JOIN usuarios u ON a.idusuarioentrada = u.idusuario
                        JOIN personas pu ON u.idpersona = pu.idpersona
                        WHERE DATE(a.fechahorainicio) = '$fecha'

                        UNION ALL

                        SELECT
                            e.idegreso AS id_movimiento,
                            'Egreso' AS tipo,
                            NULL AS habitacion,
                            NULL AS placa,
                            NULL AS modalidadpago,
                            e.monto,
                            NULL AS desayuno,
                            e.descripcion AS descripcion_egreso,
                            CONCAT(p.nombres, ' ', p.apellidos) AS usuario,
                            e.fecha
                        FROM egresos e
                        JOIN usuarios u ON e.idusuario = u.idusuario
                        JOIN personas p ON u.idpersona = p.idpersona
                        WHERE DATE(e.fecha) = '$fecha'

                        ORDER BY fecha
                        ";

                        $result = $mysqli->query($sql);

                        $total_ingresos = 0;
                        $total_egresos = 0;
                        ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Habitación</th>
                                    <th>Placa</th>
                                    <th>Modalidad de Pago</th>
                                    <th>Monto</th>
                                    <th>Desayuno</th>
                                    <th>Descripción Egreso</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['tipo']) ?></td>
                                        <td><?= htmlspecialchars($row['habitacion'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['placa'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['modalidadpago'] ?? '') ?></td>
                                        <td class="<?= $row['tipo'] === 'Ingreso' ? 'text-success' : 'text-danger' ?>">
                                            <?php
                                            if ($row['tipo'] === 'Ingreso') {
                                                echo number_format($row['monto'], 2);
                                                $total_ingresos += $row['monto'];
                                            } else {
                                                echo '-' . number_format($row['monto'], 2);
                                                $total_egresos += $row['monto'];
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['desayuno'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['descripcion_egreso'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['usuario'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Ingresos:</th>
                                    <th><?= number_format($total_ingresos, 2) ?></th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total Egresos:</th>
                                    <th><?= number_format($total_egresos, 2) ?></th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Saldo Neto:</th>
                                    <th><?= number_format($total_ingresos - $total_egresos, 2) ?></th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <?php
                        $mysqli->close();
                        ?>
                        <!-- Botón para generar PDF -->
                        <button id="btnPDF" class="btn btn-danger mt-3">
                            <i class="fas fa-file-pdf"></i> Descargar PDF
                        </button>
                        <script>
                        // Variable JS con la fecha seleccionada en el filtro
                        var fechaReporte = "<?= isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : date('Y-m-d') ?>";
                        </script>
                        <script>
                        document.getElementById('btnPDF').addEventListener('click', function () {
                            const cardBody = this.closest('.card-body');
                            html2canvas(cardBody).then(canvas => {
                                const imgData = canvas.toDataURL('image/png');
                                const pdf = new jspdf.jsPDF('l', 'pt', 'a4');
                                const imgProps = pdf.getImageProperties(imgData);
                                const pdfWidth = pdf.internal.pageSize.getWidth();
                                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                                pdf.save(`reporte_ingresos_${fechaReporte}.pdf`);
                            });
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../../includes/footer.php');
?>

<!-- Agrega esto antes de cerrar el body o en tu base.php -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>