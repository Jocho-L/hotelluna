<?php
require_once(__DIR__ . '/../models/Persona.php');
require_once(__DIR__ . '/../models/ApiDni.php');

// Activar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

class ClientesController
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

      if (empty(trim($apellidos)) || empty(trim($nombres))) {
        echo "Error: No se puede registrar un cliente sin nombres o apellidos.";
        exit;
      }

      $cliente = new Cliente();
      try {
        $idpersona = $cliente->guardarPersona($tipodoc, $numerodoc, $apellidos, $nombres, $fechanac, $telefono);

        // Solo registrar en personas, no en clientes
        header("Location: /hotelluna/views/index.php?mensaje=Persona registrada exitosamente.");
        exit;
      } catch (Exception $e) {
        echo "Error al registrar la persona: " . $e->getMessage();
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

    // Para debug (puedes quitar esto luego)
    // echo json_encode(['resultado_crudo' => $datos]); exit;

    if (isset($datos['data']) && is_array($datos['data'])) {
      $data = $datos['data'];
      echo json_encode([
        'success' => true,
        'nombres' => $data['nombres'] ?? null,
        'apellidos' => trim(($data['apellido_paterno'] ?? '') . ' ' . ($data['apellido_materno'] ?? '')),
        'fechanac' => $data['fecha_nacimiento'] ?? '',
        'genero' => $data['sexo'] ?? ''
      ]);
    } else {
      echo json_encode(['success' => false, 'error' => 'No se encontró el DNI']);
    }
  }
}

$controller = new ClientesController();

if (isset($_GET['action']) && $_GET['action'] === 'buscarDni') {
  $controller->buscarDni();
  exit;
} else {
  $controller->registrar();
}
