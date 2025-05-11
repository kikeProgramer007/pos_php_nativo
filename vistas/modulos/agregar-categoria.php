
<?php

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>


<div class="content-wrapper">
    <section class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="auth-container">
                    <div class="auth-card">
                        <div class="auth-content">
                            <!-- Columna Izquierda - Formulario -->
                            <div class="auth-form-section">
                                <div class="auth-header">
                                <h2><i class="fa fa-tags"></i> REGISTRAR CATEGORIA</h2>

                                </div>
                                
                                <form role="form" method="post" class="auth-form">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="nuevaCategoria">
                                            <i class="fa fa-cutlery"></i> Nombre de la Categoría



                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nuevaCategoria" 
                                                   name="nuevaCategoria" 
                                                   placeholder="Ingrese el nombre de la categoría" 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="auth-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-floppy-o"></i> Guardar
                                        </button>
                                        <a href="categorias" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Volver
                                        </a>
                                    </div>

                                    <?php
                                    $crearCategoria = new ControladorCategorias();
                                    $crearCategoria->ctrCrearCategoria();
                                    ?>
                                </form>
                            </div>

                            <!-- Columna Derecha - Imagen -->
                            <div class="auth-photo-section">
                                <div class="photo-upload-container">
                                    <div class="preview-container">
                                        <img src="vistas/img/plantilla/cas.webp" class="preview-image" alt="Categoría">
                                    </div>
                                    <div class="category-info">
                                        <h3>Gestión de Categorías</h3>
                                        <p>Organice sus productos de manera eficiente</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
}

.content-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #03093d, #071f2f);
}

.auth-container {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 40px);
}

.auth-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
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
    background: #f8f9fa;
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
    position: relative;
    padding: 0 15px;
    margin-bottom: 25px;
    width: 100%;
}

.col-md-12 {
    flex: 0 0 100%;
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

.photo-upload-container {
    text-align: center;
}

.preview-container {
    margin-bottom: 5px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-image {
    width: 400px;
    height: 400px;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
    border-radius: 20px;
}

.preview-image:hover {
    transform: scale(1.02);
}

.category-info {
    text-align: center;
    margin-top: 30px;
}

.category-info h3 {
    color: var(--azul-color);
    font-size: 24px;
    margin-bottom: 15px;
}

.category-info p {
    color: var(--text-color);
    font-size: 16px;
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
    text-decoration: none;
    text-align: center;
}

.btn i {
    font-size: 18px;
    margin-right: 10px;
}

.btn-primary {
    background: var(--azul-color);
    color: white;
}

.btn-primary:hover {
    background: #2f7cab;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

.alert {
    position: absolute;
    transform: translateX(108%);
    width: calc(20% - 10px);
    margin-top: 5px;
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
}

.alert-danger {
    background-color: #fff3f3;
    color: var(--error-color);
    border: 1px solid #ffebeb;
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 0;
    }

    .auth-container {
        padding: 10px;
        margin: 0;
        min-height: calc(100vh - 20px);
    }

    .auth-card {
        margin: 0;
        border-radius: 10px;
        width: 100%;
    }

    .auth-content {
        flex-direction: column;
        min-height: auto;
    }

    .auth-form-section {
        padding: 20px;
    }

    .auth-photo-section {
        padding: 20px;
        background: #f8f9fa;
        border-left: none;
        border-top: 1px solid var(--border-color);
    }

    .preview-image {
        width: 200px;
        height: 200px;
        border-width: 3px;
    }

    .category-info {
        text-align: center;
        padding: 0 15px;
    }

    .category-info h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .category-info p {
        font-size: 14px;
        margin-bottom: 0;
    }

    .auth-header h2 {
        font-size: 24px;
    }

    .btn {
        padding: 15px 25px;
        font-size: 14px;
    }

    .form-group {
        padding: 0 10px;
        margin-bottom: 30px;
    }

    .alert {
        width: calc(100% - 20px);
        font-size: 13px;
    }
}

/* Ajustes específicos para pantallas muy pequeñas */
@media (max-width: 480px) {
    .auth-container {
        padding: 5px;
    }

    .auth-card {
        border-radius: 8px;
    }

    .auth-form-section,
    .auth-photo-section {
        padding: 15px;
    }

    .preview-image {
        width: 180px;
        height: 180px;
    }

    .btn {
        padding: 12px 20px;
        font-size: 13px;
    }
}
</style>
