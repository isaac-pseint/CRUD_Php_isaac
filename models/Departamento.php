<?php
require_once __DIR__ . '/../config/conexion.php';

/**
 * Clase Departamento
 * Modelo para la tabla 'departamento'
 */
class Departamento {
    private $db;
    private $id_departamento;
    private $nombre_departamento;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Getters
    public function getIdDepartamento() {
        return $this->id_departamento;
    }

    public function getNombreDepartamento() {
        return $this->nombre_departamento;
    }

    // Setters
    public function setIdDepartamento($id_departamento) {
        $this->id_departamento = $id_departamento;
    }

    public function setNombreDepartamento($nombre_departamento) {
        $this->nombre_departamento = trim($nombre_departamento);
    }

    /**
     * Obtener todos los departamentos ordenados alfabéticamente
     * 
     * @return array
     */
    public static function getAll() {
        $db = Conexion::conectar();
        $sql = "SELECT id_departamento, nombre_departamento FROM departamento ORDER BY nombre_departamento ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un departamento por su ID
     * 
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        $db = Conexion::conectar();
        $sql = "SELECT id_departamento, nombre_departamento FROM departamento WHERE id_departamento = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Guardar el departamento actual en la base de datos (Crear o Actualizar)
     * 
     * @return bool
     */
    public function guardar() {
        if ($this->id_departamento) {
            // Actualizar
            $sql = "UPDATE departamento SET nombre_departamento = :nombre WHERE id_departamento = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $this->nombre_departamento,
                'id' => $this->id_departamento
            ]);
        } else {
            // Crear
            $sql = "INSERT INTO departamento (nombre_departamento) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute(['nombre' => $this->nombre_departamento]);
            if ($resultado) {
                $this->id_departamento = $this->db->lastInsertId();
            }
            return $resultado;
        }
    }

    /**
     * Eliminar un departamento por ID
     * Nota: Puede fallar por restricción de llave foránea si hay empleados asociados.
     * 
     * @param int $id
     * @return bool
     */
    public static function eliminar($id) {
        $db = Conexion::conectar();
        $sql = "DELETE FROM departamento WHERE id_departamento = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
