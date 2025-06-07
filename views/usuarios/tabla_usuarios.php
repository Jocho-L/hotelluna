<?php
require_once '../../app/controllers/UsuarioController.php';
if (isset($usuarios) && is_array($usuarios) && count($usuarios) > 0):
    foreach ($usuarios as $i => $col): ?>
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
                <button class="btn btn-info btn-sm" title="Ver usuario y contraseña"
                    onclick="mostrarCredenciales('<?= htmlspecialchars($col['username']) ?>')">
                    <i class="fas fa-eye"></i>
                </button>
                <form class="form-cambiar-estado" action="/hotelluna/views/usuarios/cambiar_estado.php" method="POST" style="display:inline;">
                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                    <input type="hidden" name="estado" value="<?= $col['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                    <button type="submit" class="btn btn-warning btn-sm" title="Cambiar estado">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                </form>
                <form action="usuarios/eliminar.php" method="POST" style="display:inline;" onsubmit="return confirmarEliminacion();">
                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar usuario">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                <form action="usuarios/restablecer_password.php" method="POST" style="display:inline;" onsubmit="return confirmarRestablecer();">
                    <input type="hidden" name="idusuario" value="<?= $col['idusuario'] ?>">
                    <button type="submit" class="btn btn-secondary btn-sm" title="Restablecer contraseña">
                        <i class="fas fa-key"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach;
else: ?>
    <tr>
        <td colspan="7" class="text-center">No hay usuarios registrados.</td>
    </tr>
<?php endif; ?>