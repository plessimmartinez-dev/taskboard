<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TaskBoard - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1E1E2E; color: #FFFFFF; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .login-card { background-color: #454548; border-radius: 12px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); }
        .form-control { background-color: #2D2D3A; border: 1px solid #555; color: #FFF; }
        .form-control:focus { background-color: #2D2D3A; color: #FFF; border-color: #00ADB5; box-shadow: 0 0 0 0.25rem rgba(0, 173, 181, 0.25); }
        .btn-primary { background-color: #00ADB5; border: none; width: 100%; font-weight: bold; padding: 10px; }
        .btn-primary:hover { background-color: #008C93; }
        .enlace-registro { color: #00ADB5; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <h2 class="mb-4 fw-bold">Crear Cuenta</h2>
        
        <form action="procesar_registro.php" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Registrarse</button>
        </form>
        
        <a href="index.php" class="enlace-registro">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</body>
</html>