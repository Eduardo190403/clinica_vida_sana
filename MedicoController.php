<?php
require_once '../models/Medico.php';
require_once '../models/Especialidad.php';

class MedicoController {
    private $medico;
    private $especialidad;
    
    public function __construct() {
        $this->medico = new Medico();
        $this->especialidad = new Especialidad();
    }
    
    public function index() {
        $medicos = $this->medico->obtenerTodos();
        include '../views/medicos/lista.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'especialidad_id' => $_POST['especialidad_id'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email']
            ];
            
            if ($this->medico->crear($datos)) {
                header('Location: index.php?controller=medico&action=index&msg=creado');
            } else {
                $error = "Error al crear médico";
            }
        }
        
        $especialidades = $this->especialidad->obtenerTodos();
        include '../views/medicos/crear.php';
    }
    
    public function editar() {
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'especialidad_id' => $_POST['especialidad_id'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email']
            ];
            
            if ($this->medico->actualizar($id, $datos)) {
                header('Location: index.php?controller=medico&action=index&msg=actualizado');
            } else {
                $error = "Error al actualizar médico";
            }
        }
        
        $medico = $this->medico->obtenerPorId($id);
        $especialidades = $this->especialidad->obtenerTodos();
        include '../views/medicos/editar.php';
    }
    
    public function eliminar() {
        $id = $_GET['id'];
        
        if ($this->medico->eliminar($id)) {
            header('Location: index.php?controller=medico&action=index&msg=eliminado');
        } else {
            header('Location: index.php?controller=medico&action=index&error=1');
        }
    }
}
?>