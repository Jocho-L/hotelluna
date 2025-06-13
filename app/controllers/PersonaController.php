<?php
require_once(__DIR__ . '/../models/Persona.php');
require_once(__DIR__ . '/../models/ApiDni.php');

// Activar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

class PersonasController
{
  public function registrar()
  {
    // SOLO ejecutar si hay datos para registrar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipodoc'])) {
      $tipodoc = $_POST['tipodoc'];
      $numerodoc = $_POST['numerodoc'];
      $apellidos = $_POST['apellidos'];
      $nombres = $_POST['nombres'];
      $fechanac = $_POST['fechanac'];
      $telefono = $_POST['telefono'];
      $genero = $_POST['genero'];

      // Normalizar valor de género
      if (strtolower($genero) === 'm' || strtolower($genero) === 'masculino') {
        $genero = 'masculino';
      } elseif (strtolower($genero) === 'f' || strtolower($genero) === 'femenino') {
        $genero = 'femenino';
      } else {
        $genero = '';
      }

      if (empty(trim($apellidos)) || empty(trim($nombres))) {
        echo "Error: No se puede registrar una persona sin nombres o apellidos.";
        exit;
      }

      $persona = new Persona();
      try {
        $idpersona = $persona->guardarPersona($tipodoc, $numerodoc, $apellidos, $nombres, $fechanac, $telefono, $genero);

        // Solo registrar en personas, no en clientes
        header("Location: /hotelluna/views/index.php?mensaje=Persona registrada exitosamente.");
        exit;
      } catch (Exception $e) {
        echo "Error al registrar la persona: " . $e->getMessage();
      }
    }
    // Agregar aquí el update si viene desde editar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idpersona']) && !isset($_POST['tipodoc'])) {
      $idpersona = $_POST['idpersona'];
      $genero = $_POST['genero'];
      $fechanac = $_POST['fechanac'];
      $telefono = $_POST['telefono'];

      // Normalizar valor de género
      if (strtolower($genero) === 'm' || strtolower($genero) === 'masculino') {
        $genero = 'masculino';
      } elseif (strtolower($genero) === 'f' || strtolower($genero) === 'femenino') {
        $genero = 'femenino';
      } else {
        $genero = '';
      }

      $persona = new Persona();
      try {
        $persona->actualizarPersonaParcial($idpersona, $genero, $fechanac, $telefono);
        header("Location: /hotelluna/views/index.php?mensaje=Persona actualizada exitosamente.");
        exit;
      } catch (Exception $e) {
        echo "Error al actualizar la persona: " . $e->getMessage();
      }
    }
  }

  public function buscarDni()
  {
    header('Content-Type: application/json');

    $dni = $_GET['dni'] ?? '';

    if (!$dni || strlen($dni) !== 8) {
      echo json_encode(['success' => false, 'error' => 'DNI inválido']);
      return;
    }

    $datos = ApiDni::buscarPorDni($dni);

    if (isset($datos['data']) && is_array($datos['data'])) {
      $data = $datos['data'];
      echo json_encode([
        'success' => true,
        'nombres' => $data['nombres'] ?? null,
        'apellidos' => trim(($data['apellido_paterno'] ?? '') . ' ' . ($data['apellido_materno'] ?? '')),
        'fechanac' => $data['fecha_nacimiento'] ?? ''
      ]);
    } else {
      echo json_encode(['success' => false, 'error' => 'No se encontró el DNI']);
    }
  }
}

$controller = new PersonasController();

if (isset($_GET['action']) && $_GET['action'] === 'buscarDni') {
  $controller->buscarDni();
  exit;
} else {
  $controller->registrar();
}
