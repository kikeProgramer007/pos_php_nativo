<?php
if (!Permisos::tiene("mesas.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

$gestionMesasActiva = ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas");
if (!$gestionMesasActiva) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
$mesas = ControladorMesas::ctrMostrarMesas();
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Mesas</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Mesas</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <?php if (Permisos::tiene("mesas.crear")) { ?>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMesa">
            <i class="fa fa-plus"></i> Nueva Mesa
          </button>
        <?php } ?>
      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped tablas dt-responsive" width="100%">
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>Nº</th>
              <th>Mesa</th>
              <th>Descripción</th>
              <th>Capacidad</th>
              <th>Estado</th>
              <th style="width:220px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (is_array($mesas)) {
              foreach ($mesas as $i => $mesa) {
                $nombreMesa = !empty($mesa["numero"])
                  ? ModeloMesas::mdlNombreDesdeNumero($mesa["numero"])
                  : ($mesa["nombre"] ?? "");
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . intval($mesa["numero"] ?? 0) . '</td>';
                echo '<td>' . htmlspecialchars($nombreMesa, ENT_QUOTES, "UTF-8") . '</td>';
                echo '<td>' . htmlspecialchars($mesa["descripcion"] ?? "", ENT_QUOTES, "UTF-8") . '</td>';
                echo '<td><i class="fa fa-user"></i> ' . intval($mesa["capacidad"]) . '</td>';
                echo '<td>' . ControladorMesas::etiquetaEstado($mesa["estado"]) . '</td>';
                echo '<td>
                  <div class="btn-group">';
                if (Permisos::tiene("mesas.editar")) {
                  echo '<button class="btn btn-warning btnEditarMesa" idMesa="' . intval($mesa["id"]) . '" data-toggle="modal" data-target="#modalEditarMesa"><i class="fa fa-pencil"></i></button>';
                }
                if (Permisos::tiene("mesas.eliminar")) {
                  echo '<button class="btn btn-danger btnEliminarMesa" idMesa="' . intval($mesa["id"]) . '" nombreMesa="' . htmlspecialchars($nombreMesa, ENT_QUOTES, "UTF-8") . '"><i class="fa fa-times"></i></button>';
                }
                echo '</div></td></tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!--=====================================
MODAL AGREGAR MESA
======================================-->
<div id="modalAgregarMesa" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header" style="background:#3c8dbc;color:#fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nueva mesa</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <div class="form-group">
              <label>Número de mesa</label>
              <div class="input-group">
                <span class="input-group-addon">Mesa</span>
                <input type="number" class="form-control" name="nuevoNumeroMesa" min="1" max="9999" required placeholder="Ej: 1">
              </div>
              <p class="help-block">Solo el número. Se guardará como «Mesa 1», «Mesa 2», etc.</p>
            </div>
            <div class="form-group">
              <label>Descripción <small class="text-muted">(opcional)</small></label>
              <input type="text" class="form-control" name="nuevaDescripcionMesa" maxlength="120" placeholder="Ej: Terraza, cerca de ventana">
              <p class="help-block">No se muestra en Seleccionar mesa (solo administración).</p>
            </div>
            <div class="form-group">
              <label>Capacidad</label>
              <input type="number" class="form-control" name="nuevaCapacidadMesa" min="1" max="99" value="4" required>
            </div>
            <div class="form-group">
              <label>Estado</label>
              <select class="form-control" name="nuevoEstadoMesa">
                <option value="disponible" selected>Disponible</option>
                <option value="deshabilitada">Deshabilitada</option>
              </select>
              <p class="help-block">Ocupada se asigna solo al atender una venta.</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" name="nuevaMesa" value="1">Guardar</button>
        </div>
      </form>
      <?php ControladorMesas::ctrCrearMesa(); ?>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR MESA
======================================-->
<div id="modalEditarMesa" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header" style="background:#f39c12;color:#fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar mesa</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <input type="hidden" name="idMesa" id="idMesa">
            <div class="form-group">
              <label>Número de mesa</label>
              <div class="input-group">
                <span class="input-group-addon">Mesa</span>
                <input type="number" class="form-control" name="editarNumeroMesa" id="editarNumeroMesa" min="1" max="9999" required>
              </div>
            </div>
            <div class="form-group">
              <label>Descripción <small class="text-muted">(opcional)</small></label>
              <input type="text" class="form-control" name="editarDescripcionMesa" id="editarDescripcionMesa" maxlength="120">
              <p class="help-block">No se muestra en Seleccionar mesa.</p>
            </div>
            <div class="form-group">
              <label>Capacidad</label>
              <input type="number" class="form-control" name="editarCapacidadMesa" id="editarCapacidadMesa" min="1" max="99" required>
            </div>
            <div class="form-group">
              <label>Estado</label>
              <select class="form-control" name="editarEstadoMesa" id="editarEstadoMesa">
                <option value="disponible">Disponible</option>
                <option value="ocupada" disabled>Ocupada</option>
                <option value="deshabilitada">Deshabilitada</option>
              </select>
              <p class="help-block">Ocupada solo se asigna al atender. Si ya está ocupada, use Liberar atención más adelante; aquí puede deshabilitarla.</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" name="editarMesa" value="1">Guardar cambios</button>
        </div>
      </form>
      <?php ControladorMesas::ctrEditarMesa(); ?>
    </div>
  </div>
</div>

<?php ControladorMesas::ctrEliminarMesa(); ?>
