<?php
session_start();
require 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT id_usuario, nombre, password FROM usuario WHERE email = :email";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            
            // Si entra bien, lo mandamos al tablero
            header("Location: tablero.php");
            exit();
        } else {
            echo "<h1 style='color: white; background: #1E1E2E; height: 100vh; text-align: center; padding-top: 50px;'>⚠️ Error: Email o contraseña incorrectos.</h1>";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>