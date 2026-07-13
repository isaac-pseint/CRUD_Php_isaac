<?php
session_start();

// Si ya está logueado, redirigir al directorio
if (isset($_SESSION['id_empleado'])) {
    header('Location: ver_empleados.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <h2 class="text-center">ServiPlus S.A.</h2>
            <h3 class="text-center" style="color: var(--gray-500); margin-bottom: 1.5rem; font-weight: normal;">Inicio de Sesión</h3>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form action="../controllers/login.php" method="POST">
                <div class="form-group">
                    <label for="numero_documento">Número de Documento</label>
                    <input type="text" id="numero_documento" name="numero_documento" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
