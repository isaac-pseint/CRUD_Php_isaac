# ServiPlus S.A.
## Documento de Requerimientos Funcionales (RF)
**Versión:** 1.0.0  
**Arquitectura:** MVC (Model - View - Controller)  
**Lenguaje:** PHP 8.x  
**Base de Datos:** MySQL  
**Estado del Proyecto:** En desarrollo

---

# Objetivo General

Desarrollar un sistema web para la administración de empleados de ServiPlus S.A., permitiendo la gestión centralizada de la información del personal, autenticación de usuarios, control de acceso mediante roles, administración de fotografías, generación de reportes y visualización de indicadores estadísticos.

La arquitectura del sistema deberá permitir la incorporación de nuevos módulos y funcionalidades sin afectar la estructura existente.

---

# Tecnologías

## Backend

- PHP 8.x
- MySQL
- PDO

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript ES6

## Librerías

- Chart.js
- FPDF

## Seguridad

- password_hash()
- password_verify()
- PHP Sessions

---

# Arquitectura del Proyecto

```
config/
controllers/
models/
views/
assets/
libs/
index.php
```

---

# MÓDULO 1
# Configuración del Sistema

## RF-001
### Configuración de la aplicación

### Objetivo

Establecer la configuración base del proyecto y la conexión centralizada con la base de datos.

### Prioridad

Alta

### Tareas

- Configurar estructura MVC.
- Crear conexión PDO.
- Centralizar parámetros de configuración.
- Implementar manejo de errores de conexión.

### Archivos afectados

```
config/conexion.php

models/MySQL.php

index.php
```

---

# MÓDULO 2
# Gestión de Empleados

## RF-002
### Registrar empleado

### Objetivo

Permitir registrar nuevos empleados en el sistema.

### Prioridad

Alta

### Tareas

- Crear formulario de registro.
- Validar información recibida.
- Verificar documento único.
- Registrar información en la base de datos.
- Mostrar confirmación al usuario.

### Archivos afectados

```
controllers/
    EmpleadoController.php

models/
    Empleado.php

views/
    empleados/
        crear.php
```

---

## RF-003
### Consultar empleados

### Objetivo

Permitir consultar el listado general de empleados registrados.

### Prioridad

Alta

### Tareas

- Obtener registros desde la base de datos.
- Mostrar listado en tabla.
- Visualizar fotografía del empleado.
- Mostrar estado del empleado.
- Mostrar acciones disponibles según permisos.

### Archivos afectados

```
EmpleadoController.php

Empleado.php

views/empleados/index.php
```

---

## RF-004
### Actualizar empleado

### Objetivo

Permitir modificar la información de un empleado existente.

### Prioridad

Alta

### Tareas

- Recuperar información.
- Validar cambios.
- Actualizar base de datos.
- Registrar fotografía nueva si existe.

### Archivos afectados

```
EmpleadoController.php

Empleado.php

views/empleados/editar.php
```

---

## RF-005
### Eliminar empleado

### Objetivo

Realizar eliminación lógica mediante cambio de estado.

### Prioridad

Alta

### Tareas

- Confirmar operación.
- Cambiar estado a Inactivo.
- Actualizar listado.

### Archivos afectados

```
EmpleadoController.php

Empleado.php
```

---

# MÓDULO 3
# Gestión de Cargos

## RF-006
### Administrar cargos

### Objetivo

Gestionar los cargos disponibles para los empleados.

### Prioridad

Alta

### Tareas

- Registrar cargo.
- Editar cargo.
- Eliminar cargo.
- Consultar cargos.

### Archivos afectados

```
CargoController.php

Cargo.php

views/cargos/
```

---

# MÓDULO 4
# Gestión de Departamentos

## RF-007
### Administrar departamentos

### Objetivo

Gestionar las áreas o departamentos de la organización.

### Prioridad

Alta

### Tareas

- Registrar departamento.
- Editar departamento.
- Eliminar departamento.
- Consultar departamentos.

### Archivos afectados

```
DepartamentoController.php

Departamento.php

views/departamentos/
```

---

# MÓDULO 5
# Gestión de Fotografías

## RF-008
### Registrar fotografía

### Objetivo

Permitir asociar una fotografía a un empleado.

### Prioridad

Alta

### Tareas

- Validar formato.
- Validar tamaño.
- Generar nombre único.
- Guardar archivo.
- Registrar ruta en la base de datos.

### Archivos afectados

```
EmpleadoController.php

Storage.php

assets/fotos_empleados/
```

---

## RF-009
### Actualizar fotografía

### Objetivo

Permitir reemplazar la fotografía de un empleado.

### Prioridad

Media

### Tareas

- Cargar nueva fotografía.
- Eliminar fotografía anterior.
- Actualizar ruta.

### Archivos afectados

```
EmpleadoController.php
```

---

# MÓDULO 6
# Gestión de Usuarios

## RF-010
### Registrar usuarios

### Objetivo

Administrar los usuarios que podrán acceder al sistema.

### Prioridad

Alta

### Tareas

- Registrar usuario.
- Relacionar usuario con empleado.
- Encriptar contraseña.
- Activar o desactivar usuario.

### Archivos afectados

```
UsuarioController.php

Usuario.php
```

---

# MÓDULO 7
# Autenticación

## RF-011
### Inicio de sesión

### Objetivo

Permitir la autenticación segura mediante usuario y contraseña.

### Prioridad

Alta

### Tareas

- Validar credenciales.
- Verificar contraseña.
- Crear sesión.
- Redireccionar.

### Archivos afectados

```
AuthController.php

views/login.php
```

---

## RF-012
### Cerrar sesión

### Objetivo

Finalizar la sesión del usuario.

### Prioridad

Alta

### Tareas

- Destruir sesión.
- Redireccionar al login.

### Archivos afectados

```
AuthController.php
```

---

# MÓDULO 8
# Gestión de Roles

## RF-013
### Control de acceso

### Objetivo

Restringir funcionalidades de acuerdo con el rol asignado.

### Prioridad

Alta

### Tareas

- Verificar sesión.
- Verificar permisos.
- Restringir acceso.
- Ocultar opciones no autorizadas.

### Archivos afectados

```
Middleware/

AuthController.php
```

---

# MÓDULO 9
# Dashboard

## RF-014
### Dashboard Administrativo

### Objetivo

Mostrar indicadores generales del sistema.

### Prioridad

Media

### Tareas

- Consultar estadísticas.
- Generar JSON.
- Consumir API mediante Fetch.
- Renderizar gráficos.

### Archivos afectados

```
DashboardController.php

dashboard.php

assets/js/grafico.js
```

---

# MÓDULO 10
# Reportes

## RF-015
### Reportes PDF

### Objetivo

Generar reportes descargables de empleados.

### Prioridad

Media

### Tareas

- Integrar FPDF.
- Obtener información.
- Construir reporte.
- Descargar PDF.

### Archivos afectados

```
ReporteController.php

views/reportes/pdf.php
```

---

# Base de Datos

## Tablas implementadas

- empleados
- cargos
- departamentos

## Tablas previstas

- usuarios
- roles
- usuario_roles

---

# Mejoras futuras

## Gestión documental

Prioridad: Baja

---

## Recuperación de contraseña

Prioridad: Baja

---

## Auditoría del sistema

Prioridad: Baja

---

## Exportación a Excel

Prioridad: Baja

---

## Paginación

Prioridad: Media

---

## Búsqueda avanzada

Prioridad: Media

---

## API REST

Prioridad: Baja

---

# Consideraciones Técnicas

- Arquitectura MVC.
- Uso de PDO para acceso a datos.
- Contraseñas protegidas mediante `password_hash()`.
- Eliminación lógica de empleados.
- Roles independientes del cargo laboral.
- Código organizado por responsabilidad.
- Diseño preparado para incorporar nuevos módulos sin modificar la arquitectura principal.