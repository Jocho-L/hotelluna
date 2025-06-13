<?php
require_once '../../app/config/Conexion.php';

// Obtenemos la instancia PDO
$conexion = Conexion::getConexion();

// Consulta SQL para obtener los alquileres con información relevante
$sql = "SELECT
          a.idalquiler,
          c.idcliente,
          p.nombres AS cliente_nombres,
          p.apellidos AS cliente_apellidos,
          h.numero AS habitacion_numero,
          h.piso AS habitacion_piso,
          th.tipohabitacion,
          a.fechahorainicio,
          a.fechahorafin,
          a.valoralquiler,
          a.incluyedesayuno,
          ue.username AS usuario_entrada,
          pe.nombres AS entrada_nombres,
          pe.apellidos AS entrada_apellidos,
          us.username AS usuario_salida,
          ps.nombres AS salida_nombres,
          ps.apellidos AS salida_apellidos
        FROM alquileres a
        JOIN clientes c ON a.idcliente = c.idcliente
        JOIN personas p ON c.idpersona = p.idpersona
        JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
        JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
        JOIN usuarios ue ON a.idusuarioentrada = ue.idusuario
        JOIN personas pe ON ue.idpersona = pe.idpersona
        LEFT JOIN usuarios us ON a.idusuariosalida = us.idusuario
        LEFT JOIN personas ps ON us.idpersona = ps.idpersona
        ORDER BY a.fechahorainicio DESC";

try {
  $stmt = $conexion->prepare($sql);
  $stmt->execute();
  $alquileres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  echo "Error al ejecutar la consulta: " . $e->getMessage();
  $alquileres = [];
}

$titulo = "Alquileres";
$subtitulo = "Lista de Alquileres";
?>

<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
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
                    </div>
                    <div class="card-body">
                        <table id="tablaAlquileres" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Habitación</th>
                                    <th>Tipo Habitación</th>
                                    <th>Piso</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Valor</th>
                                    <th>Desayuno</th>
                                    <th>Usuario Entrada</th>
                                    <th>Usuario Salida</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($alquileres)): ?>
                                    <?php foreach ($alquileres as $fila): ?>
                                        <tr>
                                            <td><?= $fila['idalquiler'] ?></td>
                                            <td><?= htmlspecialchars($fila['cliente_apellidos'] . ', ' . $fila['cliente_nombres']) ?></td>
                                            <td><?= htmlspecialchars($fila['habitacion_numero']) ?></td>
                                            <td><?= htmlspecialchars($fila['tipohabitacion']) ?></td>
                                            <td><?= htmlspecialchars($fila['habitacion_piso']) ?></td>
                                            <td><?= htmlspecialchars($fila['fechahorainicio']) ?></td>
                                            <td><?= htmlspecialchars($fila['fechahorafin'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars(number_format($fila['valoralquiler'], 2)) ?></td>
                                            <td><?= $fila['incluyedesayuno'] ? 'Sí' : 'No' ?></td>
                                            <td>
                                                <?= htmlspecialchars($fila['entrada_apellidos'] . ', ' . $fila['entrada_nombres']) ?>
                                                <br><small>(<?= htmlspecialchars($fila['usuario_entrada']) ?>)</small>
                                            </td>
                                            <td>
                                                <?php if ($fila['usuario_salida']): ?>
                                                    <?= htmlspecialchars($fila['salida_apellidos'] . ', ' . $fila['salida_nombres']) ?>
                                                    <br><small>(<?= htmlspecialchars($fila['usuario_salida']) ?>)</small>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="alquileres/detalle.php?idalquiler=<?= $fila['idalquiler'] ?>">
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">No hay alquileres registrados</td>
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