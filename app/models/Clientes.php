<?php
require_once(__DIR__ . '/../config/Conexion.php');

class Cliente
{
  // Guardar los datos de la persona en la tabla 'personas'
  public function guardarPersona($tipodoc, $numerodoc, $apellidos, $nombres, $fechanac, $telefono)
  {
    try {
      // Obtener la instancia de la conexión
      $conexion = Conexion::getConexion();

      // Insertar los datos en la tabla 'personas'
      $sqlPersona = "INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, fechanac, telefono) 
                     VALUES (:tipodoc, :numerodoc, :apellidos, :nombres, :fechanac, :telefono)";
      $stmtPersona = $conexion->prepare($sqlPersona);
      $stmtPersona->execute([
        ':tipodoc' => $tipodoc,
        ':numerodoc' => $numerodoc,
        ':apellidos' => $apellidos,
        ':nombres' => $nombres,
        ':fechanac' => $fechanac,
        ':telefono' => $telefono
      ]);

      // Retornar el ID de la persona recién insertada
      return $conexion->lastInsertId();
    } catch (Exception $e) {
      throw $e;  // Lanzar el error para que el controlador lo maneje
    }
  }

  // Guardar los datos del cliente en la tabla 'clientes'
  public function guardarCliente($idpersona)
  {
    try {
      // Obtener la instancia de la conexión
      $conexion = Conexion::getConexion();

      // Insertar en la tabla 'clientes', asociando el idpersona
      $sqlCliente = "INSERT INTO clientes (idpersona) VALUES (:idpersona)";
      $stmtCliente = $conexion->prepare($sqlCliente);
      $stmtCliente->execute([':idpersona' => $idpersona]);
    } catch (Exception $e) {
      throw $e;  // Lanzar el error para que el controlador lo maneje
    }
  }

  //  clientes DNI
  public static function buscarPorNombreODNI($termino)
  {
    try {
      $conexion = Conexion::getConexion();
      $sql = "SELECT c.idcliente, p.numerodoc AS dni, p.nombres, p.apellidos
                FROM clientes c
                JOIN personas p ON c.idpersona = p.idpersona
                WHERE p.numerodoc LIKE :term OR p.nombres LIKE :term OR p.apellidos LIKE :term
                LIMIT 10";
      $stmt = $conexion->prepare($sql);
      $like = '%' . $termino . '%';
      $stmt->bindValue(':term', $like);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      error_log($e->getMessage());
      return [];
    }
  }
}
