<style>
  #modalEditarOtroIngreso textarea[name="editar_descripcion_otro_ingreso"] {
    min-height: 90px;
    resize: vertical;
  }

  #modalEditarOtroIngreso .entrada-opciones {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  #modalEditarOtroIngreso .entrada-opcion {
    flex: 1;
    min-width: 110px;
    margin: 0;
    border: 1px solid #d2d6de;
    border-radius: 4px;
    padding: 10px 8px;
    text-align: center;
    cursor: pointer;
    background: #fff;
    transition: border-color .15s, box-shadow .15s, background .15s;
    font-weight: normal;
  }

  #modalEditarOtroIngreso .entrada-opcion:hover {
    border-color: #28a745;
    background: #f8fff9;
  }

  #modalEditarOtroIngreso .entrada-opcion.active {
    border-color: #28a745;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, .18);
    background: #f3fff6;
  }

  #modalEditarOtroIngreso .entrada-opcion input[type="radio"] {
    float: left;
    margin: 2px 0 0 2px;
  }

  #modalEditarOtroIngreso .entrada-opcion .entrada-icono {
    display: block;
    font-size: 28px;
    line-height: 1.2;
    margin: 4px 0 6px;
    color: #28a745;
  }

  #modalEditarOtroIngreso .entrada-opcion[data-tipo="MIXTO"] .entrada-icono,
  #modalEditarOtroIngreso .entrada-opcion[data-tipo="MIXTO"] .entrada-texto {
    color: #17a2b8;
  }

  #modalEditarOtroIngreso .entrada-opcion .entrada-texto {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #28a745;
    letter-spacing: .3px;
  }

  #modalEditarOtroIngreso .grupo-monto-mixto-editar {
    display: none;
  }
</style>
<div id="modalEditarOtroIngreso" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" id="formEditarOtroIngreso">
        <input type="hidden" name="id_otro_ingreso" id="id_otro_ingreso">
        <div class="modal-header" style="background:#6c757d; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar otro ingreso</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">OBSERVACIÓN</span>
                <textarea class="form-control" name="editar_descripcion_otro_ingreso" id="editar_descripcion_otro_ingreso" required></textarea>
              </div>
            </div>

            <div class="form-group">
              <label style="display:block; margin-bottom:8px;">ENTRADA:</label>
              <div class="entrada-opciones">
                <label class="entrada-opcion" data-tipo="QR">
                  <input type="radio" name="editar_tipo_entrada_otro_ingreso" value="QR">
                  <span class="entrada-icono"><i class="fa fa-qrcode"></i></span>
                  <span class="entrada-texto">QR</span>
                </label>
                <label class="entrada-opcion active" data-tipo="EFECTIVO">
                  <input type="radio" name="editar_tipo_entrada_otro_ingreso" value="EFECTIVO" checked>
                  <span class="entrada-icono"><i class="fa fa-money"></i></span>
                  <span class="entrada-texto">EFECTIVO</span>
                </label>
                <label class="entrada-opcion" data-tipo="MIXTO">
                  <input type="radio" name="editar_tipo_entrada_otro_ingreso" value="MIXTO">
                  <span class="entrada-icono"><i class="fa fa-exchange"></i></span>
                  <span class="entrada-texto">MIXTO</span>
                </label>
              </div>
            </div>

            <div class="form-group grupo-monto-simple-editar">
              <div class="input-group">
                <span class="input-group-addon">MONTO BS.</span>
                <input type="number" class="form-control" name="editar_monto_otro_ingreso" id="editar_monto_otro_ingreso" min="0.01" step="0.01" required>
              </div>
            </div>

            <div class="grupo-monto-mixto-editar">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">EFECTIVO BS.</span>
                  <input type="number" class="form-control" name="editar_monto_efectivo_otro_ingreso" id="editar_monto_efectivo_otro_ingreso" min="0" step="0.01">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">QR BS.</span>
                  <input type="number" class="form-control" name="editar_monto_qr_otro_ingreso" id="editar_monto_qr_otro_ingreso" min="0" step="0.01">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-" id="btnGuardarEditarOtroIngreso" style="background:#6c757d; color:white">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
