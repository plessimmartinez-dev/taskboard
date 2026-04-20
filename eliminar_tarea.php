<?php
session_start();
require 'conexion.php';

// Verificamos que el usuario tenga sesión iniciada [cite: 424-425]
if (isset($_GET['id']) && isset($_SESSION['usuario_id'])) {
    $id_tarea = $_GET['id'];
    
    try {
        // Borramos la tarea usando sentencias preparadas (PDO) para seguridad [cite: 420-421]
        $sql = "DELETE FROM tarea WHERE id_tarea = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id_tarea);
        $stmt->execute();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
// Redirigimos de vuelta al tablero al terminar
header("Location: tablero.php");
exit();
?>