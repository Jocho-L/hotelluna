<?php
require_once 'app/controllers/LoginController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $login = new LoginController();
    $resultado = $login->login($username, $password);

    if ($resultado) {
        // Si las credenciales son incorrectas
        header("Location: index.php?error=1");
        exit;
    }
}
