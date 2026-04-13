<?php
session_start();
require 'conexion.php';

// Si no hay usuario, lo devolvemos al Login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Leemos las tareas de la base de datos que pertenezcan a este usuario y estén "pendientes"
$sql = "SELECT t.* FROM tarea t 
        INNER JOIN proyecto p ON t.id_proyecto = p.id_proyecto 
        WHERE p.id_usuario = :id_usuario AND t.estado = 'pendiente'";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->execute();
$tareas_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TaskBoard - Tablero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1E1E2E; color: #FFFFFF; font-family: 'Inter', sans-serif; }
        .navbar { background-color: #2D2D3A; border-bottom: 1px solid #454548; }
        .kanban-column { background-color: #454548; border-radius: 8px; padding: 15px; min-height: 70vh; }
        .task-card { background-color: #2D2D3A; border-left: 4px solid #00ADB5; padding: 15px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .btn-primary { background-color: #00ADB5; border: none; }
        .btn-primary:hover { background-color: #008C93; }
        /* Estilos para que la ventana flotante (Modal) encaje con tu Dark Mode */
        .modal-content { background-color: #2D2D3A; color: white; }
        .modal-header { border-bottom: 1px solid #454548; }
        .modal-footer { border-top: 1px solid #454548; }
        .form-control { background-color: #1E1E2E; border: 1px solid #555; color: white; }
        .form-control:focus { background-color: #1E1E2E; color: white; border-color: #00ADB5; box-shadow: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-4 py-3">
        <a class="navbar-brand fw-bold" href="#">TaskBoard</a>
        <div class="d-flex align-items-center">
            <span class="me-3">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> 👋</span>
            <a href="index.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container-fluid mt-4 px-4">
        <div class="mb-4">
            <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevaTarea">+ Nueva Tarea</button>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <h5 class="text-center mb-3">PENDIENTE</h5>
                <div class="kanban-column">
                    <?php if (count($tareas_pendientes) > 0): ?>
                        <?php foreach ($tareas_pendientes as $tarea): ?>
                            <div class="task-card">
                                <h6 class="fw-bold"><?php echo htmlspecialchars($tarea['titulo']); ?></h6>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mt-3">No hay tareas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <h5 class="text-center mb-3">EN PROGRESO</h5>
                <div class="kanban-column"></div>
            </div>
            
            <div class="col-md-4">
                <h5 class="text-center mb-3">TERMINADO</h5>
                <div class="kanban-column"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevaTarea" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Nueva Tarea</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="procesar_tarea.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título de la tarea</label>
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
</body>
</html>