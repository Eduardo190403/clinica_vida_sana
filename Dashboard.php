<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Clínica Vida Sana</title>
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
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        .stat-card.warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
        }
        .stat-card.danger {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
        }
        .stat-card.info {
            background: linear-gradient(45deg, #17a2b8, #6610f2);
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
                            <a class="nav-link active" href="index.php?controller=auth&action=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=paciente&action=list">
                                <i class="fas fa-users"></i> Pacientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=medico&action=list">
                                <i class="fas fa-user-md"></i> Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=especialidad&action=list">
                                <i class="fas fa-stethoscope"></i> Especialidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=cita&action=list">
                                <i class="fas fa-calendar-alt"></i> Citas Médicas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=historial&action=list">
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
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <p class="text-muted">Resumen general del sistema</p>
                </div>
                
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <h3><?php echo $stats['pacientes']; ?></h3>
                                <p class="mb-0">Total Pacientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card info">
                            <div class="card-body text-center">
                                <i class="fas fa-user-md fa-3x mb-3"></i>
                                <h3><?php echo $stats['medicos']; ?></h3>
                                <p class="mb-0">Total Médicos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card warning">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-day fa-3x mb-3"></i>
                                <h3><?php echo $stats['citas_hoy']; ?></h3>
                                <p class="mb-0">Citas de Hoy</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card danger">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-3x mb-3"></i>
                                <h3><?php echo $stats['citas_pendientes']; ?></h3>
                                <p class="mb-0">Citas Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Accesos rápidos -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-plus"></i> Acciones Rápidas</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="index.php?controller=paciente&action=create" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus"></i> Nuevo Paciente
                                    </a>
                                    <a href="index.php?controller=cita&action=create" class="btn btn-outline-success">
                                        <i class="fas fa-calendar-plus"></i> Nueva Cita
                                    </a>
                                    <a href="index.php?controller=medico&action=create" class="btn btn-outline-info">
                                        <i class="fas fa-user-md"></i> Nuevo Médico
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Sistema funcionando correctamente</li>
                                    <li><i class="fas fa-shield-alt text-primary"></i> Sesión segura activa</li>
                                    <li><i class="fas fa-clock text-warning"></i> Auto-logout en 5 min de inactividad</li>
                                    <li><i class="fas fa-database text-info"></i> Base de datos conectada</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh cada 30 segundos para mantener la sesión activa
        setInterval(function() {
            fetch(window.location.href, {method: 'HEAD'});
        }, 30000);
        
        // Advertencia de cierre de sesión
        let warningTime = 4 * 60 * 1000; // 4 minutos
        setTimeout(function() {
            if (confirm('Su sesión expirará en 1 minuto. ¿Desea continuar?')) {
                location.reload();
            }
        }, warningTime);
    </script>
</body>
</html>