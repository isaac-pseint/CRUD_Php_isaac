<?php
session_start();

if (!isset($_SESSION['id_empleado'])) {
    header('Location: ../views/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .denied-container {
            max-width: 500px;
            margin: 100px auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container denied-container">
        <div class="card">
            <h2 style="color: var(--danger-color); margin-bottom: 1rem;">Acceso Denegado</h2>
            <p style="margin-bottom: 2rem;">No tienes los permisos suficientes para realizar esta acción o acceder a esta página.</p>
            <a href="ver_empleados.php" class="btn btn-primary">Volver al Directorio</a>
        </div>
    </div>
</body>
</html>
