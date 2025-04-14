<?php
// Verificar la sesión del usuario si es necesario
if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok") {
    echo '<script>window.location = "inicio";</script>';
    exit();
}
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-content">
            <!-- Columna Izquierda - Formulario -->
            <div class="auth-form-section">
                <div class="auth-header">
                    <h2><i class="fas fa-user-plus"></i> Registrarse</h2>
                </div>
                
                <form role="form" method="post" enctype="multipart/form-data" class="auth-form" id="formularioUsuario">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nuevoNombre">
                                <i class="fas fa-user"></i> Nombre
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nuevoNombre" 
                                   name="nuevoNombre" 
                                   placeholder="Ingrese su nombre completo" 
                                   required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="nuevoUsuario">
                                <i class="fas fa-at"></i> Usuario
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nuevoUsuario" 
                                   name="nuevoUsuario" 
                                   placeholder="Ingrese su nombre de usuario" 
                                   required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nuevoPassword">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <div class="password-input">
                                <input type="password" 
                                       class="form-control" 
                                       id="nuevoPassword" 
                                       name="nuevoPassword" 
                                       placeholder="Ingrese su contraseña" 
                                       required>
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="nuevoPerfil">
                                <i class="fas fa-user-tag"></i> Perfil
                            </label>
                            <select class="form-control" id="nuevoPerfil" name="nuevoPerfil" required>
                                <option value="">Seleccione un perfil</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Especial">Especial</option>
                                <option value="Vendedor">Vendedor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mover el input de la foto aquí dentro del formulario -->
                    <input type="file" class="nuevaFoto" name="nuevaFoto" id="nuevaFoto" accept="image/*" style="display: none;">
                    
                    <div class="auth-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Registrarse
                        </button>
                        <a href="usuarios" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>

                    <?php
                    $crearUsuario = new ControladorUsuarios();
                    $crearUsuario->ctrCrearUsuario();
                    ?>
                </form>
            </div>

            <!-- Columna Derecha - Foto -->
            <div class="auth-photo-section">
                <div class="photo-upload-container">
                    <div class="preview-container">
                        <img src="vistas/img/usuarios/default/anonymous.png" class="preview-image" alt="Vista previa">
                    </div>
                    <div class="file-upload">
                        <label for="nuevaFoto" class="file-label">
                            <i class="fas fa-cloud-upload-alt"></i> Elegir foto
                        </label>
                    </div>
                    <small class="file-info">Máximo 2MB (JPG o PNG)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --negro-color: #000000;
    --primary-color: #00A884;
    --secondary-color: #128C7E;
    --accent-color: #34B7F1;
    --text-color: #4a4a4a;
    --light-gray: #f5f5f5;
    --border-color: #ddd;
    --error-color: #dc3545;
    --success-color: #28a745;
    --gray-color: #6c757d;
    --azul-color: #3c8dbc;
    --nuevocolor:#,
}

.auth-container {
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #03093d, #071f2f);



 
    padding: 30px;
}

.auth-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 1200px;
    overflow: hidden;
}

.auth-content {
    display: flex;
    min-height: 600px;
}

.auth-form-section {
    flex: 2;
    padding: 40px;
    background: white;
}

.auth-photo-section {
    flex: 1;
    background: var(--light-gray);
    padding: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 2px solid var(--border-color);
}

.auth-header {
    margin-bottom: 40px;
}

.auth-header h2 {
    color: var(--azul-color);
    font-size: 32px;
    font-weight: 600;
}

.auth-header h2 i {
    font-size: 28px;
    margin-right: 10px;
}

.form-row {
    display: flex;
    margin: 0 -15px;
    margin-bottom: 25px;
}

.form-group {
    padding: 0 15px;
    margin-bottom: 25px;
}

.col-md-6 {
    flex: 0 0 50%;
}

.form-control {
    width: 100%;
    padding: 20px 15px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--azul-color);
    box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.25);

    outline: none;
}

.form-group label {
    display: block;
    margin-bottom: 12px;
    color: var(--negro-color);
    font-weight: 500;
    font-size: 16px;
}

.form-group label i {
    margin-right: 10px;
    color: var(--negro-color);
    font-size: 18px;
}

.password-input {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-color);
    cursor: pointer;
    padding: 0;
}

.photo-upload-container {
    text-align: center;
}

.preview-container {
    margin-bottom: 20px;
}

.preview-image {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
}

.file-upload {
    margin-bottom: 10px;
}

.file-label {
    display: inline-block;
    padding: 15px 30px;
    background: var(--azul-color);
    color: white;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 25px;
}

.file-label:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.file-upload input[type="file"] {
    display: none;
}

.file-info {
    display: block;
    color: var(--text-color);
    font-size: 14px;
    margin-top: 12px;
}

.auth-actions {
    display: flex;
    gap: 20px;
    margin-top: 40px;
}

.btn {
    padding: 18px 35px;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
}

.btn i {
    font-size: 18px;
    margin-right: 10px;
}

.btn-primary {
    background: var(--azul-color);
    color: white;
}

.btn-secondary {
    background: var(--gray-color);
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .auth-content {
        flex-direction: column;
    }

    .auth-photo-section {
        border-left: none;
        border-top: 2px solid var(--border-color);
        padding: 30px;
    }

    .form-row {
        flex-direction: column;
    }

    .col-md-6 {
        flex: 0 0 100%;
    }

    .auth-card {
        margin: 15px;
    }

    .preview-image {
        width: 200px;
        height: 200px;
    }

    .auth-header h2 {
        font-size: 28px;
    }
}

/* Estilos para el select */
select.form-control {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23000000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
    color: var(--negro-color);
    height: 65px;
}

select.form-control option {
    color: var(--negro-color);
    padding: 10px;
}

select.form-control option:first-child {
    color: #757575;
}

/* Ajuste específico para el select de perfil */
#nuevoPerfil {
    font-size: 14px;
   
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#nuevoPassword');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Mejorado el manejo de la carga de imagen
    const fileInput = document.querySelector('.nuevaFoto');
    const previewImage = document.querySelector('.preview-image');
    const fileLabel = document.querySelector('.file-label');
    const formulario = document.getElementById('formularioUsuario');

    fileInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            if(file.type.match('image.*')) {
                if(file.size <= 2 * 1024 * 1024) { // 2MB máximo
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                    fileLabel.innerHTML = '<i class="fas fa-check"></i> Foto seleccionada';
                    fileLabel.style.background = 'var(--success-color)';
                } else {
                    this.value = '';
                    alert('La imagen es demasiado grande. El tamaño máximo es 2MB.');
                    fileLabel.innerHTML = '<i class="fas fa-times"></i> Archivo muy grande';
                    fileLabel.style.background = 'var(--error-color)';
                }
            } else {
                this.value = '';
                alert('Por favor, seleccione un archivo de imagen válido (JPG o PNG).');
                fileLabel.innerHTML = '<i class="fas fa-times"></i> Archivo no válido';
                fileLabel.style.background = 'var(--error-color)';
            }
        }
    });

    // Validación antes de enviar
    formulario.addEventListener('submit', function(e) {
        const fileInput = this.querySelector('.nuevaFoto');
        if(fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if(!file.type.match('image.*') || file.size > 2 * 1024 * 1024) {
                e.preventDefault();
                alert('Por favor, seleccione una imagen válida de menos de 2MB.');
            }
        }
    });
});
</script>
