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

    public static function registrarUsuario($idpersona, $idrol, $username, $password, $estado) {
        $consulta = "INSERT INTO usuarios (idpersona, idrol, username, password, estado)
                     VALUES (:idpersona, :idrol, :username, :password, :estado)";
        $sql = Conexion::getConexion()->prepare($consulta);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bindParam(":idpersona", $idpersona, PDO::PARAM_INT);
        $sql->bindParam(":idrol", $idrol, PDO::PARAM_INT);
        $sql->bindParam(":username", $username, PDO::PARAM_STR);
        $sql->bindParam(":password", $hash, PDO::PARAM_STR);
        $sql->bindParam(":estado", $estado, PDO::PARAM_STR);
        return $sql->execute();
    }

    public static function listarUsuarios() {
        $consulta = "SELECT u.idusuario, u.username, u.estado, r.rol, p.nombres, p.apellidos
                     FROM usuarios u
                     INNER JOIN personas p ON u.idpersona = p.idpersona
                     INNER JOIN roles r ON u.idrol = r.idrol
                     ORDER BY p.apellidos, p.nombres";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarUsuario($idusuario) {
        $consulta = "DELETE FROM usuarios WHERE idusuario = :idusuario";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
        return $sql->execute();
    }

    public static function cambiarEstadoUsuario($idusuario, $estado) {
        $consulta = "UPDATE usuarios SET estado = :estado WHERE idusuario = :idusuario";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->bindParam(":estado", $estado, PDO::PARAM_STR);
        $sql->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
        return $sql->execute();
    }

    public static function actualizarPassword($idusuario, $nuevaPassword) {
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $consulta = "UPDATE usuarios SET password = :password WHERE idusuario = :idusuario";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->bindParam(":password", $hash, PDO::PARAM_STR);
        $sql->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
        return $sql->execute();
    }
}
