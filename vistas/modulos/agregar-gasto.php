<?php
if (!Permisos::tiene("gastos.crear")) {
  echo '<script>window.location = "no-autorizado";</script>';
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
                                <h2><i class="fa fa-user-plus"></i> REGISTRAR GASTO</h2>

                                </div>
                                
                                <form role="form" method="post" class="auth-form form-gasto-pago" id="formularioMesero">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nuevoMesero">
                                               TIPO DE GASTO
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
                                                DESCRIPCIÓN
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="descripcion_gasto" 
                                                   placeholder="Ingrese la descripcion" 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fecha_gasto">
                                                FECHA
                                            </label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="fecha_gasto" 
                                                   value="<?php echo date('Y-m-d'); ?>"
                                                   required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>FORMA DE PAGO</label>
                                            <div class="gasto-pago-opciones">
                                                <label class="gasto-pago-opcion" data-tipo="2">
                                                    <input type="radio" name="tipo_pago_gasto" value="2">
                                                    <span class="gasto-pago-icono"><i class="fa fa-qrcode"></i></span>
                                                    <span class="gasto-pago-texto">QR</span>
                                                </label>
                                                <label class="gasto-pago-opcion active" data-tipo="1">
                                                    <input type="radio" name="tipo_pago_gasto" value="1" checked>
                                                    <span class="gasto-pago-icono"><i class="fa fa-money"></i></span>
                                                    <span class="gasto-pago-texto">EFECTIVO</span>
                                                </label>
                                                <label class="gasto-pago-opcion" data-tipo="4">
                                                    <input type="radio" name="tipo_pago_gasto" value="4">
                                                    <span class="gasto-pago-icono"><i class="fa fa-exchange"></i></span>
                                                    <span class="gasto-pago-texto">MIXTO</span>
                                                </label>
                                                <label class="gasto-pago-opcion" data-tipo="3">
                                                    <input type="radio" name="tipo_pago_gasto" value="3">
                                                    <span class="gasto-pago-icono"><i class="fa fa-university"></i></span>
                                                    <span class="gasto-pago-texto">TRANSF.</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row grupo-monto-gasto-simple">
                                        <div class="form-group col-md-6">
                                            <label for="monto_gasto">
                                            <span style="font-weight: bold; font-size: 15px; margin-left: 8px;">Bs.</span> MONTO:
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="monto_gasto" 
                                                   id="monto_gasto"
                                                   step="0.01" min="0.01"
                                                   placeholder="Ingrese el monto" 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="grupo-monto-gasto-mixto" style="display:none;">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="monto_efectivo_gasto">EFECTIVO BS.</label>
                                                <input type="number" class="form-control" name="monto_efectivo_gasto" id="monto_efectivo_gasto" placeholder="0.00" min="0" step="0.01">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="monto_qr_gasto">QR BS.</label>
                                                <input type="number" class="form-control" name="monto_qr_gasto" id="monto_qr_gasto" placeholder="0.00" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ENTRADA PARA LA DIRECCIÓN -->
                                    <input type="hidden" name="id_usuario_gasto" value="<?php echo $_SESSION["id"]; ?>">
                                    <input type="hidden" name="id_arqueo_caja_gasto" value="<?php echo $_SESSION["idArqueoCaja"]; ?>">
                                    <input type="hidden" name="redirigir_gasto" value="gastos">
                                    
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

.gasto-pago-opciones {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.gasto-pago-opcion {
    flex: 1;
    min-width: 90px;
    margin: 0;
    border: 1px solid #d2d6de;
    border-radius: 12px;
    padding: 10px 6px;
    text-align: center;
    cursor: pointer;
    background: #fff;
    transition: border-color .15s, box-shadow .15s, background .15s;
    font-weight: normal;
}

.gasto-pago-opcion:hover {
    border-color: #28a745;
    background: #f8fff9;
}

.gasto-pago-opcion.active {
    border-color: #28a745;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, .18);
    background: #f3fff6;
}

.gasto-pago-opcion input[type="radio"] {
    float: left;
    margin: 2px 0 0 2px;
}

.gasto-pago-opcion .gasto-pago-icono {
    display: block;
    font-size: 24px;
    line-height: 1.2;
    margin: 4px 0 6px;
    color: #28a745;
}

.gasto-pago-opcion[data-tipo="4"] .gasto-pago-icono,
.gasto-pago-opcion[data-tipo="4"] .gasto-pago-texto {
    color: #17a2b8;
}

.gasto-pago-opcion[data-tipo="3"] .gasto-pago-icono,
.gasto-pago-opcion[data-tipo="3"] .gasto-pago-texto {
    color: #6c757d;
}

.gasto-pago-opcion .gasto-pago-texto {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #28a745;
    letter-spacing: .3px;
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
