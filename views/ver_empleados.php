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
    <div class="container layout-with-aside">
        <header>
            <a href="<?= isset($_SESSION['id_empleado']) ? 'ver_empleados.php' : 'login.php' ?>" style="text-decoration: none;">
                <h1>ServiPlus S.A.</h1>
            </a>
        </header>

        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar" style="overflow: hidden;">
                    <?php if (!empty($_SESSION['foto_ruta'])): ?>
                        <img src="../<?= htmlspecialchars($_SESSION['foto_ruta']) ?>" alt="Foto de perfil" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <?= htmlspecialchars(strtoupper(substr($_SESSION['nombre_completo'] ?? 'U', 0, 1))) ?>
                    <?php endif; ?>
                </div>
                <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['nombre_completo']) ?></div>
                <div class="sidebar-user-role"><?= $es_admin ? 'Administrador' : 'Empleado' ?></div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="ver_empleados.php" class="active">Directorio</a></li>
                <?php if ($es_admin): ?>
                    <li><a href="formulario_crear.php">Registrar Empleado</a></li>
                <?php endif; ?>
                <li><a href="#">Cambiar Contraseña</a></li>
                <li><a href="../controllers/logout.php" class="text-danger" style="color: var(--danger-color);">Cerrar Sesión</a></li>
            </ul>
        </aside>

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
                                <th>Foto</th>
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
                                        <td>
                                            <?php if (!empty($emp['foto_ruta'])): ?>
                                                <img src="../<?= htmlspecialchars($emp['foto_ruta']) ?>" alt="Foto" width="40" height="40" style="border-radius: 50%; object-fit: cover; box-shadow: var(--shadow-sm);">
                                            <?php else: ?>
                                                <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: bold; font-size: 1rem;">
                                                    <?= htmlspecialchars(strtoupper(substr($emp['nombre_completo'], 0, 1))) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
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
