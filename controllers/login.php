<?php
/**
 * Controlador: Login de Empleado
 * Módulo: Autenticación
 * Dependencias: models/Empleado.php
 */

require_once __DIR__ . '/../models/Empleado.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($numero_documento === '' || $password === '') {
        header("Location: ../views/login.php?error=" . urlencode("Debe ingresar documento y contraseña."));
        exit;
    }

    try {
        $empleado = Empleado::getByDocumento($numero_documento);

        if ($empleado) {
            
            // Verificar estado activo
            if ($empleado['estado'] !== 'activo') {
                header("Location: ../views/login.php?error=" . urlencode("El usuario está inactivo."));
                exit;
            }

            // Verificar contraseña
            if (password_verify($password, $empleado['password_hash'])) {
                // Autenticación exitosa, guardar variables en sesión
                $_SESSION['id_empleado'] = $empleado['id_empleado'];
                $_SESSION['nombre_completo'] = $empleado['nombre_completo'];
                $_SESSION['cargo_id'] = $empleado['cargo_id'];
                $_SESSION['departamento_id'] = $empleado['departamento_id'];

                // Redirigir a la vista principal
                header("Location: ../views/ver_empleados.php");
                exit;
            } else {
                // Contraseña incorrecta
                header("Location: ../views/login.php?error=" . urlencode("Credenciales incorrectas."));
                exit;
            }
        } else {
            // Usuario no encontrado
            header("Location: ../views/login.php?error=" . urlencode("Credenciales incorrectas."));
            exit;
        }

    } catch (PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        header("Location: ../views/login.php?error=" . urlencode("Ocurrió un error en el sistema."));
        exit;
    }
} else {
    header("Location: ../views/login.php");
    exit;
}
