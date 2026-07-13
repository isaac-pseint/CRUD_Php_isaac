<?php
require_once __DIR__ . '/../config/conexion.php';

/**
 * Clase Cargo
 * Modelo para la tabla 'cargo'
 */
class Cargo {
    private $db;
    private $id_cargo;
    private $nombre_cargo;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Getters
    public function getIdCargo() {
        return $this->id_cargo;
    }

    public function getNombreCargo() {
        return $this->nombre_cargo;
    }

    // Setters
    public function setIdCargo($id_cargo) {
        $this->id_cargo = $id_cargo;
    }

    public function setNombreCargo($nombre_cargo) {
        $this->nombre_cargo = trim($nombre_cargo);
    }

    /**
     * Obtener todos los cargos ordenados alfabéticamente
     * 
     * @return array
     */
    public static function getAll() {
        $db = Conexion::conectar();
        $sql = "SELECT id_cargo, nombre_cargo FROM cargo ORDER BY nombre_cargo ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un cargo por su ID
     * 
     * @param int $id
     * @return array|false
     */
    public static function getById($id) {
        $db = Conexion::conectar();
        $sql = "SELECT id_cargo, nombre_cargo FROM cargo WHERE id_cargo = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Guardar el cargo actual en la base de datos (Crear o Actualizar)
     * 
     * @return bool
     */
    public function guardar() {
        if ($this->id_cargo) {
            // Actualizar
            $sql = "UPDATE cargo SET nombre_cargo = :nombre WHERE id_cargo = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nombre' => $this->nombre_cargo,
                'id' => $this->id_cargo
            ]);
        } else {
            // Crear
            $sql = "INSERT INTO cargo (nombre_cargo) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute(['nombre' => $this->nombre_cargo]);
            if ($resultado) {
                $this->id_cargo = $this->db->lastInsertId();
            }
            return $resultado;
        }
    }

    /**
     * Eliminar un cargo por ID
     * Nota: Puede fallar por restricción de llave foránea si hay empleados asociados.
     * 
     * @param int $id
     * @return bool
     */
    public static function eliminar($id) {
        $db = Conexion::conectar();
        $sql = "DELETE FROM cargo WHERE id_cargo = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
