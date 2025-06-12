<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Vida Sana - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn {
            border-radius: 10px;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="bg-white rounded-circle p-3 d-inline-flex">
                            <i class="fas fa-heartbeat text-primary fa-2x"></i>
                        </div>
                        <h5 class="text-white mt-2">Clínica Vida Sana</h5>
                        <small class="text-white-50">Bienvenido, <?php echo $_SESSION['usuario']; ?></small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? 'auth') == 'auth' ? 'active' : ''; ?>" 
                               href="index.php?controller=auth&action=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? '') == 'paciente' ? 'active' : ''; ?>" 
                               href="index.php?controller=paciente&action=list">
                                <i class="fas fa-users"></i> Pacientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? '') == 'medico' ? 'active' : ''; ?>" 
                               href="index.php?controller=medico&action=list">
                                <i class="fas fa-user-md"></i> Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? '') == 'especialidad' ? 'active' : ''; ?>" 
                               href="index.php?controller=especialidad&action=list">
                                <i class="fas fa-stethoscope"></i> Especialidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? '') == 'cita' ? 'active' : ''; ?>" 
                               href="index.php?controller=cita&action=list">
                                <i class="fas fa-calendar-alt"></i> Citas Médicas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_GET['controller'] ?? '') == 'historial' ? 'active' : ''; ?>" 
                               href="index.php?controller=historial&action=list">
                                <i class="fas fa-file-medical"></i> Historiales
                            </a>
                        </li>
                        <hr class="text-white-50">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=logout">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div id="sessionWarning" class="alert alert-warning alert-dismissible fade" role="alert" style="display: none;">
                    <i class="fas fa-clock"></i> Su sesión expirará en 1 minuto por inactividad.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Control de sesión - Auto-logout después de 5 minutos
        let lastActivity = new Date().getTime();
        let warningShown = false;
        
        // Actualizar última actividad
        function updateActivity() {
            lastActivity = new Date().getTime();
            warningShown = false;
            document.getElementById('sessionWarning').style.display = 'none';
        }
        
        // Verificar inactividad cada 30 segundos
        setInterval(function() {
            const now = new Date().getTime();
            const timeDiff = now - lastActivity;
            
            // Mostrar advertencia a los 4 minutos (240000 ms)
            if (timeDiff > 240000 && !warningShown) {
                const warning = document.getElementById('sessionWarning');
                warning.style.display = 'block';
                warning.classList.add('show');
                warningShown = true;
            }
            
            // Logout automático a los 5 minutos (300000 ms)
            if (timeDiff > 300000) {
                window.location.href = 'index.php?controller=auth&action=logout';
            }
        }, 30000);
        
        // Detectar actividad del usuario
        document.addEventListener('mousemove', updateActivity);
        document.addEventListener('keypress', updateActivity);
        document.addEventListener('click', updateActivity);
        document.addEventListener('scroll', updateActivity);
        
        // Mantener sesión activa con peticiones AJAX periódicas
        setInterval(function() {
            fetch('index.php?controller=auth&action=dashboard', {
                method: 'HEAD',
                credentials: 'same-origin'
            }).catch(function() {
                // Si falla la petición, redirigir al login
                window.location.href = 'index.php?action=login&timeout=1';
            });
        }, 120000); // Cada 2 minutos
        
        // Validación global de formularios
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let valid = true;
                    
                    requiredFields.forEach(function(field) {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            valid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!valid) {
                        e.preventDefault();
                        alert('Por favor, complete todos los campos obligatorios.');
                    }
                });
            });
            
            // Validación en tiempo real
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
        
        // Función para confirmar eliminaciones
        function confirmarEliminar(mensaje = '¿Está seguro de que desea eliminar este elemento?') {
            return confirm(mensaje);
        }
        
        // Auto-hide de alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 150);
                }
            });
        }, 5000);
    </script>
</body>
</html>