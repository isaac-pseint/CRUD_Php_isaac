# Proyecto CRUD ServiPlus

## Descripción

Este proyecto es una aplicación CRUD básica en PHP para la gestión de empleados de una empresa ficticia llamada ServiPlus. Incluye autenticación de usuario, permisos de acceso y manejo de empleados, cargos y departamentos.

## Estructura del proyecto

- `index.php` - Redirige al listado principal de empleados.
- `config/` - Configuración de la base de datos y validaciones de sesión/permiso.
- `controllers/` - Lógica de negocio para crear, editar, eliminar y autenticar usuarios.
- `models/` - Modelos para acceder a las tablas `cargo`, `departamento` y `empleados`.
- `views/` - Interfaces de usuario, formularios y listados.
- `serviplus_schema.sql` - Script de creación de la base de datos y datos iniciales.

## Requisitos

- XAMPP instalado con Apache y MySQL.
- PHP 7.4 o superior.
- Navegador web.

## Configuración en XAMPP

1. Copia la carpeta del proyecto en el directorio de XAMPP:
   - `C:\xampp\htdocs\ProyectoCRUD`
2. Inicia Apache y MySQL desde el panel de control de XAMPP.
3. Abre el navegador y accede a:
   - `http://localhost/ProyectoCRUD/`

## Importar la base de datos

1. Abre `phpMyAdmin` desde el panel de control de XAMPP o en:
   - `http://localhost/phpmyadmin`
2. Crea una nueva base de datos llamada `serviplus_db` si no existe.
3. Selecciona la base de datos `serviplus_db`.
4. Ve a la pestaña `Importar`.
5. Selecciona el archivo `serviplus_schema.sql` del proyecto.
6. Haz clic en `Continuar` para ejecutar el script.

## Exportar la base de datos

### Usando phpMyAdmin

1. En `phpMyAdmin`, selecciona la base de datos `serviplus_db`.
2. Ve a la pestaña `Exportar`.
3. Elige el método `Rápido` y el formato `SQL`.
4. Haz clic en `Continuar` para descargar el archivo SQL.

### Usando la línea de comandos

1. Abre una terminal o `cmd`.
2. Ejecuta:
   ```bash
   mysqldump -u root -p serviplus_db > serviplus_db_export.sql
   ```
3. Ingresa la contraseña de MySQL si se solicita (por defecto en XAMPP es vacía).

## Ejecutar el proyecto

1. Asegúrate de que Apache y MySQL estén en ejecución.
2. Importa la base de datos usando `serviplus_schema.sql`.
3. Abre en el navegador:
   - `http://localhost/ProyectoCRUD/`
4. Accede con los datos de usuario si ya hay cuentas registradas.

## Notas

- La configuración de conexión usa `root` con contraseña vacía en `config/conexion.php`.
- Si cambias el nombre de la base de datos o las credenciales de MySQL, actualiza `config/conexion.php`.
