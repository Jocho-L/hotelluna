<?php
require_once __DIR__ . '/../models/Habitaciones.php';

class ReporteUsuarioController {
    public function menu() {
        $titulo = "Reportes de Usuario";
        $subtitulo = "Habitaciones Ocupadas y en Mantenimiento";
        $habitaciones = Habitacion::obtenerOcupadasYMantenimiento();
        require __DIR__ . '/../../views/reportes_usuario/menu.php';
    }
}

$controller = new ReporteUsuarioController();

if (isset($_GET['accion']) && $_GET['accion'] === 'menu') {
    $controller->menu();
    exit;
}