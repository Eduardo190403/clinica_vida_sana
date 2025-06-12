<?php
// controllers/PacienteControlador
require_once 'models/Paciente.php';

class PacienteController {
    private $paciente;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->paciente = new Paciente($db);
    }
    
    public function index() {
        $pacientes = $this->paciente->readAll();
        include 'views/pacientes/index.php';
    }
    
    public function create() {
        include 'views/pacientes/create.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->paciente->dni = $_POST['dni'];
            $this->paciente->nombres = $_POST['nombres'];
            $this->paciente->apellidos = $_POST['apellidos'];
            $this->paciente->direccion = $_POST['direccion'];
            $this->paciente->telefono = $_POST['telefono'];
            $this->paciente->email = $_POST['email'];
            
            if ($this->paciente->create()) {
                header("Location: index.php?controller=paciente&action=list&success=1");
            } else {
                header("Location: index.php?controller=paciente&action=create&error=1");
            }
            exit();
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $paciente = $this->paciente->readOne($id);
        if (!$paciente) {
            header("Location: index.php?controller=paciente&action=list&error=2");
            exit();
        }
        include 'views/pacientes/edit.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->paciente->idpaciente = $_POST['idpaciente'];
            $this->paciente->dni = $_POST['dni'];
            $this->paciente->nombres = $_POST['nombres'];
            $this->paciente->apellidos = $_POST['apellidos'];
            $this->paciente->direccion = $_POST['direccion'];
            $this->paciente->telefono = $_POST['telefono'];
            $this->paciente->email = $_POST['email'];
            
            if ($this->paciente->update()) {
                header("Location: index.php?controller=paciente&action=list&success=2");
            } else {
                header("Location: index.php?controller=paciente&action=edit&id=" . $_POST['idpaciente'] . "&error=1");
            }
            exit();
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($this->paciente->delete($id)) {
            header("Location: index.php?controller=paciente&action=list&success=3");
        } else {
            header("Location: index.php?controller=paciente&action=list&error=3");
        }
        exit();
    }
}
?>