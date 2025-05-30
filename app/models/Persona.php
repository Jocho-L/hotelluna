<?php
require_once(__DIR__ . '/../config/Conexion.php');

class Cliente
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConexion();
    }

    public function guardarPersona($tipodoc, $numerodoc, $apellidos, $nombres, $fechanac, $telefono)
    {
        // Puedes ajustar el valor de 'genero' según tu formulario
        $genero = $_POST['genero'] ?? 'otro';

        $sql = "INSERT INTO personas (tipodoc, numerodoc, apellidos, genero, nombres, telefono, fechanac)
                VALUES (:tipodoc, :numerodoc, :apellidos, :genero, :nombres, :telefono, :fechanac)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tipodoc', $tipodoc);
        $stmt->bindParam(':numerodoc', $numerodoc);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fechanac', $fechanac);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            throw new Exception("No se pudo registrar la persona.");
        }
    }
}