<?php
require_once 'includes/config.php';

// Si el usuario ya está autenticado, redirigir al dashboard
if (estaAutenticado()) {
    header('Location: dashboard.php');
    exit;
}

$errores = [];
$email = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $email = limpiarDato($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validar email
    if (empty($email)) {
        $errores[] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El formato del email no es válido.';
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errores[] = 'La contraseña es obligatoria.';
    }
    
    // Si no hay errores, verificar las credenciales
    if (empty($errores)) {
        $conexion = conectarDB();
        
        // Buscar el usuario por email
        $stmt = $conexion->prepare("SELECT id, email, nombre, password FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($password, $usuario['password'])) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                
                // Redirigir al dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $errores[] = 'Contraseña incorrecta.';
            }
        } else {
            $errores[] = 'No existe ningún usuario con este email.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Iniciar Sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="form-container">
            <h2>Iniciar Sesión</h2>
            
            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="login.php">
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
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
            
            <p class="form-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
    
    <script src="js/validacion-login.js"></script>
</body>
</html>