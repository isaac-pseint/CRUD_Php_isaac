<?php
/**
 * Vista: Formulario de Creación de Empleado
 * Módulo: Empleados (Prioridad 1)
 * Dependencias: Ninguna directa de BD aquí. Envía POST a controllers/crear_empleado.php
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado - ServiPlus S.A.</title>
    <!-- Se asume que el enrutamiento base es la carpeta public/raiz del proyecto -->
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
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono">
                        </div>

                        <div class="form-group">
                            <label for="cargo">Cargo *</label>
                            <select id="cargo" name="cargo" required>
                                <option value="">Seleccione...</option>
                                <option value="Técnico">Técnico</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Operario">Operario</option>
                                <option value="Asistente">Asistente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="area">Área *</label>
                            <select id="area" name="area" required>
                                <option value="">Seleccione...</option>
                                <option value="Electricidad">Electricidad</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="RRHH">RRHH</option>
                                <option value="Contabilidad">Contabilidad</option>
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
