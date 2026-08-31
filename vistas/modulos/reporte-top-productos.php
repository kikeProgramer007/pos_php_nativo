
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
  .rv-action-row{display:flex;align-items:center;justify-content:flex-end;margin-top:14px}
  .rv-btn-orange{background:var(--orange);border:none;color:#fff;padding:10px 16px;border-radius:8px;box-shadow:none}
  .rv-btn-orange .fa{margin-right:8px}
  label.small{font-size:13px;color:#555}
  @media(max-width:991px){.rv-breadcrumb{margin-left:0;width:100%;text-align:right}} 
  @media(max-width:767px){.rv-header{justify-content:center}.rv-breadcrumb{text-align:center;margin-top:6px}}
</style>

<div class="rv-page">
  <div class="rv-container">

    <div class="rv-card">
      <div class="rv-header">
        <div class="icon"><i class="fa fa-star"></i></div>
        <div style="flex:1;min-width:220px">
          <h1 class="rv-title">REPORTE DE PRODUCTOS MÁS VENDIDOS</h1>
          <div class="rv-sub">Consulta y exporta los productos con mayor demanda por rango de fechas</div>
        </div>
        <div class="rv-breadcrumb">Inicio &gt; Reportes &gt; Top Productos</div>
      </div>
    </div>

    <div class="rv-card">
      <div class="filters-title"><i class="fa fa-filter"></i> FILTROS DE BÚSQUEDA</div>

      <form id="report-form">
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">

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
            <label class="small">Categoría</label>
            <select class="form-control select2" id="id_categoria" name="id_categoria">
              <option value="0">Todas las categorías</option>
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
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <label class="small">Mesero</label>
            <select class="form-control select2" id="id_mesero" name="id_mesero">
              <option value="">Todos los meseros</option>
              <?php
              $meseros = ControladorMeseros::ctrMostrarMeseros(null, null, 1);
              foreach ($meseros as $key => $value) {
                echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <label class="small">&nbsp;</label>
            <button type="button" class="btn btn-block rv-btn-orange" onclick="generatePDF()">
              <i class="fa fa-file-pdf"></i> Generar PDF
            </button>
          </div>
        </div>
      </form>
    </div>
  <img src="vistas/img/plantilla/cat.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
  </div>
</div>

<script>
  const fechaActual = "<?php echo $fechaActual; ?>";
  const fechaInicio = document.getElementById('fecha_inicio');
  const fechaFin = document.getElementById('fecha_fin');
  if (fechaInicio) fechaInicio.setAttribute('max', fechaActual);
  if (fechaFin) fechaFin.setAttribute('max', fechaActual);

  if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
    $(document).ready(function(){
      $('.select2').select2({width: '100%'});
    });
  }

  var popupWindow = null;

  function generatePDF() {
    const idUsuario = document.getElementById('id_usuario').value;
    const idCategoria = document.getElementById('id_categoria').value;
    const idMesero = document.getElementById('id_mesero').value;

    if (!fechaInicio || !fechaFin || !fechaInicio.value || !fechaFin.value) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, seleccione las fechas requeridas.'
      });
      return;
    }

    if (fechaFin.value > fechaActual) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual
      });
      return;
    }

    if (fechaInicio.value > fechaFin.value) {
      swal({
        icon: 'warning',
        title: 'Advertencia',
        text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.'
      });
      return;
    }

    const width = 800;
    const height = 600;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

    if (popupWindow && !popupWindow.closed) {
      popupWindow.close();
    }

    popupWindow = window.open(
      "extensiones/tcpdf/pdf/top-productos-mas-vendidos.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
      "&fechaFin=" + encodeURIComponent(fechaFin.value) +
      "&idUsuario=" + encodeURIComponent(idUsuario) +
      "&idCategoria=" + encodeURIComponent(idCategoria) +
      "&idMesero=" + encodeURIComponent(idMesero),
      "_blank",
      windowFeatures
    );
  }
</script>
