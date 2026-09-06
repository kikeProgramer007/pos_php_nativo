<?php
if (!Permisos::tiene("promociones.crear")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Registrar promoción</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="promociones">Ofertas y Promociones</a></li>
      <li class="active">Registrar</li>
    </ol>
  </section>

  <section class="content">
    <div class="box box-primary">
      <form method="post">
        <div class="box-body">
          <p class="help-block">
            Complete la información general. Luego podrá agregar intervalos y productos vinculados.
          </p>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nombre de la promoción *</label>
                <input type="text" class="form-control" name="nombrePromocion" required maxlength="150">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Prioridad *</label>
                <input type="number" class="form-control" name="prioridadPromocion" value="1" min="1" required>
                <small class="help-block">Mayor = más prioridad</small>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Estado</label>
                <select class="form-control" name="estadoPromocion">
                  <option value="0">Deshabilitada</option>
                  <option value="1">Habilitada</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Modo cantidad</label>
                <select class="form-control" name="modoCantidadPromocion" id="modoCantidadPromocionAlta">
                  <option value="individual">Por rango</option>
                  <option value="multiplo">Por múltiplo</option>
                </select>
              </div>
            </div>
          </div>
          <div id="ayudaModoRangoAlta" class="alert alert-info" style="margin-bottom:15px; padding:10px 12px;">
            <strong>Por rango:</strong> luego configurará mínimos y máximos (ej. de 3 a 6 und.).
          </div>
          <div id="ayudaModoMultiploAlta" class="alert alert-warning" style="margin-bottom:15px; padding:10px 12px; display:none;">
            <strong>Por múltiplo:</strong> luego configurará el múltiplo N (cada 5, 10, 50…). El sobrante no tiene descuento.
          </div>

          <div class="form-group">
            <label>Descripción (opcional)</label>
            <textarea class="form-control" name="descripcionPromocion" rows="2"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Fecha y hora de inicio *</label>
                <input type="datetime-local" class="form-control" name="fechaInicioPromocion" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Fecha y hora de finalización *</label>
                <input type="datetime-local" class="form-control" name="fechaFinPromocion" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Observación (opcional)</label>
            <textarea class="form-control" name="observacionPromocion" rows="2"></textarea>
          </div>
        </div>
        <div class="box-footer">
          <a href="promociones" class="btn btn-default">Cancelar</a>
          <button type="submit" class="btn btn-primary" name="nuevaPromocion" value="1">Guardar y continuar</button>
        </div>
        <?php ControladorPromociones::ctrCrearPromocion(); ?>
      </form>
    </div>
  </section>
</div>
