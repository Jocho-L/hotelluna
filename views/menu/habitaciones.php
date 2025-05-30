<?php
require_once '../../app/config/Conexion.php';

class Habitacion {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }

    public function getAllHabitaciones(): array {
        try {
            $sql = "SELECT 
                        h.idhabitacion, 
                        h.numero, 
                        t.tipohabitacion AS tipo, 
                        h.estado, 
                        h.precioregular AS precio,
                        h.piso
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