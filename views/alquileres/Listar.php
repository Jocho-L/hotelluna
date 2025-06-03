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

if (empty($alquileres)) {
  echo "<p>No se encontraron alquileres.</p>";
}
?>

<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Alquileres</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Alquileres</a></li>
          <li class="breadcrumb-item active">Listar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <!-- Contenido principal -->
        <div id="contenido" class="container" style="flex-grow: 1; padding: 20px;">
          <h2>Lista de Alquileres</h2>
          <!-- Tabla de alquileres -->
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
                      <button class="btn btn-info btn-sm ver-huespedes" data-idalquiler="<?= $fila['idalquiler'] ?>">
                        Ver
                      </button>
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
        </div> <!-- Cierre de #contenido -->

        <script>
        // filepath: c:\xampp\htdocs\hotelluna\views\alquileres\Listar.php
        document.addEventListener('DOMContentLoaded', function() {
          document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('ver-huespedes')) {
              const idalquiler = e.target.getAttribute('data-idalquiler');
              // Llama a la función global definida en index.php
              if (typeof mostrarHuespedesAlquiler === 'function') {
                mostrarHuespedesAlquiler(idalquiler);
              } else {
                alert('No se puede mostrar el modal de huéspedes. Recargue la página.');
              }
            }
          });
        });
        </script>
      </div>
    </div>
  </div>
</div>