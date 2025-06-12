<?php
// controllers/AutentificadorControlador
require_once 'models/Usuario.php';

class AuthController {
    private $usuario;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->usuario = new Usuario($db);
    }
    
    public function login() {
        $error = '';
        $timeout = false;
        
        if (isset($_GET['timeout'])) {
            $timeout = true;
        }
        
        if (isset($_GET['error'])) {
            $error = 'Usuario o contraseña incorrectos';
        }
        
        // Verificar cookie de saludo
        $saludo_personalizado = '';
        if (isset($_COOKIE['ultimo_usuario'])) {
            $saludo_personalizado = "¡Bienvenido de nuevo, " . $_COOKIE['ultimo_usuario'] . "!";
        }
        
        include 'views/auth/login.php';
    }
    
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            
            $user = $this->usuario->authenticate($usuario, $clave);
            
            if ($user) {
                $_SESSION['user_id'] = $user['idusuario'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['last_activity'] = time();
                
                // Establecer cookie para saludo personalizado (7 días)
                setcookie('ultimo_usuario', $user['usuario'], time() + (7 * 24 * 60 * 60), '/');
                
                header("Location: index.php?controller=auth&action=dashboard");
                exit();
            } else {
                header("Location: index.php?action=login&error=1");
                exit();
            }
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
    
    public function dashboard() {
        // Obtener estadísticas para el dashboard
        $stats = $this->usuario->getStats();
        include 'views/dashboard.php';
    }
}
?>