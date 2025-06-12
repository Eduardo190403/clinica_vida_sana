<?php include '../views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-alt"></i> Gestión de Citas</h2>
                <a href="index.php?controller=cita&action=crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Cita
                </a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php
                    switch ($_GET['msg']) {
                        case 'creado':
                            echo 'Cita creada exitosamente';
                            break;
                        case 'actualizado':
                            echo 'Cita actualizada exitosamente';
                            break;
                        case 'eliminado':
                            echo 'Cita eliminada exitosamente';
                            break;
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error al realizar la operación
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($citas)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No hay citas registradas</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($citas as $cita): ?>
                                        <tr>
                                            <td><?php echo $cita['idcita']; ?></td>
                                            <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($cita['especialidad_nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($cita['hora'])); ?></td>
                                            <td>
                                                <span class="badge <?php 
                                                    switch($cita['estado']) {
                                                        case 'pendiente': echo 'bg-warning text-dark'; break;
                                                        case 'atendida': echo 'bg-success'; break;
                                                        case 'cancelada': echo 'bg-danger'; break;
                                                        default: echo 'bg-secondary';
                                                    }
                                                ?>">
                                                    <?php echo ucfirst($cita['estado']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?controller=cita&action=editar&id=<?php echo $cita['idcita']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($cita['estado'] == 'pendiente'): ?>
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                onclick="cambiarEstado(<?php echo $cita['idcita']; ?>, 'atendida')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary" 
                                                                onclick="cambiarEstado(<?php echo $cita['idcita']; ?>, 'cancelada')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmarEliminacion(<?php echo $cita['idcita']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de que desea eliminar esta cita?')) {
        window.location.href = 'index.php?controller=cita&action=eliminar&id=' + id;
    }
}

function cambiarEstado(id, estado) {
    const mensaje = estado === 'atendida' ? 'marcar como atendida' : 'cancelar';
    if (confirm(`¿Está seguro de que desea ${mensaje} esta cita?`)) {
        fetch('index.php?controller=cita&action=cambiarEstado', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&estado=${estado}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado de la cita');
            }
        });
    }
}
</script>

<?php include '../views/layouts/footer.php'; ?>