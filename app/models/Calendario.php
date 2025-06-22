<?php
require_once(__DIR__ . '/../config/Conexion.php');

class Calendario
{
    // Obtener todos los alquileres para el calendario
    public static function obtenerTodosParaCalendario()
    {
        try {
            $conexion = Conexion::getConexion();
            $sql = "SELECT idalquiler, fechahorainicio, fechahorafin
                    FROM alquileres
                    WHERE (fechahorafin IS NULL OR fechahorafin >= NOW())";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $alquileres = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $alquileres[] = [
                    'id' => $row['idalquiler'],
                    'title' => 'Alquiler #' . $row['idalquiler'],
                    'start' => $row['fechahorainicio'],
                    'end' => $row['fechahorafin']
                ];
            }
            return $alquileres;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}