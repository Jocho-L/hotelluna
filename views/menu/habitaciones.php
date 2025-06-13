<?php
require_once '../../app/config/Conexion.php';

class Habitacion {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }

    public function getAllHabitaciones(): array {
        try {
            // Traer idalquiler solo si la habitación está ocupada (fechahorafin IS NULL)
            $sql = "SELECT
                        h.idhabitacion,
                        h.numero,
                        t.tipohabitacion AS tipo,
                        h.estado,
                        h.precioregular AS precio,
                        h.piso,
                        -- Solo trae el idalquiler si la habitación está ocupada
                        CASE
                            WHEN h.estado = 'ocupada' THEN (
                                SELECT a2.idalquiler
                                FROM alquileres a2
                                WHERE a2.idhabitacion = h.idhabitacion
                                ORDER BY a2.idalquiler DESC
                                LIMIT 1
                            )
                            ELSE NULL
                        END AS idalquiler
                    FROM habitaciones h
                    JOIN tipohabitaciones t ON h.idtipohabitacion = t.idtipohabitacion
                    ORDER BY h.piso ASC, h.numero ASC";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            return $resultados ?: [];
        } catch (PDOException $e) {
            error_log("🛑 Error en la consulta: " . $e->getMessage());
            return [];
        }
    }
}

// Instancia y envío de datos en JSON
$habitacionObj = new Habitacion();
$habitaciones = $habitacionObj->getAllHabitaciones();

header('Content-Type: application/json');
echo json_encode($habitaciones);