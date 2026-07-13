<?php
require_once __DIR__ . '/../config/verificar_sesion.php';
require_once __DIR__ . '/../config/verificar_permisos.php';
$es_admin = esAdministrador($_SESSION['cargo_id'] ?? 0);
/**
 * Vista: Listado de Empleados
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: models/Empleado.php
 * Descripción: Muestra en una tabla HTML los empleados cuyo estado es 'activo'.
 */

require_once __DIR__ . '/../models/Empleado.php';

try {
    // Obtener solo empleados activos usando el modelo
    $empleados = Empleado::getAllActivos();
} catch (PDOException $e) {
    error_log("Error al consultar empleados: " . $e->getMessage());
    $error_bd = "Ocurrió un error al cargar la lista de empleados.";
    $empleados = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>ServiPlus S.A.</h1>
            <div>
                <span style="margin-right: 15px;">Hola, <strong><?= htmlspecialchars($_SESSION['nombre_completo']) ?></strong></span>
                <?php if ($es_admin): ?>
                    <a href="formulario_crear.php" class="btn btn-primary">Registrar Empleado</a>
                <?php endif; ?>
                <a href="../controllers/logout.php" class="btn btn-secondary" style="margin-left: 10px;">Cerrar sesión</a>
            </div>
        </header>

        <main>
            <div class="card">
                <h2>Directorio de Empleados Activos</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        Operación realizada con éxito.
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_bd)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_bd) ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Cargo</th>
                                <th>Departamento</th>
                                <th>Fecha Ingreso</th>
                                <th>Teléfono</th>
                                <?php if ($es_admin): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($empleados) > 0): ?>
                                <?php foreach ($empleados as $emp): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emp['nombre_completo']) ?></td>
                                        <td><?= htmlspecialchars($emp['numero_documento']) ?></td>
                                        <td><?= htmlspecialchars($emp['nombre_cargo']) ?></td>
                                        <td><?= htmlspecialchars($emp['nombre_departamento']) ?></td>
                                        <td><?= htmlspecialchars($emp['fecha_ingreso']) ?></td>
                                        <td><?= htmlspecialchars($emp['telefono']) ?></td>
                                        <?php if ($es_admin): ?>
                                            <td class="actions">
                                                <a href="formulario_editar.php?id=<?= urlencode($emp['id_empleado']) ?>" class="btn btn-secondary btn-sm">Editar</a>
                                                <!-- Botón eliminar mediante formulario POST para mayor seguridad -->
                                                <form action="../controllers/eliminar_empleado.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este empleado?');">
                                                    <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($emp['id_empleado']) ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center mt-4 mb-4">No hay empleados registrados o activos en el sistema.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
