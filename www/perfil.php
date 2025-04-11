<?php
require_once 'includes/config.php';

// Verificar que el usuario esté autenticado
requiereAutenticacion();

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'];
$errores = [];
$exito = false;

// Obtener datos actuales del usuario
$conexion = conectarDB();
$stmt = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

$nombre = $usuario['nombre'];
$email = $usuario['email'];

// Procesar el formulario de actualización de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $nombre = limpiarDato($_POST['nombre'] ?? '');
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nuevo = $_POST['password_nuevo'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validar nombre
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio.';
    }
    
    // Si se está cambiando la contraseña
    if (!empty($password_nuevo) || !empty($password_confirm)) {
        // Verificar contraseña actual
        if (empty($password_actual)) {
            $errores[] = 'Debes introducir tu contraseña actual para cambiarla.';
        } else {
            // Verificar que la contraseña actual sea correcta
            $stmt = $conexion->prepare("SELECT password FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario_id]);
            $usuario_db = $stmt->fetch();
            
            if (!password_verify($password_actual, $usuario_db['password'])) {
                $errores[] = 'La contraseña actual no es correcta.';
            }
        }
        
        // Validar nueva contraseña
        if (empty($password_nuevo)) {
            $errores[] = 'La nueva contraseña es obligatoria.';
        } elseif (strlen($password_nuevo) < 6) {
            $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres.';
        }
        
        // Validar confirmación de contraseña
        if ($password_nuevo !== $password_confirm) {
            $errores[] = 'Las contraseñas no coinciden.';
        }
    }
    
    // Si no hay errores, actualizar el perfil
    if (empty($errores)) {
        // Si hay una nueva contraseña, actualizarla
        if (!empty($password_nuevo)) {
            $password_hash = password_hash($password_nuevo, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, password = ? WHERE id = ?");
            $resultado = $stmt->execute([$nombre, $password_hash, $usuario_id]);
        } else {
            // Solo actualizar el nombre
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
            $resultado = $stmt->execute([$nombre, $usuario_id]);
        }
        
        if ($resultado) {
            // Actualizar el nombre en la sesión
            $_SESSION['usuario_nombre'] = $nombre;
            $exito = true;
        } else {
            $errores[] = 'Error al actualizar el perfil. Por favor, inténtalo de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Mi Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Mis Tareas</a></li>
                    <li><a href="perfil.php" class="active">Mi Perfil</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="form-container">
            <h2>Mi Perfil</h2>
            
            <?php if ($exito): ?>
                <div class="alert alert-success">
                    Tu perfil se ha actualizado correctamente.
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="perfilForm" method="POST" action="perfil.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    <span class="error-message" id="nombreError"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                    <small>No puedes cambiar tu email.</small>
                </div>
                
                <h3>Cambiar Contraseña</h3>
                <p class="form-info">Deja estos campos en blanco si no quieres cambiar tu contraseña.</p>
                
                <div class="form-group">
                    <label for="password_actual">Contraseña Actual:</label>
                    <input type="password" id="password_actual" name="password_actual">
                    <span class="error-message" id="passwordActualError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password_nuevo">Nueva Contraseña:</label>
                    <input type="password" id="password_nuevo" name="password_nuevo">
                    <span class="error-message" id="passwordNuevoError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm">
                    <span class="error-message" id="passwordConfirmError"></span>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
    
    <script src="js/validacion-perfil.js"></script>
</body>
</html>