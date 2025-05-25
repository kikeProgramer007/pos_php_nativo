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
                                <h2><i class="fa fa-user-plus"></i> REGISTRAR GASTO</h2>

                                </div>
                                
                                <form role="form" method="post" class="auth-form" id="formularioMesero">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nuevoMesero">
                                                <i class="fa fa-user"></i> TIPO DE GASTO
                                            </label>
                                        
                                            <select class="form-control " id="id_tipo_gasto" name="id_tipo_gasto" required>
                                                <?php
                                                $item = null;
                                                $valor = null;
                                                $categorias = ControladorTipoGasto::ctrMostrarTipoGasto($item, $valor);
                                                foreach ($categorias as $key => $value) {
                                                    echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="descripcion_gasto">
                                                <i class="fa fa-map-marker"></i> DESCRIPCIÓN
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="descripcion_gasto" 
                                                   placeholder="Ingrese la dirección" 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="monto_gasto">
                                                <i class="fa fa-phone"></i> MONTO
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="monto_gasto" 
                                                   step="0.01" min="0"
                                                   placeholder="Ingrese el teléfono" 
                                                   
                                                   required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="fecha_gasto">
                                                <i class="fa fa-id-card"></i> FECHA
                                            </label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="fecha_gasto" 
                                                   value="<?php echo date('Y-m-d'); ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                        <label for="monto_gasto">
                                                <i class="fa fa-phone"></i> FORMA DE PAGO
                                            </label>
                                            <select class="form-control input-lg" id="tipo_pago_gasto" name="tipo_pago_gasto">
                                                <option value="1">Efectivo</option>
                                                <option value="2">QR</option>
                                                <option value="3">Transferencia</option>
                                                <option value="4">Qr y Efectivo(Mixto)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ENTRADA PARA LA DIRECCIÓN -->
                                    <input type="hidden" name="id_usuario_gasto" value="<?php echo $_SESSION["id"]; ?>">
                                    <input type="hidden" name="id_arqueo_caja_gasto" value="<?php echo $_SESSION["idArqueoCaja"]; ?>">
                                    
                                    <div class="auth-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>
                                        <a href="gastos" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Volver
                                        </a>
                                    </div>

                                    <?php
                                    $crearMesero = new ControladorGastos();
                                    $crearMesero->ctrCrearGasto();
                                    ?>
                                </form>
                            </div>

                            <!-- Columna Derecha - Foto -->
                            <div class="auth-photo-section">
                                <div class="photo-upload-container">
                                    <div class="preview-container">
                                        <img src="vistas/img/plantilla/egreso.png" class="preview-image" alt="Vista previa">
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
    background:var(--success-color);
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
    width: 400px;
    height: 400px;
    border: none;
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
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
