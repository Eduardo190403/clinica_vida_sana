<?php
require_once '../models/Historial.php';
require_once '../models/Cita.php';

class HistorialController {
    private $historial;
    private $cita;
    
    public function __construct() {
        $this->historial = new Historial();
        $this->cita = new Cita();
    }
    
    public function index() {
        $historiales = $this->historial->obtenerTodos();
        include '../views/historiales/lista.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'cita_id' => $_POST['cita_id'],
                'diagnostico' => $_POST['diagnostico'],
                'tratamiento' => $_POST['tratamiento'],
                'observaciones' => $_POST['observaciones']
            ];
            
            if ($this->historial->crear($datos)) {
                // Cambiar estado de la cita a "atendida"
                $this->cita->cambiarEstado($datos['cita_id'], 'atendida');
                header('Location: index.php?controller=historial&action=index&msg=creado');
            } else {
                $error = "Error al crear historial clínico";
            }
        }
        
        $citasAtendidas = $this->cita->obtenerCitasAtendidas();
        include '../views/historiales/crear.php';
    }
    
    public function editar() {
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'diagnostico' => $_POST['diagnostico'],
                'tratamiento' => $_POST['tratamiento'],
                'observaciones' => $_POST['observaciones']
            ];
            
            if ($this->historial->actualizar($id, $datos)) {
                header('Location: index.php?controller=historial&action=index&msg=actualizado');
            } else {
                $error = "Error al actualizar historial clínico";
            }
        }
        
        $historial = $this->historial->obtenerPorId($id);
        include '../views/historiales/editar.php';
    }
    
    public function ver() {
        $id = $_GET['id'];
        $historial = $this->historial->obtenerPorId($id);
        include '../views/historiales/ver.php';
    }
    
    public function eliminar() {
        $id = $_GET['id'];
        
        if ($this->historial->eliminar($id)) {
            header('Location: index.php?controller=historial&action=index&msg=eliminado');
        } else {
            header('Location: index.php?controller=historial&action=index&error=1');
        }
    }
    
    public function porPaciente() {
        $paciente_id = $_GET['paciente_id'];
        $historiales = $this->historial->obtenerPorPaciente($paciente_id);
        include '../views/historiales/por_paciente.php';
    }
}
?>