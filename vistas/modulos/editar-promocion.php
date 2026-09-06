<?php
if (!Permisos::tiene("promociones.editar")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

if (!isset($_GET["idPromocion"]) || !is_numeric($_GET["idPromocion"])) {
  echo '<script>window.location = "promociones";</script>';
  return;
}

$idPromocion = intval($_GET["idPromocion"]);
$promo = ControladorPromociones::ctrMostrarPromociones("id", $idPromocion);
if (!$promo) {
  echo '<script>window.location = "promociones";</script>';
  return;
}

$categorias = ControladorCategorias::ctrMostrarCategorias(null, null);
$fechaInicioLocal = date('Y-m-d\TH:i', strtotime($promo["fecha_inicio"]));
$fechaFinLocal = date('Y-m-d\TH:i', strtotime($promo["fecha_fin"]));
$modoMultiplo = ($promo["modo_cantidad"] ?? "") === "multiplo";
$estadoCalc = $promo["estado_calculado"] ?? "";
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Detalle de promoción</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="promociones">Ofertas y Promociones</a></li>
      <li class="active">Editar</li>
    </ol>
  </section>

  <section class="content">
    <input type="hidden" id="idPromocionActual" value="<?php echo $idPromocion; ?>">

    <!-- 1. Información general -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">1. Información general</h3>
        <span class="label label-default pull-right"><?php echo strtoupper($estadoCalc); ?></span>
      </div>
      <form method="post">
        <div class="box-body">
          <input type="hidden" name="idPromocion" value="<?php echo $idPromocion; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" name="editarNombrePromocion" value="<?php echo htmlspecialchars($promo["nombre"]); ?>" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Prioridad *</label>
                <input type="number" class="form-control" name="editarPrioridadPromocion" value="<?php echo intval($promo["prioridad"]); ?>" min="1" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Estado</label>
                <select class="form-control" name="editarEstadoPromocion">
                  <option value="1" <?php echo intval($promo["estado"])===1?'selected':''; ?>>Habilitada</option>
                  <option value="0" <?php echo intval($promo["estado"])===0?'selected':''; ?>>Deshabilitada</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Modo cantidad</label>
                <select class="form-control" name="editarModoCantidadPromocion" id="editarModoCantidadPromocion">
                  <option value="individual" <?php echo ($promo["modo_cantidad"] ?? "individual") !== "multiplo" ? "selected" : ""; ?>>Por rango</option>
                  <option value="multiplo" <?php echo ($promo["modo_cantidad"] ?? "") === "multiplo" ? "selected" : ""; ?>>Por múltiplo</option>
                </select>
              </div>
            </div>
          </div>
          <div id="ayudaModoRango" class="alert alert-info" style="margin-bottom:15px; padding:10px 12px; <?php echo $modoMultiplo ? "display:none;" : ""; ?>">
            <strong>Por rango:</strong> el descuento aplica si la cantidad de la línea está entre un mínimo y un máximo (ej. de 3 a 6 unidades).
            Guarde con el botón <em>Guardar información</em> para que el modo quede activo en ventas.
          </div>
          <div id="ayudaModoMultiplo" class="alert alert-warning" style="margin-bottom:15px; padding:10px 12px; <?php echo $modoMultiplo ? "" : "display:none;"; ?>">
            <strong>Por múltiplo:</strong> el descuento se aplica de N en N (ej. cada 5, cada 10 o cada 50).
            El valor de N se configura abajo en la sección 2, en el campo <strong>Múltiplo</strong>.
            Las unidades que sobran van a precio normal. Guarde con <em>Guardar información</em> para activarlo en ventas.
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" name="editarDescripcionPromocion" rows="2"><?php echo htmlspecialchars($promo["descripcion"]); ?></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Fecha y hora de inicio *</label>
                <input type="datetime-local" class="form-control" name="editarFechaInicioPromocion" value="<?php echo $fechaInicioLocal; ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Fecha y hora de finalización *</label>
                <input type="datetime-local" class="form-control" name="editarFechaFinPromocion" value="<?php echo $fechaFinLocal; ?>" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Observación</label>
            <textarea class="form-control" name="editarObservacionPromocion" rows="2"><?php echo htmlspecialchars($promo["observacion"]); ?></textarea>
          </div>
        </div>
        <div class="box-footer">
          <a href="promociones" class="btn btn-default">Volver al listado</a>
          <button type="submit" class="btn btn-primary" name="editarPromocion" value="1">Guardar información</button>
        </div>
        <?php ControladorPromociones::ctrEditarPromocion(); ?>
      </form>
    </div>

    <!-- 2. Intervalos / múltiplo -->
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title" id="tituloSeccionCantidad"><?php echo $modoMultiplo ? "2. Múltiplo y descuento" : "2. Intervalos de cantidad"; ?></h3>
        <button type="button" class="btn btn-warning btn-sm pull-right" id="btnNuevoIntervalo">
          <i class="fa fa-plus"></i> <span id="textoBtnIntervalo"><?php echo $modoMultiplo ? "Configurar múltiplo" : "Agregar intervalo"; ?></span>
        </button>
      </div>
      <div class="box-body">
        <p class="help-block" id="ayudaIntervalosRango"<?php echo $modoMultiplo ? ' style="display:none;"' : ''; ?>>
          Defina uno o más rangos (mínimo–máximo). Cuando la cantidad de la venta caiga en un rango, se aplica ese descuento.
          Los rangos no deben superponerse.
        </p>
        <p class="help-block" id="ayudaIntervalosMultiplo"<?php echo $modoMultiplo ? '' : ' style="display:none;"'; ?>>
          Defina el <strong>múltiplo</strong> (N): el descuento se aplica cada N unidades.
          Ejemplo con N = 5 y descuento Bs 1 por unidad: 5 und. → −Bs 5; 7 und. → −Bs 5 (sobran 2 sin desc.); 10 und. → −Bs 10.
          Suele bastar una sola fila.
        </p>
        <table class="table table-bordered table-striped" id="tablaIntervalosPromo">
          <thead>
            <tr>
              <th id="thCantMin"><?php echo $modoMultiplo ? "Múltiplo (cada N und.)" : "Cantidad mínima"; ?></th>
              <th id="thCantMax"><?php echo $modoMultiplo ? "Máximo (no aplica)" : "Cantidad máxima"; ?></th>
              <th>Tipo de descuento</th>
              <th>Valor</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- 3. Productos vinculados -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">3. Productos vinculados</h3>
        <button type="button" class="btn btn-success btn-sm pull-right" id="btnAgregarProductosPromo" data-toggle="modal" data-target="#modalAgregarProductosPromo">
          <i class="fa fa-plus"></i> Agregar productos
        </button>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped" id="tablaProductosPromo">
          <thead>
            <tr>
              <th>Imagen</th>
              <th>Código</th>
              <th>Nombre</th>
              <th>Precio actual</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- 4. Vista previa -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">4. Vista previa</h3>
      </div>
      <div class="box-body" id="vistaPreviaPromo">
        <p class="text-muted">Seleccione un producto vinculado y configure un intervalo para ver el precio estimado.</p>
      </div>
    </div>
  </section>
</div>

<!-- Modal intervalo -->
<div class="modal fade" id="modalIntervaloPromo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="tituloModalIntervalo">Intervalo de cantidad</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idIntervaloEdit">
        <div class="alert alert-warning" id="avisoModalMultiplo" style="display:none; padding:8px 12px;">
          Indique cada cuántas unidades aplica el descuento (5, 10, 50…). Ese número es el <strong>múltiplo</strong>.
        </div>
        <div class="form-group">
          <label id="labelIntCantMin">Cantidad mínima *</label>
          <input type="number" class="form-control" id="intCantMin" min="1" placeholder="">
          <small class="help-block" id="ayudaIntCantMin"></small>
        </div>
        <div class="form-group" id="grupoIntCantMax">
          <label>Cantidad máxima (opcional)</label>
          <input type="number" class="form-control" id="intCantMax" min="1" placeholder="Vacío = sin límite (solo último intervalo)">
          <small class="help-block">Deje vacío solo si es el último intervalo (“desde esta cantidad en adelante”).</small>
        </div>
        <div class="form-group">
          <label>Tipo de descuento *</label>
          <select class="form-control" id="intTipoDescuento">
            <option value="fijo">Monto fijo por unidad</option>
            <option value="porcentaje">Porcentaje</option>
          </select>
        </div>
        <div class="form-group">
          <label>Valor del descuento *</label>
          <input type="number" class="form-control" id="intValorDescuento" min="0.01" step="0.01">
          <small class="help-block text-warning" id="ayudaTipoDescuento">
            Monto fijo representa el importe que se descontará por cada unidad, no el precio final del producto.
          </small>
        </div>
        <div class="form-group">
          <label>Producto para vista previa</label>
          <select class="form-control" id="intProductoPreview"></select>
        </div>
        <div class="well" id="previewIntervaloModal">
          <strong>Vista previa</strong>
          <div id="previewIntervaloTexto">Complete los datos para calcular.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" id="btnGuardarIntervalo">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal productos -->
<div class="modal fade" id="modalAgregarProductosPromo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar productos</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" id="buscarNombreProdPromo" placeholder="Buscar por nombre">
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="buscarCodigoProdPromo" placeholder="Buscar por código / SKU">
          </div>
          <div class="col-md-4">
            <select class="form-control" id="buscarCategoriaProdPromo">
              <option value="0">Todas las categorías</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat["id"]; ?>"><?php echo htmlspecialchars($cat["categoria"]); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <br>
        <label><input type="checkbox" id="checkTodosVisiblePromo"> Seleccionar todos los resultados visibles</label>
        <div style="max-height:360px; overflow:auto; margin-top:10px;">
          <table class="table table-bordered table-hover" id="tablaBuscarProdPromo">
            <thead>
              <tr>
                <th></th>
                <th>Imagen</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnConfirmarProductosPromo">Confirmar vinculación</button>
      </div>
    </div>
  </div>
</div>
