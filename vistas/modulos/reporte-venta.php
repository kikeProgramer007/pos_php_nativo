<?php
date_default_timezone_set('America/La_Paz');
$fechaActual = date('Y-m-d');
if (!class_exists("ModeloEmpresa")) {
  require_once __DIR__ . "/../../modelos/empresa.modelo.php";
}
if (!class_exists("ModeloMesas")) {
  require_once __DIR__ . "/../../modelos/mesas.modelo.php";
}
$gestionMesasReporte = ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas");
$cobrarDespuesReporte = ModeloEmpresa::mdlFuncionActiva("venta.cobrar_despues");
$mesasReporte = array();
$mesasReporteSelect2 = array();
if ($gestionMesasReporte) {
  $mesasReporte = class_exists("ControladorMesas")
    ? ControladorMesas::ctrMostrarMesas(null, null)
    : ModeloMesas::mdlMostrarMesas();
  if (!is_array($mesasReporte)) {
    $mesasReporte = array();
  }
  foreach ($mesasReporte as $mesaFiltro) {
    $num = !empty($mesaFiltro["numero"]) ? intval($mesaFiltro["numero"]) : 0;
    $label = $num > 0
      ? ("Mesa " . $num)
      : (string) ($mesaFiltro["nombre"] ?? ("Mesa " . intval($mesaFiltro["id"] ?? 0)));
    $mesasReporteSelect2[] = array(
      "id" => intval($mesaFiltro["id"] ?? 0),
      "text" => $label
    );
  }
}
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
  .rv-cliente-wrap{position:relative}
  .rv-cliente-btn{
    display:flex;align-items:center;justify-content:space-between;gap:8px;
    width:100%;min-height:34px;padding:6px 10px;text-align:left;
    background:#fff;border:1px solid #d2d6de;border-radius:4px;cursor:pointer;
    font-size:14px;color:#555;line-height:1.4;
  }
  .rv-cliente-btn:hover,.rv-cliente-btn:focus{border-color:#3c8dbc;outline:none}
  .rv-cliente-btn .rv-cliente-label{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .rv-cliente-btn .rv-cliente-caret{color:#999;font-size:12px}
  .rv-cliente-btn .rv-cliente-clear-sel{
    display:none;border:0;background:transparent;color:#999;padding:0 4px;cursor:pointer;line-height:1;font-size:14px;
  }
  .rv-cliente-btn.has-value .rv-cliente-clear-sel{display:inline-flex;align-items:center}
  .rv-cliente-btn.has-value .rv-cliente-caret{display:none}
  .rv-cliente-panel{
    display:none;position:absolute;left:0;right:0;top:100%;margin-top:2px;z-index:10050;
    background:#fff;border:1px solid #aaa;border-radius:4px;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
  }
  .rv-cliente-panel.open{display:block}
  .rv-cliente-search{padding:8px;border-bottom:1px solid #eee;display:flex;align-items:center;gap:6px}
  .rv-cliente-search input{
    flex:1;width:100%;height:32px;padding:4px 8px;border:1px solid #aaa;border-radius:3px;
    font-size:13px;box-sizing:border-box;
  }
  .rv-cliente-clear-term{
    display:none;flex-shrink:0;width:28px;height:28px;border:1px solid #ccc;border-radius:3px;
    background:#f7f7f7;color:#666;cursor:pointer;font-size:14px;line-height:1;padding:0;
  }
  .rv-cliente-clear-term.visible{display:inline-flex;align-items:center;justify-content:center}
  .rv-cliente-clear-term:hover{background:#eee;color:#333}
  .rv-cliente-list{max-height:220px;overflow-y:auto;padding:4px 0}
  .rv-cliente-item{
    display:block;width:100%;padding:7px 12px;border:0;background:transparent;
    text-align:left;font-size:13px;color:#333;cursor:pointer;
  }
  .rv-cliente-item:hover,.rv-cliente-item.active{background:#5897fb;color:#fff}
  .rv-cliente-status{padding:8px 12px;font-size:12px;color:#888}
  /* Restaurar Select2 single (filtros) — no tocar con estilos del multiselect Mesas */
  .rv-page .select2-container{width:100% !important}
  .rv-page .select2-container--default .select2-selection--single{
    height:34px !important;min-height:34px !important;border:1px solid #d2d6de !important;border-radius:4px !important;padding:0 12px !important;
  }
  .rv-page .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height:32px !important;height:32px !important;margin-top:0 !important;padding:0 !important;color:#555;
  }
  .rv-page .select2-container--default .select2-selection--single .select2-selection__arrow{
    height:32px !important;top:1px !important;right:6px !important;
  }
  /* Multiselect Mesas = mismo look Preferencias (Select2 classic) */
  .rv-mesas-wrap .select2-container{width:100% !important}
  .rv-mesas-wrap .select2-container--classic .select2-selection--multiple{
    min-height:38px !important;border:1px solid #3c8dbc !important;border-radius:4px !important;background:#fff;
    padding:3px 4px !important;overflow:visible !important;
  }
  .rv-mesas-wrap .select2-container--classic.select2-container--focus .select2-selection--multiple{
    border-color:#3c8dbc !important;outline:0;
  }
  .rv-mesas-wrap .select2-selection--multiple .select2-selection__rendered{
    display:block !important;padding:0 2px !important;line-height:28px !important;overflow:visible !important;
  }
  .rv-mesas-wrap .select2-selection--multiple .select2-selection__placeholder{
    line-height:28px !important;margin:0 !important;color:#999;
  }
  .rv-mesas-wrap .select2-selection--multiple .select2-search--inline{
    float:none !important;display:inline-block !important;vertical-align:middle !important;
    line-height:28px !important;height:28px !important;
  }
  .rv-mesas-wrap .select2-selection--multiple .select2-search--inline .select2-search__field{
    margin:0 !important;padding:0 4px !important;height:28px !important;min-height:28px !important;
    line-height:28px !important;font-size:14px !important;border:0 !important;box-sizing:border-box !important;
  }
  .rv-mesas-wrap .select2-container--classic .select2-selection--multiple .select2-selection__choice{
    background-color:#eef1f5 !important;border:1px solid #c5c9d0 !important;color:#333 !important;border-radius:3px;
    padding:2px 6px;margin:3px 0 3px 4px;line-height:1.3;
  }
  .rv-mesas-wrap .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove{
    color:#777 !important;margin-right:4px;border:0;background:transparent;float:none;
  }
  .select2-container--classic .rv-mesas-dd .select2-results__option[aria-selected=true],
  .select2-container--classic.select2-container--open .select2-results__option[aria-selected=true]{
    background-color:#0056b3 !important;color:#fff !important;
  }
  .rv-mesas-wrap .select2-dropdown,
  .select2-container--classic .rv-mesas-dd{z-index:10050 !important}
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
            <?php if ($cobrarDespuesReporte) { ?>
            <option value="1">Pendiente</option>
            <?php } ?>
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
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Forma de atención</label>
          <select id="forma_atencion" name="forma_atencion" class="form-control select2">
            <option value="0" selected>Todos</option>
            <option value="1">Mesa</option>
            <option value="2">Llevar</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          <label class="small">Cliente</label>
          <div class="rv-cliente-wrap" id="rvClienteWrap">
            <input type="hidden" id="id_cliente" name="id_cliente" value="0">
            <button type="button" class="rv-cliente-btn" id="rvClienteBtn" aria-haspopup="listbox" aria-expanded="false">
              <span class="rv-cliente-label" id="rvClienteLabel">Todas</span>
              <span class="rv-cliente-clear-sel" id="rvClienteClearSel" title="Limpiar" aria-label="Limpiar">&times;</span>
              <i class="fa fa-caret-down rv-cliente-caret"></i>
            </button>
            <div class="rv-cliente-panel" id="rvClientePanel" role="listbox">
              <div class="rv-cliente-search">
                <input type="text" id="rvClienteSearch" placeholder="Buscar cliente..." autocomplete="off">
                <button type="button" class="rv-cliente-clear-term" id="rvClienteClearTerm" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">&times;</button>
              </div>
              <div class="rv-cliente-list" id="rvClienteList"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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

      <?php if ($gestionMesasReporte) { ?>
      <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12 mb-3">
          <label class="small">Mesas</label>
          <div class="rv-mesas-wrap">
            <select id="ids_mesas" name="ids_mesas[]" class="form-control select2-nota" multiple="multiple" data-placeholder="Todas las mesas">
              <?php
              if ($mesasReporteSelect2) {
                foreach ($mesasReporteSelect2 as $optMesa) {
                  if (empty($optMesa["id"])) {
                    continue;
                  }
                  echo '<option value="' . intval($optMesa["id"]) . '">' .
                    htmlspecialchars($optMesa["text"], ENT_QUOTES, "UTF-8") .
                    '</option>';
                }
              }
              ?>
            </select>
          </div>
          <?php if (!$mesasReporteSelect2) { ?>
          <small class="text-danger">No hay mesas registradas. Créalas en el módulo Mesas.</small>
          <?php } else { ?>
          <small class="text-muted">Vacío = todas las mesas</small>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <div class="rv-action-row">
        <div>
          <div class="form-check rv-check-wrap">
            <input class="form-check-input" type="checkbox" value="" id="registros_eliminados">
            <label class="form-check-label" for="registros_eliminados">Registros eliminados</label>
          </div>
        </div>
        <div>
          <button class="rv-btn-orange" type="button" onclick="generatePDF()" style="margin-right:8px;"><i class="fa fa-file-pdf"></i> PDF</button>
          <button class="btn btn-success" type="button" onclick="generateExcelVentas()" style="padding:10px 16px;border-radius:8px;"><i class="fa fa-file-excel-o"></i> Excel</button>
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

  <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $basePath = rtrim($basePath, '/');
    if ($basePath === '.' || $basePath === '\\') {
      $basePath = '';
    }
    $urlAjaxClientes = ($basePath === '' ? '' : $basePath) . '/ajax/clientes.ajax.php';
  ?>
  var urlAjaxClientes = <?php echo json_encode($urlAjaxClientes, JSON_UNESCAPED_SLASHES); ?>;

  // Solo Mesas (select2-nota). Los filtros .select2 los inicia plantilla.js — no reiniciarlos.
  var mesasReporteData = <?php echo json_encode($mesasReporteSelect2, JSON_UNESCAPED_UNICODE); ?>;
  (function initSelect2MesasReporte() {
    function boot() {
      if (typeof jQuery === "undefined" || !jQuery.fn || !jQuery.fn.select2) {
        setTimeout(boot, 50);
        return;
      }
      var $ = jQuery;
      var $mesas = $("#ids_mesas");
      if (!$mesas.length) return;

      if ($mesas.find("option").length === 0 && Array.isArray(mesasReporteData)) {
        mesasReporteData.forEach(function (m) {
          if (!m || !m.id) return;
          $mesas.append($("<option></option>").val(m.id).text(m.text || ("Mesa " + m.id)));
        });
      }
      if ($mesas.hasClass("select2-hidden-accessible")) {
        $mesas.select2("destroy");
      }
      $mesas.select2({
        theme: "classic",
        multiple: true,
        width: "100%",
        placeholder: "Todas las mesas",
        allowClear: true,
        closeOnSelect: false,
        dropdownParent: $mesas.closest(".rv-mesas-wrap"),
        dropdownCssClass: "rv-mesas-dd",
        language: {
          noResults: function () {
            return "No hay resultados";
          }
        }
      });
    }
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", function () { setTimeout(boot, 80); });
    } else {
      setTimeout(boot, 80);
    }
  })();

  // Cliente: dropdown propio (buscador + scroll infinito), sin Select2 AJAX
  (function () {
    var urlsAjaxClientes = [
      urlAjaxClientes,
      "ajax/clientes.ajax.php",
      "./ajax/clientes.ajax.php"
    ].filter(function (u, i, arr) {
      return u && arr.indexOf(u) === i;
    });

    var wrap = document.getElementById('rvClienteWrap');
    var btn = document.getElementById('rvClienteBtn');
    var panel = document.getElementById('rvClientePanel');
    var list = document.getElementById('rvClienteList');
    var search = document.getElementById('rvClienteSearch');
    var clearTermBtn = document.getElementById('rvClienteClearTerm');
    var clearSelBtn = document.getElementById('rvClienteClearSel');
    var label = document.getElementById('rvClienteLabel');
    var hidden = document.getElementById('id_cliente');
    if (!wrap || !btn || !panel || !list || !search || !label || !hidden) return;

    var page = 1;
    var more = false;
    var loading = false;
    var term = '';
    var searchTimer = null;
    var selectedId = '0';

    function syncClearButtons() {
      if (clearTermBtn) {
        if (term) clearTermBtn.classList.add('visible');
        else clearTermBtn.classList.remove('visible');
      }
      if (String(selectedId) !== '0') btn.classList.add('has-value');
      else btn.classList.remove('has-value');
    }

    function setSelection(id, text) {
      selectedId = String(id || '0');
      hidden.value = selectedId;
      label.textContent = text || 'Todas';
      syncClearButtons();
    }

    function setStatus(msg) {
      var el = document.createElement('div');
      el.className = 'rv-cliente-status';
      el.textContent = msg;
      list.appendChild(el);
    }

    function markActive() {
      var items = list.querySelectorAll('.rv-cliente-item');
      for (var i = 0; i < items.length; i++) {
        if (String(items[i].getAttribute('data-id')) === String(selectedId)) {
          items[i].classList.add('active');
        } else {
          items[i].classList.remove('active');
        }
      }
    }

    function appendItems(rows, reset) {
      if (reset) list.innerHTML = '';
      // "Todas" solo sin filtro; al buscar se reemplaza por la X del buscador
      if (reset && !term) {
        var todas = document.createElement('button');
        todas.type = 'button';
        todas.className = 'rv-cliente-item';
        todas.setAttribute('data-id', '0');
        todas.setAttribute('data-text', 'Todas');
        todas.textContent = 'Todas';
        list.appendChild(todas);
      }
      (rows || []).forEach(function (row) {
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'rv-cliente-item';
        b.setAttribute('data-id', String(row.id));
        b.setAttribute('data-text', row.text || '');
        b.textContent = row.text || '';
        list.appendChild(b);
      });
      markActive();
    }

    function fetchPage(reset) {
      if (loading) return;
      if (!reset && !more) return;
      loading = true;
      if (reset) {
        page = 1;
        more = true;
        list.innerHTML = '';
        setStatus('Buscando...');
      } else {
        setStatus('Cargando más...');
      }

      var intento = 0;
      var reqPage = page;
      var reqTerm = term;

      function probar() {
        if (intento >= urlsAjaxClientes.length) {
          loading = false;
          if (reset) {
            list.innerHTML = '';
            appendItems([], true);
            setStatus('No se pudo cargar clientes');
          } else {
            var st = list.querySelector('.rv-cliente-status');
            if (st) st.textContent = 'Error al cargar más';
          }
          return;
        }
        var url = urlsAjaxClientes[intento++];
        var qs = 'term=' + encodeURIComponent(reqTerm) + '&page=' + encodeURIComponent(reqPage);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url + (url.indexOf('?') >= 0 ? '&' : '?') + qs, true);
        xhr.onreadystatechange = function () {
          if (xhr.readyState !== 4) return;
          if (xhr.status < 200 || xhr.status >= 300) {
            probar();
            return;
          }
          var data;
          try { data = JSON.parse(xhr.responseText); } catch (e) { data = null; }
          if (!data || !Array.isArray(data.results)) {
            probar();
            return;
          }
          loading = false;
          var statuses = list.querySelectorAll('.rv-cliente-status');
          for (var i = 0; i < statuses.length; i++) statuses[i].remove();
          appendItems(data.results, reset);
          more = !!(data.pagination && data.pagination.more);
          page = reqPage + 1;
          if (reset && data.results.length === 0) {
            setStatus('Sin resultados');
          }
        };
        xhr.send();
      }
      probar();
    }

    function clearSearchAndReload() {
      clearTimeout(searchTimer);
      term = '';
      search.value = '';
      syncClearButtons();
      fetchPage(true);
      search.focus();
    }

    function openPanel() {
      panel.classList.add('open');
      btn.setAttribute('aria-expanded', 'true');
      search.value = term;
      syncClearButtons();
      fetchPage(true);
      setTimeout(function () { search.focus(); }, 0);
    }

    function closePanel() {
      panel.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }

    function togglePanel() {
      if (panel.classList.contains('open')) closePanel();
      else openPanel();
    }

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      togglePanel();
    });

    if (clearSelBtn) {
      clearSelBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        setSelection('0', 'Todas');
        term = '';
        search.value = '';
        syncClearButtons();
        if (panel.classList.contains('open')) fetchPage(true);
      });
    }

    if (clearTermBtn) {
      clearTermBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        clearSearchAndReload();
      });
    }

    search.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        term = String(search.value || '').trim();
        syncClearButtons();
        fetchPage(true);
      }, 250);
    });

    search.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        e.preventDefault();
        if (term) {
          clearSearchAndReload();
        } else {
          closePanel();
          btn.focus();
        }
      }
    });

    list.addEventListener('scroll', function () {
      if (loading || !more) return;
      if (list.scrollTop + list.clientHeight >= list.scrollHeight - 40) {
        fetchPage(false);
      }
    });

    list.addEventListener('click', function (e) {
      var item = e.target.closest('.rv-cliente-item');
      if (!item) return;
      setSelection(item.getAttribute('data-id') || '0', item.getAttribute('data-text') || 'Todas');
      closePanel();
    });

    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) closePanel();
    });

    syncClearButtons();
  })();

  var popupWindow = null;

  function validarFiltrosVentas() {
    if (!fechaInicio.value || !fechaFin.value) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione las fechas requeridas.'});
      return null;
    }
    if (fechaFin.value > fechaActual) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual});
      return null;
    }
    if (fechaInicio.value > fechaFin.value) {
      swal({icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.'});
      return null;
    }
    var idsMesas = [];
    var selMesas = document.getElementById('ids_mesas');
    if (selMesas) {
      idsMesas = Array.from(selMesas.selectedOptions || [])
        .map(function (opt) { return opt.value; })
        .filter(function (v) { return v && v !== '0'; });
    }
    return {
      fechaInicio: fechaInicio.value,
      fechaFin: fechaFin.value,
      idMesero: document.getElementById('id_mesero').value,
      idUsuario: document.getElementById('id_usuario').value,
      idCategoria: document.getElementById('id_categoria').value,
      idCliente: document.getElementById('id_cliente').value || '0',
      tipoPago: document.getElementById('tipo_pago').value,
      estadoPago: document.getElementById('estado_pago').value,
      formaAtencion: document.getElementById('forma_atencion').value || '0',
      registroEliminados: document.getElementById('registros_eliminados').checked,
      idsMesas: idsMesas
    };
  }

  function queryReporteVentas(f) {
    var qs = "fechaInicio=" + encodeURIComponent(f.fechaInicio) +
      "&fechaFin=" + encodeURIComponent(f.fechaFin) +
      "&idMesero=" + encodeURIComponent(f.idMesero) +
      "&idUsuario=" + encodeURIComponent(f.idUsuario) +
      "&idCategoria=" + encodeURIComponent(f.idCategoria) +
      "&idCliente=" + encodeURIComponent(f.idCliente) +
      "&tipoPago=" + encodeURIComponent(f.tipoPago) +
      "&estadoPago=" + encodeURIComponent(f.estadoPago) +
      "&formaAtencion=" + encodeURIComponent(f.formaAtencion) +
      "&registroEliminados=" + encodeURIComponent(f.registroEliminados);
    (f.idsMesas || []).forEach(function (id) {
      qs += "&idsMesas[]=" + encodeURIComponent(id);
    });
    return qs;
  }

  function generatePDF() {
    const f = validarFiltrosVentas();
    if (!f) return;

    const width = 1000; const height = 700;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;
    if (popupWindow && !popupWindow.closed) popupWindow.close();

    popupWindow = window.open(
      "extensiones/tcpdf/pdf/reporte-ventas.php?" + queryReporteVentas(f),
      "_blank",
      windowFeatures
    );
  }

  function generateExcelVentas() {
    const f = validarFiltrosVentas();
    if (!f) return;
    window.location.href = "extensiones/excel/reporte-ventas.php?" + queryReporteVentas(f);
  }
</script>