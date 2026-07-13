-- ============================================
-- ServiPlus S.A. - Esquema de Base de Datos
-- Fase: Prioridad 1
-- ============================================

CREATE DATABASE IF NOT EXISTS serviplus_schema
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE serviplus_schema;

-- ============================================
-- Tabla: empleados (Versión inicial)
-- ============================================
CREATE TABLE IF NOT EXISTS empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    cargo ENUM('Técnico','Administrador','Operario','Asistente') NOT NULL,
    area ENUM('Electricidad','Mantenimiento','RRHH','Contabilidad') NOT NULL,
    fecha_ingreso DATE NOT NULL,
    salario_base DECIMAL(10,2) NOT NULL,
    estado ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
    correo VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
