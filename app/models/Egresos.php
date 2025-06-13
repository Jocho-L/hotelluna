<?php
require_once(__DIR__ . '/../config/Conexion.php');

class Egreso
{
    public static function registrar($idusuario, $fecha, $descripcion, $monto, $observaciones)
    {
        $consulta = "INSERT INTO egresos (idusuario, fecha, descripcion, monto, observaciones)
                     VALUES (:idusuario, :fecha, :descripcion, :monto, :observaciones)";
        $sql = Conexion::getConexion()->prepare($consulta);
        $sql->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $sql->bindParam(':monto', $monto);
        $sql->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
        return $sql->execute();
    }
}