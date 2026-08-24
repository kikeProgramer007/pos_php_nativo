<?php

if (!Permisos::tiene("reportes.gastos")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

date_default_timezone_set('America/La_Paz');
$fechaActual = date('Y-m-d');
?>

<div class="content-wrapper text-uppercase">

  <section class="content-header">
    <h1>Reporte de Gastos</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Reporte de Gastos</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">
      <div class="box-body">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center;">Filtrar Reporte</h3>
          </div>
          <div class="panel-body">
            <input type="hidden" id="id_usuario_sesion" name="id_usuario_sesion" value="<?php echo $_SESSION["id"]; ?>">

            <div class="row">
              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label><i class="text-danger">*</i> Fecha de inicio:</label>
                  <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fechaActual; ?>" class="form-control" required>
                </div>
              </div>

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label><i class="text-danger">*</i> Fecha de fin:</label>
                  <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaActual; ?>" class="form-control" required>
                </div>
              </div>

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Tipo de gasto:</label>
                  <select class="form-control select2" id="id_tipo_gasto" name="id_tipo_gasto">
                    <option value="0">Todos</option>
                    <?php
                    $tiposGasto = ControladorTipoGasto::ctrMostrarTipoGasto(null, null);
                    if (is_array($tiposGasto)) {
                      foreach ($tiposGasto as $tipo) {
                        echo '<option value="' . $tipo["id"] . '">' . htmlspecialchars($tipo["nombre"]) . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Forma de pago:</label>
                  <select class="form-control select2" id="forma_pago" name="forma_pago">
                    <option value="0">Todas</option>
                    <option value="1">Efectivo</option>
                    <option value="2">QR</option>
                    <option value="3">Transferencia</option>
                    <option value="4">QR y Efectivo (Mixto)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Usuario:</label>
                  <select class="form-control select2" id="id_usuario_filtro" name="id_usuario_filtro">
                    <option value="0">Todos</option>
                    <?php
                    $usuarios = ControladorUsuarios::ctrMostrarUsuarios(null, null);
                    if (is_array($usuarios)) {
                      foreach ($usuarios as $usuario) {
                        echo '<option value="' . $usuario["id"] . '">' . htmlspecialchars($usuario["nombre"]) . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="text-right">
              <button type="button" class="btn btn-primary" onclick="generatePDFGastos()">
                <i class="fa fa-print"></i> Generar PDF
              </button>
            </div>
          </div>

          <img src="vistas/img/plantilla/w.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
          <br>
        </div>
      </div>
    </div>

  </section>

</div>

<script>
const fechaActual = "<?php echo $fechaActual; ?>";
const fechaInicio = document.getElementById('fecha_inicio');
const fechaFin = document.getElementById('fecha_fin');
fechaInicio.setAttribute('max', fechaActual);
fechaFin.setAttribute('max', fechaActual);

var popupWindowGastos = null;

function generatePDFGastos() {
  const idUsuarioSesion = document.getElementById('id_usuario_sesion').value;
  const idTipoGasto = document.getElementById('id_tipo_gasto').value;
  const formaPago = document.getElementById('forma_pago').value;
  const idUsuarioFiltro = document.getElementById('id_usuario_filtro').value;

  if (!fechaInicio.value || !fechaFin.value) {
    swal({
      type: 'warning',
      title: 'Advertencia',
      text: 'Por favor, seleccione las fechas requeridas.'
    });
    return;
  }

  if (fechaFin.value > fechaActual) {
    swal({
      type: 'warning',
      title: 'Advertencia',
      text: 'La fecha fin no puede ser mayor a la fecha actual: ' + fechaActual
    });
    return;
  }

  if (fechaInicio.value > fechaFin.value) {
    swal({
      type: 'warning',
      title: 'Advertencia',
      text: 'La fecha de inicio debe ser menor o igual a la fecha fin.'
    });
    return;
  }

  const width = 800;
  const height = 600;
  const left = (screen.width / 2) - (width / 2);
  const top = (screen.height / 2) - (height / 2);
  const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

  if (popupWindowGastos && !popupWindowGastos.closed) {
    popupWindowGastos.close();
  }

  popupWindowGastos = window.open(
    "extensiones/tcpdf/pdf/reporte-gastos.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
    "&fechaFin=" + encodeURIComponent(fechaFin.value) +
    "&idTipoGasto=" + encodeURIComponent(idTipoGasto) +
    "&formaPago=" + encodeURIComponent(formaPago) +
    "&idUsuarioFiltro=" + encodeURIComponent(idUsuarioFiltro) +
    "&idUsuario=" + encodeURIComponent(idUsuarioSesion),
    "_blank",
    windowFeatures
  );
}
</script>
