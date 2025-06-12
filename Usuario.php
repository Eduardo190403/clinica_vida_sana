<?php
// models/Usuario.php
class Usuario {
    private $conn;
    private $table_name = "usuarios";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function authenticate($usuario, $clave) {
        $query = "SELECT idusuario, usuario, clave, rol FROM " . $this->table_name . " WHERE usuario = :usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($clave, $user['clave'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getStats() {
        $stats = [];
        
        // Total pacientes
        $query = "SELECT COUNT(*) as total FROM pacientes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pacientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total médicos
        $query = "SELECT COUNT(*) as total FROM medicos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['medicos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total citas hoy
        $query = "SELECT COUNT(*) as total FROM citas WHERE fecha = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['citas_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Citas pendientes
        $query = "SELECT COUNT(*) as total FROM citas WHERE estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['citas_pendientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
}
?>