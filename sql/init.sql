-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS gestor_tareas;
USE gestor_tareas;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tareas
CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('pendiente', 'en_progreso', 'completada') NOT NULL DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta') NOT NULL DEFAULT 'media',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_limite DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Datos de prueba (usuarios)
INSERT INTO usuarios (email, password, nombre) VALUES 
('usuario1@ejemplo.com', '$2y$10$tSPV1JQ3Ij/OTkrkIFvCJeQQ4T1IW0DhPXm8FXdgr1SL1JlKnrFWC', 'Usuario Ejemplo 1'), -- contraseña: password123
('usuario2@ejemplo.com', '$2y$10$tSPV1JQ3Ij/OTkrkIFvCJeQQ4T1IW0DhPXm8FXdgr1SL1JlKnrFWC', 'Usuario Ejemplo 2'); -- contraseña: password123

-- Datos de prueba (tareas)
INSERT INTO tareas (usuario_id, titulo, descripcion, estado, prioridad, fecha_limite) VALUES
(1, 'Completar informe', 'Finalizar el informe trimestral para el departamento', 'pendiente', 'alta', '2025-04-15'),
(1, 'Reunión de equipo', 'Preparar agenda para la reunión semanal', 'en_progreso', 'media', '2025-04-12'),
(2, 'Actualizar base de datos', 'Realizar backup y actualización de registros', 'pendiente', 'alta', '2025-04-20'),
(2, 'Revisar documentación', 'Leer y revisar la nueva documentación del proyecto', 'completada', 'baja', '2025-04-05');