<?php
require_once '../../app/models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idusuario'])) {
    $idusuario = intval($_POST['idusuario']);

    // Generar una nueva contraseña temporal
    $nuevaPassword = bin2hex(random_bytes(4)); // 8 caracteres aleatorios

    // Actualizar la contraseña en la base de datos
    $exito = Usuario::actualizarPassword($idusuario, $nuevaPassword);

    if ($exito) {
        // Mostrar la nueva contraseña al superadministrador
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Contraseña restablecida</title>
            <link rel="stylesheet" href="../../public/css/adminlte.min.css">
            <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
        </head>
        <body class="container mt-5">
            <div class="alert alert-success">
                <h4 class="alert-heading"><i class="fas fa-key"></i> Contraseña restablecida</h4>
                <p>La nueva contraseña temporal del usuario es:</p>
                <div class="alert alert-info text-center" style="font-size:1.5em;">
                    <strong><?= htmlspecialchars($nuevaPassword) ?></strong>
                </div>
                <hr>
                <a href="listar.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver a la lista de usuarios</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        header('Location: listar.php?error=No se pudo restablecer la contraseña');
        exit;
    }
} else {
    header('Location: listar.php');
    exit;
}