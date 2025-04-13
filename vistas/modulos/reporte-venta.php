<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>



<div class="content-wrapper text-uppercase">

  <section class="content-header">
    <h1>REPORTE DE VENTAS ENTRE FECHA</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar ventas</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">



      <div class="box-body">

        <div class="card card-secondary card-outline">
          <div class="card-body">
            <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION["id"]; ?>">
            <div class="row">
              <div class="col-12 col-sm-2">
                <div class="form-group">
                  <label><i class="text-danger">*</i> Fecha de inicio:</label>
                  <div class="input-group date">
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-01'); ?>" class="form-control" required />
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-2">
                <div class="form-group">
                  <label><i class="text-danger">*</i> Fecha de fin:</label>
                  <div class="input-group date">
                    <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-2">
                <div class="form-group">
                  <label>Categoria</label>
                  <select class="select2" class="form-control" id="id_categoria" name="id_categoria" required>
                    <option value="0">Todas</option>

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
              </div>
              <div class="col-12 col-sm-3">
                <div class="form-group">
                  <label>Cliente</label>
                  <select  class="select2"  class="form-control" id="id_cliente" name="id_cliente" required>
                    <option value="0">Todas</option>

                    <?php
                    $item = null;
                    $valor = null;
                    $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

                    foreach ($clientes as $key => $value) {
                      echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-12 col-sm-3">
                <div class="form-group">
                  <label><i class="text-danger">*</i>Mesero</label>
                  <select  class="select2"  class="form-control" id="id_mesero" name="id_mesero" required>
                    <option value="0">Todas</option>

                    <?php
                    $item = null;
                    $valor = null;
                    $meseros = ControladorMeseros::ctrMostrarMeseros($item, $valor);

                    foreach ($meseros as $key => $value) {
                      echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-sm-6  col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon">
                    <input type="checkbox" aria-label="..." id="registros_eliminados" name="registros_eliminados">
                  </span>
                  <input type="text" class="form-control" aria-label="Registros Eliminados" value="Registros Eliminados" readonly>
                </div><!-- /input-group -->
              </div>
              <div class="col-md-9 col-sm-6 col-xs-8">
              <div class="text-right">
              <button type="button" class="btn btn-sm btn-warning" onclick="generatePDF()">
                <i class="fa fa-print"></i> Generate PDF
              </button>
            </div>
              </div>
            </div>
       

            <img src="vistas/img/plantilla/rep1.jpg" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">

          </div><!--/body card-->

        </div><!--/CARD FIN-->

      </div>

    </div>

  </section>

</div>
<script>
  // Asignar la fecha actual a una variable global en JavaScript
  const fechaActual = "<?php echo $fechaActual; ?>";
  const fechaInicio = document.getElementById('fecha_inicio');
  const fechaFin = document.getElementById('fecha_fin');
  fechaInicio.setAttribute('max', fechaActual);
  fechaFin.setAttribute('max', fechaActual);

  // Variable global para mantener la referencia de la ventana emergente
  var popupWindow = null;

  function generatePDF() {

    const idMesero = document.getElementById('id_mesero').value;
    const idUsuario = document.getElementById('id_usuario').value;
    const idCategoria = document.getElementById('id_categoria').value;
    const idCliente = document.getElementById('id_cliente').value;
    const registroEliminados = document.getElementById('registros_eliminados').checked;
 
    // Validar campos

    if (!fechaInicio.value || !fechaFin.value) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, seleccione las fechas requeridas.',
      });
      return;
    }
    if (fechaFin.value > fechaActual) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual,
      });
      return;
    }

    if (fechaInicio.value > fechaFin.value) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.',
      });
      return;
    }


    // Tamaño de la ventana emergente
    const width = 800;
    const height = 600;

    // Configuración de la ventana emergente
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

    // Cierra la ventana emergente existente si está abierta
    if (popupWindow && !popupWindow.closed) {
      popupWindow.close();
    }

    // Abre la URL en una nueva ventana (popup)
    popupWindow = window.open(
      "extensiones/tcpdf/pdf/reporte-ventas.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
      "&fechaFin=" + encodeURIComponent(fechaFin.value) +
      "&idMesero=" + encodeURIComponent(idMesero) +
      "&idUsuario=" + encodeURIComponent(idUsuario) +
      "&idCategoria=" + encodeURIComponent(idCategoria) +
      "&idCliente=" + encodeURIComponent(idCliente)+
      "&registroEliminados=" + encodeURIComponent(registroEliminados),
      "_blank",
      windowFeatures
    );
  }
</script>