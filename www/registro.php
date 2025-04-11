<?php
require_once 'includes/config.php';

// Si el usuario ya está autenticado, redirigir al dashboard
if (estaAutenticado()) {
    header('Location: dashboard.php');
    exit;
}

$errores = [];
$nombre = '';
$email = '';

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $nombre = limpiarDato($_POST['nombre'] ?? '');
    $email = limpiarDato($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validar nombre
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio.';
    }
    
    // Validar email
    if (empty($email)) {
        $errores[] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El formato del email no es válido.';
    } else {
        // Verificar que el email no esté ya registrado
        $conexion = conectarDB();
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            $errores[] = 'Este email ya está registrado.';
        }
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errores[] = 'La contraseña es obligatoria.';
    } elseif (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    
    // Validar confirmación de contraseña
    if ($password !== $password_confirm) {
        $errores[] = 'Las contraseñas no coinciden.';
    }
    
    // Si no hay errores, registrar al usuario
    if (empty($errores)) {
        $conexion = conectarDB();
        
        // Encriptar la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar el nuevo usuario
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$nombre, $email, $password_hash])) {
            // Redirigir a la página de login con un mensaje de éxito
            header('Location: login.php?mensaje=registro_exitoso');
            exit;
        } else {
            $errores[] = 'Error al registrar el usuario. Por favor, inténtalo de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Registro</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="form-container">
            <h2>Crear una cuenta</h2>
            
            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="registroForm" method="POST" action="registro.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                    <span class="error-message" id="nombreError"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                    <span class="error-message" id="emailError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-message" id="passwordError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                    <span class="error-message" id="passwordConfirmError"></span>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
            </form>
            
            <p class="form-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
    
    <script src="js/validacion-registro.js"></script>
</body>
</html>