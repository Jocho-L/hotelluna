<?php
// Server.php
define("SERVER", "localhost");
define("DB", "hotel");
define("USER", "root");
define("PASS", "");
define("SGBD", "mysql:host=" . SERVER . ";port=3306;dbname=" . DB . ";charset=UTF8");

define('BASE_PATH', dirname(__DIR__, 2)); // /hotelluna
define('APP_PATH', BASE_PATH . '/app');
define('VIEWS_PATH', BASE_PATH . '/views');
?>
