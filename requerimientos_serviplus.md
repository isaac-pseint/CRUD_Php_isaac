# ServiPlus S.A. — Requerimientos del Proyecto
### Objetivos y Tareas Ordenados por Prioridad

---

## 🔴 Prioridad 1 — Base del Proyecto y CRUD de Empleados

**Objetivo:** Establecer la arquitectura base y digitalizar el registro de empleados (reemplazar hojas de cálculo).

- [*ø] Crear estructura de carpetas del proyecto: `config/`, `controllers/`, `models/`, `views/`, `assets/`, `libs/`
- [*] Implementar `config/conexion.php` (o `models/MySQL.php`) para centralizar la conexión a la base de datos MySQL
- [*] Crear tabla `empleados` con los campos:
  - ID (autogenerado)
  - Nombre completo
  - Número de documento (**único**)
  - Cargo (Técnico, Administrador, Operario, Asistente)
  - Área (Electricidad, Mantenimiento, RRHH, Contabilidad)
  - Fecha de ingreso
  - Salario base
  - Estado (activo/inactivo, por defecto "Activo")
  - Correo electrónico (**único**)
  - Teléfono
- [ ] Implementar operación **Crear** (`formulario_crear.php` + controlador)
- [ ] Implementar operación **Leer** (`ver_empleados.php` — listado en tabla HTML)
- [ ] Implementar operación **Actualizar** (`formulario_editar.php` + controlador)
- [ ] Implementar operación **Eliminar** (marcar como inactivo, no borrado físico)

---

## 🟠 Prioridad 2 — Validación y Sanitización (Backend)

**Objetivo:** Evitar datos corruptos o inválidos en la base de datos.

- [ ] Validar que el nombre completo no esté vacío
- [ ] Validar que el número de documento no esté repetido (unicidad)
- [ ] Validar formato correcto de correo electrónico y unicidad
- [ ] Validar formato correcto de fecha de ingreso
- [ ] Validar que el salario sea numérico y no negativo
- [ ] Sanitizar entradas para evitar caracteres no deseados / inyección de datos
- [ ] Asegurar que todos los campos obligatorios estén diligenciados antes de insertar

---

## 🟠 Prioridad 3 — Normalización de la Base de Datos

**Objetivo:** Eliminar redundancia y errores de escritura en "cargo" y "área/departamento".

- [ ] Crear tabla `cargo` (relación 1 a muchos con `empleados`)
- [ ] Crear tabla `departamento` (relación 1 a muchos con `empleados`)
- [ ] Migrar `empleados` para referenciar `cargo_id` y `departamento_id` (llaves foráneas)
- [ ] Modificar formularios (`formulario_crear.php`, `formulario_editar.php`) para cargar cargos y departamentos **dinámicamente** desde la base de datos (ej. `<select>` poblado por consulta)

---

## 🟡 Prioridad 4 — Carga y Visualización de Fotografías

**Objetivo:** Asociar una fotografía a cada empleado.

- [ ] Añadir campo `input type="file"` en el formulario (con `enctype="multipart/form-data"`)
- [ ] Validar tipo de archivo permitido: `.jpg`, `.jpeg`, `.png` (usar `mime_content_type()`)
- [ ] Generar nombre de archivo único con `date("His").microtime(true)`
- [ ] Guardar el archivo físico en `assets/fotos_empleados/` usando `move_uploaded_file()`
- [ ] Guardar únicamente la **ruta relativa** en la base de datos
- [ ] Al editar: eliminar la imagen anterior con `unlink()` antes de guardar la nueva
- [ ] Mostrar la fotografía como miniatura en `ver_empleados.php` mediante etiqueta `<img>`
- [ ] Implementar controladores `guardar_imagen.php` y `actualizar_imagen.php`

---

## 🟢 Prioridad 5 — Autenticación Segura y Sesiones

**Objetivo:** Proteger el acceso al sistema.

- [ ] Añadir campo de contraseña al registro de empleados/usuarios
- [ ] Encriptar contraseña con `password_hash()` (algoritmo Blowfish/`PASSWORD_DEFAULT`) — **no usar `md5`**
- [ ] Crear `views/login.php` (formulario de acceso con documento/correo + contraseña)
- [ ] Crear `views/register.php` y `controllers/registrar_usuario.php`
- [ ] Implementar `controllers/login.php`: validar con `password_verify()` e iniciar `session_start()`
- [ ] Guardar variables de sesión (ej. `$_SESSION['usuario_id']`, `$_SESSION['cargo']`)
- [ ] Proteger todas las vistas privadas: verificar sesión activa al inicio del script y redirigir al login si no existe
- [ ] Implementar `controllers/logout.php` con `session_unset()` y `session_destroy()`
- [ ] (Opcional) Control de permisos por cargo: restringir vistas/acciones según `$_SESSION['cargo']`

---

## 🔵 Prioridad 6 — Reportes y Visualización de Datos (Extras)

**Objetivo:** Generar reportes y gráficos a partir de los datos de empleados.

- [ ] **Gráficos (Chart.js):**
  - [ ] Crear `datos_grafico.php` que consulte la BD y devuelva JSON
  - [ ] Crear `assets/js/grafico.js` que use `fetch()` para consumir el JSON
  - [ ] Renderizar el gráfico en un `<canvas>` HTML
- [ ] **Reportes en PDF (FPDF):**
  - [ ] Integrar librería en `libs/fpdf/`
  - [ ] Crear clase que extienda `FPDF` (encabezados/pies de página)
  - [ ] Usar `AddPage()`, `SetFont()`, `Cell()`, `Ln()` para estructurar el contenido
  - [ ] Generar `views/generar_pdf.php` con `Output()` para visualizar/descargar

---

### Resumen de Prioridades

| Prioridad | Módulo | Tipo |
|---|---|---|
| 1 | CRUD Empleados + Arquitectura base | Crítico |
| 2 | Validación y Sanitización | Crítico |
| 3 | Normalización de BD | Alto |
| 4 | Carga de Imágenes | Medio |
| 5 | Autenticación y Sesiones | Medio |
| 6 | Gráficos y Reportes PDF | Opcional / Extra |
