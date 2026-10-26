<?php
if (!Permisos::tiene("empresa.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

require_once "controladores/empresa.controlador.php";
require_once "modelos/empresa.modelo.php";

ControladorEmpresa::ctrGuardar();

$empresa = ModeloEmpresa::mdlActual();
$funciones = ModeloEmpresa::mdlFuncionalidades();
$logoWeb = "";
$logoDashboard = ModeloEmpresa::mdlLogoDashboard();
$notaTicket = $empresa ? trim((string) ($empresa["nota_ticket"] ?? "")) : "";
if ($notaTicket === "") {
  $notaTicket = "¡GRACIAS POR SU COMPRA!\nPEDIDOS AL 79902345";
}
if ($empresa && !empty($empresa["logo"])) {
  $logoWeb = "extensiones/tcpdf/pdf/" . $empresa["logo"];
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Empresa</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Empresa</li>
    </ol>
  </section>

  <section class="content">
    <?php if (!$empresa) { ?>
      <div class="alert alert-warning">
        Esta base todavía no tiene la empresa cargada. Ejecuta el script de este cliente y vuelve a abrir esta pantalla.
      </div>
    <?php } else { ?>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="guardarEmpresa" value="1">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tabEmpresa" data-toggle="tab">Empresa</a></li>
          <li><a href="#tabFuncionalidad" data-toggle="tab">Funcionalidad</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tabEmpresa">
            <div class="box-body">
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="nombre" maxlength="150" required value="<?php echo htmlspecialchars($empresa["nombre"], ENT_QUOTES, "UTF-8"); ?>">
              </div>
              <div class="form-group">
                <label>Dirección</label>
                <input type="text" class="form-control" name="direccion" maxlength="250" value="<?php echo htmlspecialchars($empresa["direccion"], ENT_QUOTES, "UTF-8"); ?>">
              </div>
              <div class="form-group">
                <label>Nota final del ticket</label>
                <textarea class="form-control" name="nota_ticket" maxlength="100" rows="3"><?php echo htmlspecialchars($notaTicket, ENT_QUOTES, "UTF-8"); ?></textarea>
                <p class="help-block">Máximo 100 caracteres. Sale al final del ticket, arriba de la línea de sistemas.</p>
              </div>
              <div class="form-group">
                <label>Logo de los reportes</label>
                <?php if ($logoWeb !== "" && is_file($logoWeb)) { ?>
                  <div style="margin-bottom:8px;"><img src="<?php echo htmlspecialchars($logoWeb, ENT_QUOTES, "UTF-8"); ?>" alt="Logo" style="max-height:70px;"></div>
                <?php } ?>
                <input type="file" name="logo" accept=".jpg,.jpeg,.png">
                <p class="help-block">Si no eliges un archivo, se conserva el logo actual.</p>
              </div>
              <div class="form-group">
                <label>Logo del tablero</label>
                <div style="margin-bottom:8px;"><img src="<?php echo htmlspecialchars($logoDashboard, ENT_QUOTES, "UTF-8"); ?>" alt="Logo del tablero" style="max-height:42px;"></div>
                <input type="file" name="logo_dashboard" accept=".jpg,.jpeg,.png,.webp">
                <p class="help-block">Es el logo pequeño de la esquina del sistema. Si no eliges un archivo, se conserva el actual.</p>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tabFuncionalidad">
            <div class="box-body">
              <?php if (!$funciones) { ?>
                <p>No hay funcionalidades cargadas.</p>
              <?php } ?>
              <?php foreach ($funciones as $funcion) { ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="funcionalidad[]" value="<?php echo intval($funcion["id"]); ?>" <?php echo intval($funcion["activo"]) === 1 ? "checked" : ""; ?>>
                    <strong><?php echo htmlspecialchars($funcion["nombre"], ENT_QUOTES, "UTF-8"); ?></strong>
                    <?php if (!empty($funcion["descripcion"])) { ?>
                      <div class="text-muted"><?php echo htmlspecialchars($funcion["descripcion"], ENT_QUOTES, "UTF-8"); ?></div>
                    <?php } ?>
                  </label>
                </div>
              <?php } ?>
              <p class="help-block">Mientras esté marcada, aplica en cada venta. Si la desmarcas, vuelve el comportamiento anterior.</p>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
    <?php } ?>
  </section>
</div>
