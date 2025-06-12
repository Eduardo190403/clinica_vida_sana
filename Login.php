<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica Vida Sana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card p-5">
                    <div class="text-center mb-4">
                        <div class="logo">
                            <i class="fas fa-heartbeat text-white fa-2x"></i>
                        </div>
                        <h2 class="text-primary fw-bold">Clínica Vida Sana</h2>
                        <p class="text-muted">Sistema de Gestión Médica</p>
                    </div>
                    
                    <?php if ($saludo_personalizado): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-smile"></i> <?php echo $saludo_personalizado; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($timeout): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> Su sesión ha expirado por inactividad.
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?action=authenticate" id="loginForm">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="clave" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            <strong>Usuarios de prueba:</strong><br>
                            admin / password<br>
                            recepcionista1 / password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const clave = document.getElementById('clave').value.trim();
            
            if (!usuario || !clave) {
                e.preventDefault();
                alert('Por favor, complete todos los campos.');
                return false;
            }
            
            if (usuario.length < 3) {
                e.preventDefault();
                alert('El usuario debe tener al menos 3 caracteres.');
                return false;
            }
        });
        
        // Auto-logout warning
        let warningTime = 4 * 60 * 1000; // 4 minutos
        let logoutTime = 5 * 60 * 1000;  // 5 minutos
        
        setTimeout(function() {
            alert('Su sesión expirará en 1 minuto por inactividad.');
        }, warningTime);
    </script>
</body>
</html>