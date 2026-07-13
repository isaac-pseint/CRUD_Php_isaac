<?php
/**
 * Controlador: Eliminar Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: config/conexion.php
 * Descripción: Realiza un borrado lógico del empleado cambiando su estado a 'Inactivo'.
 */

require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        header("Location: ../views/ver_empleados.php?error=" . urlencode("ID de empleado no válido."));
        exit;
    }

    try {
        // Borrado lógico: actualizar el estado a Inactivo en lugar de DELETE FROM
        $sql = "UPDATE empleados SET estado = 'Inactivo' WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id]);

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
