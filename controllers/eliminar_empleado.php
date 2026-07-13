<?php
/**
 * Controlador: Eliminar Empleado (Borrado Lógico)
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: models/Empleado.php
 * Descripción: Realiza un borrado lógico del empleado cambiando su estado a 'inactivo'
 *              utilizando el modelo Empleado.
 */

require_once __DIR__ . '/../models/Empleado.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id_empleado'] ?? 0);

    if ($id <= 0) {
        header("Location: ../views/ver_empleados.php?error=" . urlencode("ID de empleado no válido."));
        exit;
    }

    try {
        // Borrado lógico: actualizar el estado a 'inactivo' usando el modelo
        Empleado::desactivar($id);

        header("Location: ../views/ver_empleados.php?success=eliminado");
        exit;
    } catch (PDOException $e) {
        error_log("Error al eliminar (borrado lógico) empleado: " . $e->getMessage());
        $mensaje = "Ocurrió un error al intentar eliminar el empleado.";
        header("Location: ../views/ver_empleados.php?error=" . urlencode($mensaje));
        exit;
    }
} else {
    header("Location: ../views/ver_empleados.php");
    exit;
}
