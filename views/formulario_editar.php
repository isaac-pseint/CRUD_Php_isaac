<?php
/**
 * Vista: Formulario de Edición de Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: config/conexion.php
 * Descripción: Muestra un formulario precargado con los datos de un empleado, para ser actualizado.
 */

require_once '../config/conexion.php';

// Obtener el ID del empleado desde GET
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ver_empleados.php");
    exit;
}

try {
    // Consulta para obtener los datos del empleado (uso de consultas preparadas)
    $sql = "SELECT * FROM empleados WHERE id = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $empleado = $stmt->fetch();

    if (!$empleado) {
        header("Location: ver_empleados.php?error=" . urlencode("Empleado no encontrado."));
        exit;
    }
} catch (PDOException $e) {
    error_log("Error al cargar empleado para editar: " . $e->getMessage());
    header("Location: ver_empleados.php?error=" . urlencode("Error al cargar datos del empleado."));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>ServiPlus S.A.</h1>
            <a href="ver_empleados.php" class="btn btn-secondary">Volver al Listado</a>
        </header>

        <main>
            <div class="card">
                <h2>Editar Empleado</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="../controllers/editar_empleado.php" method="POST">
                    <!-- Campo oculto con el ID del empleado a editar -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($empleado['id']) ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo *</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" value="<?= htmlspecialchars($empleado['nombre_completo']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="numero_documento">Número de Documento *</label>
                            <input type="text" id="numero_documento" name="numero_documento" value="<?= htmlspecialchars($empleado['numero_documento']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo Electrónico *</label>
                            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($empleado['correo']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="cargo">Cargo *</label>
                            <select id="cargo" name="cargo" required>
                                <option value="Técnico" <?= $empleado['cargo'] == 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                                <option value="Administrador" <?= $empleado['cargo'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                                <option value="Operario" <?= $empleado['cargo'] == 'Operario' ? 'selected' : '' ?>>Operario</option>
                                <option value="Asistente" <?= $empleado['cargo'] == 'Asistente' ? 'selected' : '' ?>>Asistente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="area">Área *</label>
                            <select id="area" name="area" required>
                                <option value="Electricidad" <?= $empleado['area'] == 'Electricidad' ? 'selected' : '' ?>>Electricidad</option>
                                <option value="Mantenimiento" <?= $empleado['area'] == 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                                <option value="RRHH" <?= $empleado['area'] == 'RRHH' ? 'selected' : '' ?>>RRHH</option>
                                <option value="Contabilidad" <?= $empleado['area'] == 'Contabilidad' ? 'selected' : '' ?>>Contabilidad</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de Ingreso *</label>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?= htmlspecialchars($empleado['fecha_ingreso']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="salario_base">Salario Base *</label>
                            <input type="number" step="0.01" min="0" id="salario_base" name="salario_base" value="<?= htmlspecialchars($empleado['salario_base']) ?>" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
