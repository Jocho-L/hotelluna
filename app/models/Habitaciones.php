<?php
require_once(__DIR__ . '/../config/Conexion.php');
class Habitacion {
    public static function registrar($data) {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado)
                VALUES (:idtipohabitacion, :numero, :piso, :numcamas, :precioregular, :estado)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':idtipohabitacion' => $data['idtipohabitacion'],
            ':numero' => $data['numero'],
            ':piso' => $data['piso'],
            ':numcamas' => $data['numcamas'],
            ':precioregular' => $data['precioregular'],
            ':estado' => $data['estado']
        ]);
    }

    public static function eliminar($idhabitacion) {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM habitaciones WHERE idhabitacion = :idhabitacion";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':idhabitacion' => $idhabitacion]);
    }

    // Nuevo método para obtener los datos de una habitación por su ID
    public static function obtenerPorId($idhabitacion) {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM habitaciones WHERE idhabitacion = :idhabitacion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':idhabitacion' => $idhabitacion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nuevo método para actualizar una habitación
    public static function actualizar($data) {
        $conn = Conexion::getConexion();
        $sql = "UPDATE habitaciones SET
                idtipohabitacion = :idtipohabitacion,
                numero = :numero,
                piso = :piso,
                numcamas = :numcamas,
                precioregular = :precioregular,
                estado = :estado
                WHERE idhabitacion = :idhabitacion";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Método para obtener habitaciones ocupadas y en mantenimiento con el nombre del tipo
    public static function obtenerOcupadasYMantenimiento() {
        $conn = Conexion::getConexion();
        $sql = "SELECT h.idhabitacion, h.numero, t.tipohabitacion, h.estado, a.idalquiler
                FROM habitaciones h
                INNER JOIN tipohabitaciones t ON h.idtipohabitacion = t.idtipohabitacion
                LEFT JOIN alquileres a ON h.idhabitacion = a.idhabitacion AND h.estado = 'ocupada' AND a.fechahorafin IS NULL
                WHERE h.estado IN ('ocupada', 'mantenimiento')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
