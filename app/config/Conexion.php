<?php

require_once __DIR__ . "\Server.php";

if (!defined('METHOD')) define('METHOD', "AES-256-CBC");
if (!defined('SECRET_KEY')) define('SECRET_KEY', "_H0T3L@DB.");
if (!defined('SECRET_IV')) define('SECRET_IV', "037970");

if (!class_exists('Conexion')) {
    class Conexion {
        private static $instancia = null;
        private $conexion;

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }


        private function __construct() {
            try {
                $this->conexion = new PDO(SGBD, USER, PASS);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                error_log($e->getMessage());
                throw new Exception("No se pudo conectar a la base de datos.");
            }
        }

        public static function getConexion() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia->conexion;
        }

        public static function ejecutarConsultaSimple($consulta) {
            try {
                $sql = self::getConexion()->prepare($consulta);
                $sql->execute();
                return $sql;
            } catch (PDOException $e) {
                error_log($e->getMessage());
                return null;
            }
        }

        public static function encryption($string) {
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
            return $output !== false ? base64_encode($output) : false;
        }

        public static function decryption($string) {
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            return openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv) ?: false;
        }

        public static function generarCodigoAleatorio($letra, $longitud, $numero) {
            return $letra . '-' . str_pad(rand(0, pow(10, $longitud) - 1), $longitud, '0', STR_PAD_LEFT) . "-" . $numero;
        }

        public static function limpiarCadena($cadena) {
            $cadena = trim($cadena);
            $cadena = stripslashes($cadena);
            $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
            $cadena = preg_replace("/[^a-zA-Z0-9@._-]/", "", $cadena);
            return $cadena;
        }
    }
}
