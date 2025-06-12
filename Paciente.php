<?php
// modelo/Paciente.php
class Paciente {
    private $conn;
    private $table_name = "pacientes";
    
    public $idpaciente;
    public $dni;
    public $nombres;
    public $apellidos;
    public $direccion;
    public $telefono;
    public $email;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY apellidos, nombres";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE idpaciente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (dni, nombres, apellidos, direccion, telefono, email) 
                  VALUES (:dni, :nombres, :apellidos, :direccion, :telefono, :email)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET dni = :dni, nombres = :nombres, apellidos = :apellidos, 
                      direccion = :direccion, telefono = :telefono, email = :email
                  WHERE idpaciente = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->idpaciente);
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function delete($id) {
        // Verificar si tiene citas asociadas
        $query = "SELECT COUNT(*) as total FROM citas WHERE paciente_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total'] > 0) {
            return false; // No se puede eliminar si tiene citas
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE idpaciente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    public function getForSelect() {
        $query = "SELECT idpaciente, CONCAT(apellidos, ', ', nombres) as nombre_completo 
                  FROM " . $this->table_name . " ORDER BY apellidos, nombres";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>