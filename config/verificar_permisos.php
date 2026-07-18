<?php
require_once __DIR__ . '/../models/Cargo.php';

function esAdministrador($cargo_id) {
    if (!$cargo_id) return false;
    
    try {
        $cargo = Cargo::getById($cargo_id);
        if ($cargo && stripos($cargo['nombre_cargo'], 'admin') !== false) {
            return true;
     }
    } catch (Exception $e) {
        return false;
    }
    
    // Fallback: Asumimos que el cargo_id 1 es Administrador si no se puede verificar por nombre
    return $cargo_id == 1;
}

function verificarPermisoAdministrador() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['cargo_id']) || !esAdministrador($_SESSION['cargo_id'])) {
        header('Location: ../views/acceso_denegado.php');
        exit;
    }
}
