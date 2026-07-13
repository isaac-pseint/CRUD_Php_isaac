<?php
/**
 * Controlador: Editar Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: models/Empleado.php
 * Descripción: Recibe los datos del formulario de edición (POST), valida y actualiza
 *              el registro en la BD usando el modelo Empleado.
 */

require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../config/verificar_permisos.php';
verificarPermisoAdministrador();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id               = (int)($_POST['id_empleado'] ?? 0);
    $nombre_completo  = trim($_POST['nombre_completo'] ?? '');
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $correo           = trim(strtolower($_POST['correo'] ?? ''));
    $telefono         = trim($_POST['telefono'] ?? '');
    $cargo_id         = (int)($_POST['cargo_id'] ?? 0);
    $departamento_id  = (int)($_POST['departamento_id'] ?? 0);
    $fecha_ingreso    = $_POST['fecha_ingreso'] ?? '';
    $salario_base     = (float)($_POST['salario_base'] ?? 0);

    if ($id <= 0) {
        header("Location: ../views/ver_empleados.php?error=" . urlencode("ID de empleado no válido."));
        exit;
    }

    // ── Validaciones de backend ──────────────────────────────────
    $errores = [];

    if ($nombre_completo === '') {
        $errores[] = "El nombre completo es obligatorio.";
    }
    if ($numero_documento === '') {
        $errores[] = "El número de documento es obligatorio.";
    }
    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Debe ingresar un correo electrónico válido.";
    }
    if ($cargo_id <= 0) {
        $errores[] = "Debe seleccionar un cargo válido.";
    }
    if ($departamento_id <= 0) {
        $errores[] = "Debe seleccionar un departamento válido.";
    }
    if ($fecha_ingreso === '') {
        $errores[] = "La fecha de ingreso es obligatoria.";
    }
    if ($salario_base < 0) {
        $errores[] = "El salario no puede ser negativo.";
    }

    // Verificar unicidad excluyendo el registro actual
    if (empty($errores) && Empleado::documentoExiste($numero_documento, $id)) {
        $errores[] = "El número de documento ya está registrado por otro empleado.";
    }
    if (empty($errores) && Empleado::correoExiste($correo, $id)) {
        $errores[] = "El correo electrónico ya está registrado por otro empleado.";
    }

    if (!empty($errores)) {
        header("Location: ../views/formulario_editar.php?id=" . urlencode($id) . "&error=" . urlencode(implode(' ', $errores)));
        exit;
    }

    // ── Actualizar mediante el modelo ────────────────────────────
    try {
        $empleado = new Empleado();
        $empleado->setIdEmpleado($id);
        $empleado->setNombreCompleto($nombre_completo);
        $empleado->setNumeroDocumento($numero_documento);
        $empleado->setCorreo($correo);
        $empleado->setTelefono($telefono);
        $empleado->setCargoId($cargo_id);
        $empleado->setDepartamentoId($departamento_id);
        $empleado->setFechaIngreso($fecha_ingreso);
        $empleado->setSalarioBase($salario_base);

        // Preservar el estado y campos opcionales del registro existente
        $existente = Empleado::getById($id);
        if ($existente) {
            $empleado->setEstado($existente['estado']);
            $empleado->setFotoRuta($existente['foto_ruta'] ?? null);
            $empleado->setPasswordHash($existente['password_hash'] ?? null);
        }

        $empleado->guardar();

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
