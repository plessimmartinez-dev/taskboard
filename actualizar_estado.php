<?php
session_start();
require 'conexion.php';

// Indicamos al navegador que la respuesta será JSON
header('Content-Type: application/json');

// Verificamos sesión y existencia de datos por POST [cite: 368]
if (isset($_POST['id']) && isset($_POST['nuevo_estado']) && isset($_SESSION['usuario_id'])) {
    $id_tarea = $_POST['id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    try {
        // Lógica de negocio en el backend con PDO [cite: 25, 363]
        $sql = "UPDATE tarea SET estado = :estado WHERE id_tarea = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id', $id_tarea);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        // En caso de error, devolvemos el mensaje al frontend [cite: 215]
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Si faltan datos o no hay sesión
    echo json_encode(['success' => false, 'error' => 'Acceso no autorizado o datos incompletos']);
}
?>