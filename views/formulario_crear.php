<?php
require_once __DIR__ . '/../config/verificar_sesion.php';
require_once __DIR__ . '/../config/verificar_permisos.php';
verificarPermisoAdministrador();
/**
 * Vista: Formulario de Creación de Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: models/Cargo.php, models/Departamento.php
 * Descripción: Carga dinámicamente cargos y departamentos desde la BD
 *              y envía POST a controllers/crear_empleado.php
 */

require_once __DIR__ . '/../models/Cargo.php';
require_once __DIR__ . '/../models/Departamento.php';

// Cargar cargos y departamentos dinámicamente desde la BD
try {
    $cargos = Cargo::getAll();
} catch (PDOException $e) {
    error_log("Error al cargar cargos: " . $e->getMessage());
    $cargos = [];
}

try {
    $departamentos = Departamento::getAll();
} catch (PDOException $e) {
    error_log("Error al cargar departamentos: " . $e->getMessage());
    $departamentos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado - ServiPlus S.A.</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>ServiPlus S.A.</h1>
            <a href="ver_empleados.php" class="btn btn-secondary">Ver Empleados</a>
        </header>

        <main>
            <div class="card">
                <h2>Registrar Nuevo Empleado</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="../controllers/crear_empleado.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo *</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" required>
                        </div>

                        <div class="form-group">
                            <label for="numero_documento">Número de Documento *</label>
                            <input type="text" id="numero_documento" name="numero_documento" required>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo Electrónico *</label>
                            <input type="email" id="correo" name="correo" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" id="password" name="password" minlength="6" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono">
                        </div>

                        <div class="form-group">
                            <label for="cargo_id">Cargo *</label>
                            <select id="cargo_id" name="cargo_id" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= htmlspecialchars($cargo['id_cargo']) ?>">
                                        <?= htmlspecialchars($cargo['nombre_cargo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="departamento_id">Departamento *</label>
                            <select id="departamento_id" name="departamento_id" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($departamentos as $depto): ?>
                                    <option value="<?= htmlspecialchars($depto['id_departamento']) ?>">
                                        <?= htmlspecialchars($depto['nombre_departamento']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de Ingreso *</label>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
                        </div>

                        <div class="form-group">
                            <label for="salario_base">Salario Base *</label>
                            <input type="number" step="0.01" min="0" id="salario_base" name="salario_base" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Guardar Empleado</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
