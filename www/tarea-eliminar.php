<?php
require_once 'includes/config.php';

// Verificar que el usuario esté autenticado
requiereAutenticacion();

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'];

// Verificar que se ha proporcionado un ID de tarea
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php?error=id_invalido');
    exit;
}

$tarea_id = (int)$_GET['id'];

// Eliminar la tarea (solo si pertenece al usuario)
$conexion = conectarDB();
$stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$tarea_id, $usuario_id]);

// Verificar si se eliminó la tarea
if ($stmt->rowCount() > 0) {
    $mensaje = 'tarea_eliminada';
} else {
    $mensaje = 'tarea_no_encontrada';
}

// Redirigir al dashboard
header("Location: dashboard.php?mensaje=$mensaje");
exit;
?>