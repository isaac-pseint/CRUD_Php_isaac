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

    // Procesar fotografía
    $foto_ruta = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK && empty($errores)) {
        $tmp_name = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        
        $mime = mime_content_type($tmp_name);
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!in_array($mime, $allowed_mimes)) {
            $errores[] = "Formato de fotografía inválido. Solo se permiten .jpg, .jpeg y .png.";
        } else {
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $nombre_unico = date("His") . round(microtime(true) * 1000) . "." . $ext;
            $directorio = __DIR__ . '/../assets/fotos_empleados/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $ruta_destino = $directorio . $nombre_unico;
            
            if (move_uploaded_file($tmp_name, $ruta_destino)) {
                $foto_ruta = 'assets/fotos_empleados/' . $nombre_unico;
                
                // Borrar foto anterior
                $existente_para_borrar = Empleado::getById($id);
                if ($existente_para_borrar && !empty($existente_para_borrar['foto_ruta'])) {
                    $ruta_anterior = __DIR__ . '/../' . $existente_para_borrar['foto_ruta'];
                    if (file_exists($ruta_anterior)) {
                        unlink($ruta_anterior);
                    }
                }
            } else {
                $errores[] = "Error al subir la fotografía.";
            }
        }
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
            $empleado->setFotoRuta($foto_ruta ? $foto_ruta : ($existente['foto_ruta'] ?? null));
            $empleado->setPasswordHash($existente['password_hash'] ?? null);
        }

        $empleado->guardar();

        // Si el usuario editado es el mismo logueado, actualizar la sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id_empleado']) && $_SESSION['id_empleado'] == $id) {
            $_SESSION['nombre_completo'] = $nombre_completo;
            if ($foto_ruta) {
                $_SESSION['foto_ruta'] = $foto_ruta;
            }
        }

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
