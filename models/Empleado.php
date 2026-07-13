<?php
require_once __DIR__ . '/../config/conexion.php';

/**
 * Clase Empleado
 * Modelo para la tabla 'empleados'
 */
class Empleado {
    private $db;

    private $id_empleado;
    private $nombre_completo;
    private $numero_documento;
    private $cargo_id;
    private $departamento_id;
    private $fecha_ingreso;
    private $salario_base;
    private $estado;
    private $correo;
    private $telefono;
    private $foto_ruta;
    private $password_hash;

    public function __construct() {
        $this->db = Conexion::conectar();
        $this->estado = 'activo'; // Por defecto activo
    }

    // Getters
    public function getIdEmpleado() { return $this->id_empleado; }
    public function getNombreCompleto() { return $this->nombre_completo; }
    public function getNumeroDocumento() { return $this->numero_documento; }
    public function getCargoId() { return $this->cargo_id; }
    public function getDepartamentoId() { return $this->departamento_id; }
    public function getFechaIngreso() { return $this->fecha_ingreso; }
    public function getSalarioBase() { return $this->salario_base; }
    public function getEstado() { return $this->estado; }
    public function getCorreo() { return $this->correo; }
    public function getTelefono() { return $this->telefono; }
    public function getFotoRuta() { return $this->foto_ruta; }
    public function getPasswordHash() { return $this->password_hash; }

    // Setters
    public function setIdEmpleado($id) { $this->id_empleado = $id; }
    public function setNombreCompleto($nombre) { $this->nombre_completo = trim($nombre); }
    public function setNumeroDocumento($doc) { $this->numero_documento = trim($doc); }
    public function setCargoId($cargo_id) { $this->cargo_id = (int)$cargo_id; }
    public function setDepartamentoId($dept_id) { $this->departamento_id = (int)$dept_id; }
    public function setFechaIngreso($fecha) { $this->fecha_ingreso = $fecha; }
    public function setSalarioBase($salario) { $this->salario_base = (float)$salario; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setCorreo($correo) { $this->correo = trim(strtolower($correo)); }
    public function setTelefono($tel) { $this->telefono = trim($tel); }
    public function setFotoRuta($ruta) { $this->foto_ruta = $ruta; }
    public function setPasswordHash($hash) { $this->password_hash = $hash; }

    /**
     * Obtener todos los empleados, incluyendo nombres de cargo y departamento
     * 
     * @return array
     */
    public static function getAll() {
        $db = Conexion::conectar();
        $sql = "SELECT e.*, c.nombre_cargo, d.nombre_departamento 
                FROM empleados e
                INNER JOIN cargo c ON e.cargo_id = c.id_cargo
                INNER JOIN departamento d ON e.departamento_id = d.id_departamento
                ORDER BY e.nombre_completo ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener todos los empleados activos, incluyendo nombres de cargo y departamento
     * 
     * @return array
     */
    public static function getAllActivos() {
        $db = Conexion::conectar();
        $sql = "SELECT e.*, c.nombre_cargo, d.nombre_departamento 
                FROM empleados e
                INNER JOIN cargo c ON e.cargo_id = c.id_cargo
                INNER JOIN departamento d ON e.departamento_id = d.id_departamento
                WHERE e.estado = 'activo'
                ORDER BY e.nombre_completo ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un empleado por su ID
     * 
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        $db = Conexion::conectar();
        $sql = "SELECT e.*, c.nombre_cargo, d.nombre_departamento 
                FROM empleados e
                INNER JOIN cargo c ON e.cargo_id = c.id_cargo
                INNER JOIN departamento d ON e.departamento_id = d.id_departamento
                WHERE e.id_empleado = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener un empleado por su Correo (útil para autenticación)
     * 
     * @param string $correo
     * @return array|false
     */
    public static function getByCorreo($correo) {
        $db = Conexion::conectar();
        $sql = "SELECT e.*, c.nombre_cargo, d.nombre_departamento 
                FROM empleados e
                INNER JOIN cargo c ON e.cargo_id = c.id_cargo
                INNER JOIN departamento d ON e.departamento_id = d.id_departamento
                WHERE e.correo = :correo";
        $stmt = $db->prepare($sql);
        $stmt->execute(['correo' => trim(strtolower($correo))]);
        return $stmt->fetch();
    }

    /**
     * Obtener un empleado por su Documento (útil para autenticación)
     * 
     * @param string $documento
     * @return array|false
     */
    public static function getByDocumento($documento) {
        $db = Conexion::conectar();
        $sql = "SELECT e.*, c.nombre_cargo, d.nombre_departamento 
                FROM empleados e
                INNER JOIN cargo c ON e.cargo_id = c.id_cargo
                INNER JOIN departamento d ON e.departamento_id = d.id_departamento
                WHERE e.numero_documento = :documento";
        $stmt = $db->prepare($sql);
        $stmt->execute(['documento' => trim($documento)]);
        return $stmt->fetch();
    }

    /**
     * Verificar si un número de documento ya existe en la base de datos
     * 
     * @param string $doc
     * @param int|null $excluirId
     * @return bool
     */
    public static function documentoExiste($doc, $excluirId = null) {
        $db = Conexion::conectar();
        if ($excluirId) {
            $sql = "SELECT COUNT(*) FROM empleados WHERE numero_documento = :doc AND id_empleado != :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['doc' => trim($doc), 'id' => $excluirId]);
        } else {
            $sql = "SELECT COUNT(*) FROM empleados WHERE numero_documento = :doc";
            $stmt = $db->prepare($sql);
            $stmt->execute(['doc' => trim($doc)]);
        }
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verificar si un correo ya existe en la base de datos
     * 
     * @param string $correo
     * @param int|null $excluirId
     * @return bool
     */
    public static function correoExiste($correo, $excluirId = null) {
        $db = Conexion::conectar();
        $correo = trim(strtolower($correo));
        if ($excluirId) {
            $sql = "SELECT COUNT(*) FROM empleados WHERE correo = :correo AND id_empleado != :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['correo' => $correo, 'id' => $excluirId]);
        } else {
            $sql = "SELECT COUNT(*) FROM empleados WHERE correo = :correo";
            $stmt = $db->prepare($sql);
            $stmt->execute(['correo' => $correo]);
        }
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Guardar el empleado actual en la base de datos (Crear o Actualizar)
     * 
     * @return bool
     */
    public function guardar() {
        if ($this->id_empleado) {
            // Actualizar
            $sql = "UPDATE empleados SET 
                        nombre_completo = :nombre_completo,
                        numero_documento = :numero_documento,
                        cargo_id = :cargo_id,
                        departamento_id = :departamento_id,
                        fecha_ingreso = :fecha_ingreso,
                        salario_base = :salario_base,
                        estado = :estado,
                        correo = :correo,
                        telefono = :telefono,
                        foto_ruta = :foto_ruta,
                        password_hash = :password_hash
                    WHERE id_empleado = :id_empleado";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre_completo' => $this->nombre_completo,
                'numero_documento' => $this->numero_documento,
                'cargo_id' => $this->cargo_id,
                'departamento_id' => $this->departamento_id,
                'fecha_ingreso' => $this->fecha_ingreso,
                'salario_base' => $this->salario_base,
                'estado' => $this->estado,
                'correo' => $this->correo,
                'telefono' => $this->telefono,
                'foto_ruta' => $this->foto_ruta,
                'password_hash' => $this->password_hash,
                'id_empleado' => $this->id_empleado
            ]);
        } else {
            // Crear nuevo
            $sql = "INSERT INTO empleados (
                        nombre_completo, numero_documento, cargo_id, departamento_id, 
                        fecha_ingreso, salario_base, estado, correo, telefono, foto_ruta, password_hash
                    ) VALUES (
                        :nombre_completo, :numero_documento, :cargo_id, :departamento_id, 
                        :fecha_ingreso, :salario_base, :estado, :correo, :telefono, :foto_ruta, :password_hash
                    )";
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                'nombre_completo' => $this->nombre_completo,
                'numero_documento' => $this->numero_documento,
                'cargo_id' => $this->cargo_id,
                'departamento_id' => $this->departamento_id,
                'fecha_ingreso' => $this->fecha_ingreso,
                'salario_base' => $this->salario_base,
                'estado' => $this->estado,
                'correo' => $this->correo,
                'telefono' => $this->telefono,
                'foto_ruta' => $this->foto_ruta,
                'password_hash' => $this->password_hash
            ]);

            if ($resultado) {
                $this->id_empleado = $this->db->lastInsertId();
            }
            return $resultado;
        }
    }

    /**
     * Marcar un empleado como inactivo (eliminación lógica según requerimientos)
     * 
     * @param int $id
     * @return bool
     */
    public static function desactivar($id) {
        $db = Conexion::conectar();
        $sql = "UPDATE empleados SET estado = 'inactivo' WHERE id_empleado = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Activar un empleado
     * 
     * @param int $id
     * @return bool
     */
    public static function activarEmpleado($id) {
        $db = Conexion::conectar();
        $sql = "UPDATE empleados SET estado = 'activo' WHERE id_empleado = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Eliminar físicamente un empleado de la base de datos (si fuera necesario)
     * 
     * @param int $id
     * @return bool
     */
    public static function eliminarFisico($id) {
        $db = Conexion::conectar();
        $sql = "DELETE FROM empleados WHERE id_empleado = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
