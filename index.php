<?php
// index.php
session_start();

// Auto-logout después de 5 minutos de inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    session_unset();
    session_destroy();
    header("Location: index.php?action=login&timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PacienteController.php';
require_once 'controllers/MedicoController.php';
require_once 'controllers/EspecialidadController.php';
require_once 'controllers/CitaController.php';
require_once 'controllers/HistorialController.php';
require_once 'controllers/UsuariosController.php';

// Enrutamiento básico
$action = $_GET['action'] ?? 'login';
$controller = $_GET['controller'] ?? 'auth';

// Verificar si el usuario está logueado (excepto para login)
if ($action !== 'login' && $action !== 'authenticate' && !isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'login':
                $authController->login();
                break;
            case 'authenticate':
                $authController->authenticate();
                break;
            case 'logout':
                $authController->logout();
                break;
            case 'dashboard':
                $authController->dashboard();
                break;
            default:
                $authController->login();
        }
        break;
        
    case 'paciente':
        $pacienteController = new PacienteController();
        switch ($action) {
            case 'list':
                $pacienteController->index();
                break;
            case 'create':
                $pacienteController->create();
                break;
            case 'store':
                $pacienteController->store();
                break;
            case 'edit':
                $pacienteController->edit();
                break;
            case 'update':
                $pacienteController->update();
                break;
            case 'delete':
                $pacienteController->delete();
                break;
            default:
                $pacienteController->index();
        }
        break;
        
    case 'Medico':
        $medicoController = new MedicoController();
        switch ($action) {
            case 'list':
                $medicoController->index();
                break;
            case 'create':
                $medicoController->create();
                break;
            case 'store':
                $medicoController->store();
                break;
            case 'edit':
                $medicoController->edit();
                break;
            case 'update':
                $medicoController->update();
                break;
            case 'delete':
                $medicoController->delete();
                break;
            default:
                $medicoController->index();
        }
        break;
        
    case 'Especialidad':
        $especialidadController = new EspecialidadController();
        switch ($action) {
            case 'list':
                $especialidadController->index();
                break;
            case 'create':
                $especialidadController->create();
                break;
            case 'store':
                $especialidadController->store();
                break;
            case 'edit':
                $especialidadController->edit();
                break;
            case 'update':
                $especialidadController->update();
                break;
            case 'delete':
                $especialidadController->delete();
                break;
            default:
                $especialidadController->index();
        }
        break;
        
    case 'cita':
        $citaController = new CitaController();
        switch ($action) {
            case 'list':
                $citaController->index();
                break;
            case 'create':
                $citaController->create();
                break;
            case 'store':
                $citaController->store();
                break;
            case 'edit':
                $citaController->edit();
                break;
            case 'update':
                $citaController->update();
                break;
            case 'delete':
                $citaController->delete();
                break;
            case 'atender':
                $citaController->atender();
                break;
            default:
                $citaController->index();
        }
        break;
        
    case 'historial':
        $historialController = new HistorialController();
        switch ($action) {
            case 'list':
                $historialController->index();
                break;
            case 'create':
                $historialController->create();
                break;
            case 'store':
                $historialController->store();
                break;
            case 'view':
                $historialController->view();
                break;
            default:
                $historialController->index();
        }
        break;

         case 'Usuario':
        $historialController = new UsuariosController();
        switch ($action) {
            case 'list':
                $historialController->index();
                break;
            case 'create':
                $historialController->create();
                break;
            case 'store':
                $historialController->store();
                break;
            case 'view':
                $historialController->view();
                break;
            default:
                $historialController->index();
        }
        break;
        
    default:
        header("Location: index.php?controller=auth&action=dashboard");
}
?>