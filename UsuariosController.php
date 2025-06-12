<?php
require_once 'models/Usuario.php';

class UsuariosController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    public function index() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        $usuarios = $this->usuarioModel->obtenerTodos();
        include 'views/usuarios/index.php';
    }
    
    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        include 'views/usuarios/create.php';
    }
    
    public function store() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $rol = $_POST['rol'] ?? 'recepcionista';
            
            // Validar que el usuario no exista
            if ($this->usuarioModel->existeUsuario($usuario)) {
                $_SESSION['error'] = "El nombre de usuario ya existe";
                header("Location: index.php?controller=usuarios&action=create");
                exit();
            }
            
            if ($this->usuarioModel->crear($usuario, $clave, $rol)) {
                $_SESSION['success'] = "Usuario creado exitosamente";
                header("Location: index.php?controller=usuarios&action=index");
            } else {
                $_SESSION['error'] = "Error al crear el usuario";
                header("Location: index.php?controller=usuarios&action=create");
            }
        }
    }
    
    public function edit($id) {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        $usuario = $this->usuarioModel->obtenerPorId($id);
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: index.php?controller=usuarios&action=index");
            exit();
        }
        
        include 'views/usuarios/edit.php';
    }
    
    public function update($id) {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $rol = $_POST['rol'] ?? 'recepcionista';
            $clave = null;
            
            // Si se proporciona nueva clave, cifrarla
            if (!empty($_POST['clave'])) {
                $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            }
            
            // Validar que el usuario no exista (excepto el actual)
            if ($this->usuarioModel->existeUsuarioExcepto($usuario, $id)) {
                $_SESSION['error'] = "El nombre de usuario ya existe";
                header("Location: index.php?controller=usuarios&action=edit&id=$id");
                exit();
            }
            
            if ($this->usuarioModel->actualizar($id, $usuario, $clave, $rol)) {
                $_SESSION['success'] = "Usuario actualizado exitosamente";
                header("Location: index.php?controller=usuarios&action=index");
            } else {
                $_SESSION['error'] = "Error al actualizar el usuario";
                header("Location: index.php?controller=usuarios&action=edit&id=$id");
            }
        }
    }
    
    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        // No permitir eliminar el usuario actual
        if ($id == $_SESSION['usuario_id']) {
            $_SESSION['error'] = "No puedes eliminar tu propio usuario";
            header("Location: index.php?controller=usuarios&action=index");
            exit();
        }
        
        if ($this->usuarioModel->eliminar($id)) {
            $_SESSION['success'] = "Usuario eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        
        header("Location: index.php?controller=usuarios&action=index");
    }
    
    public function perfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        include 'views/usuarios/perfil.php';
    }
    
    public function actualizarPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $clave = null;
            
            // Si se proporciona nueva clave, cifrarla
            if (!empty($_POST['clave'])) {
                $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            }
            
            // Validar que el usuario no exista (excepto el actual)
            if ($this->usuarioModel->existeUsuarioExcepto($usuario, $_SESSION['usuario_id'])) {
                $_SESSION['error'] = "El nombre de usuario ya existe";
                header("Location: index.php?controller=usuarios&action=perfil");
                exit();
            }
            
            if ($this->usuarioModel->actualizar($_SESSION['usuario_id'], $usuario, $clave, $_SESSION['rol'])) {
                $_SESSION['success'] = "Perfil actualizado exitosamente";
                $_SESSION['usuario'] = $usuario; // Actualizar el usuario en la sesión
            } else {
                $_SESSION['error'] = "Error al actualizar el perfil";
            }
            
            header("Location: index.php?controller=usuarios&action=perfil");
        }
    }
    
    public function logout() {
        // Eliminar todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruir la sesión completamente
        session_destroy();
        
        // Eliminar cookies de bienvenida
        setcookie('usuario_bienvenida', '', time() - 3600, '/');
        
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
    
    public function verificarSesion() {
        // Verificar si hay una sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['activa' => false]);
            exit();
        }
        
        // Verificar tiempo de inactividad (5 minutos = 300 segundos)
        if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad']) > 300) {
            echo json_encode(['activa' => false, 'expirada' => true]);
            exit();
        }
        
        // Actualizar tiempo de última actividad
        $_SESSION['ultima_actividad'] = time();
        
        echo json_encode(['activa' => true]);
    }
}
?>