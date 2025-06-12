<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-edit"></i> Editar Historial Clínico</h4>
                </div>
                <div class="card-body">
                    <!-- Información de la Cita -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Información de la Cita</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Paciente:</strong><br>
                                        <?php echo htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Médico:</strong><br>
                                        <?php echo htmlspecialchars($cita['medico_nombres'] . ' ' . $cita['medico_apellidos']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Fecha:</strong><br>
                                        <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Hora:</strong><br>
                                        <?php echo date('H:i', strtotime($cita['hora'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="formHistorial" method="POST" action="index.php?controller=historiales&action=update&id=<?php echo $historial['idhistorial']; ?>">
                        <input type="hidden" name="cita_id" value="<?php echo $cita['idcita']; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="diagnostico" class="form-label">Diagnóstico <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="diagnostico" name="diagnostico" rows="4" 
                                    placeholder="Ingrese el diagnóstico médico..." required><?php echo htmlspecialchars($historial['diagnostico']); ?></textarea>
                            <div class="invalid-feedback">
                                El diagnóstico es obligatorio
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="tratamiento" class="form-label">Tratamiento <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="tratamiento" name="tratamiento" rows="4" 
                                    placeholder="Describa el tratamiento prescrito..." required><?php echo htmlspecialchars($historial['tratamiento']); ?></textarea>
                            <div class="invalid-feedback">
                                El tratamiento es obligatorio
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                    placeholder="Observaciones adicionales..."><?php echo htmlspecialchars($historial['observaciones']); ?></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?controller=historiales&action=index" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Historial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formHistorial');
    const diagnosticoInput = document.getElementById('diagnostico');
    const tratamientoInput = document.getElementById('tratamiento');
    
    // Validación en tiempo real
    [diagnosticoInput, tratamientoInput].forEach(textarea => {
        textarea.addEventListener('input', function() {
            if (this.value.trim().length >= 10) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (diagnosticoInput.value.trim().length < 10) {
            diagnosticoInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (tratamientoInput.value.trim().length < 10) {
            tratamientoInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                text: 'El diagnóstico y tratamiento deben tener al menos 10 caracteres'
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>