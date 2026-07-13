<?php
/**
 * Vista: Listado de Empleados
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: config/conexion.php
 * Descripción: Muestra en una tabla HTML los empleados cuyo estado es 'Activo'.
 */

require_once '../config/conexion.php';

try {
    // Consulta para obtener solo empleados activos (Prioridad 1)
    $sql = "SELECT * FROM empleados WHERE estado = 'Activo' ORDER BY id DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $empleados = $stmt->fetchAll();
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
            <a href="formulario_crear.php" class="btn btn-primary">Registrar Empleado</a>
        </header>

        <main>
            <div class="card">
                <h2>Directorio de Empleados Activos</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        Operación realizada con éxito.
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
                                <th>Área</th>
                                <th>Fecha Ingreso</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($empleados) > 0): ?>
                                <?php foreach ($empleados as $emp): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emp['nombre_completo']) ?></td>
                                        <td><?= htmlspecialchars($emp['numero_documento']) ?></td>
                                        <td><?= htmlspecialchars($emp['cargo']) ?></td>
                                        <td><?= htmlspecialchars($emp['area']) ?></td>
                                        <td><?= htmlspecialchars($emp['fecha_ingreso']) ?></td>
                                        <td><?= htmlspecialchars($emp['telefono']) ?></td>
                                        <td class="actions">
                                            <a href="formulario_editar.php?id=<?= urlencode($emp['id']) ?>" class="btn btn-secondary btn-sm">Editar</a>
                                            <!-- Botón eliminar mediante formulario POST para mayor seguridad -->
                                            <form action="../controllers/eliminar_empleado.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este empleado?');">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($emp['id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
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
