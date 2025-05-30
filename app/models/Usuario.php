<?php

require_once (__DIR__ . '/../config/Conexion.php');
class Usuario {

    public static function obtenerUsuario($username) {
        $consulta = "SELECT u.*, p.nombres, p.apellidos, r.rol
                     FROM usuarios u
                     INNER JOIN personas p ON u.idpersona = p.idpersona
                     INNER JOIN roles r ON u.idrol = r.idrol
                     WHERE u.username = :username AND u.estado = 'activo'";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->bindParam(":username", $username, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}
