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
                                <h2><i class="fa fa-shopping-cart"></i> REGISTRAR PRODUCTO</h2>

                                </div>
                                
                                <form role="form" method="post" enctype="multipart/form-data" class="auth-form" id="formularioProducto">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nuevaCategoria">
                                                <i class="fa fa-th"></i> Categoría
                                            </label>
                                            <select class="form-control select2" id="nuevaCategoria" name="nuevaCategoria" required>
                                                <option value="">Seleccione una categoría</option>
                                                <?php
                                                $item = null;
                                                $valor = null;
                                                $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                                                foreach ($categorias as $key => $value) {
                                                    echo '<option value="' . $value["id"] . '">' . $value["categoria"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="nuevoCodigo">
                                                <i class="fa fa-barcode"></i> Código
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nuevoCodigo" 
                                                   name="nuevoCodigo" 
                                                   placeholder="Código del producto" 
                                                   readonly 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nuevaDescripcion">
                                                <i class="fa fa-file-text"></i> Descripción
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nuevaDescripcion" 
                                                   name="nuevaDescripcion" 
                                                   placeholder="Descripción del producto" 
                                                   required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="inventariable">
                                                <i class="fa fa-warehouse"></i> Es Inventariable
                                            </label>
                                            <select class="form-control" id="inventariable" name="inventariable" required>
                                                <option value="0">No</option>
                                                <option value="1" selected>Sí</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nuevoPrecioCompra">
                                                <i class="fa fa-money"></i> Precio de Compra
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="nuevoPrecioCompra" 
                                                   name="nuevoPrecioCompra" 
                                                   min="0" 
                                                   step="any" 
                                                   placeholder="Precio de compra" 
                                                   required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="nuevoPrecioVenta">
                                                <i class="fa fa-tag"></i> Precio de Venta
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="nuevoPrecioVenta" 
                                                   name="nuevoPrecioVenta" 
                                                   min="0" 
                                                   step="any" 
                                                   placeholder="Precio de venta" 
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>
                                                <i class="fa fa-percent"></i> Porcentaje (Opcional)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control nuevoPorcentaje" 
                                                       min="0" 
                                                       value="0" 
                                                       required>
                                                <div class="porcentaje-checkbox">
                                                    <input type="checkbox" class="minimal porcentaje" id="checkPorcentaje">
                                                    <label for="checkPorcentaje">Utilizar porcentaje</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="nuevoStock">
                                                <i class="fa fa-cubes"></i> Stock
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="nuevoStock" 
                                                   name="nuevoStock" 
                                                   min="0" 
                                                   value="0" 
                                                   readonly 
                                                   required>
                                        </div>
                                    </div>

                                    <input type="file" class="nuevaImagen" name="nuevaImagen" id="nuevaImagen" accept="image/*" style="display: none;">
                                    
                                    <div class="auth-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>
                                        <a href="productos" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Volver
                                        </a>
                                    </div>

                                    <?php
                                    $crearProducto = new ControladorProductos();
                                    $crearProducto->ctrCrearProducto();
                                    ?>
                                </form>
                            </div>

                            <!-- Columna Derecha - Foto -->
                            <div class="auth-photo-section">
                                <div class="photo-upload-container">
                                    <div class="preview-container">
                                        <img src="vistas/img/productos/default/d.png" class="preview-image" alt="Vista previa">
                                    </div>
                                    <div class="file-upload">
                                        <label for="nuevaImagen" class="file-label">
                                            <i class="fas fa-cloud-upload-alt"></i> Elegir foto
                                        </label>
                                    </div>
                                    <small class="file-info">Máximo 2MB (JPG o PNG)</small>
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
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(90vh - 40px);
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
    padding: 25px;
    background: white;
}

.auth-photo-section {
    flex: 1;
    background: rgb(139, 0, 0);


    padding: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 2px solid var(--border-color);
}

.photo-upload-container {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.preview-container {
    margin-bottom: 20px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-image {
    width: 250px;
    height: 250px;
    border: none; /* Quita el borde blanco */
 
    
   
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
}

.file-upload {
    margin-bottom: 10px;
    width: 100%;
    display: flex;
    justify-content: center;
}

.file-label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 15px 30px;
    background: var(--azul-color);
    color: white;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 25px;
    min-width: 200px;
}

.file-label i {
    margin-right: 8px;
}

.file-label:hover {
    background: var(--success-color);
    transform: translateY(-2px);
}

.file-info {
    display: block;
    color: white;
    font-size: 14px;
    margin-top: 12px;
    text-align: center;
    width: 100%;
}

.form-row {
    display: flex;
    margin: 0 -15px;
    margin-bottom: 15px;
}

.form-group {
    position: relative;
    padding: 0 15px;
    margin-bottom: 15px;
    width: 100%;
}

.col-md-6 {
    flex: 0 0 50%;
}

.form-control {
    width: 100%;
    height: 45px;
    padding: 8px 15px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--azul-color);
    box-shadow: 0 0 0 3px rgba(60, 141, 188, 0.25);
    outline: none;
}

select.form-control {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23000000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
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
    text-align: center;
    text-decoration: none;
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

.input-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.porcentaje-checkbox {
    display: flex;
    align-items: center;
    margin-top: 0;
    white-space: nowrap;
}

.porcentaje-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}

.porcentaje-checkbox label {
    margin: 0;
    font-size: 14px;
    color: var(--text-color);
}

.nuevoPorcentaje {
    width: 120px !important;
    flex-shrink: 0;
}

.auth-header {
    margin-bottom: 25px;
}

.auth-header h2 {
    color: var(--azul-color);
    font-size: 32px;
    font-weight: 600;
}

.auth-header h2 i {
    font-size: 28px;
    margin-right: 10px;
    color: var(--azul-color);
}

@media (max-width: 768px) {
    .auth-content {
        flex-direction: column;
    }

    .auth-form-section,
    .auth-photo-section {
        flex: 1;
        width: 100%;
    }

    .form-row {
        flex-direction: column;
    }

    .col-md-6 {
        flex: 0 0 100%;
    }

    .btn {
        padding: 15px 25px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.nuevaImagen');
    const previewImage = document.querySelector('.preview-image');
    const fileLabel = document.querySelector('.file-label');
    const formulario = document.getElementById('formularioProducto');

    // Manejo de la carga de imagen
    fileInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            if(file.type.match('image.*')) {
                if(file.size <= 2 * 1024 * 1024) {
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

    // Manejo del inventariable
    const inventariableSelect = document.getElementById('inventariable');
    const stockInput = document.getElementById('nuevoStock');

    inventariableSelect.addEventListener('change', function() {
        if (this.value === "0") {
            stockInput.removeAttribute('readonly');
        } else {
            stockInput.setAttribute('readonly', true);
            stockInput.value = "0";
        }
    });

    // Manejo del porcentaje
    const checkPorcentaje = document.getElementById('checkPorcentaje');
    const precioCompra = document.getElementById('nuevoPrecioCompra');
    const precioVenta = document.getElementById('nuevoPrecioVenta');
    const porcentajeInput = document.querySelector('.nuevoPorcentaje');

    function calcularPrecioVenta() {
        if(checkPorcentaje.checked) {
            let porcentaje = parseFloat(porcentajeInput.value);
            let precioCompraValor = parseFloat(precioCompra.value);
            if(!isNaN(precioCompraValor) && !isNaN(porcentaje)) {
                let nuevoPrecioVenta = precioCompraValor + (precioCompraValor * porcentaje / 100);
                precioVenta.value = nuevoPrecioVenta.toFixed(2);
            }
        }
    }

    function actualizarReadonlyPrecioVenta() {
        if (checkPorcentaje.checked) {
            precioVenta.setAttribute('readonly', true);
        } else {
            precioVenta.removeAttribute('readonly');
        }
    }

    checkPorcentaje.addEventListener('change', function() {
        actualizarReadonlyPrecioVenta();
        calcularPrecioVenta();
    });
    precioCompra.addEventListener('input', calcularPrecioVenta);
    porcentajeInput.addEventListener('input', calcularPrecioVenta);

    // Llama una vez al cargar la página para el estado inicial
    actualizarReadonlyPrecioVenta();
});
</script>