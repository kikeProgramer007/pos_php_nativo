<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>



<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>

<style>
  :root{--orange:#ff7a00;--muted:#6c757d;--card-bg:#ffffff;--page-bg:#f5f6f8}
  .rv-page{background:var(--page-bg);padding:30px 20px;display:flex;justify-content:center}
  .rv-container{max-width:1100px;width:100%}
  .rv-card{background:var(--card-bg);border-radius:10px;border:1px solid #e9e9ea;box-shadow:0 1px 3px rgba(0,0,0,0.03);padding:22px;margin-bottom:20px}
  .rv-header{display:flex;align-items:center;gap:18px;flex-wrap:wrap}
  .rv-header .icon{width:64px;height:64px;border-radius:8px;background:#f3f3f4;display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:28px}
  .rv-title{font-size:20px;font-weight:700;margin:0}
  .rv-sub{color:var(--muted);margin-top:4px}
  .rv-breadcrumb{margin-left:auto;color:var(--muted);font-size:14px}
  .filters-title{font-weight:700;color:#333;display:flex;align-items:center;gap:8px;margin-bottom:12px}
  .filters-title .fa{color:var(--orange)}
  .rv-action-row{display:flex;align-items:center;justify-content:space-between;margin-top:14px}
  .rv-check-wrap{display:inline-flex;align-items:center;gap:10px;padding:8px 14px;border:1px solid rgba(255,122,0,0.18);background:#fffaf3;border-radius:8px;box-shadow:0 1px 2px rgba(0,0,0,0.03)}
  .rv-check-wrap .form-check-input{width:18px;height:18px;margin:0;border-color:#ff7a00;cursor:pointer;accent-color:#ff7a00}
  .rv-check-wrap .form-check-label{font-size:13px;color:#444;font-weight:600;cursor:pointer}
  .rv-btn-orange{background:var(--orange);border:none;color:#fff;padding:10px 16px;border-radius:8px;box-shadow:none}
  .rv-btn-orange .fa{margin-right:8px}
  .empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:36px;text-align:center}
  .empty-circle{width:110px;height:110px;border-radius:50%;background:#fff;border:2px dashed rgba(255,122,0,0.25);display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:42px;margin-bottom:12px}
  label.small{font-size:13px;color:#555}
  @media(max-width:991px){.rv-breadcrumb{margin-left:0;width:100%;text-align:right}} 
  @media(max-width:767px){.rv-header{justify-content:center}.rv-breadcrumb{text-align:center;margin-top:6px}}
</style>

<div class="rv-page">
  <div class="rv-container">

    <div class="rv-card">
      <div class="rv-header">
       <div class="icon"><i class="fa fa-money"></i></div>
        <div style="flex:1;min-width:220px">
          <h1 class="rv-title">REPORTE DE VENTAS ENTRE FECHAS</h1>
          <div class="rv-sub">Consulta y exporta las ventas realizadas en un período específico</div>
        </div>
        <div class="rv-breadcrumb">Inicio &gt; Administrar Ventas &gt; Reporte de Ventas</div>
      </div>
    </div>

    <div class="rv-card">
      <div class="filters-title"><i class="fa fa-filter"></i> FILTROS DE BÚSQUEDA</div>

      <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION["id"]; ?>">

      <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Fecha de inicio</label>
          <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Fecha de fin</label>
          <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Estado de pago</label>
          <select id="estado_pago" name="estado_pago" class="form-control select2">
            <option value="0" selected>Todos</option>
            <option value="1">Pendiente</option>
            <option value="2">Pagado</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Tipo de pago</label>
          <select id="tipo_pago" name="tipo_pago" class="form-control select2">
            <option value="0">Todos</option>
            <option value="Efectivo">Efectivo</option>
            <option value="QR">QR</option>
            <option value="Qr y Efectivo(Mixto)">Qr y Efectivo (Mixto)</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 col-md-6 mb-3">
          <label class="small">Categoría</label>
          <select id="id_categoria" name="id_categoria" class="form-control select2">
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
        <div class="col-lg-4 col-md-6 mb-3">
          <label class="small">Cliente</label>
          <select id="id_cliente" name="id_cliente" class="form-control select2">
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
        <div class="col-lg-4 col-md-12 mb-3">
          <label class="small">Mesero</label>
          <select id="id_mesero" name="id_mesero" class="form-control select2">
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

      <div class="rv-action-row">
        <div>
          <div class="form-check rv-check-wrap">
            <input class="form-check-input" type="checkbox" value="" id="registros_eliminados">
            <label class="form-check-label" for="registros_eliminados">Registros eliminados</label>
          </div>
        </div>
        <div>
          <button class="rv-btn-orange" type="button" onclick="generatePDF()"><i class="fa fa-file-pdf"></i> Generar PDF</button>
        </div>
      </div>
<img src="vistas/img/plantilla/1.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
    </div>

    
  </div>
</div>

<script>
  const fechaActual = "<?php echo $fechaActual; ?>";
  const fechaInicio = document.getElementById('fecha_inicio');
  const fechaFin = document.getElementById('fecha_fin');
  if(fechaInicio) fechaInicio.setAttribute('max', fechaActual);
  if(fechaFin) fechaFin.setAttribute('max', fechaActual);

  // Inicializar Select2 si está cargado
  if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
    $(document).ready(function(){
      $('.select2').select2({width: '100%'});
    });
  }

  var popupWindow = null;

  function generatePDF() {
    const idMesero = document.getElementById('id_mesero').value;
    const idUsuario = document.getElementById('id_usuario').value;
    const idCategoria = document.getElementById('id_categoria').value;
    const idCliente = document.getElementById('id_cliente').value;
    const tipoPago = document.getElementById('tipo_pago').value;
    const estadoPago = document.getElementById('estado_pago').value;
    const registroEliminados = document.getElementById('registros_eliminados').checked;

    if (!fechaInicio.value || !fechaFin.value) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione las fechas requeridas.'});
      return;
    }
    if (fechaFin.value > fechaActual) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual});
      return;
    }
    if (fechaInicio.value > fechaFin.value) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.'});
      return;
    }

    const width = 1000; const height = 700;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;
    if (popupWindow && !popupWindow.closed) popupWindow.close();

    const url = "extensiones/tcpdf/pdf/reporte-ventas.php?" +
      "fechaInicio=" + encodeURIComponent(fechaInicio.value) +
      "&fechaFin=" + encodeURIComponent(fechaFin.value) +
      "&idMesero=" + encodeURIComponent(idMesero) +
      "&idUsuario=" + encodeURIComponent(idUsuario) +
      "&idCategoria=" + encodeURIComponent(idCategoria) +
      "&idCliente=" + encodeURIComponent(idCliente) +
      "&tipoPago=" + encodeURIComponent(tipoPago) +
      "&estadoPago=" + encodeURIComponent(estadoPago) +
      "&registroEliminados=" + encodeURIComponent(registroEliminados);

    popupWindow = window.open(url, "_blank", windowFeatures);
  }
</script>