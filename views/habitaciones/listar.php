<?php require_once '../../app/config/Conexion.php'; ?>
<?php require_once '../../app/models/Habitaciones.php'; ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Habitaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Habitaciones</a></li>
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
            <div class="col-12">
                <!-- Contenido principal -->
                <div class="card shadow" style="box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Lista de Habitaciones</h3>
                        <a href="/hotelluna/views/habitaciones/registrar.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nueva Habitación
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="tablaHabitaciones" class="table table-bordered table-striped display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Piso</th>
                                    <th>Número de camas</th>
                                    <th>Precio regular</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = Conexion::getConexion();
                                $sql = "SELECT h.idhabitacion, t.tipohabitacion, h.numero, h.piso, h.numcamas, h.precioregular
                                        FROM habitaciones h
                                        JOIN tipohabitaciones t ON h.idtipohabitacion = t.idtipohabitacion";
                                $result = $conn->query($sql);
                                if ($result && $result->rowCount() > 0):
                                    $i = 1;
                                    foreach ($result as $row): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($row['tipohabitacion']) ?></td>
                                            <td><?= htmlspecialchars($row['numero']) ?></td>
                                            <td><?= htmlspecialchars($row['piso']) ?></td>
                                            <td><?= htmlspecialchars($row['numcamas']) ?></td>
                                            <td><?= htmlspecialchars($row['precioregular']) ?></td>
                                            <td>
                                                <a href="/hotelluna/views/habitaciones/editar.php?idhabitacion=<?= $row['idhabitacion'] ?>" class="btn btn-warning btn-sm mr-1" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="/hotelluna/app/controllers/HabitacionController.php" method="GET" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta habitación?');">
                                                    <input type="hidden" name="action" value="eliminar">
                                                    <input type="hidden" name="idhabitacion" value="<?= $row['idhabitacion'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No se encontraron habitaciones.</td>
                                    </tr>
                                <?php endif;
                                $conn = null;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables y JS -->
<script src="/public/plugins/datatables/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaHabitaciones').DataTable();
    });
</script>