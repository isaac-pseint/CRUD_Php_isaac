# Proyecto CRUD ServiPlus

## Descripción

Este proyecto es una aplicación CRUD básica en PHP para la gestión de empleados de una empresa ficticia llamada ServiPlus. Incluye autenticación de usuario, control de permisos de acceso basado en roles, visualización de perfiles y un sistema de gestión de fotografías.

## Nuevas Características Implementadas

- **Página de Bienvenida:** El `index.php` se convirtió en un portal de bienvenida interactivo y dinámico que adapta su contenido y botones según el estado de la sesión del usuario.
- **Barra Lateral de Navegación (`aside`):** La sección de listado de empleados ahora cuenta con un menú lateral (`aside`) que muestra los datos de la sesión actual (Avatar, Nombre y Rol) además de enlaces a: Directorio, Registro (solo administradores), Cambiar Contraseña y Cerrar Sesión.
- **Control de Acceso y Roles:** Solo los usuarios con rol de Administrador pueden acceder a los formularios de creación o edición de empleados, así como eliminarlos físicamente o mediante borrado lógico.
- **Cierre de Sesión Seguro:** Se implementó una lógica rigurosa de logout que elimina de manera definitiva los datos de la sesión en el servidor y fuerza al navegador a eliminar la cookie de sesión de PHP.
- **Gestión de Fotografías:**
  - Carga de imágenes en formatos `.jpg`, `.jpeg` y `.png` con validación estricta de tipo MIME.
  - Creación dinámica del directorio `assets/fotos_empleados/` para el guardado de archivos.
  - Generación de nombres únicos basados en microsegundos y fecha para evitar colisiones.
  - Eliminación del archivo de imagen anterior en el servidor mediante `unlink()` cuando la fotografía de un empleado es actualizada.
  - Visualización del retrato del empleado en el listado de consulta o, en su defecto, un avatar con su inicial.

## Estructura del proyecto

- `index.php` - Portal de bienvenida interactivo.
- `config/` - Configuración de conexión y middleware de autenticación/permisos.
- `controllers/` - Controladores del flujo de negocio (autenticación, logout completo, creación, edición y eliminación lógica).
- `models/` - Modelos PDO (`Empleado.php`, `Cargo.php`, `Departamento.php`).
- `views/` - Vistas y formularios interactivos (`ver_empleados.php`, `formulario_crear.php`, `formulario_editar.php`, `login.php`, `acceso_denegado.php`).
- `assets/` - Archivos estáticos como CSS (`style.css`) y la carpeta de imágenes subidas (`fotos_empleados/`).
- `serviplus_schema.sql` - Script de base de datos MySQL inicial.

## Requisitos

- Servidor local como XAMPP (con Apache y MySQL).
- PHP 7.4 o superior (Recomendado PHP 8.x).
- Navegador web.

## Configuración en XAMPP

1. Copia la carpeta del proyecto en el directorio de XAMPP:
   - `C:\xampp\htdocs\ProyectoCRUD`
2. Inicia Apache y MySQL desde el panel de control de XAMPP.
3. Abre el navegador y accede a:
   - `http://localhost/ProyectoCRUD/`

## Importar la base de datos

1. Abre `phpMyAdmin` en:
   - `http://localhost/phpmyadmin`
2. Crea una nueva base de datos llamada `serviplus_db`.
3. Selecciona la base de datos `serviplus_db` e impórtale el archivo `serviplus_schema.sql` ubicado en la raíz del proyecto.

## Notas Adicionales

- Las credenciales predeterminadas de conexión se configuran en `config/conexion.php`.
- Asegúrate de habilitar los permisos de escritura para la carpeta `assets/` a fin de que PHP pueda crear dinámicamente el directorio para subir las imágenes de los empleados.
