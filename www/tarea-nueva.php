<?php
require_once 'includes/config.php';

// Verificar que el usuario esté autenticado
requiereAutenticacion();

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'];

$errores = [];
$titulo = '';
$descripcion = '';
$estado = 'pendiente';
$prioridad = 'media';
$fecha_limite = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $titulo = limpiarDato($_POST['titulo'] ?? '');
    $descripcion = limpiarDato($_POST['descripcion'] ?? '');
    $estado = limpiarDato($_POST['estado'] ?? 'pendiente');
    $prioridad = limpiarDato($_POST['prioridad'] ?? 'media');
    $fecha_limite = !empty($_POST['fecha_limite']) ? limpiarDato($_POST['fecha_limite']) : null;
    
    // Validar título
    if (empty($titulo)) {
        $errores[] = 'El título es obligatorio.';
    }
    
    // Validar estado
    if (!in_array($estado, ['pendiente', 'en_progreso', 'completada'])) {
        $errores[] = 'El estado seleccionado no es válido.';
    }
    
    // Validar prioridad
    if (!in_array($prioridad, ['baja', 'media', 'alta'])) {
        $errores[] = 'La prioridad seleccionada no es válida.';
    }
    
    // Si no hay errores, crear la tarea
    if (empty($errores)) {
        $conexion = conectarDB();
        
        // Preparar la consulta
        $stmt = $conexion->prepare("INSERT INTO tareas (usuario_id, titulo, descripcion, estado, prioridad, fecha_limite) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$usuario_id, $titulo, $descripcion, $estado, $prioridad, $fecha_limite])) {
            // Redirigir al dashboard con un mensaje de éxito
            header('Location: dashboard.php?mensaje=tarea_creada');
            exit;
        } else {
            $errores[] = 'Error al crear la tarea. Por favor, inténtalo de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Nueva Tarea</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Mis Tareas</a></li>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="form-container">
            <h2>Nueva Tarea</h2>
            
            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="tareaForm" method="POST" action="tarea-nueva.php">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>" required>
                    <span class="error-message" id="tituloError"></span>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo $descripcion; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado">
                        <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="en_progreso" <?php echo $estado === 'en_progreso' ? 'selected' : ''; ?>>En Progreso</option>
                        <option value="completada" <?php echo $estado === 'completada' ? 'selected' : ''; ?>>Completada</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="prioridad">Prioridad:</label>
                    <select id="prioridad" name="prioridad">
                        <option value="baja" <?php echo $prioridad === 'baja' ? 'selected' : ''; ?>>Baja</option>
                        <option value="media" <?php echo $prioridad === 'media' ? 'selected' : ''; ?>>Media</option>
                        <option value="alta" <?php echo $prioridad === 'alta' ? 'selected' : ''; ?>>Alta</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fecha_limite">Fecha Límite (opcional):</label>
                    <input type="date" id="fecha_limite" name="fecha_limite" value="<?php echo $fecha_limite; ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
    
    <script src="js/validacion-tarea.js"></script>
</body>
</html>