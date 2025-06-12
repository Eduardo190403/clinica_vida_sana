<?php
require_once '../models/Especialidad.php';

class EspecialidadController {
    private $especialidad;
    
    public function __construct() {
        $this->especialidad = new Especialidad();
    }
    
    public function index() {
        $especialidades = $this->especialidad->obtenerTodos();
        include '../views/especialidades/lista.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre']
            ];
            
            if ($this->especialidad->crear($datos)) {
                header('Location: index.php?controller=especialidad&action=index&msg=creado');
            } else {
                $error = "Error al crear especialidad";
            }
        }
        
        include '../views/especialidades/crear.php';
    }
    
    public function editar() {
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre']
            ];
            
            if ($this->especialidad->actualizar($id, $datos)) {
                header('Location: index.php?controller=especialidad&action=index&msg=actualizado');
            } else {
                $error = "Error al actualizar especialidad";
            }
        }
        
        $especialidad = $this->especialidad->obtenerPorId($id);
        include '../views/especialidades/editar.php';
    }
    
    public function eliminar() {
        $id = $_GET['id'];
        
        if ($this->especialidad->eliminar($id)) {
            header('Location: index.php?controller=especialidad&action=index&msg=eliminado');
        } else {
            header('Location: index.php?controller=especialidad&action=index&error=1');
        }
    }
}
?>