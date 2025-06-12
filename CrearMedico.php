<?php include '../views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-stethoscope"></i> Nuevo Médico</h2>
                <a href="index.php?controller=medico&action=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" id="formMedico">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombres" class="form-label">Nombres *</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    <div class="invalid-feedback">Por favor ingrese los nombres</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    <div class="invalid-feedback">Por favor ingrese los apellidos</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="especialidad_id" class="form-label">Especialidad *</label>
                                    <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                        <option value="">Seleccione una especialidad</option>
                                        <?php foreach ($especialidades as $especialidad): ?>
                                            <option value="<?php echo $especialidad['idespecialidad']; ?>">
                                                <?php echo htmlspecialchars($especialidad['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione una especialidad</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                                    <div class="invalid-feedback">Por favor ingrese el teléfono</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Por favor ingrese un email válido</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="history.back()">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Médico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formMedico').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const especialidad_id = document.getElementById('especialidad_id').value;
    const telefono = document.getElementById('telefono').value.trim();
    const email = document.getElementById('email').value.trim();
    
    let valid = true;
    
    // Validar nombres
    if (nombres === '') {
        document.getElementById('nombres').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('nombres').classList.remove('is-invalid');
    }
    
    // Validar apellidos
    if (apellidos === '') {
        document.getElementById('apellidos').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('apellidos').classList.remove('is-invalid');
    }
    
    // Validar especialidad
    if (especialidad_id === '') {
        document.getElementById('especialidad_id').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('especialidad_id').classList.remove('is-invalid');
    }
    
    // Validar teléfono
    if (telefono === '' || telefono.length < 9) {
        document.getElementById('telefono').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('telefono').classList.remove('is-invalid');
    }
    
    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '' || !emailRegex.test(email)) {
        document.getElementById('email').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('email').classList.remove('is-invalid');
    }
    
    if (valid) {
        this.submit();
    }
});
</script>

<?php include '../views/layouts/footer.php'; ?>