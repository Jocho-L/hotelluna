<?php
include('../../includes/base.php');
include('../../app/controllers/ClienteController.php');

// Conexión a la base de datos (ajusta según tu configuración)
$mysqli = new mysqli("localhost", "root", "", "hotel");
if ($mysqli->connect_errno) {
    echo "Error de conexión: " . $mysqli->connect_error;
    exit();
}

$query = "
SELECT
    h.numero AS numero_habitacion,
    CONCAT(pcli.nombres, ' ', pcli.apellidos) AS cliente,
    1 + COUNT(DISTINCT CASE WHEN hu.idpersona IS NOT NULL AND hu.idpersona <> pcli.idpersona THEN hu.idpersona END) AS total_personas,
    a.incluyedesayuno
FROM habitaciones h
JOIN alquileres a ON a.idhabitacion = h.idhabitacion
JOIN clientes c ON c.idcliente = a.idcliente
JOIN personas pcli ON pcli.idpersona = c.idpersona
LEFT JOIN huespedes hu ON hu.idalquiler = a.idalquiler
WHERE
    a.incluyedesayuno = TRUE
    AND CURDATE() BETWEEN DATE(a.fechahorainicio) AND IFNULL(DATE(a.fechahorafin), CURDATE())
    AND h.estado = 'ocupada'
    AND a.idalquiler = (
        SELECT MAX(a2.idalquiler)
        FROM alquileres a2
        WHERE a2.idhabitacion = h.idhabitacion
          AND CURDATE() BETWEEN DATE(a2.fechahorainicio) AND IFNULL(DATE(a2.fechahorafin), CURDATE())
    )
GROUP BY h.numero, cliente, a.incluyedesayuno
ORDER BY h.numero
";

$result = $mysqli->query($query);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reporte de desayunos</h1>
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
                        <h3 class="card-title"><?= date('d/m/Y') ?></h3>
                        <button id="exportarPDF" class="btn btn-danger float-right">Exportar a PDF</button>
                    </div>
                    <div class="card-body">
                        <table id="tablaDesayunos" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Habitación</th>
                                    <th>Cliente</th>
                                    <th>Total Personas</th>
                                    <th>Incluye Desayuno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['numero_habitacion']) ?></td>
                                            <td><?= htmlspecialchars($row['cliente']) ?></td>
                                            <td><?= htmlspecialchars($row['total_personas']) ?></td>
                                            <td><?= $row['incluyedesayuno'] ? 'Sí' : 'No' ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay habitaciones ocupadas con desayuno.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if ($result) $result->free(); ?>
                        <?php $mysqli->close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../../includes/footer.php');
?>
<!-- Agrega los scripts de jsPDF y AutoTable antes de cerrar el body -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
document.getElementById('exportarPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Reporte de Habitaciones con Desayuno", 14, 15);

    // Agregar la fecha actual debajo del título
    const fecha = new Date();
    const fechaStr = fecha.toLocaleDateString('es-ES');
    doc.text("Fecha: " + fechaStr, 14, 22);

    doc.autoTable({
        html: '#tablaDesayunos',
        startY: 28,
        styles: { fontSize: 10 },
        headStyles: { fillColor: [52, 58, 64] }
    });

    doc.save('habitaciones_desayuno.pdf');
});
</script>