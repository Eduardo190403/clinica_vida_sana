<?php
require_once '../config/database.php';

class Medico {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos() {
        $query = "SELECT m.*, e.nombre as especialidad_nombre 
                  FROM medicos m 
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  ORDER BY m.apellidos, m.nombres";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM medicos WHERE idmedico = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorEspecialidad($especialidad_id) {
        $query = "SELECT * FROM medicos WHERE especialidad_id = ? ORDER BY apellidos, nombres";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$especialidad_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $query = "INSERT INTO medicos (nombres, apellidos, especialidad_id, telefono, email) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['nombres'],
            $datos['apellidos'],
            $datos['especialidad_id'],
            $datos['telefono'],
            $datos['email']
        ]);
    }
    
    public function actualizar($id, $datos) {
        $query = "UPDATE medicos 
                  SET nombres = ?, apellidos = ?, especialidad_id = ?, telefono = ?, email = ?
                  WHERE idmedico = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['nombres'],
            $datos['apellidos'],
            $datos['especialidad_id'],
            $datos['telefono'],
            $datos['email'],
            $id
        ]);
    }
    
    public function eliminar($id) {
        $query = "DELETE FROM medicos WHERE idmedico = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function verificarDisponibilidad($medico_id, $fecha, $hora, $cita_id = null) {
        $query = "SELECT COUNT(*) FROM citas 
                  WHERE medico_id = ? AND fecha = ? AND hora = ? AND estado != 'cancelada'";
        
        $params = [$medico_id, $fecha, $hora];
        
        if ($cita_id) {
            $query .= " AND idcita != ?";
            $params[] = $cita_id;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() == 0;
    }
}
?>