<?php
session_start();
$url_destino = isset($_SESSION['id_empleado']) ? 'views/ver_empleados.php' : 'views/login.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .welcome-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 4rem 3rem;
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .welcome-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .welcome-subtitle {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }
        .welcome-logo {
            text-decoration: none;
            color: inherit;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .welcome-logo h1 {
            color: white;
            margin: 0;
            font-size: 2.5rem;
        }
        .btn-welcome {
            display: inline-block;
            background-color: white;
            color: var(--primary-color);
            font-size: 1.1rem;
            padding: 1rem 2.5rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            text-decoration: none;
        }
        .btn-welcome:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <a href="<?= $url_destino ?>" class="welcome-logo">
                <h1>ServiPlus S.A.</h1>
            </a>
            <h2 class="welcome-title">Bienvenido</h2>
            <p class="welcome-subtitle">Sistema integral de gestión de empleados y recursos humanos. Accede para continuar.</p>
            <a href="<?= $url_destino ?>" class="btn-welcome">
                <?= isset($_SESSION['id_empleado']) ? 'Ir al Panel de Empleados' : 'Iniciar Sesión' ?>
            </a>
        </div>
    </div>
</body>
</html>
