<?php
require_once(__DIR__ . '/../models/Calendario.php');

class CalendarioController
{
    // Muestra la vista del calendario
    public function mostrar()
    {
        include(__DIR__ . '/../views/calendario.php');
    }

    // Devuelve los alquileres en formato JSON para el calendario (AJAX)
    public function eventos()
    {
        $alquileres = Calendario::obtenerTodosParaCalendario();
        header('Content-Type: application/json');
        echo json_encode($alquileres);
    }
}