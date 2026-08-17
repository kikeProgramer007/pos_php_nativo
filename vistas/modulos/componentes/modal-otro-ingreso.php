<style>
  #modalAgregarOtroIngreso textarea[name="descripcion_otro_ingreso"] {
    min-height: 90px;
    resize: vertical;
  }
</style>
<div id="modalAgregarOtroIngreso" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" id="formAgregarOtroIngreso">
        <div class="modal-header" style="background:#6c757d; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar otro ingreso</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">OBSERVACIÓN</span>
                <textarea class="form-control" name="descripcion_otro_ingreso" id="descripcion_otro_ingreso" placeholder="Ej. Aporte adicional, reintegro, ajuste de efectivo" required></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">MONTO BS.</span>
                <input type="number" class="form-control" name="monto_otro_ingreso" id="monto_otro_ingreso" placeholder="0.00" min="0.01" step="0.01" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-" id="btnGuardarOtroIngreso" style="background:#6c757d; color:white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
