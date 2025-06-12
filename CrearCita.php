<?php include '../views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-alt"></i> Nueva Cita</h2>
                <a href="index.php?controller=cita&action=index" class="btn btn-secondary">
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
                    <form method="POST" id="formCita">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="paciente_id" class="form-label">Paciente *</label>
                                    <select class="form-select" id="paciente_id" name="paciente_id" required>
                                        <option value="">Seleccione un paciente</option>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <option value="<?php echo $paciente['idpaciente']; ?>">
                                                <?php echo htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos'] . ' - ' . $paciente['dni']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un paciente</div>
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medico_id" class="form-label">Médico *</label>
                                    <select class="form-select" id="medico_id" name="medico_id" required disabled>
                                        <option value="">Primero seleccione una especialidad</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione un médico</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                                    <div class="invalid-feedback">Por favor seleccione una fecha válida</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora" class="form-label">Hora *</label>
                                    <select class="form-select" id="hora" name="hora" required>
                                        <option value="">Seleccione una hora</option>
                                        <option value="08:00">08:00</option>
                                        <option value="08:30">08:30</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <option value="11:30">11:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <option value="16:30">16:30</option>
                                        <option value="17:00">17:00</option>
                                        <option value="17:30">17:30</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor seleccione una hora</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="history.back()">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar médicos por especialidad
document.getElementById('especialidad_id').addEventListener('change', function() {
    const especialidadId = this.value;
    const medicoSelect = document.getElementById('medico_id');
    
    if (especialidadId) {
        fetch(`index.php?controller=cita&action=getMedicosPorEspecialidad&especialidad_id=${especialidadId}`)
            .then(response => response.json())
            .then(data => {
                medicoSelect.innerHTML = '<option value="">Seleccione un médico</option>';
                data.forEach(medico => {
                    medicoSelect.innerHTML += `<option value="${medico.idmedico}">${medico.nombres} ${medico.apellidos}</option>`;
                });
                medicoSelect.disabled = false;
            });
    } else {
        medicoSelect.innerHTML = '<option value="">Primero seleccione una especialidad</option>';
        medicoSelect.disabled = true;
    }
});

// Validación del formulario
document.getElementById('formCita').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paciente_id = document.getElementById('paciente_id').value;
    const especialidad_id = document.getElementById('especialidad_id').value;
    const medico_id = document.getElementById('medico_id').value;
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    
    let valid = true;
    
    // Validar todos los campos
    if (!paciente_id) {
        document.getElementById('paciente_id').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('paciente_id').classList.remove('is-invalid');
    }
    
    if (!especialidad_id) {
        document.getElementById('especialidad_id').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('especialidad_id').classList.remove('is-invalid');
    }
    
    if (!medico_id) {
        document.getElementById('medico_id').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('medico_id').classList.remove('is-invalid');
    }
    
    if (!fecha) {
        document.getElementById('fecha').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('fecha').classList.remove('is-invalid');
    }
    
    if (!hora) {
        document.getElementById('hora').classList.add('is-invalid');
        valid = false;
    } else {
        document.getElementById('hora').classList.remove('is-invalid');
    }
    
    if (valid) {
        this.submit();
    }
});
</script>

<?php include '../views/layouts/footer.php'; ?>