<?php
// Estas dos líneas obligan a PHP a mostrar los errores en pantalla
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$usuario = "root"; 
$password = ""; 
$base_de_datos = "taskboard_db"; 

try {
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h1>¡Conexión exitosa a la base de datos! 🚀</h1>";
} catch(PDOException $e) {
    echo "<h1>⚠️ Error de conexión:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>