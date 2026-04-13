<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $conexion->prepare($sql);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        
        $stmt->execute();

        echo "<div style='background-color:#1E1E2E; color:white; height:100vh; text-align:center; padding-top:100px; font-family:sans-serif;'>";
        echo "<h1>¡Registro completado con éxito! 🎉</h1>";
        echo "<br><a href='index.php' style='color:#00ADB5; font-size:20px;'>Haz clic aquí para iniciar sesión</a>";
        echo "</div>";

    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            echo "<h1 style='color:red;'>El correo ya está registrado.</h1>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>