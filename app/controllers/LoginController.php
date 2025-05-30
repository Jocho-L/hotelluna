<?php

require_once (__DIR__ . '/../models/Usuario.php');

class LoginController {

    public function login($username, $password) {
        $username = Conexion::limpiarCadena($username);
        $password = Conexion::limpiarCadena($password);

        $usuario = Usuario::obtenerUsuario($username);

        //if ($usuario && Conexion::decryption($usuario['password']) === $password) {
        if ($usuario && $usuario['password'] === $password) { //cambiar. esto es solo de prueba

            session_start();
            $_SESSION['idusuario'] = $usuario['idusuario'];
            $_SESSION['nombres'] = $usuario['nombres'];
            $_SESSION['apellidos'] = $usuario['apellidos'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: views/index.php");
            exit;
        } else {
            return "Credenciales incorrectas";
        }
    }
}
