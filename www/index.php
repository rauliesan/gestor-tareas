<?php
require_once 'includes/config.php';

// Si el usuario ya está autenticado, redirigir al dashboard
if (estaAutenticado()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="welcome">
            <h2>Bienvenido al Gestor de Tareas</h2>
            <p>Una aplicación que te ayuda a gestionar tus tareas de forma eficiente.</p>
            <div class="cta-buttons">
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                <a href="registro.php" class="btn btn-secondary">Crear Cuenta</a>
            </div>
        </section>
        
        <section class="features">
            <h3>Características</h3>
            <div class="feature-list">
                <div class="feature">
                    <h4>Gestión Simple</h4>
                    <p>Crea, edita y elimina tus tareas de manera intuitiva.</p>
                </div>
                <div class="feature">
                    <h4>Organización</h4>
                    <p>Establece prioridades y fechas límite para tus tareas.</p>
                </div>
                <div class="feature">
                    <h4>Seguimiento</h4>
                    <p>Visualiza el estado de todas tus tareas en un solo lugar.</p>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
</body>
</html>