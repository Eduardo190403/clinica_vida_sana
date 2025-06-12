<?php
require_once '../config/database.php';

class Especialidad {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos() {
        $query = "SELECT * FROM especialidades ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM especialidades WHERE idespecialidad = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $query = "INSERT INTO especialidades (nombre) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$datos['nombre']]);
    }
    
    public function actualizar($id, $datos) {
        $query = "UPDATE especialidades SET nombre = ? WHERE idespecialidad = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$datos['nombre'], $id]);
    }
    
    public function eliminar($id) {
        // Verificar si tiene médicos asociados
        $query = "SELECT COUNT(*) FROM medicos WHERE especialidad_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // No se puede eliminar si tiene médicos
        }
        
        $query = "DELETE FROM especialidades WHERE idespecialidad = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>