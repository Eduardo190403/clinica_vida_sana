<?php
require_once '../models/Cita.php';
require_once '../models/Paciente.php';
require_once '../models/Medico.php';
require_once '../models/Especialidad.php';

class CitaController {
    private $cita;
    private $paciente;
    private $medico;
    private $especialidad;
    
    public function __construct() {
        $this->cita = new Cita();
        $this->paciente = new Paciente();
        $this->medico = new Medico();
        $this->especialidad = new Especialidad();
    }
    
    public function index() {
        $citas = $this->cita->obtenerTodos();
        include '../views/citas/lista.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'paciente_id' => $_POST['paciente_id'],
                'medico_id' => $_POST['medico_id'],
                'fecha' => $_POST['fecha'],
                'hora' => $_POST['hora'],
                'estado' => 'pendiente'
            ];
            
            // Validar disponibilidad del médico
            if (!$this->medico->verificarDisponibilidad($datos['medico_id'], $datos['fecha'], $datos['hora'])) {
                $error = "El médico ya tiene una cita programada en esa fecha y hora";
            } else {
                if ($this->cita->crear($datos)) {
                    header('Location: index.php?controller=cita&action=index&msg=creado');
                } else {
                    $error = "Error al crear cita";
                }
            }
        }
        
        $pacientes = $this->paciente->obtenerTodos();
        $especialidades = $this->especialidad->obtenerTodos();
        include '../views/citas/crear.php';
    }
    
    public function editar() {
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'paciente_id' => $_POST['paciente_id'],
                'medico_id' => $_POST['medico_id'],
                'fecha' => $_POST['fecha'],
                'hora' => $_POST['hora'],
                'estado' => $_POST['estado']
            ];
            
            // Validar disponibilidad del médico (excluyendo la cita actual)
            if (!$this->medico->verificarDisponibilidad($datos['medico_id'], $datos['fecha'], $datos['hora'], $id)) {
                $error = "El médico ya tiene una cita programada en esa fecha y hora";
            } else {
                if ($this->cita->actualizar($id, $datos)) {
                    header('Location: index.php?controller=cita&action=index&msg=actualizado');
                } else {
                    $error = "Error al actualizar cita";
                }
            }
        }
        
        $cita = $this->cita->obtenerPorId($id);
        $pacientes = $this->paciente->obtenerTodos();
        $especialidades = $this->especialidad->obtenerTodos();
        $medicos = $this->medico->obtenerPorEspecialidad($cita['especialidad_id']);
        include '../views/citas/editar.php';
    }
    
    public function eliminar() {
        $id = $_GET['id'];
        
        if ($this->cita->eliminar($id)) {
            header('Location: index.php?controller=cita&action=index&msg=eliminado');
        } else {
            header('Location: index.php?controller=cita&action=index&error=1');
        }
    }
    
    public function getMedicosPorEspecialidad() {
        $especialidad_id = $_GET['especialidad_id'];
        $medicos = $this->medico->obtenerPorEspecialidad($especialidad_id);
        
        header('Content-Type: application/json');
        echo json_encode($medicos);
    }
    
    public function cambiarEstado() {
        $id = $_POST['id'];
        $estado = $_POST['estado'];
        
        if ($this->cita->cambiarEstado($id, $estado)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
?>