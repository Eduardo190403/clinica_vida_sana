<?php include 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Pacientes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?controller=paciente&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Paciente
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        switch($_GET['success']) {
            case 1: echo "Paciente registrado exitosamente"; break;
            case 2: echo "Paciente actualizado exitosamente"; break;
            case 3: echo "Paciente eliminado exitosamente"; break;
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php 
        switch($_GET['error']) {
            case 1: echo "Error al procesar la solicitud"; break;
            case 2: echo "Paciente no encontrado"; break;
            case 3: echo "No se puede eliminar el paciente porque tiene citas asociadas"; break;
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="pacientesTable">
                <thead class="table-dark">
                    <tr>
                        <th>DNI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pacientes)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No hay pacientes registrados
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['dni']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['email']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?controller=paciente&action=edit&id=<?php echo $paciente['idpaciente']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmarEliminar(<?php echo $paciente['idpaciente']; ?>)" title="Eliminar">
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

<script>
function confirmarEliminar(id) {
    if (confirm('¿Está seguro de que desea eliminar este paciente?')) {
        window.location.href = 'index.php?controller=paciente&action=delete&id=' + id;
    }
}

// Inicializar DataTable si hay datos
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('pacientesTable');
    if (table.rows.length > 1) {
        // Agregar funcionalidad de búsqueda simple
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'form-control mb-3';
        searchInput.placeholder = 'Buscar paciente...';
        
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
        
        table.parentNode.insertBefore(searchInput, table);
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>