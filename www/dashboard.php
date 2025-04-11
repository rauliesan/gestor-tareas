<?php
require_once 'includes/config.php';

// Verificar que el usuario esté autenticado
requiereAutenticacion();

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'];
$usuario_nombre = $_SESSION['usuario_nombre'];

// Obtener las tareas del usuario (usando PDO en lugar de mysqli)
$conexion = conectarDB();
$stmt = $conexion->prepare("SELECT id, titulo, descripcion, estado, prioridad, fecha_creacion, fecha_limite FROM tareas WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmt->execute([$usuario_id]);
$tareas = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Panel de Control</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="active">Mis Tareas</a></li>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="dashboard-header">
            <h2>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></h2>
            <a href="tarea-nueva.php" class="btn btn-primary">Nueva Tarea</a>
        </div>
        
        <section class="tareas-container">
            <h3>Mis Tareas</h3>
            
            <div class="filtros">
                <button class="btn btn-filter active" data-filter="todas">Todas</button>
                <button class="btn btn-filter" data-filter="pendiente">Pendientes</button>
                <button class="btn btn-filter" data-filter="en_progreso">En Progreso</button>
                <button class="btn btn-filter" data-filter="completada">Completadas</button>
            </div>
            
            <?php if (empty($tareas)): ?>
                <div class="no-tareas">
                    <p>No tienes tareas pendientes. ¡Crea una nueva tarea!</p>
                </div>
            <?php else: ?>
                <div class="tareas-list">
                    <?php foreach ($tareas as $tarea): ?>
                        <div class="tarea-card" data-estado="<?php echo $tarea['estado']; ?>">
                            <div class="tarea-header">
                                <h4><?php echo htmlspecialchars($tarea['titulo']); ?></h4>
                                <span class="prioridad prioridad-<?php echo $tarea['prioridad']; ?>">
                                    <?php echo ucfirst($tarea['prioridad']); ?>
                                </span>
                            </div>
                            
                            <div class="tarea-content">
                                <p><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                            </div>
                            
                            <div class="tarea-footer">
                                <div class="tarea-estado">
                                    Estado: <span class="estado estado-<?php echo $tarea['estado']; ?>">
                                        <?php 
                                            $estados = [
                                                'pendiente' => 'Pendiente',
                                                'en_progreso' => 'En Progreso',
                                                'completada' => 'Completada'
                                            ];
                                            echo $estados[$tarea['estado']];
                                        ?>
                                    </span>
                                </div>
                                
                                <?php if (!empty($tarea['fecha_limite'])): ?>
                                    <div class="tarea-fecha">
                                        Fecha límite: <?php echo date('d/m/Y', strtotime($tarea['fecha_limite'])); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="tarea-acciones">
                                    <a href="tarea-editar.php?id=<?php echo $tarea['id']; ?>" class="btn btn-small btn-edit">Editar</a>
                                    <a href="tarea-eliminar.php?id=<?php echo $tarea['id']; ?>" class="btn btn-small btn-delete" data-id="<?php echo $tarea['id']; ?>">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - <?php echo SITE_NAME; ?></p>
        </div>
    </footer>
    
    <script src="js/dashboard.js"></script>
</body>
</html>