<?php
require_once(__DIR__ . '/../models/Usuario.php');

function obtenerRoles() {
    $conn = new mysqli("localhost", "root", "", "hotel");
    $roles = [];
    $sql = "SELECT idrol, rol FROM roles ORDER BY rol ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
    }
    $conn->close();
    return $roles;
}

function obtenerPersonas() {
    $conn = new mysqli("localhost", "root", "", "hotel");
    $personas = [];
    $sql = "SELECT idpersona, apellidos, nombres, numerodoc FROM personas ORDER BY apellidos, nombres ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $personas[] = $row;
        }
    }
    $conn->close();
    return $personas;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_usuario'])) {
    $idpersona = $_POST['idpersona'];
    $idrol = $_POST['idrol'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $estado = $_POST['estado'];

    // Validación básica (puedes mejorarla)
    if ($idpersona && $idrol && $username && $password && $estado) {
        $exito = Usuario::registrarUsuario($idpersona, $idrol, $username, $password, $estado);
        if ($exito) {
            header("Location: ../../views/usuarios/index.php?msg=Usuario registrado");
            exit;
        } else {
            header("Location: ../../views/usuarios/registrar.php?error=No se pudo registrar");
            exit;
        }
    } else {
        header("Location: ../../views/usuarios/registrar.php?error=Faltan datos");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && basename($_SERVER['PHP_SELF']) === 'listar.php') {
    $usuarios = Usuario::listarUsuarios();
    // Puedes incluir la vista aquí o dejar que la vista lo requiera
    // require_once '../../views/usuarios/listar.php';
}