<?php include 'views/includes/header.php'; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h4>
                </div>
                <div class="card-body">
                    <form id="formPaciente" method="POST" action="index.php?controller=pacientes&action=store">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="dni" name="dni" maxlength="8" required>
                                    <div class="invalid-feedback">
                                        Ingrese un DNI válido de 8 dígitos
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15" required>
                                    <div class="invalid-feedback">
                                        Ingrese un número de teléfono válido
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" maxlength="100" required>
                                    <div class="invalid-feedback">
                                        Los nombres son obligatorios
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" maxlength="100" required>
                                    <div class="invalid-feedback">
                                        Los apellidos son obligatorios
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2" maxlength="255"></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="100">
                            <div class="invalid-feedback">
                                Ingrese un email válido
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?controller=pacientes&action=index" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Paciente
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
    const form = document.getElementById('formPaciente');
    const dniInput = document.getElementById('dni');
    const telefonoInput = document.getElementById('telefono');
    const nombresInput = document.getElementById('nombres');
    const apellidosInput = document.getElementById('apellidos');
    const emailInput = document.getElementById('email');
    
    // Validación solo números para DNI
    dniInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length === 8) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Validación solo números para teléfono
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length >= 7) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Validación solo letras y espacios para nombres y apellidos
    [nombresInput, apellidosInput].forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            if (this.value.trim().length >= 2) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
    
    // Validación de email
    emailInput.addEventListener('input', function() {
        if (this.value === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
            this.classList.remove('is-invalid');
            if (this.value !== '') this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    // Validación del formulario antes del envío
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validar DNI
        if (dniInput.value.length !== 8) {
            dniInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar teléfono
        if (telefonoInput.value.length < 7) {
            telefonoInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar nombres y apellidos
        if (nombresInput.value.trim().length < 2) {
            nombresInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (apellidosInput.value.trim().length < 2) {
            apellidosInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar email si se ingresó
        if (emailInput.value !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                text: 'Por favor, corrija los errores antes de continuar'
            });
        }
    });
});
</script>

<?php include 'views/includes/footer.php'; ?>