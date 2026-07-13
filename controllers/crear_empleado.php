<?php
/**
 * Controlador: Crear Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: config/conexion.php
 * Descripción: Recibe los datos del formulario (POST), y realiza la inserción en la base de datos usando consultas preparadas.
 */

// Incluir la conexión a la base de datos
require_once '../config/conexion.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener los datos enviados por el formulario (Prioridad 1: asignación directa, sanitización fina va en Prioridad 2)
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $area = $_POST['area'] ?? '';
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
    $salario_base = $_POST['salario_base'] ?? 0;

    try {
        // Preparar la consulta SQL usando placeholders (?) para evitar inyección SQL
        // Nota: en esta fase, cargo y área son ENUM.
        $sql = "INSERT INTO empleados 
                (nombre_completo, numero_documento, cargo, area, fecha_ingreso, salario_base, correo, telefono) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        
        // Ejecutar la consulta pasando el array de valores en el mismo orden que los placeholders
        $stmt->execute([
            $nombre_completo,
            $numero_documento,
            $cargo,
            $area,
            $fecha_ingreso,
            $salario_base,
            $correo,
            $telefono
        ]);

        // Redirigir a la vista de lista de empleados si todo sale bien
        header("Location: ../views/ver_empleados.php?success=creado");
        exit;

    } catch (PDOException $e) {
        // Registrar el error real en el log (invisible para el usuario)
        error_log("Error al crear empleado: " . $e->getMessage());
        
        // Comprobar si el error es por duplicidad de número de documento o correo (Código SQLSTATE 23000)
        if ($e->getCode() == 23000) {
            $mensaje = "El número de documento o el correo ya están registrados.";
        } else {
            $mensaje = "Ocurrió un error al guardar el empleado. Intente nuevamente.";
        }

        // Redirigir de vuelta al formulario con el mensaje de error
        header("Location: ../views/formulario_crear.php?error=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si no es POST, redirigir al formulario
    header("Location: ../views/formulario_crear.php");
    exit;
}
