<?php
require_once '../config/database.php';

class Cita {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function obtenerTodos() {
        $query = "SELECT c.*, 
                         CONCAT(p.nombres, ' ', p.apellidos) as paciente_nombre,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre
                  FROM citas c
                  LEFT JOIN pacientes p ON c.paciente_id = p.idpaciente
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  ORDER BY c.fecha DESC, c.hora DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT c.*, 
                         CONCAT(p.nombres, ' ', p.apellidos) as paciente_nombre,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre,
                         m.especialidad_id
                  FROM citas c
                  LEFT JOIN pacientes p ON c.paciente_id = p.idpaciente
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  WHERE c.idcita = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $query = "INSERT INTO citas (paciente_id, medico_id, fecha, hora, estado) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['paciente_id'],
            $datos['medico_id'],
            $datos['fecha'],
            $datos['hora'],
            $datos['estado']
        ]);
    }
    
    public function actualizar($id, $datos) {
        $query = "UPDATE citas 
                  SET paciente_id = ?, medico_id = ?, fecha = ?, hora = ?, estado = ?
                  WHERE idcita = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $datos['paciente_id'],
            $datos['medico_id'],
            $datos['fecha'],
            $datos['hora'],
            $datos['estado'],
            $id
        ]);
    }
    
    public function eliminar($id) {
        $query = "DELETE FROM citas WHERE idcita = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE citas SET estado = ? WHERE idcita = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$estado, $id]);
    }
    
    public function obtenerCitasAtendidas() {
        $query = "SELECT c.*, 
                         CONCAT(p.nombres, ' ', p.apellidos) as paciente_nombre,
                         CONCAT(m.nombres, ' ', m.apellidos) as medico_nombre,
                         e.nombre as especialidad_nombre
                  FROM citas c
                  LEFT JOIN pacientes p ON c.paciente_id = p.idpaciente
                  LEFT JOIN medicos m ON c.medico_id = m.idmedico
                  LEFT JOIN especialidades e ON m.especialidad_id = e.idespecialidad
                  WHERE c.estado = 'atendida'
                  ORDER BY c.fecha DESC, c.hora DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>