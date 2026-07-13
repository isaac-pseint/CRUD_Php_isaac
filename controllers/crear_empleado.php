<?php
/**
 * Controlador: Crear Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: models/Empleado.php, models/Cargo.php, models/Departamento.php
 * Descripción: Recibe los datos del formulario (POST), valida y realiza la inserción
 *              en la base de datos usando el modelo Empleado.
 */

// Incluir los modelos necesarios
require_once __DIR__ . '/../models/Empleado.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener y sanitizar los datos enviados por el formulario
    $nombre_completo  = trim($_POST['nombre_completo'] ?? '');
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $correo           = trim(strtolower($_POST['correo'] ?? ''));
    $telefono         = trim($_POST['telefono'] ?? '');
    $cargo_id         = (int)($_POST['cargo_id'] ?? 0);
    $departamento_id  = (int)($_POST['departamento_id'] ?? 0);
    $fecha_ingreso    = $_POST['fecha_ingreso'] ?? '';
    $salario_base     = (float)($_POST['salario_base'] ?? 0);

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

    // Verificar unicidad de documento y correo antes de insertar
    if (empty($errores) && Empleado::documentoExiste($numero_documento)) {
        $errores[] = "El número de documento ya está registrado.";
    }
    if (empty($errores) && Empleado::correoExiste($correo)) {
        $errores[] = "El correo electrónico ya está registrado.";
    }

    // Si hay errores de validación, redirigir con el primer error
    if (!empty($errores)) {
        header("Location: ../views/formulario_crear.php?error=" . urlencode(implode(' ', $errores)));
        exit;
    }

    // ── Guardar mediante el modelo ───────────────────────────────
    try {
        $empleado = new Empleado();
        $empleado->setNombreCompleto($nombre_completo);
        $empleado->setNumeroDocumento($numero_documento);
        $empleado->setCorreo($correo);
        $empleado->setTelefono($telefono);
        $empleado->setCargoId($cargo_id);
        $empleado->setDepartamentoId($departamento_id);
        $empleado->setFechaIngreso($fecha_ingreso);
        $empleado->setSalarioBase($salario_base);
        // estado por defecto ya es 'activo' en el constructor

        $empleado->guardar();

        // Redirigir a la vista de lista de empleados si todo sale bien
        header("Location: ../views/ver_empleados.php?success=creado");
        exit;

    } catch (PDOException $e) {
        // Registrar el error real en el log (invisible para el usuario)
        error_log("Error al crear empleado: " . $e->getMessage());
        
        // Comprobar si el error es por duplicidad (Código SQLSTATE 23000)
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
