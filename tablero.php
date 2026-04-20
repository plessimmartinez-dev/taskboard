<?php
session_start();
require 'conexion.php';

// Si no hay usuario, lo devolvemos al Login [cite: 368]
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

/**
 * Función para obtener tareas por estado
 * Esto evita repetir código para cada columna [cite: 26, 70]
 */
function obtenerTareas($conexion, $id_usuario, $estado) {
    $sql = "SELECT t.* FROM tarea t 
            INNER JOIN proyecto p ON t.id_proyecto = p.id_proyecto 
            WHERE p.id_usuario = :id_usuario AND t.estado = :estado";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':estado', $estado);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pendientes = obtenerTareas($conexion, $id_usuario, 'pendiente');
$en_progreso = obtenerTareas($conexion, $id_usuario, 'en_progreso');
$terminadas = obtenerTareas($conexion, $id_usuario, 'terminado');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TaskBoard - Tablero Kanban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1E1E2E; color: #FFFFFF; font-family: 'Inter', sans-serif; }
        .navbar { background-color: #2D2D3A; border-bottom: 1px solid #454548; }
        .kanban-column { background-color: #454548; border-radius: 8px; padding: 15px; min-height: 70vh; }
        .task-card { background-color: #2D2D3A; border-left: 4px solid #00ADB5; padding: 15px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .btn-primary { background-color: #00ADB5; border: none; }
        .btn-primary:hover { background-color: #008C93; }
        .modal-content { background-color: #2D2D3A; color: white; }
        .modal-header, .modal-footer { border-color: #454548; }
        .form-control { background-color: #1E1E2E; border: 1px solid #555; color: white; }
        .form-control:focus { background-color: #1E1E2E; color: white; border-color: #00ADB5; box-shadow: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-4 py-3">
        <a class="navbar-brand fw-bold" href="#">TaskBoard</a>
        <div class="d-flex align-items-center">
            <span class="me-3">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> 👋</span>
            <a href="cerrar_sesion.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container-fluid mt-4 px-4">
        <div class="mb-4">
            <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevaTarea">+ Nueva Tarea</button>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-center mb-3">PENDIENTE</h5>
                <div class="kanban-column">
                    <?php foreach ($pendientes as $tarea): ?>
                        <div class="task-card">
                            <h6 class="fw-bold"><?php echo htmlspecialchars($tarea['titulo']); ?></h6>
                            <p class="text-muted small"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                            <div class="d-flex justify-content-between mt-3">
                                <button onclick="moverTarea(<?php echo $tarea['id_tarea']; ?>, 'en_progreso')" class="btn btn-sm btn-info text-white">Empezar →</button>
                                <a href="eliminar_tarea.php?id=<?php echo $tarea['id_tarea']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar tarea?')">Borrar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="text-center mb-3">EN PROGRESO</h5>
                <div class="kanban-column">
                    <?php foreach ($en_progreso as $tarea): ?>
                        <div class="task-card" style="border-left-color: #ffc107;">
                            <h6 class="fw-bold"><?php echo htmlspecialchars($tarea['titulo']); ?></h6>
                            <p class="text-muted small"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                            <div class="d-flex justify-content-between mt-3">
                                <button onclick="moverTarea(<?php echo $tarea['id_tarea']; ?>, 'terminado')" class="btn btn-sm btn-success text-white">Finalizar ✓</button>
                                <a href="eliminar_tarea.php?id=<?php echo $tarea['id_tarea']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar tarea?')">Borrar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="text-center mb-3">TERMINADO</h5>
                <div class="kanban-column">
                    <?php foreach ($terminadas as $tarea): ?>
                        <div class="task-card" style="border-left-color: #28a745;">
                            <h6 class="fw-bold text-decoration-line-through"><?php echo htmlspecialchars($tarea['titulo']); ?></h6>
                            <p class="text-muted small"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                            <div class="text-end">
                                <a href="eliminar_tarea.php?id=<?php echo $tarea['id_tarea']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar tarea?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevaTarea" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Nueva Tarea</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="procesar_tarea.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function moverTarea(idTarea, estadoDestino) {
        const datos = new FormData();
        datos.append('id', idTarea);
        datos.append('nuevo_estado', estadoDestino);

        // AJAX con Fetch API [cite: 33, 132]
        fetch('actualizar_estado.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload(); 
            } else {
                alert("Error al actualizar: " + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>