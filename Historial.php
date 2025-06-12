<?php
require_once '../config/database.php';

class Historial {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos() {
        $query = "SELECT h.*, 
                         c.fecha, c.hora,
                         CONCAT(p.nombres, ' ', p.apellidos) as paciente_nombre,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre
                  FROM historiales h
                  LEFT JOIN citas c ON h.cita_id = c.idcita
                  LEFT JOIN pacientes p ON c.paciente_id = p.idpaciente
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  ORDER BY c.fecha DESC, c.hora DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT h.*, 
                         c.fecha, c.hora,
                         CONCAT(p.nombres, ' ', p.apellidos) as paciente_nombre,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre,
                         p.dni as paciente_dni,
                         p.telefono as paciente_telefono
                  FROM historiales h
                  LEFT JOIN citas c ON h.cita_id = c.idcita
                  LEFT JOIN pacientes p ON c.paciente_id = p.idpaciente
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  WHERE h.idhistorial = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorPaciente($paciente_id) {
        $query = "SELECT h.*, 
                         c.fecha, c.hora,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre
                  FROM historiales h
                  LEFT JOIN citas c ON h.cita_id = c.idcita
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  WHERE c.paciente_id = ?
                  ORDER BY c.fecha DESC, c.hora DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $query = "INSERT INTO historiales (cita_id, diagnostico, tratamiento, observaciones) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['cita_id'],
            $datos['diagnostico'],
            $datos['tratamiento'],
            $datos['observaciones']
        ]);
    }
    
    public function actualizar($id, $datos) {
        $query = "UPDATE historiales 
                  SET diagnostico = ?, tratamiento = ?, observaciones = ?
                  WHERE idhistorial = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['diagnostico'],
            $datos['tratamiento'],
            $datos['observaciones'],
            $id
        ]);
    }
    
    public function eliminar($id) {
        $query = "DELETE FROM historiales WHERE idhistorial = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function existePorCita($cita_id) {
        $query = "SELECT COUNT(*) FROM historiales WHERE cita_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$cita_id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>