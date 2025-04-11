<?php
require_once 'includes/config.php';

// Verificar que el usuario esté autenticado
if (estaAutenticado()) {
    // Destruir la sesión
    session_unset();
    session_destroy();
}

// Redirigir al inicio
header('Location: index.php');
exit;
?>