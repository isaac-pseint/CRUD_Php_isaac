<?php
/**
 * Controlador: Editar Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: config/conexion.php
 * Descripción: Recibe los datos del formulario de edición (POST), y actualiza el registro en la BD.
 */

require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $area = $_POST['area'] ?? '';
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
    $salario_base = $_POST['salario_base'] ?? 0;

    if (!$id) {
        header("Location: ../views/ver_empleados.php?error=" . urlencode("ID de empleado no válido."));
        exit;
    }

    try {
        $sql = "UPDATE empleados SET 
                    nombre_completo = ?, 
                    numero_documento = ?, 
                    cargo = ?, 
                    area = ?, 
                    fecha_ingreso = ?, 
                    salario_base = ?, 
                    correo = ?, 
                    telefono = ?
                WHERE id = ?";
                
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $nombre_completo,
            $numero_documento,
            $cargo,
            $area,
            $fecha_ingreso,
            $salario_base,
            $correo,
            $telefono,
            $id
        ]);

        header("Location: ../views/ver_empleados.php?success=actualizado");
        exit;
    } catch (PDOException $e) {
        error_log("Error al actualizar empleado: " . $e->getMessage());
        
        if ($e->getCode() == 23000) {
            $mensaje = "El número de documento o el correo ya están registrados por otro empleado.";
        } else {
            $mensaje = "Ocurrió un error al actualizar el empleado.";
        }

        header("Location: ../views/formulario_editar.php?id=" . urlencode($id) . "&error=" . urlencode($mensaje));
        exit;
    }
} else {
    header("Location: ../views/ver_empleados.php");
    exit;
}
