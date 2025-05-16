<?php
require_once(__DIR__ . '/../models/Habitaciones.php');

// Registro (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idtipohabitacion']) && !isset($_POST['action'])) {
    $data = [
        'idtipohabitacion' => $_POST['idtipohabitacion'],
        'numero' => $_POST['numero'],
        'piso' => $_POST['piso'],
        'numcamas' => $_POST['numcamas'],
        'precioregular' => $_POST['precioregular'],
        'estado' => $_POST['estado'] ?? 'Disponible'
    ];

    if (Habitacion::registrar($data)) {
        header("Location: /hotelluna/views/index.php");
    } else {
        header("Location: /hotelluna/views/habitaciones/registrar.php?error=1");
    }
    exit();
}

// Eliminación (GET con action=eliminar)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    $idhabitacion = $_GET['idhabitacion'] ?? null;

    if ($idhabitacion && Habitacion::eliminar($idhabitacion)) {
        header("Location: /hotelluna/views/index.php");
    } else {
        header("Location: /hotelluna/views/habitaciones/listar.php?error=1");
    }
    exit();
}

// Editar (GET con action=editar)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === 'editar') {
    $idhabitacion = $_GET['idhabitacion'] ?? null;

    if ($idhabitacion) {
        $habitacion = Habitacion::obtenerPorId($idhabitacion); // Obtener los datos de la habitación

        if ($habitacion) {
            // Mostrar los datos en el formulario de edición
            include(__DIR__ . '/../views/habitaciones/editar.php');
        } else {
            header("Location: /hotelluna/views/habitaciones/listar.php?error=1");
        }
    } else {
        header("Location: /hotelluna/views/habitaciones/listar.php?error=1");
    }
    exit();
}

// Actualización (POST con action=actualizar)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'actualizar') {
    $data = [
        'idtipohabitacion' => $_POST['idtipohabitacion'],
        'numero' => $_POST['numero'],
        'piso' => $_POST['piso'],
        'numcamas' => $_POST['numcamas'],
        'precioregular' => $_POST['precioregular'],
        'estado' => $_POST['estado'] ?? 'Disponible',
        'idhabitacion' => $_POST['idhabitacion']
    ];

    // Actualizar habitación
    if (Habitacion::actualizar($data)) {
        header("Location: /hotelluna/views/index.php");
    } else {
        header("Location: /hotelluna/views/habitaciones/editar.php?idhabitacion=" . $data['idhabitacion'] . "&error=1");
    }
    exit();
}
