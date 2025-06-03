<?php require_once '../../app/controllers/UsuarioController.php'; ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">usuarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">usuarios</li>
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
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title">Lista de usuarios</h3>
                        <a href="usuarios/registrar.php" class="btn btn-primary btn-sm float-right">
                            <i class="fas fa-user-plus"></i> Nuevo Usuario
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="tablaUsuarios" class="table table-bordered table-striped display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($usuarios) && is_array($usuarios) && count($usuarios) > 0): ?>
                                    <?php foreach ($usuarios as $i => $col): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($col['username']) ?></td>
                                            <td><?= htmlspecialchars($col['rol']) ?></td>
                                            <td><?= htmlspecialchars($col['apellidos']) ?></td>
                                            <td><?= htmlspecialchars($col['nombres']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $col['estado'] === 'activo' ? 'success' : 'secondary' ?>">
                                                    <?= htmlspecialchars($col['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Ver usuario y contraseña -->
                                                <button class="btn btn-info btn-sm" title="Ver usuario y contraseña"
                                                    onclick="mostrarCredenciales('<?= htmlspecialchars($col['username']) ?>')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <!-- Cambiar estado -->
                                                <form action="usuarios/cambiar_estado.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                                                    <input type="hidden" name="estado" value="<?= $col['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm" title="Cambiar estado">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                </form>
                                                <!-- Eliminar usuario -->
                                                <form action="usuarios/eliminar.php" method="POST" style="display:inline;" onsubmit="return confirmarEliminacion();">
                                                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar usuario">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <!-- Restablecer contraseña -->
                                                <form action="usuarios/restablecer_password.php" method="POST" style="display:inline;" onsubmit="return confirmarRestablecer();">
                                                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                                                    <button type="submit" class="btn btn-secondary btn-sm" title="Restablecer contraseña">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay usuarios registrados.</td>
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

<script src="/public/plugins/datatables/datatables.min.js"></script>
<script src="/public/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/public/plugins/datatables/jszip.min.js"></script>
<script src="/public/plugins/datatables/buttons.html5.min.js"></script>
<script src="/public/js/datatables/usuarios.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.inicializarDataTableusuarios) {
            window.inicializarDataTableusuarios();
        }
    });

    function confirmarEliminacion() {
        return confirm('¿Está seguro de eliminar este usuario?');
    }

    function confirmarRestablecer() {
        return confirm('¿Está seguro de restablecer la contraseña de este usuario? Se asignará una nueva contraseña temporal.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Delegación para todos los formularios de cambiar estado
        document.querySelectorAll('form[action="usuarios/cambiar_estado.php"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetch('usuarios/cambiar_estado.php', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Recarga la vista de usuarios por AJAX
                            fetch('usuarios/listar.php')
                                .then(r => r.text())
                                .then(html => {
                                    document.querySelector("#contenido").innerHTML = html;
                                    // Re-inicializa DataTable aquí si es necesario
                                    setTimeout(() => {
                                        if (
                                            document.querySelector("#tablaUsuarios") &&
                                            typeof inicializarDataTableUsuarios === "function"
                                        ) {
                                            inicializarDataTableUsuarios();
                                        }
                                    }, 0);
                                });
                        } else {
                            alert(data.error);
                        }
                    });
            });
        });
    });
</script>