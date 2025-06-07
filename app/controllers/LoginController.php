<?php
require_once (__DIR__ . '/../models/Usuario.php');

class LoginController {

    public function login($username, $password) {
        $username = Conexion::limpiarCadena($username);
        $password = Conexion::limpiarCadena($password);
        $usuario = Usuario::obtenerUsuario($username);

        if ($usuario && password_verify($password, $usuario['password']) && $usuario['estado'] === 'activo') {
            session_start();
            $_SESSION['idusuario'] = $usuario['idusuario'];
            $_SESSION['nombres'] = $usuario['nombres'];
            $_SESSION['apellidos'] = $usuario['apellidos'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['usuario_nombre'] = $usuario['nombres'] . ' ' . $usuario['apellidos'];
            header("Location: views/index.php");
            exit;
        } else if ($usuario && $usuario['estado'] !== 'activo') {
            return "Usuario deshabilitado. Contacte al administrador.";
        } else {
            return "Credenciales incorrectas";
        }
    }
}
