<?php include '../views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-stethoscope"></i> Gestión de Médicos</h2>
                <a href="index.php?controller=medico&action=crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Médico
                </a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php
                    switch ($_GET['msg']) {
                        case 'creado':
                            echo 'Médico creado exitosamente';
                            break;
                        case 'actualizado':
                            echo 'Médico actualizado exitosamente';
                            break;
                        case 'eliminado':
                            echo 'Médico eliminado exitosamente';
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
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Especialidad</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($medicos)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay médicos registrados</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($medicos as $medico): ?>
                                        <tr>
                                            <td><?php echo $medico['idmedico']; ?></td>
                                            <td><?php echo htmlspecialchars($medico['nombres']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['apellidos']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['especialidad_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['email']); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?controller=medico&action=editar&id=<?php echo $medico['idmedico']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmarEliminacion(<?php echo $medico['idmedico']; ?>)">
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
    if (confirm('¿Está seguro de que desea eliminar este médico?')) {
        window.location.href = 'index.php?controller=medico&action=eliminar&id=' + id;
    }
}
</script>

<?php include '../views/layouts/footer.php'; ?>