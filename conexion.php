<?php
$host = "127.0.0.1:3307"; // Forzamos el uso del puerto 3307
$usuario = "root"; 
$password = ""; 
$base_de_datos = "taskboard_db"; 

try {
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Borraremos el echo del mensaje de éxito para que el diseño quede limpio [cite: 496]
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>