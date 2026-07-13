-- ============================================
-- ServiPlus S.A. - Esquema de Base de Datos
-- Tablas normalizadas: cargo, departamento, empleados
-- ============================================

CREATE DATABASE IF NOT EXISTS serviplus_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE serviplus_db;

-- ============================================
-- Tabla: cargo
-- Lado "uno" de la relación 1:N con empleados
-- ============================================
CREATE TABLE cargo (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cargo VARCHAR(50) NOT NULL UNIQUE
);

-- ============================================
-- Tabla: departamento
-- Lado "uno" de la relación 1:N con empleados
-- ============================================
CREATE TABLE departamento (
    id_departamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_departamento VARCHAR(50) NOT NULL UNIQUE
);

-- ============================================
-- Tabla: empleados
-- Lado "muchos" de ambas relaciones
-- ============================================
CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    numero_documento VARCHAR(30) NOT NULL UNIQUE,
    cargo_id INT NOT NULL,
    departamento_id INT NOT NULL,
    fecha_ingreso DATE NOT NULL,
    salario_base DECIMAL(12,2) NOT NULL CHECK (salario_base >= 0),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    correo VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    foto_ruta VARCHAR(255) DEFAULT NULL,       -- Parte 4: ruta relativa en fotos_empleados/
    password_hash VARCHAR(255) DEFAULT NULL,   -- Parte 5: generado con password_hash()

    CONSTRAINT fk_empleado_cargo
        FOREIGN KEY (cargo_id) REFERENCES cargo(id_cargo)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_empleado_departamento
        FOREIGN KEY (departamento_id) REFERENCES departamento(id_departamento)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- ============================================
-- Datos iniciales (según el caso de estudio)
-- ============================================
INSERT INTO cargo (nombre_cargo) VALUES
    ('Técnico'),
    ('Administrador'),
    ('Operario'),
    ('Asistente');

INSERT INTO departamento (nombre_departamento) VALUES
    ('Electricidad'),
    ('Mantenimiento'),
    ('RRHH'),
    ('Contabilidad');

-- ============================================
-- Notas de diseño
-- ============================================
-- 1. Relación cargo (1) --- (N) empleados: un cargo puede estar
--    asignado a muchos empleados, pero cada empleado tiene solo un cargo.
-- 2. Relación departamento (1) --- (N) empleados: mismo principio.
-- 3. ON DELETE RESTRICT evita borrar un cargo/departamento que ya
--    tenga empleados asociados (evita huérfanos). Si se prefiere
--    permitir la eliminación, considerar ON DELETE SET NULL y
--    volver nullable cargo_id/departamento_id.
-- 4. numero_documento y correo son UNIQUE para cumplir la Parte 2
--    (validación de unicidad) a nivel de base de datos, además
--    de la validación en PHP.
-- 5. password_hash es nullable porque se agrega en la Parte 5;
--    si el login es obligatorio desde el inicio, cambiar a NOT NULL.
