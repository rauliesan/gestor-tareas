<?php
// Mostrar todos los errores para depuración (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos para Docker
define('DB_HOST', 'db'); // Nombre del servicio en docker-compose
define('DB_USER', 'gestor_user');
define('DB_PASS', 'gestor_password'); // Contraseña definida en docker-compose
define('DB_NAME', 'gestor_tareas');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuración de la aplicación
define('SITE_NAME', 'Gestor de Tareas Colaborativo');
define('BASE_URL', '/');

// Función para conectar a la base de datos usando PDO en lugar de mysqli
function conectarDB() {
    try {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $conexion = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        return $conexion;
    } catch (PDOException $e) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }
}

// Función para limpiar entradas de formulario
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Verificar si el usuario está autenticado
function estaAutenticado() {
    return isset($_SESSION['usuario_id']);
}

// Redireccionar si no está autenticado
function requiereAutenticacion() {
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit;
    }
}
?>