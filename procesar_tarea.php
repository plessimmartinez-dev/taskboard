<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['usuario_id'];

    try {
        // 1. Verificamos si el usuario ya tiene un proyecto.
        $sql_proy = "SELECT id_proyecto FROM proyecto WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt_proy = $conexion->prepare($sql_proy);
        $stmt_proy->bindParam(':id_usuario', $id_usuario);
        $stmt_proy->execute();
        $proyecto = $stmt_proy->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            $id_proyecto = $proyecto['id_proyecto'];
        } else {
            // Si no tiene, le creamos un "Tablero Principal" automáticamente
            $sql_nuevo = "INSERT INTO proyecto (nombre, descripcion, id_usuario) VALUES ('Tablero Principal', 'Proyecto por defecto', :id_usuario)";
            $stmt_nuevo = $conexion->prepare($sql_nuevo);
            $stmt_nuevo->bindParam(':id_usuario', $id_usuario);
            $stmt_nuevo->execute();
            $id_proyecto = $conexion->lastInsertId();
        }

        // 2. Guardamos la tarea asociada a su proyecto
        $sql_tarea = "INSERT INTO tarea (titulo, descripcion, id_proyecto, estado) VALUES (:titulo, :descripcion, :id_proyecto, 'pendiente')";
        $stmt_tarea = $conexion->prepare($sql_tarea);
        $stmt_tarea->bindParam(':titulo', $titulo);
        $stmt_tarea->bindParam(':descripcion', $descripcion);
        $stmt_tarea->bindParam(':id_proyecto', $id_proyecto);
        $stmt_tarea->execute();

        // Volvemos al tablero al instante
        header("Location: tablero.php");
        exit();

    } catch(PDOException $e) {
        echo "Error al guardar la tarea: " . $e->getMessage();
    }
}
?>