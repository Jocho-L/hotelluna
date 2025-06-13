<?php
require_once(__DIR__ . '/../config/Conexion.php');

class Persona
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::getConexion();
    }

    public function guardarPersona($tipodoc, $numerodoc, $apellidos, $nombres, $fechanac, $telefono, $genero)
    {
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

    public function actualizarPersonaParcial($idpersona, $genero, $fechanac, $telefono)
    {
        $conexion = Conexion::getConexion();
        $sql = "UPDATE personas SET genero = ?, fechanac = ?, telefono = ? WHERE idpersona = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$genero, $fechanac, $telefono, $idpersona]);
    }
}