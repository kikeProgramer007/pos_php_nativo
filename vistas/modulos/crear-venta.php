<?php

if (isset($_GET["editarCuenta"])) {
  if (!Permisos::tiene("ventas.editar")) {
    echo '<script>window.location = "no-autorizado";</script>';
    return;
  }
} elseif (!Permisos::tiene("ventas.crear")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

$modoEdicionCuenta = false;
$ventaEditar = null;
$detalleEditar = [];
$clienteEditarNombre = "";

if (isset($_GET["editarCuenta"]) && is_numeric($_GET["editarCuenta"])) {
  $ventaEditar = ControladorVentas::ctrMostrarVentas("id", $_GET["editarCuenta"]);
  $cajaCuentaAbierta = $ventaEditar
    && !empty($ventaEditar["id_arqueo_caja"])
    && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($ventaEditar["id_arqueo_caja"]);

  if ($ventaEditar && isset($ventaEditar["estado_pago"]) && $ventaEditar["estado_pago"] === "PENDIENTE" && $ventaEditar["estado"] == 1 && $cajaCuentaAbierta) {
    $modoEdicionCuenta = true;
    $detalleEditar = ControladorVentas::ctrMostrarDetalleVentas($ventaEditar["id"]);
    foreach ($detalleEditar as $key => $linea) {
      $productoLinea = ControladorProductos::ctrMostrarProductos("id", $linea["id_producto"], "id");
      $detalleEditar[$key]["stock_actual"] = $productoLinea ? $productoLinea["stock"] : 0;
      $detalleEditar[$key]["inventariable"] = $productoLinea ? $productoLinea["inventariable"] : 1;
      $detalleEditar[$key]["imagen"] = $productoLinea ? $productoLinea["imagen"] : "";
      $detalleEditar[$key]["codigo"] = $productoLinea ? $productoLinea["codigo"] : "";
      $detalleEditar[$key]["presentaciones"] = [];
      try {
        $detalleEditar[$key]["presentaciones"] = ModeloProductoPresentaciones::mdlListarPorProducto($linea["id_producto"], true);
      } catch (Exception $e) {
        $detalleEditar[$key]["presentaciones"] = [];
      }
    }
    $clienteEditar = ControladorClientes::ctrMostrarClientes("id", $ventaEditar["id_cliente"]);
    $clienteEditarNombre = $clienteEditar ? $clienteEditar["nombre"] : "";
  } else {
    echo '<script>
      swal({
        type: "warning",
        title: "No se puede editar",
        text: "Esta cuenta pertenece a una caja cerrada o ya no está pendiente.",
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      }).then(function(){ window.location = "ventas"; });
    </script>';
    return;
  }
}

$formaAtencionEditar = 1;
if ($modoEdicionCuenta) {
  switch ($ventaEditar["forma_atencion"]) {
    case "Para Llevar":
      $formaAtencionEditar = 2;
      break;
    case "Mixto":
      $formaAtencionEditar = 3;
      break;
    default:
      $formaAtencionEditar = 1;
      break;
  }
}

$cajaArqueoAbierta = !empty($_SESSION["idArqueoCaja"]) && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_SESSION["idArqueoCaja"]);

?>
<style>
  .select2-results__option[aria-selected=true] {
    background-color: #0056b3 !important;
    color: #fff !important;
  }

  .nota-dropdown {
    padding: 10px;
    position: absolute;
    right: 0;
    left: auto;
    top: 100%;
    z-index: 9999;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    display: none;
    text-transform: none;
    min-width: 280px;
  }

  .dropdown.open > .nota-dropdown,
  .lv-notas-wrap.open > .nota-dropdown,
  .nota-dropdown.show {
    display: block !important;
  }

  .nota-producto-dropdown {
    position: relative;
  }

  .select2-nota-container .select2-container {
    width: 100% !important;
    z-index: 10000;
  }

  .nota-form-container {
    padding: 10px;
    width: 100%;
  }

  .select2-dropdown {
    z-index: 10001 !important;
  }

  .btn-xs.dropdown-toggle {
    padding: 1px 5px;
  }

  /* Estilos para el catálogo de productos — compacto (50% panel) */
  .catalogo-productos,
  #catalogoProductos {
    padding: 0;
    background: transparent;
    margin-bottom: 8px;
  }

  .catalogo-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    justify-content: space-between;
    margin-bottom: 0;
    padding: 8px 10px;
    border-bottom: 1px solid #eee;
  }

  .catalogo-header h3 {
    margin: 0;
    color: #333;
    font-weight: 600;
    font-size: 15px;
  }

  #catalogoProductos {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
    margin-left: 0;
    margin-right: 0;
    align-items: stretch;
  }

  #catalogoProductos::after {
    display: none;
  }

  /* En panel 50%: 4 cards por fila en desktop amplio */
  .col-producto-catalogo {
    width: auto;
    float: none;
    padding: 0;
    box-sizing: border-box;
    min-width: 0;
  }

  @media (max-width: 1600px) {
    #catalogoProductos { grid-template-columns: repeat(4, minmax(0, 1fr)); }
  }

  @media (max-width: 1400px) {
    #catalogoProductos { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  }

  @media (max-width: 1200px) {
    #catalogoProductos { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  }

  @media (max-width: 992px) {
    #catalogoProductos { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }

  @media (max-width: 767px) {
    #catalogoProductos { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }

  .catalogo-productos .thumbnail,
  #catalogoProductos .thumbnail {
    background: #fff;
    border: 1px solid #e3e6ea;
    border-radius: 6px;
    text-align: center;
    transition: box-shadow 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
    cursor: pointer;
    position: relative;
    padding: 0;
    margin-bottom: 0;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    overflow: visible; /* permite menú ⋮; la imagen se recorta en .card-producto-img */
    height: 100%;
    display: flex;
    flex-direction: column;
    z-index: 1;
  }

  #catalogoProductos .col-producto-catalogo:hover .thumbnail,
  #catalogoProductos .col-producto-catalogo .thumbnail.open,
  #catalogoProductos .col-producto-catalogo .dropdown.open {
    z-index: 20;
  }

  .catalogo-productos .thumbnail:hover,
  #catalogoProductos .thumbnail:hover {
    transform: translateY(-3px);
    border-color: #28a745;
    box-shadow:
      0 0 0 2px rgba(40, 167, 69, 0.35),
      0 8px 18px rgba(40, 167, 69, 0.28),
      0 3px 8px rgba(0, 0, 0, 0.08);
  }

  .catalogo-productos .thumbnail:hover .dress-name,
  #catalogoProductos .thumbnail:hover .dress-name {
    color: #1e7e34;
  }

  .catalogo-productos .thumbnail:hover .new-price,
  #catalogoProductos .thumbnail:hover .new-price {
    color: #b52b27;
    text-shadow: 0 0 0.5px rgba(181, 43, 39, 0.35);
  }

  .catalogo-productos .thumbnail .dress-name,
  .catalogo-productos .thumbnail .new-price,
  #catalogoProductos .thumbnail .dress-name,
  #catalogoProductos .thumbnail .new-price {
    transition: color 0.18s ease, text-shadow 0.18s ease;
  }

  .producto-imagen {
    object-fit: cover;
    border-radius: 4px;
    margin: 0 auto;
    width: 100%;
    height: 100%;
  }

  .producto-nombre {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin: 8px 0;
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
   
    -webkit-box-orient: vertical;
  }

  .producto-stock {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 3px;
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  .producto-precio {
    font-size: 16px;
    font-weight: 600;
    color: #28a745;
    margin-bottom: 10px;
  }

  .btn-agregar {
    background-color: #28a745;
    color: #fff;
    border: 1px solid #24963e;
    padding: 2px 8px;
    border-radius: 3px;
    width: 100%;
    transition: background-color 0.2s ease, color 0.2s ease;
  }

  .btn-agregar:hover {
    background-color: #218838;
    color: #fff;
  }

  .btn-agregar.btn-agregar-en-venta,
  .btn-agregar.btn-agregar-en-venta.disabled {
    background-color: #c0c0c0;
    color: #444;
    border-color: #a8a8a8;
    cursor: not-allowed;
  }

  .btn-agregar.btn-agregar-agotado,
  .btn-agregar.btn-agregar-agotado.disabled,
  .btn-agregar.disabled {
    background-color: #ececec;
    color: #999;
    border-color: #ddd;
    cursor: not-allowed;
  }

  /* Estilos para el filtro y búsqueda */
  .catalogo-filtros {
    display: flex;
    flex: 1 1 280px;
    gap: 15px;
    margin-bottom: 0;
    min-width: 220px;
  }

  .catalogo-busqueda {
    flex: 1;
    max-width: none;
    width: 100%;
  }

  .catalogo-busqueda .input-group {
    width: 100%;
    display: flex;
    align-items: stretch;
    box-shadow: 0 1px 4px rgba(40, 167, 69, 0.15);
  }

  .catalogo-busqueda .input-group-addon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    min-width: 44px;
    height: 44px;
    padding: 0;
    background: #28a745;
    color: #fff;
    border: 2px solid #28a745;
    border-right: none;
    border-radius: 0;
    font-size: 16px;
    line-height: 1;
    box-sizing: border-box;
  }

  .catalogo-busqueda-field {
    position: relative;
    flex: 1;
    min-width: 0;
  }

  .catalogo-busqueda input,
  .catalogo-busqueda .form-control {
    display: block;
    width: 100%;
    height: 44px;
    padding: 0 40px 0 16px;
    margin: 0;
    font-size: 16px;
    font-weight: 500;
    line-height: 40px;
    border: 2px solid #28a745;
    border-left: none;
    border-radius: 0;
    background: #fff;
    box-shadow: none;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .catalogo-busqueda input:focus,
  .catalogo-busqueda .form-control:focus {
    outline: none;
    border-color: #218838;
    box-shadow: none;
  }

  .catalogo-busqueda .input-group:focus-within {
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
  }

  .catalogo-busqueda input::placeholder {
    color: #6c757d;
    font-weight: 400;
  }

  .btn-limpiar-busqueda {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    width: 28px;
    height: 28px;
    padding: 0;
    margin: 0;
    border: none;
    border-radius: 50%;
    background: transparent;
    color: #888;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 3;
  }

  .btn-limpiar-busqueda.is-visible {
    display: inline-flex;
  }

  .btn-limpiar-busqueda:hover,
  .btn-limpiar-busqueda:focus {
    color: #333;
    background: #e9ecef;
    outline: none;
  }

  /* Etiqueta compacta de promoción en línea de producto */
  .promo-aplicada-info {
    margin-top: 2px;
    line-height: 1.2;
    min-height: 0;
    text-align: right;
  }

  .promo-etiqueta {
    display: inline-block;
    max-width: 100%;
    font-size: 11px;
    font-weight: 600;
    color: #27ae60;
    cursor: help;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .promo-tooltip-detalle {
    text-align: left;
    line-height: 1.45;
  }

  .promo-etiqueta .fa {
    margin-left: 2px;
    opacity: 0.75;
    font-size: 11px;
  }

  /* ===== Tabla de líneas de venta (compacta, legible) ===== */
  .tabla-lineas-venta-wrap {
    margin: 0 0 6px;
    overflow: visible; /* no recortar dropdown de notas */
  }

  .tabla-lineas-venta {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
    margin-bottom: 0;
    table-layout: fixed;
  }

  .tabla-lineas-venta thead th {
    background: #f5f7fa;
    color: #555;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.01em;
    padding: 6px 4px;
    border-bottom: 1px solid #dde2e8;
    white-space: nowrap;
    text-align: center;
  }

  .tabla-lineas-venta thead th.th-producto {
    text-align: left;
    padding-left: 6px;
    width: 32%;
  }

  .tabla-lineas-venta thead th.th-atencion { width: 7%; }
  .tabla-lineas-venta thead th.th-cant { width: 14%; }
  .tabla-lineas-venta thead th.th-money { width: 9%; }
  .tabla-lineas-venta thead th.th-acciones { width: 8%; }

  .tabla-lineas-venta tbody.nuevoProducto {
    display: table-row-group;
  }

  .tabla-lineas-venta tr.linea-venta > td {
    padding: 6px 4px;
    vertical-align: middle;
    border-bottom: 1px solid #eceff3;
    background: #fff;
    overflow: visible;
    position: relative;
  }

  .tabla-lineas-venta tr.linea-venta:hover > td {
    background: #fafbfc;
  }

  .lv-producto {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
  }

  .lv-producto-img {
    width: 34px;
    height: 34px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e5e8ec;
    flex-shrink: 0;
    background: #f8f9fa;
  }

  .lv-producto-info {
    min-width: 0;
    flex: 1;
    position: relative;
    overflow: visible;
  }

  .lv-producto-info .lv-nombre-producto {
    font-weight: 700;
    font-size: 12px;
    line-height: 1.25;
    color: #222;
    text-transform: uppercase;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: anywhere;
    width: 100%;
    margin-bottom: 2px;
    cursor: help;
  }

  /* Input real oculto: el JS sigue leyendo .val() / idProducto */
  .lv-producto-info .nuevaDescripcionProducto {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    border: 0 !important;
    opacity: 0 !important;
    pointer-events: none !important;
  }

  .lv-producto-controles {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: nowrap;
    min-width: 0;
  }

  .lv-producto-controles .lv-presentacion {
    flex: 1 1 auto;
    min-width: 0;
    text-align: left;
  }

  .lv-producto-controles .lv-presentacion.lv-pres-solo-unidad,
  .select-presentacion-venta.lv-pres-solo-unidad {
    display: none !important;
  }

  .lv-producto-controles .select-presentacion-venta {
    width: 100%;
    max-width: 140px;
    height: 26px;
    font-size: 11px;
    padding: 1px 4px;
    margin: 0;
    display: block;
  }

  .lv-producto-controles .lv-notas-wrap {
    flex: 0 0 auto;
    margin-top: 0;
    position: relative;
  }

  .lv-codigo {
    display: none !important;
  }

  .lv-producto-meta {
    display: none !important;
  }

  .lv-notas-wrap {
    display: inline-block;
    text-transform: none;
    position: relative;
  }

  .lv-notas-wrap > .btn-abrir-notas,
  .lv-notas-wrap > .dropdown-toggle {
    padding: 2px 6px;
    line-height: 1.2;
    height: 26px;
  }

  .lv-notas-wrap.open > .btn-abrir-notas,
  .lv-notas-wrap > .btn-abrir-notas.tiene-notas {
    background: #337ab7;
    border-color: #2e6da4;
    color: #fff;
  }

  .tabla-lineas-venta tr.linea-venta > td.lv-celda-producto {
    overflow: visible;
    vertical-align: middle;
  }

  .tabla-lineas-venta tr.linea-venta.dropdown-notas-abierto {
    position: relative;
    z-index: 40;
  }

  .tabla-lineas-venta tr.linea-venta.dropdown-notas-abierto > td.lv-celda-producto {
    z-index: 41;
  }

  .lv-atencion {
    text-align: center;
  }

  .lv-forma-atencion {
    width: 56px;
    max-width: 100%;
    height: 28px;
    padding: 0 2px;
    font-size: 12px;
    display: inline-block;
    margin: 0 auto;
  }

  .lv-presentacion {
    text-align: left;
  }

  .lv-cant {
    text-align: center;
  }

  .lv-cant .cantidad-stepper {
    max-width: 96px;
    margin: 0 auto;
  }

  .tabla-lineas-venta .cantidad-stepper .btn-cantidad-ajuste {
    width: 26px;
    min-width: 26px;
    height: 26px;
    font-size: 12px;
  }

  .tabla-lineas-venta .cantidad-stepper input[type="number"] {
    height: 26px;
    font-size: 13px;
    padding: 0 2px;
  }

  .lv-cant .lbl-unidades-reales {
    font-size: 10px;
    color: #888;
    margin-top: 1px;
    line-height: 1.1;
  }

  .lv-money {
    text-align: right;
    white-space: nowrap;
    font-size: 13px;
    font-weight: 600;
    color: #333;
    padding-right: 4px !important;
  }

  .lv-subtotal .nuevoPrecioProducto {
    border: none;
    background: transparent;
    box-shadow: none;
    padding: 0;
    height: auto;
    text-align: right;
    font-weight: 600;
    font-size: 13px;
    color: #333;
    width: 100%;
  }

  .lv-desc {
    text-align: right;
    font-size: 13px;
    font-weight: 700;
    color: #27ae60;
    white-space: nowrap;
  }

  .lv-desc.es-vacio {
    color: #bbb;
    font-weight: 500;
  }

  .lv-total-linea {
    text-align: right;
    font-size: 13px;
    font-weight: 700;
    color: #111;
    white-space: nowrap;
  }

  .lv-acciones {
    text-align: center;
    white-space: nowrap;
  }

  .lv-acciones .btn {
    margin: 0 1px;
    padding: 3px 6px;
  }

  .lv-acciones .btn-duplicar-linea {
    background: #5bc0de;
    border-color: #46b8da;
    color: #fff;
  }

  .promo-aplicada-info {
    display: none !important;
  }

  @media (max-width: 1200px) {
    .lv-producto-img { width: 28px; height: 28px; }
    .tabla-lineas-venta { font-size: 12px; }
  }

  /* Resumen de totales (legible) */
  .resumen-venta-totales {
    width: 100%;
    padding: 4px 0 6px;
  }

  .resumen-fila {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 8px;
    padding: 3px 0;
    line-height: 1.3;
  }

  .resumen-label {
    flex: 1 1 auto;
    min-width: 0;
    font-size: 14px;
    color: #555;
    white-space: nowrap;
  }

  .resumen-monto {
    flex: 0 0 auto;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    text-align: right;
  }

  .resumen-fila-descuento .resumen-monto {
    color: #c0392b;
    font-weight: 700;
  }

  .resumen-fila-descuento .resumen-label .fa {
    font-size: 11px;
    margin-left: 3px;
    color: #999;
    cursor: help;
  }

  .resumen-fila-total {
    margin-top: 4px;
    padding: 7px 10px;
    border-top: none;
    background: #e8f8ef;
    border-radius: 4px;
  }

  .resumen-fila-total .resumen-label {
    font-size: 14px;
    font-weight: 700;
    color: #1e7e34;
    text-transform: uppercase;
  }

  .resumen-fila-total .resumen-monto {
    font-size: 17px;
    font-weight: 700;
    color: #1e7e34;
  }

  @media (max-width: 767px) {
    .resumen-fila {
      flex-wrap: nowrap;
    }

    .resumen-monto,
    .resumen-label {
      white-space: nowrap;
    }
  }

  /* Responsividad */
  @media (max-width: 992px) {
    .catalogo-header {
      flex-direction: column;
      align-items: stretch;
    }

    .catalogo-filtros,
    .catalogo-busqueda {
      width: 100%;
      flex: 1 1 100%;
      min-width: 0;
    }

    .catalogo-busqueda .input-group-addon,
    .catalogo-busqueda input,
    .catalogo-busqueda .form-control,
    .catalogo-busqueda-field {
      height: 48px;
    }

    .catalogo-busqueda .input-group-addon {
      width: 48px;
      min-width: 48px;
    }

    .catalogo-busqueda input,
    .catalogo-busqueda .form-control {
      font-size: 16px;
      line-height: 44px;
      padding-right: 40px;
    }
  }

  @media (max-width: 768px) {
      /*.catalogo-grid {
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 10px;
    }*/

    .producto-imagen {
      width: 80px;
      height: 80px;
    }

    .producto-nombre {
      font-size: 12px;
    }
  }

  /* Estilos para los filtros de categorías */
  .filtros-categorias {
    display: flex;
    gap: 10px;
    margin-bottom: 0;
    flex-wrap: wrap;
    flex: 1 1 auto;
    min-width: 0;
  }

  .btn-categoria {
    padding: 4px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f8f9fa;
    color: #333;
    transition: all 0.3s ease;
    text-transform: capitalize;
  }

  .btn-categoria:hover,
  .btn-categoria.active {
    background: #007bff;
    color: white;
    border-color: #0056b3;
  }

  /* Estilos para la paginación */
  .catalogo-paginacion {
    display: block;
    flex-direction: column;
    align-items: center;
    padding: 20px;
      /*border-bottom: 10px solid #dee2e6;*/
    gap: 15px;
  }

  .paginacion-controles-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    width: 100%;
  }

  .registros-por-pagina {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .paginacion-controles {
    display: flex;
    gap: 5px;
  }

  .paginacion-info {
    color: #6c757d;
    text-align: center;
    margin-top: 10px;
  }

  .paginacion-controles button {
    padding: 6px 12px;
    border: 1px solid #dee2e6;
    background: white;
    color: #007bff;
    border-radius: 4px;
  }

  .paginacion-controles button:hover:not(:disabled) {
    background: #007bff;
    color: white;
    border-color: #0056b3;
  }

  .paginacion-controles button:disabled {
    color: #6c757d;
    cursor: not-allowed;
  }

  .paginacion-paginas {
    display: flex;
    gap: 5px;
  }

  .card {
      background-color: #fff;
      border: none;
      border-radius: 10px;
      width:  150px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      margin-bottom: 20px;
  }

  .image-container {
      position: relative
  }

  .thumbnail-image {
      border-top-left-radius: 6px !important;
      border-top-right-radius: 6px !important;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      padding: 0;
      box-sizing: border-box;
  }

  .card-producto-img {
      overflow: hidden;
      background: #f0f0f0;
      position: relative;
      flex-shrink: 0;
      width: 100%;
      aspect-ratio: 1 / 1;
      border-top-left-radius: 6px;
      border-top-right-radius: 6px;
  }

  .first {
      position: absolute;
      left: 0;
      top: 0;
      right: 0;
      width: 100%;
      padding: 4px;
      z-index: 3;
      pointer-events: none;
  }

  .first .card-producto-header,
  .first .badge,
  .first .dropdown-disponibilidad,
  .first .btn-menu-disponibilidad {
      pointer-events: auto;
  }

  .card-producto-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
  }

  .card-producto-header .badge {
      font-size: 10px;
      padding: 2px 5px;
      font-weight: 600;
  }

  .dropdown-disponibilidad {
      position: relative;
      float: right;
      z-index: 4;
  }

  .btn-menu-disponibilidad {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid #ddd;
      border-radius: 3px;
      padding: 1px 5px;
      margin: 0;
      color: #444;
      font-size: 12px;
      line-height: 1;
      position: relative;
  }

  .btn-menu-disponibilidad .icon {
      margin: 0;
      vertical-align: middle;
  }

  .btn-menu-disponibilidad:hover,
  .btn-menu-disponibilidad:focus,
  .btn-menu-disponibilidad:active {
      background: #fff;
      color: #111;
  }

  .dropdown-disponibilidad .dropdown-menu {
      min-width: 220px;
      z-index: 20;
  }

  .dropdown-disponibilidad .dropdown-menu > li > a > .fa {
      margin-right: 6px;
      width: 14px;
      text-align: center;
  }

  .dropdown-disponibilidad .dropdown-menu > .disabled > a {
      pointer-events: none;
      opacity: 0.55;
  }

  .card-producto-meta {
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      justify-content: space-between;
      gap: 6px;
      width: 100%;
      margin: 0 0 4px;
      min-height: 36px;
  }

  .dress-name {
      font-size: 12px;
      font-weight: 700;
      flex: 1 1 auto;
      min-width: 0;
      max-width: calc(100% - 64px);
      height: auto;
      max-height: 48px;
      line-height: 1.25;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      word-break: break-word;
      overflow-wrap: anywhere;
      margin: 0;
      text-align: left;
      cursor: help;
  }

  .new-price {
      font-size: 14px;
      font-weight: 700;
      color: #d9534f;
      flex: 0 0 auto;
      display: block;
      text-align: right;
      white-space: nowrap;
      margin: 0;
      line-height: 1.25;
      padding-top: 0;
  }

  .catalogo-productos .caption,
  #catalogoProductos .caption {
      padding: 4px 6px 6px !important;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
  }

  .catalogo-productos .btn-agregar,
  .catalogo-productos .btn.btn-agregar,
  .catalogo-productos .btn.btn-default.btn-sm.btn-agregar,
  #catalogoProductos .btn-agregar,
  #catalogoProductos .btn.btn-agregar,
  #catalogoProductos .btn.btn-default.btn-sm.btn-agregar {
      padding: 2px 4px;
      font-size: 11px;
      line-height: 1.25;
      border-radius: 0 0 5px 5px;
      margin-top: auto;
      background: #28a745;
      border-color: #24963e;
      color: #fff;
  }

  .catalogo-productos .btn-agregar:hover:not(.disabled):not(:disabled),
  #catalogoProductos .btn-agregar:hover:not(.disabled):not(:disabled) {
      background: #218838;
      color: #fff;
      border-color: #1e7e34;
  }

  .catalogo-productos .btn-agregar.btn-agregar-en-venta,
  .catalogo-productos .btn-agregar.btn-agregar-en-venta.disabled,
  .catalogo-productos .btn-agregar.btn-agregar-en-venta:disabled,
  #catalogoProductos .btn-agregar.btn-agregar-en-venta,
  #catalogoProductos .btn-agregar.btn-agregar-en-venta.disabled,
  #catalogoProductos .btn-agregar.btn-agregar-en-venta:disabled {
      background: #c0c0c0 !important;
      color: #444 !important;
      border-color: #a8a8a8 !important;
      opacity: 1;
  }

  .catalogo-productos .btn-agregar.btn-agregar-agotado,
  .catalogo-productos .btn-agregar.btn-agregar-agotado.disabled,
  .catalogo-productos .btn-agregar.btn-agregar-agotado:disabled,
  .catalogo-productos .btn-agregar.disabled,
  .catalogo-productos .btn-agregar:disabled,
  #catalogoProductos .btn-agregar.btn-agregar-agotado,
  #catalogoProductos .btn-agregar.btn-agregar-agotado.disabled,
  #catalogoProductos .btn-agregar.disabled,
  #catalogoProductos .btn-agregar:disabled {
      background: #ececec !important;
      color: #999 !important;
      border-color: #ddd !important;
      opacity: 1;
  }

  .buy {
      font-size: 12px;
      color: purple;
      font-weight: 500;
      cursor: pointer;
  }

  /*  .product-detail-container {
      padding: 10px;
  }*/

  /* Helper classes for Bootstrap 3 compatibility */
  .d-flex {
      display: flex;
  }

  .justify-content-between {
      justify-content: space-between;
  }

  .align-items-center {
      align-items: center;
  }

  .flex-column {
      flex-direction: column;
  }

  .pt-1 {
      padding-top: 5px;
  }

  .mb-2 {
      margin-bottom: 10px;
  }

  .p-2 {
      padding: 10px;
  }

  .w-100 {
      width: 100%;
  }

  .col-lg-6 > .box > .box-body {
    padding: 8px 10px;
  }

  .col-lg-6 > .box > .box-body > hr {
    margin: 4px 0;
  }

  .caja-cabecera-venta .form-group {
    margin-bottom: 4px;
  }

  .caja-cabecera-venta .input-group-addon {
    padding: 4px 8px;
    font-size: 11px;
  }

  .caja-cabecera-venta .form-control {
    height: 30px;
    padding: 4px 8px;
    font-size: 12px;
  }

  .caja-acciones-botones {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .caja-acciones-botones .btn {
    width: 100%;
    white-space: nowrap;
    padding: 4px 8px;
    font-size: 11px;
  }

  .forma-atencion-venta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    margin-top: 6px;
    margin-bottom: 4px;
  }

  .forma-atencion-venta label {
    margin: 0;
    white-space: nowrap;
    flex-shrink: 0;
    font-weight: 700;
    font-size: 12px;
  }

  .forma-atencion-venta select {
    flex: 0 1 200px;
    width: 200px;
    max-width: 100%;
    height: 30px;
    padding: 3px 6px;
    font-size: 12px;
  }

  @media (min-width: 768px) {
    .caja-cabecera-venta {
      display: flex;
      align-items: stretch;
    }

    .caja-cabecera-venta > [class*="col-"] {
      float: none;
    }

    .caja-acciones-botones {
      height: 100%;
    }

    .caja-acciones-botones .btn {
      flex: 1;
    }
  }

  @media (max-width: 767px) {
    .caja-acciones-botones {
      margin-top: 8px;
      margin-bottom: 4px;
    }

    .forma-atencion-venta {
      flex-wrap: nowrap;
    }

    .forma-atencion-venta select {
      width: 220px;
      flex-basis: 220px;
    }
  }

  #modalAgregarOtroIngreso .form-control,
  #modalAgregarOtroIngreso .input-group-addon {
    font-size: 16px;
  }

  #modalAgregarOtroIngreso textarea[name="descripcion_otro_ingreso"] {
    min-height: 90px;
    resize: vertical;
  }

  /* Estilos para hacer más grandes los selects del modal de gastos */
  #modalAgregarMesero .form-control,
  #modalAgregarMesero .input-group-addon {
    font-size: 16px;
    height: 40px;
    padding: 6px 12px;
    line-height: 1.5;
  }

  #modalAgregarMesero .form-group {
      margin-bottom: 15px;
  }

  /* Estilo específico para el input de monto */
  #modalAgregarMesero input[name="monto_gasto"] {
      width: 180px;
      min-width: 180px;
  }

  /* Ajustar el input-group para el monto */
  #modalAgregarMesero .input-group:has(input[name="monto_gasto"]) {
      flex-wrap: nowrap;
  }

  /* Contenedor Flexbox para los campos de fecha y monto */
  .gastos-flex-container {
    display: flex;
    flex-wrap: wrap; /* Permite que los elementos se apilen en pantallas pequeñas */
    margin-left: -5px;
    margin-right: -5px;
  }

  .gastos-flex-container > div {
    flex-grow: 1; /* Permite que los elementos crezcan para ocupar el espacio */
    padding: 0 5px;
    margin-bottom: 15px;
  }

  .gastos-flex-container .fecha-gasto-container {
    flex-basis: 220px; /* Ancho base para el campo de fecha */
  }

  .gastos-flex-container .monto-gasto-container {
    flex-basis: 150px; /* Ancho base para el campo de monto */
  }

  /* Estilos para campos de pago y cambio (compactos) */
  .cajasMetodoPago .form-group {
    margin-bottom: 6px;
  }
  .cajasMetodoPago .form-group label {
    font-size: 14px;
    margin-bottom: 4px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
  }
  .cajasMetodoPago .form-control {
    font-size: 14px;
    font-weight: 600;
    height: 32px;
    padding: 4px 8px;
  }
  /* Misma altura/tamaño: Efectivo, QR y Cambio */
  #nuevoValorEfectivo,
  #nuevoValorQR,
  #nuevoCambioEfectivo {
    font-size: 2rem !important; /* 32px */
    font-weight: bold !important;
    height: 45px !important;
    padding: 8px 12px !important;
    text-align: center;
    line-height: 1;
  }
  .pago-mixto-row {
    display: flex;
    flex-wrap: wrap;
    margin-left: -15px;
    margin-right: -15px;
  }
  .pago-mixto-row > [class*="col-"] {
    padding-left: 15px;
    padding-right: 15px;
  }
  /* Contenedores de Pago en Efectivo, QR y Cambio */
  #contenedorEfectivo .input-group,
  #contenedorQR .input-group,
  #capturarCambioEfectivo .input-group {
    display: flex;
    align-items: center;
  }
  #contenedorEfectivo .input-group-addon,
  #contenedorQR .input-group-addon,
  #capturarCambioEfectivo .input-group-addon {
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    padding: 0 14px !important;
    min-width: 60px;
  }
  #contenedorEfectivo .input-group-addon i,
  #contenedorQR .input-group-addon i,
  #capturarCambioEfectivo .input-group-addon i {
    font-size: 24px;
    line-height: 1;
  }
  .cajasMetodoPago textarea.form-control {
    height: auto;
    min-height: 52px;
    font-size: 12px;
    font-weight: 400;
  }
  .cajasMetodoPago .input-group-addon {
    font-size: 13px;
    padding: 4px 8px;
  }
  .cajasMetodoPago select.form-control {
    font-size: 12px;
    font-weight: 500;
  }
  .pago-actions {
    margin-top: 4px;
  }
  .pago-actions .btn {
    padding: 6px 14px;
    font-size: 13px;
  }

  /* Estilos legacy del total (inputs ocultos; el resumen usa .resumen-venta-totales) */
  .cajaTotal th {
    font-size: 15px;
    padding-bottom: 5px;
  }

  @media (max-width: 767px) {
    .form-group .input-group {
      display: block;
      width: 100%;
    }
    .form-group .input-group .input-group-addon,
    .form-group .input-group .select2-container,
    .form-group .input-group .form-control {
      display: block;
      width: 100% !important;
      box-sizing: border-box;
      text-align: left;
    }
    .form-group .input-group .input-group-addon {
      margin-bottom: 5px;
    }
    .form-group .input-group .input-group-addon {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
      display: block;
      width: 100%;
      box-sizing: border-box;
      text-align: left;
    }
  }

  .pago-mixto-row > div {
    padding-left: 0px;
    padding-right: 5px;
  }

  .cajasMetodoPago .input-group {
    width: 100%;
  }

  .btn-editar-qr {
    position: absolute;
    top: 1px;
    right: 5px;
    z-index: 10;
    padding: 2px 8px;
    font-size: 12px;
  }
</style>

<div class="content-wrapper text-uppercase ">

<!--   <section class="content-header">



    <h1 style="font-weight: bold; font-family: Arial, sans-serif;">
      Crear ventas
    </h1>




    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Crear venta</li>

    </ol>

  </section>
 -->
  <section class="content">



    <div class="row">

  
   

 <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-6 hidden-md hidden-sm hidden-xs">

        <div class="box box-success">

          <div class=" with-border">
            <div class="catalogo-header">
            <div class="filtros-categorias" id="filtrosCategorias">
              <!-- Los botones de categoría se agregarán dinámicamente -->
            </div>
              <div class="catalogo-filtros">
                <div class="catalogo-busqueda">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-search"></i>
                    </span>
                    <div class="catalogo-busqueda-field">
                      <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar productos..." autocomplete="off">
                      <button type="button" class="btn-limpiar-busqueda" id="btnLimpiarBusquedaProducto" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
                        &times;
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          </div>

          <div class="">
            <div class="">
              <div class="catalogo-productos" id="catalogoProductos" style="padding-left: 8px; padding-right: 4px;">
                <!-- Los productos se cargarán dinámicamente aquí -->
              </div>
              <div class="catalogo-paginacion">
                <div class="registros-por-pagina">
                  <span>Mostrar</span>
                  <select id="registrosPorPagina">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                  </select>
                  <span>registros</span>
                </div>
                <div class="paginacion-info" id="paginacionInfo">
                  <!-- La información de paginación se mostrará aquí -->
                </div>
                <div class="paginacion-controles">
                  <button id="btnAnterior" disabled>Anterior</button>
                  <div class="paginacion-paginas" id="paginacionPaginas">
                    <!-- Los números de página se agregarán dinámicamente -->
                  </div>
                  <button id="btnSiguiente">Siguiente</button>
                </div>
              </div>
            </div>
          </div>

        </div>


      </div>
          <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-6 col-xs-12">

        <div class="box">

          <form role="form" method="post" class="formularioVenta" id="ventaForm">

            <div class="box-body">

              <div class="">

                <div class="row caja-cabecera-venta">

                  <div class="col-sm-8">

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->

                <div class="row" style="margin-bottom: 10px;">

                  <!-- ENTRADA DEL VENDEDOR -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">USUARIO</span>
                        <input type="text" class="form-control text-uppercase" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>
                        <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">
                      </div>
                    </div>
                  </div>

                  <!-- ENTRADA DEL CÓDIGO -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">N° TICKET</span>
                        <?php
                        $item = null;
                        $ultimoNroTicket = 0;
                        if (isset($_SESSION["idArqueoCaja"])) {
                          echo '<input type="hidden" name="idArqueoCaja" value="' . $_SESSION["idArqueoCaja"] . '" hidden>';
                          echo '<input type="hidden" name="idCaja" value="' . $_SESSION["idCaja"] . '" hidden>';
                          if ($modoEdicionCuenta) {
                            echo '<input type="hidden" id="idVentaEditar" name="idVentaEditar" value="' . $ventaEditar["id"] . '">';
                            echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' . $ventaEditar["codigo"] . '" readonly>';
                          } else {
                            $ultimoNroTicket = ControladorArqueo::ctrObtenerUltimoNroTicket($_SESSION["idArqueoCaja"]);
                            $ultimoNroTicket++;
                            echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' . $ultimoNroTicket . '" readonly>';
                          }
                        } else {
                          echo '<input type="text" class="form-control" value="0" readonly>';
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <!--=====================================
                ENTRADA DEL MESERO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon">MESEROS</span>

                    <select class="select2 text-uppercase form-control" id="seleccionarMesero" name="seleccionarMesero" required>

                      <option value="0" disabled>Seleccionar Meseros</option>

                      <?php

                      $item = null;
                      $valor = null;

                      $categorias = ControladorMeseros::ctrMostrarMeseros($item, $valor);



                      foreach ($categorias as $key => $value) {
                        if ($modoEdicionCuenta) {
                          $selectedMesero = ((int)$ventaEditar["id_mesero"] === (int)$value['id']) ? 'selected' : '';
                        } else {
                          $selectedMesero = ((int)$value['id'] === 1) ? 'selected' : '';
                        }
                        echo "<option value='" . $value['id'] . "' " . $selectedMesero . ">" . $value['nombre'] . "</option>";
                      }




                      ?>

                    </select>

                  </div>

                </div>

               <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================-->

                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon">CLIENTES</span>
                    <input type="text" class="form-control text-uppercase" id="cliente" name="cliente" value="<?php echo $modoEdicionCuenta ? htmlspecialchars($clienteEditarNombre) : ''; ?>" placeholder="Ingrese el Cliente (Opcional)" autocomplete="off"  >
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $modoEdicionCuenta ? $ventaEditar["id_cliente"] : '0'; ?>"/>
                  </div>
                </div>

                  </div>

                  <div class="col-sm-4">
                    <div class="caja-acciones-botones">
                      <?php
                      if (Permisos::tiene("caja.otros_ingresos")) {
                        if ($cajaArqueoAbierta) {
                          echo '<button type="button" class="btn btn-default btn-sm text-uppercase" data-toggle="modal" data-target="#modalAgregarOtroIngreso">Agregar otro ingreso</button>';
                        } else {
                          echo '<button type="button" class="btn btn-default btn-sm text-uppercase btnCajaCerradaIngreso" disabled>Agregar otro ingreso</button>';
                        }
                      }

                      if (Permisos::tiene("gastos.crear")) {
                        if ($cajaArqueoAbierta) {
                          echo '<button type="button" class="btn btn-default btn-sm text-uppercase" data-toggle="modal" data-target="#modalAgregarMesero">Agregar gastos</button>';
                        } else {
                          echo '<button type="button" class="btn btn-default btn-sm text-uppercase" disabled title="Abra la caja para registrar gastos">Agregar gastos</button>';
                        }
                      }
                      ?>
                      <a href="arqueo-de-caja" class="btn btn-default btn-sm text-uppercase">Cerrar caja</a>
                    </div>
                  </div>

                </div>

                <div class="form-group forma-atencion-venta">
                  <label for="formaAtencion">FORMA DE ATENCIÓN:</label>
                  <select class="form-control input-sm" id="formaAtencion" name="formaAtencion">
                    <option value="1" <?php echo $formaAtencionEditar == 1 ? 'selected' : ''; ?>>🍽️ En Mesa</option>
                    <option value="2" <?php echo $formaAtencionEditar == 2 ? 'selected' : ''; ?>>🚚 Para Llevar</option>
                    <option value="3" <?php echo $formaAtencionEditar == 3 ? 'selected' : ''; ?>>🔀 Mixto</option>
                  </select>
                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->
              
                <hr style="border-top: 1px solid #ddd; margin:4px 0">

                <div class="tabla-lineas-venta-wrap">
                  <table class="tabla-lineas-venta">
                    <thead>
                      <tr>
                        <th class="th-producto">Producto</th>
                        <th class="th-atencion">Atención</th>
                        <th class="th-cant">Cant.</th>
                        <th class="th-money">P. Unit.</th>
                        <th class="th-money">Subtotal</th>
                        <th class="th-money">Desc.</th>
                        <th class="th-money">Total</th>
                        <th class="th-acciones">Acciones</th>
                      </tr>
                    </thead>
                    <tbody class="nuevoProducto"></tbody>
                  </table>
                </div>

                <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto"> Agregar producto</button>


                <div class="row">

                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->

                  <div class="col-xs-6 pull-right cajaTotal">

                    <input type="hidden" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="0">
                    <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>
                    <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>

                    <div class="resumen-venta-totales">
                      <div class="resumen-fila">
                        <span class="resumen-label">Total ítems</span>
                        <span class="resumen-monto" id="vistaTotalItems">Bs 0.00</span>
                      </div>
                      <div class="resumen-fila resumen-fila-descuento" id="filaDescuentoResumen" data-toggle="tooltip" data-placement="left" title="Descuentos por oferta / volumen">
                        <span class="resumen-label">Descuentos <i class="fa fa-info-circle"></i></span>
                        <span class="resumen-monto" id="vistaTotalDescuento">- Bs 0.00</span>
                      </div>
                      <div class="resumen-fila resumen-fila-total">
                        <span class="resumen-label">Total a cobrar</span>
                        <span class="resumen-monto" id="vistaTotalVenta">Bs 0.00</span>
                      </div>
                    </div>

                    <input type="hidden" id="nuevoTotalItems" name="nuevoTotalItems" value="0.00">
                    <input type="hidden" name="totalItems" id="totalItems" value="0">
                    <input type="hidden" id="nuevoTotalDescuento" name="nuevoTotalDescuento" value="0.00">
                    <input type="hidden" name="totalDescuento" id="totalDescuento" value="0">
                    <input type="hidden" id="nuevoTotalVenta" name="nuevoTotalVenta" total="" value="0" required>
                    <input type="hidden" name="totalVenta" id="totalVenta" value="0">

                  </div>

                </div>
                <hr>


                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->


                <div class="row">


                
                  <div class="col-md-6">

                    <div class="form-group" id="contenedorTipoPago">
                      <label for="tipo_pago">TIPO DE PAGO:</label>
                      <select class="form-control input-sm" id="tipoPago" name="tipoPago">
                        <option value="1">Efectivo</option>
                        <option value="2">QR</option>
                        <option value="4">Qr y Efectivo (Mixto)</option>
                      </select>
                    </div>
            


                    <!--=====================================
                      ENTRADA DEL NOTA
                      ======================================-->

                      <div class="row">
                        <!-- Primera columna: Textarea -->
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group-prepend">
                              <label class="input-group-text">NOTA GENERAL(OPCIONAL)</label>
                            </div>
                            <textarea class="form-control text-uppercase" id="nota" cols="100" rows="2" name="nota" aria-label="With textarea"><?php echo $modoEdicionCuenta ? htmlspecialchars($ventaEditar["nota"]) : ''; ?></textarea>
                          </div>
                        </div>
                      </div>

                  </div>

                  <div class="col-md-6 cajasMetodoPago">

                    <div class="pago-mixto-row">

                      <div class="col-md-6" id="contenedorEfectivo">
                        <div class="form-group ">
                          <label for="nuevoValorEfectivo">Pago en Efectivo:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i><b><i class="fa fa-money" aria-hidden="true"></i></b></i></span>
                            <input type="text" class="form-control" id="nuevoValorEfectivo" name="nuevoValorEfectivo" placeholder="0.00" min="0" step="0.01" inputmode="decimal" required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6" id="contenedorQR">
                        <div class="form-group position-relative">
                          <!-- Botón flotante -->
                          <!-- <button type="button" class="btn btn-default btnEditarQR btn-editar-qr">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                          </button> -->
                          <label for="nuevoValorQR">Pago en QR:</label>
                          <div class="input-group">
                            <span class="input-group-addon">
                              <i class="fa fa-qrcode"></i>
                            </span>
                            <input type="text" class="form-control" id="nuevoValorQR" name="nuevoValorQR" placeholder="0" min="0" step="0.01"  inputmode="decimal" readonly required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12" id="capturarCambioEfectivo">
                        <div class="form-group">
                          <label for="nuevoCambioEfectivo">Cambio:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i><b>Bs</b></i></span>
                            <input type="text" class="form-control" id="nuevoCambioEfectivo" name="nuevoCambioEfectivo" placeholder="0" min="0" step="0.01"  readonly required>
                          </div>
                        </div>
                      </div>

                    </div>

                  <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">
                </div>


              </div>

            </div>

            <div class="box-footer">
              <div class="row">
                <div class="col-xs-6 text-left ">
                     <div class="">
                      <div class="input-group">
                        <span class="input-group-addon text-bold">IMPRIMIR EN</span>
                        <select class="form-control input-sm text-uppercase text-bold" id="idTipoImpresion" name="idTipoImpresion">
                          <option value="1">CAJA Y COCINA</option>
                          <option value="2">CAJA (TICKET)</option>
                          <option value="5" selected>CAJA (TICKET + COMANDA)</option>
                          <option value="3">COCINA</option>
                          <option value="4">NO IMPRIMIR</option>
                        </select>
                      </div>
                    </div>
                </div>
                <div class="col-xs-6 text-right">
                  <style>
                    /* Estilos locales profesionales para los botones de pago */
                    .pago-actions { display:flex; justify-content:flex-end; gap:12px; align-items:center; }
                    .btn-pagar-ahora { background:#1e73be; color:#fff; border:none; padding:10px 22px; border-radius:8px; font-weight:700; box-shadow:0 6px 14px rgba(30,115,190,0.16); }
                    .btn-pagar-ahora:hover { background:#165f9b; }
                    .btn-pagar-despues { background:#f39c12; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:700; box-shadow:0 6px 14px rgba(243,156,18,0.14); }
                    .btn-pagar-despues:hover { background:#d98a0f; }
                    .btn-actualizar { background:#ffd54a; color:#222; border:none; padding:10px 18px; border-radius:8px; font-weight:600; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
                    /* Asegurar que no colisione con utilidades existentes */
                    .pago-actions .btn-pagar-ahora, .pago-actions .btn-pagar-despues, .pago-actions .btn-actualizar { display:inline-block; }
                  </style>

                  <div class="pago-actions">
                  <?php if ($modoEdicionCuenta): ?>
                  <button type="button" id="actualizarCuentaBtn" class="btn-actualizar" style="margin-right:0;">Actualizar cuenta</button>
                  <?php else: ?>
                  <button type="button" id="cuentaPendienteBtn" class="btn-pagar-despues">Cobrar Despues</button>
                  <?php endif; ?>
                  <button type="button" id="guardarVentaBtn" class="btn-pagar-ahora" <?php echo $modoEdicionCuenta ? 'style="display:none;"' : ''; ?>>Cobrar Ahora</button>
                  </div>
                </div>
              </div>
            </div>

          </form>

          <?php

          $guardarVenta = new ControladorVentas();
          $guardarVenta->ctrCrearVenta();

          ?>

        </div>

      </div>

  

    </div>
 
   <!-- <php
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    ?>-->
  </section>

</div>

<!--=====================================
MODAL AGREGAR MESERO
======================================-->

<style>
  #modalAgregarMesero .gasto-pago-opciones {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  #modalAgregarMesero .gasto-pago-opcion {
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
  #modalAgregarMesero .gasto-pago-opcion:hover {
    border-color: #28a745;
    background: #f8fff9;
  }
  #modalAgregarMesero .gasto-pago-opcion.active {
    border-color: #28a745;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, .18);
    background: #f3fff6;
  }
  #modalAgregarMesero .gasto-pago-opcion input[type="radio"] {
    float: left;
    margin: 2px 0 0 2px;
  }
  #modalAgregarMesero .gasto-pago-opcion .gasto-pago-icono {
    display: block;
    font-size: 28px;
    line-height: 1.2;
    margin: 4px 0 6px;
    color: #28a745;
  }
  #modalAgregarMesero .gasto-pago-opcion[data-tipo="4"] .gasto-pago-icono,
  #modalAgregarMesero .gasto-pago-opcion[data-tipo="4"] .gasto-pago-texto {
    color: #17a2b8;
  }
  #modalAgregarMesero .gasto-pago-opcion .gasto-pago-texto {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #28a745;
    letter-spacing: .3px;
  }
  #modalAgregarMesero .gasto-pago-ayuda {
    margin-top: 8px;
    color: #888;
    font-size: 12px;
  }
</style>
<div id="modalAgregarMesero" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" id="formAgregarGastoVenta" class="form-gasto-pago">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Gastos</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA EL TIPO DE GASTO -->

         
            <div class="form-group">
              <div class="input-group">
              <span class="input-group-addon">TIPO DE GASTO</span>
                  <select class="form-control" id="id_tipo_gasto" name="id_tipo_gasto" required>

                    <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorTipoGasto::ctrMostrarTipoGasto($item, $valor);
                      foreach ($categorias as $key => $value) {
                        $selected = (strtolower($value['nombre']) == 'otros') ? 'selected' : '';
                        echo '<option value="' . $value["id"] . '" ' . $selected . '>' . $value["nombre"] . '</option>';
                      }
                    ?>
                  </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">DESCRIPCIÓN</span>
                <input type="text" class="form-control" name="descripcion_gasto" id="descripcion_gasto" placeholder="Ingresar Descripción" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA FECHA DE GASTO Y MONTO -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">FECHA DE GASTO</span>
                <input type="date" id="fecha_gasto" name="fecha_gasto" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
              </div>
            </div>

            <div class="form-group">
              <label style="display:block; margin-bottom:8px;">FORMA DE PAGO:</label>
              <div class="gasto-pago-opciones">
                <label class="gasto-pago-opcion" data-tipo="2">
                  <input type="radio" name="tipo_pago_gasto" value="2">
                  <span class="gasto-pago-icono"><i class="fa fa-qrcode"></i></span>
                  <span class="gasto-pago-texto">QR</span>
                </label>
                <label class="gasto-pago-opcion active" data-tipo="1">
                  <input type="radio" name="tipo_pago_gasto" value="1" checked>
                  <span class="gasto-pago-icono"><i class="fa fa-money"></i></span>
                  <span class="gasto-pago-texto">EFECTIVO</span>
                </label>
                <label class="gasto-pago-opcion" data-tipo="4">
                  <input type="radio" name="tipo_pago_gasto" value="4">
                  <span class="gasto-pago-icono"><i class="fa fa-exchange"></i></span>
                  <span class="gasto-pago-texto">MIXTO</span>
                </label>
              </div>
              <div class="gasto-pago-ayuda">
                <i class="fa fa-info-circle"></i> Seleccione cómo se pagó el gasto. En mixto indique efectivo y QR.
              </div>
            </div>

            <div class="form-group grupo-monto-gasto-simple">
              <div class="input-group">
                <span class="input-group-addon">MONTO BS.</span>
                <input type="number" class="form-control" name="monto_gasto" id="monto_gasto" placeholder="0.00" min="0.01" step="0.01" required>
              </div>
            </div>

            <div class="grupo-monto-gasto-mixto" style="display:none;">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">EFECTIVO BS.</span>
                  <input type="number" class="form-control" name="monto_efectivo_gasto" id="monto_efectivo_gasto" placeholder="0.00" min="0" step="0.01">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">QR BS.</span>
                  <input type="number" class="form-control" name="monto_qr_gasto" id="monto_qr_gasto" placeholder="0.00" min="0" step="0.01">
                </div>
              </div>
            </div>

            <input type="hidden" name="redirigir_gasto" value="crear-venta">
            <input type="hidden" name="id_usuario_gasto" value="<?php echo $_SESSION["id"]; ?>">
            <input type="hidden" name="id_arqueo_caja_gasto" value="<?php echo $_SESSION["idArqueoCaja"]; ?>">
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-" style="background:#6c757d; color:white">Registrar Gasto </button>

        </div>

      </form>


      
      <?php

      $crearGasto= new ControladorGastos();
      $crearGasto->ctrCrearGasto();

      ?>

    </div>

  </div>

</div>

<?php include "componentes/modal-otro-ingreso.php"; ?>

<script>
  const idUsuario = <?php echo $_SESSION["id"]; ?>;
  var modoEdicionCuenta = <?php echo $modoEdicionCuenta ? 'true' : 'false'; ?>;
</script>
<script src="vistas/js/validar-caja.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {

  const tipoPago = document.getElementById("tipoPago");
  const efectivo = document.getElementById("contenedorEfectivo");
  const qr = document.getElementById("contenedorQR");
  const cambio = document.getElementById("capturarCambioEfectivo");
  const contenedorTipoPago = document.getElementById("contenedorTipoPago");
  const cajasMetodoPago = document.querySelector(".cajasMetodoPago");

  if (modoEdicionCuenta) {
    if (contenedorTipoPago) contenedorTipoPago.style.display = "none";
    if (efectivo) efectivo.style.display = "none";
    if (qr) qr.style.display = "none";
    if (cambio) cambio.style.display = "none";
    if (cajasMetodoPago) cajasMetodoPago.style.display = "none";
    return;
  }

  function setColumna(elemento, size) {
    elemento.classList.remove("col-md-6", "col-md-12");
    elemento.classList.add(size);
  }

  function actualizarCampos() {
    const valor = tipoPago.value;

    if (valor == "1") { // Efectivo
      efectivo.style.display = "block";
      qr.style.display = "none";
  

      setColumna(efectivo, "col-md-12");

    } 
    else if (valor == "2") { // QR
      efectivo.style.display = "none";
      qr.style.display = "block";
      
      setColumna(qr, "col-md-12");

    } 
    else if (valor == "4") { // Mixto
      efectivo.style.display = "block";
      qr.style.display = "block";
  

      setColumna(efectivo, "col-md-6");
      setColumna(qr, "col-md-6");

    }
  }

  tipoPago.addEventListener("change", actualizarCampos);
  actualizarCampos();

});

   // Prevenir que el dropdown se cierre al interactuar con el formulario
   $(document).on('click', '.dropdown-menu', function (e) {
      e.stopPropagation();
    });
    
$(document).ready(function() {
    $("#cliente").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax/clientes.ajax.php',
                dataType: 'json',
                data: {
                    term: request.term
                },
                success: function(data) {
                    if (data.length === 0) {
                        $("#id_cliente").val(0);
                    }
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            event.preventDefault();
            $("#id_cliente").val(ui.item.id);
            $("#cliente").val(ui.item.value);
        },
        change: function(event, ui) {
            if (!ui.item) {
                $("#id_cliente").val(0);
            }
        }
    });

    $("#cliente").keydown(function(event) {
        if (event.key === "Delete") {
            $("#id_cliente").val(0);
        }
    });

    $('.select2-selection-multiple').select2({
      theme: "classic"
    });
    
    $("select[name='states[]']").on("change", function () {
      const selectedTexts = $(this).find("option:selected").map(function () {
        return $(this).text();
      }).get();
      console.log(selectedTexts);
    });
});

var ventaAjaxEnCurso = false;

document.getElementById("guardarVentaBtn") && document.getElementById("guardarVentaBtn").addEventListener("click", function(e) {
  e.preventDefault(); // Evita el submit tradicional
  if (ventaAjaxEnCurso) return;

  var totalVenta = Number($('#nuevoTotalVenta').val());
  var efectivoRaw = String($('#nuevoValorEfectivo').val() || "").trim();
  var tipopagovalue = $('#tipoPago').val();
  var efectivo = Number(efectivoRaw.replace(",", "."));
  
  function esMontoValido(raw, num) {
    if (raw === "" || !/^\d+(\.\d+)?$/.test(raw.replace(",", "."))) {
      return false;
    }
    return Number.isFinite(num) && num >= 0;
  }

  switch(tipopagovalue) {
    case "1": // Efectivo
      if (!esMontoValido(efectivoRaw, efectivo)) {
        swal({
          type: "warning",
          title: "Pago inválido",
          text: "Ingrese un monto numérico válido en efectivo.",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      if(efectivo < totalVenta) {
        swal({
          type: "warning",
          title: "El pago en efectivo debe ser igual o mayor al total",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      break;
    case "2": // QR
      var qrRaw = String($('#nuevoValorQR').val() || "").trim();
      var valorQR = Number(qrRaw.replace(",", "."));
      if (!esMontoValido(qrRaw, valorQR)) {
        swal({
          type: "warning",
          title: "Pago inválido",
          text: "Ingrese un monto numérico válido en QR.",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      if(valorQR < totalVenta) {
        swal({
          type: "warning",
          title: "El pago en QR debe ser igual o mayor al total",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      break;
    case "4": // Mixto
      var qrRawMixto = String($('#nuevoValorQR').val() || "").trim();
      var valorQR = Number(qrRawMixto.replace(",", "."));
      if (!esMontoValido(efectivoRaw, efectivo) || !esMontoValido(qrRawMixto, valorQR)) {
        swal({
          type: "warning",
          title: "Pago inválido",
          text: "Ingrese montos numéricos válidos en efectivo y QR.",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      if((efectivo + valorQR) < totalVenta) {
        swal({
          type: "warning",
          title: "La suma del efectivo y el QR debe ser igual o mayor al total",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        return;
      }
      break;
  }


  if ($(".nuevoProducto .nuevaDescripcionProducto").length <= 0) {
    swal({
      type: "warning",
      title: "Debe agregar al menos un producto a la venta",
      showConfirmButton: true,
      confirmButtonText: "Cerrar"
    });
    return;
  }

    // Recoge los datos del formulario
    var form = document.getElementById("ventaForm");
    var formData = new FormData(form);
  ventaAjaxEnCurso = true;
  $("#guardarVentaBtn").prop("disabled", true);
  $("#cuentaPendienteBtn").prop("disabled", true);
    $.ajax({
      url: "ajax/ventas.ajax.php", // Cambia aquí
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success:  function(respuesta) {
        if(respuesta.status == "ok") {
            notificarExitoVenta(respuesta.mensaje || "La venta ha sido registrada correctamente");
            setTimeout(function() {
              imprimirVentaSegunTipo(respuesta.idVenta);
            }, 600);
        }else if(respuesta.status == "recargar"){
          ventaAjaxEnCurso = false;
          $("#guardarVentaBtn").prop("disabled", false);
          $("#cuentaPendienteBtn").prop("disabled", false);
          swal({
                  title:"Actualice de la caja",
                  text: "es necesario recargar la pagina para continuar",
                  type: "warning",
                  showCancelButton: false,
                  confirmButtonColor: "#3085d6",
                  confirmButtonText: "Sí, recargar pagina",
                
              }).then((result) => {
                  if (result.value) {
                      window.location.href = "crear-venta";
                  }
              }); 
        }
        else {
          ventaAjaxEnCurso = false;
          $("#guardarVentaBtn").prop("disabled", false);
          $("#cuentaPendienteBtn").prop("disabled", false);
          swal({
            type: "error",
            title: "Error al guardar la venta",
            text: respuesta.mensaje || "Error desconocido",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
          });
        }
      },
      error: function(xhr, status, error) {
        ventaAjaxEnCurso = false;
        $("#guardarVentaBtn").prop("disabled", false);
        $("#cuentaPendienteBtn").prop("disabled", false);
        
        swal({
          type: "error",
          title: "Error de comunicación",
          text: "No se pudo guardar la venta",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });

      }
    });

});

function validarProductosEnVenta() {
  if ($(".nuevoProducto .nuevaDescripcionProducto").length <= 0) {
    swal({
      type: "warning",
      title: "Debe agregar al menos un producto a la venta",
      showConfirmButton: true,
      confirmButtonText: "Cerrar"
    });
    return false;
  }
  return true;
}

function notificarExitoVenta(mensaje) {
  // SweetAlert2 v7.1.2: usar "top-right" (no "top-end", eso es v8+)
  swal({
    toast: true,
    position: "top-right",
    type: "success",
    title: mensaje || "Operación realizada correctamente",
    showConfirmButton: false,
    timer: 3000,
    animation: true,
    backdrop: false,
    allowOutsideClick: true,
    allowEscapeKey: true
  });
}

function continuarTrasCuentaPendiente(respuesta) {
  var idImpresion = $("#idTipoImpresion").val();
  if (idImpresion == "2") {
    imprimirSoloCaja(respuesta.idVenta).finally(function() {
      window.location.href = "crear-venta";
    });
  } else if (idImpresion == "5") {
    imprimirAmbosEnCaja(respuesta.idVenta).finally(function() {
      window.location.href = "crear-venta";
    });
  } else if (idImpresion == "1") {
    imprimirCajaCocina(respuesta.idVenta).finally(function() {
      window.location.href = "crear-venta";
    });
  } else if (idImpresion == "3") {
    imprimirSoloCocina(respuesta.idVenta).finally(function() {
      window.location.href = "crear-venta";
    });
  } else if (idImpresion == "4") {
    imprimirSoloCaja(respuesta.idVenta, null, false).finally(function() {
      window.location.href = "crear-venta";
    });
  } else {
    window.location.href = "crear-venta";
  }
}

function continuarTrasActualizarCuenta(respuesta) {
  var idsNuevos = respuesta.idsDetalleNuevos || [];
  if (idsNuevos.length > 0) {
    var idsDetalle = idsNuevos.join(",");
    var idImpresion = $("#idTipoImpresion").val();
    if (idImpresion == "2") {
      imprimirSoloCaja(respuesta.idVenta, idsDetalle).finally(function() {
        window.location.href = "ventas";
      });
    } else if (idImpresion == "5") {
      imprimirAmbosEnCaja(respuesta.idVenta, idsDetalle).finally(function() {
        window.location.href = "ventas";
      });
    } else if (idImpresion == "1") {
      imprimirCajaCocina(respuesta.idVenta, idsDetalle).finally(function() {
        window.location.href = "ventas";
      });
    } else if (idImpresion == "3") {
      imprimirSoloCocina(respuesta.idVenta, idsDetalle).finally(function() {
        window.location.href = "ventas";
      });
    } else if (idImpresion == "4") {
      imprimirSoloCaja(respuesta.idVenta, idsDetalle, false).finally(function() {
        window.location.href = "ventas";
      });
    } else {
      window.location.href = "ventas";
    }
  } else {
    window.location.href = "ventas";
  }
}

function enviarVentaAjax(extraData, onSuccess, onFail) {
  var form = document.getElementById("ventaForm");
  var formData = new FormData(form);
  if (extraData) {
    Object.keys(extraData).forEach(function(key) {
      formData.append(key, extraData[key]);
    });
  }

  $.ajax({
    url: "ajax/ventas.ajax.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(respuesta) {
      if (respuesta.status == "ok") {
        onSuccess(respuesta);
      } else if (respuesta.status == "recargar") {
        if (typeof onFail === "function") onFail();
        swal({
          title: "Actualice de la caja",
          text: "Es necesario recargar la página para continuar",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Sí, recargar página"
        }).then(function(result) {
          if (result.value) {
            window.location.href = "crear-venta";
          }
        });
      } else {
        if (typeof onFail === "function") onFail();
        swal({
          type: "error",
          title: "Error",
          text: respuesta.mensaje || "Error desconocido",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
      }
    },
    error: function() {
      if (typeof onFail === "function") onFail();
      swal({
        type: "error",
        title: "Error de comunicación",
        text: "No se pudo completar la operación",
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      });
    }
  });
}

var cuentaPendienteBtn = document.getElementById("cuentaPendienteBtn");
if (cuentaPendienteBtn) {
  cuentaPendienteBtn.addEventListener("click", function(e) {
    e.preventDefault();
    if (ventaAjaxEnCurso) return;
    if (!validarProductosEnVenta()) return;
    listarProductos();

    ventaAjaxEnCurso = true;
    $("#cuentaPendienteBtn").prop("disabled", true);
    $("#guardarVentaBtn").prop("disabled", true);

    enviarVentaAjax(
      { estadoPago: "PENDIENTE" },
      function(respuesta) {
        notificarExitoVenta(respuesta.mensaje);
        setTimeout(function() {
          continuarTrasCuentaPendiente(respuesta);
        }, 600);
      },
      function() {
        ventaAjaxEnCurso = false;
        $("#cuentaPendienteBtn").prop("disabled", false);
        $("#guardarVentaBtn").prop("disabled", false);
      }
    );
  });
}

var actualizarCuentaBtn = document.getElementById("actualizarCuentaBtn");
if (actualizarCuentaBtn) {
  actualizarCuentaBtn.addEventListener("click", function(e) {
    e.preventDefault();
    if (ventaAjaxEnCurso) return;
    if (!validarProductosEnVenta()) return;
    listarProductos();

    ventaAjaxEnCurso = true;
    $("#actualizarCuentaBtn").prop("disabled", true);

    enviarVentaAjax(
      { actualizarCuentaPendiente: "1" },
      function(respuesta) {
        notificarExitoVenta(respuesta.mensaje);
        setTimeout(function() {
          continuarTrasActualizarCuenta(respuesta);
        }, 600);
      },
      function() {
        ventaAjaxEnCurso = false;
        $("#actualizarCuentaBtn").prop("disabled", false);
      }
    );
  });
}

function htmlOpcionesPreferenciasProducto() {
  return `
    <option value="1">Bien cocido </option>
    <option value="2">Tres cuartos </option>
    <option value="3">Término medio </option>
    <option value="4">Medio rojo 🥩</option>
    <option value="5">Rojo (Inglés) </option>
    <option value="6">Sin yuca ❌</option>
    <option value="7">Sin arroz ❌</option>
    <option value="8">Sin ensalada ❌</option>
    <option value="9">Sin chorizo ❌</option>
    <option value="10">Sin Cordon Blue ❌</option>
    <option value="11">Sin papas fritas ❌</option>
    <option value="12">Sin arroz con queso ❌</option>
    <option value="13">Más yuca ✅</option>
    <option value="14">Más arroz ✅</option>
    <option value="15">Más ensalada ✅</option>
    <option value="16">Más chorizo ✅</option>
    <option value="17">Más Cordon Blue ✅</option>
    <option value="18">Más papas fritas ✅</option>
    <option value="19">Más arroz con queso ✅</option>
    <option value="20">Solo yuca</option>
    <option value="21">Solo arroz</option>
    <option value="22">Solo ensalada</option>
    <option value="23">Solo papas fritas</option>
    <option value="24">Solo chorizo</option>
    <option value="25">Poca yuca</option>
    <option value="26">Poco arroz</option>
    <option value="27">Poca ensalada</option>
    <option value="28">Pocas papas fritas</option>
    <option value="29">Poco chorizo</option>
    <option value="30">Salsa aparte</option>
    <option value="31">Ají aparte 🌶️</option>
    <option value="32">Sin sal</option>
    <option value="33">Poca sal</option>
    <option value="34">Bien sazonado</option>`;
}

function htmlNotasProductoLinea() {
  return `
      <div class="dropdown lv-notas-wrap">
          <button class="btn btn-default btn-xs dropdown-toggle btn-abrir-notas" type="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                  title="Preferencias">
            <i class="fa fa-file-text-o"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-right nota-dropdown">
            <li style="width: 280px; padding: 10px;">
              <form class="noteForm" onsubmit="return false;">
                <label>Preferencias</label>
                <div class="form-group">
                  <select class="select2-nota form-control input-sm nota-producto" multiple="multiple" name="states[]">
                    ${htmlOpcionesPreferenciasProducto()}
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                  <label>Nota Adicional (Opcional)</label>
                  <textarea class="form-control input-sm nota-adicional" rows="2"
                            placeholder="Nota adicional..."></textarea>
                </div>
              </form>
            </li>
          </ul>
        </div>`;
}

function inicializarSelect2NotasEnFila($contexto) {
  var $root = $contexto && $contexto.length ? $contexto : $(document);
  $root.find(".select2-nota").each(function() {
    var $sel = $(this);
    if ($sel.hasClass("select2-hidden-accessible")) return;
    $sel.select2({
      theme: "classic",
      multiple: true,
      width: "100%",
      dropdownParent: $sel.closest(".nota-dropdown"),
      language: {
        noResults: function() { return "No hay resultados"; }
      }
    }).on("change", function() {
      var $fila = $(this).closest("tr.linea-venta");
      if (typeof actualizarEstadoBotonNotas === "function") {
        actualizarEstadoBotonNotas($fila);
      }
      listarProductos();
    });
  });
  $root.find(".nota-adicional").off("change.notas keyup.notas").on("change.notas keyup.notas", function() {
    var $fila = $(this).closest("tr.linea-venta");
    if (typeof actualizarEstadoBotonNotas === "function") {
      actualizarEstadoBotonNotas($fila);
    }
    listarProductos();
  });
}

function actualizarEstadoBotonNotas($fila) {
  var $btn = $fila.find(".btn-abrir-notas");
  if (!$btn.length) return;
  var prefs = $fila.find(".nota-producto").val() || [];
  var nota = String($fila.find(".nota-adicional").val() || "").trim();
  var tiene = (prefs && prefs.length > 0) || nota !== "";
  $btn.toggleClass("tiene-notas", !!tiene);
}

/** Normaliza texto de preferencia para comparar (trim, espacios, emoji variation). */
function normalizarTextoPreferencia(t) {
  return String(t || "")
    .replace(/\uFE0F/g, "")
    .replace(/\s+/g, " ")
    .trim();
}

/**
 * Restaura preferencias (texto CSV guardado en BD) y nota adicional en una fila.
 * Debe llamarse después de inicializar Select2 en esa fila.
 */
function aplicarPreferenciasYNotaEnFila($fila, preferenciasStr, notaAdicional) {
  if (!$fila || !$fila.length) return;
  var $sel = $fila.find(".nota-producto");
  var $nota = $fila.find(".nota-adicional");
  if ($nota.length) {
    $nota.val(notaAdicional != null ? String(notaAdicional) : "");
  }
  if (!$sel.length) {
    actualizarEstadoBotonNotas($fila);
    return;
  }

  var textos = String(preferenciasStr || "")
    .split(",")
    .map(normalizarTextoPreferencia)
    .filter(Boolean);
  var valores = [];
  if (textos.length) {
    $sel.find("option").each(function () {
      var optTxt = normalizarTextoPreferencia($(this).text());
      if (textos.indexOf(optTxt) !== -1) {
        valores.push(String($(this).val()));
      }
    });
  }
  $sel.val(valores).trigger("change");
  actualizarEstadoBotonNotas($fila);
}

function construirHtmlLineaVenta(cfg) {
  var img = cfg.imagen && String(cfg.imagen).trim() !== ""
    ? cfg.imagen
    : "vistas/img/productos/default/anonymous.webp";
  var presentaciones = cfg.presentaciones || [];
  var selectorPres = window.PresentacionesVenta
    ? PresentacionesVenta.bloqueSelectorHtml(presentaciones, cfg.idPresentacion || 0)
    : '<div class="lv-presentacion lv-pres-solo-unidad"><select class="form-control input-sm select-presentacion-venta lv-pres-solo-unidad"><option value="0" data-factor="1" data-nombre="Unidad">Unidad (1 und.)</option></select></div>';
  var formaSel1 = cfg.formaAtencion === "1" || cfg.formaAtencion === 1 ? "selected" : "";
  var formaSel2 = cfg.formaAtencion === "2" || cfg.formaAtencion === 2 ? "selected" : "";
  var idDetalleAttr = cfg.idDetalle ? ' data-idDetalle="' + cfg.idDetalle + '"' : "";
  var promoAttrs = cfg.promoAttr
    ? ' data-promo=\'' + cfg.promoAttr + '\' data-subtotal-final="' + (cfg.subtotalFinal || "") + '" data-precio-final="' + (cfg.precioFinal || "") + '"'
    : "";
  var precioUnit = Number(cfg.precioVenta) || 0;
  var factorNum = Number(cfg.factor) || 1;
  var undCalc = (Number(cfg.qty) || 1) * factorNum;
  var subtotalBruto = cfg.subtotalBruto != null
    ? Number(cfg.subtotalBruto)
    : (precioUnit * undCalc);
  var descMonto = Number(cfg.descuentoTotal) || 0;
  var totalLinea = cfg.subtotalFinal != null && cfg.subtotalFinal !== ""
    ? Number(cfg.subtotalFinal)
    : (subtotalBruto - descMonto);
  if (totalLinea < 0) totalLinea = 0;
  var undLabel = cfg.unidadesLabel;
  if (undLabel === undefined || undLabel === null) {
    undLabel = factorNum > 1 ? ("= " + undCalc + " Unidades") : "";
  } else if (factorNum <= 1) {
    undLabel = "";
  }
  var undStyle = undLabel ? "" : ' style="display:none"';
  var extraNotas = cfg.extra || "";
  var descHtml = descMonto > 0
    ? ('- Bs ' + descMonto.toFixed(2))
    : "—";
  var descClass = descMonto > 0 ? "lv-desc" : "lv-desc es-vacio";
  var mostrarDuplicar = cfg.mostrarDuplicar !== false;
  var btnDuplicar = mostrarDuplicar
    ? `<button type="button" class="btn btn-info btn-xs btn-duplicar-linea" title="Duplicar Producto" idProducto="${cfg.idProducto}">
          <i class="fa fa-copy"></i>
        </button>`
    : "";
  var nombreProducto = String(cfg.descripcion || "");
  var nombreEscapado = nombreProducto
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");

  return `
    <tr class="linea-venta">
      <td class="lv-celda-producto">
        <div class="lv-producto">
          <img class="lv-producto-img" src="${img}" alt="" onerror="this.src='vistas/img/productos/default/anonymous.webp'">
          <div class="lv-producto-info">
            <div class="lv-nombre-producto" title="${nombreEscapado}">${nombreEscapado}</div>
            <input type="text" class="form-control input-sm nuevaDescripcionProducto text-uppercase"
                   idProducto="${cfg.idProducto}"${idDetalleAttr} name="agregarProducto"
                   value="${nombreEscapado}" readonly required tabindex="-1" aria-hidden="true">
            <div class="lv-producto-controles">
              ${selectorPres}
              ${extraNotas}
            </div>
          </div>
        </div>
      </td>
      <td class="lv-atencion">
        <select class="form-control input-sm lv-forma-atencion" name="formaAtencionDetalle">
          <option value="1" ${formaSel1}>🍽️ M</option>
          <option value="2" ${formaSel2}>🚚 LL</option>
        </select>
      </td>
      <td class="lv-cant">
        <div class="cantidad-stepper">
          <button type="button" class="btn btn-default btn-sm btn-cantidad-ajuste btn-minus" data-action="decrementar" title="Disminuir">
            <i class="fa fa-minus"></i>
          </button>
          <input type="number" class="form-control input-sm nuevaCantidadProducto"
                 name="nuevaCantidadProducto" min="1" value="${cfg.qty || 1}"
                 stock="${cfg.stock}" data-idProducto="${cfg.idProducto}" data-inventariable="${cfg.inventariable ? 1 : 0}"
                 data-factor="${cfg.factor || 1}"
                 data-id-presentacion="${cfg.idPresentacion > 0 ? cfg.idPresentacion : ""}"
                 data-nombre-presentacion="${cfg.nombrePresentacion || "Unidad"}" required>
          <button type="button" class="btn btn-success btn-sm btn-cantidad-ajuste btn-plus" data-action="incrementar" title="Aumentar">
            <i class="fa fa-plus"></i>
          </button>
        </div>
        <div class="lbl-unidades-reales"${undStyle}>${undLabel}</div>
      </td>
      <td class="lv-money lv-precio-unit">Bs ${precioUnit.toFixed(2)}</td>
      <td class="lv-money lv-subtotal ingresoPrecio">
        <input type="text" class="form-control input-sm nuevoPrecioProducto"
               precioReal="${precioUnit}" precioOriginal="${precioUnit}"${promoAttrs}
               name="nuevoPrecioProducto" value="${subtotalBruto.toFixed(2)}" readonly required>
        <input type="hidden" precioRealCompra="${cfg.precioCompra}"
               name="nuevoPrecioCompraProducto" class="nuevoPrecioCompraProducto" value="${cfg.precioCompra}">
        <div class="promo-aplicada-info"></div>
      </td>
      <td class="${descClass}">${descHtml}</td>
      <td class="lv-total-linea">Bs ${totalLinea.toFixed(2)}</td>
      <td class="lv-acciones">
        ${btnDuplicar}
        <button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="${cfg.idProducto}" title="Quitar">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>`;
}

function agregarLineaProductoEdicion(linea) {
  var formaAtencionLinea = linea.forma_atencion === "LL" ? "2" : "1";
  var formaAtencionGeneral = $("#formaAtencion").val();
  var esInventariableLinea = Number(linea.inventariable) === 1;
  var stockLinea = esInventariableLinea
    ? (parseInt(linea.stock_actual || 0) + parseInt(linea.cantidad || 0))
    : 1;
  var precioOriginal = (linea.precio_original !== null && linea.precio_original !== undefined && linea.precio_original !== "")
    ? linea.precio_original
    : linea.precio_venta;
  var cantLinea = Number(linea.cantidad) || 1;
  var precioOrigNum = Number(precioOriginal) || 0;
  var subtotalOriginal = Math.round((precioOrigNum * cantLinea + Number.EPSILON) * 100) / 100;
  var descTotal = Number(linea.descuento_total) || 0;
  var descUnit = Number(linea.descuento_unitario) || 0;
  var precioFinal = Math.round((precioOrigNum - descUnit + Number.EPSILON) * 100) / 100;
  if (precioFinal < 0) precioFinal = 0;
  var subtotalFinal = Math.round((subtotalOriginal - descTotal + Number.EPSILON) * 100) / 100;
  if (subtotalFinal < 0) subtotalFinal = 0;
  var promoData = {
    id_promocion: linea.id_promocion || null,
    id_intervalo_promocion: linea.id_intervalo_promocion || null,
    nombre_promocion: linea.nombre_promocion || null,
    tipo_descuento: linea.tipo_descuento || null,
    valor_descuento: linea.valor_descuento || null,
    descuento_unitario: descUnit,
    descuento_total: descTotal,
    precio_original: precioOrigNum,
    precio_unitario_final: precioFinal,
    subtotal_original: subtotalOriginal,
    subtotal_final: subtotalFinal
  };
  var promoAttr = JSON.stringify(promoData).replace(/'/g, "&#39;");

  var factorLinea = parseInt(linea.unidades_por_presentacion, 10) || 1;
  var idPresLinea = parseInt(linea.id_presentacion, 10) || 0;
  var nombrePresLinea = linea.nombre_presentacion || "Unidad";
  var qtyPresLinea = parseInt(linea.cantidad_presentaciones, 10);
  if (!qtyPresLinea || qtyPresLinea < 1) {
    qtyPresLinea = Math.max(1, Math.round(cantLinea / factorLinea));
  }

  $(".nuevoProducto").append(construirHtmlLineaVenta({
    idProducto: linea.id_producto,
    idDetalle: linea.id,
    descripcion: linea.producto,
    codigo: linea.codigo || "",
    imagen: linea.imagen || "",
    stock: stockLinea,
    inventariable: esInventariableLinea,
    precioVenta: precioOrigNum,
    precioCompra: linea.precio_compra,
    qty: qtyPresLinea,
    factor: factorLinea,
    idPresentacion: idPresLinea,
    nombrePresentacion: nombrePresLinea,
    presentaciones: linea.presentaciones || [],
    formaAtencion: formaAtencionLinea,
    promoAttr: promoAttr,
    subtotalBruto: subtotalOriginal,
    descuentoTotal: descTotal,
    subtotalFinal: subtotalFinal,
    precioFinal: precioFinal,
    unidadesLabel: factorLinea > 1 ? ("= " + cantLinea + " Unidades") : "",
    extra: esInventariableLinea ? "" : htmlNotasProductoLinea(),
    mostrarDuplicar: true
  }));

  var nuevoSelector = $(".nuevoProducto").find("select[name='formaAtencionDetalle']").last();
  if (formaAtencionGeneral !== "3") {
    nuevoSelector.prop("disabled", true);
  }

  var $fila = $(".nuevoProducto .linea-venta").last();
  $fila.data("prefsEdicion", linea.preferencias || "");
  $fila.data("notaEdicion", linea.nota_adicional || "");
}

<?php if ($modoEdicionCuenta): ?>
var detalleCuentaEditar = <?php echo json_encode($detalleEditar, JSON_UNESCAPED_UNICODE); ?>;
$(document).ready(function() {
  if (detalleCuentaEditar && detalleCuentaEditar.length) {
    detalleCuentaEditar.forEach(function(linea) {
      agregarLineaProductoEdicion(linea);
    });
    sumarTotalPrecios();
    $(".nuevoPrecioProducto").number(true, 2);
    setTimeout(function() {
      inicializarSelect2NotasEnFila($(".nuevoProducto"));
      $(".nuevoProducto .linea-venta").each(function() {
        var $fila = $(this);
        aplicarPreferenciasYNotaEnFila(
          $fila,
          $fila.data("prefsEdicion"),
          $fila.data("notaEdicion")
        );
        $fila.removeData("prefsEdicion");
        $fila.removeData("notaEdicion");
      });
      listarProductos();
      $(".linea-venta").each(function() {
        actualizarEstadoBotonNotas($(this));
      });
    }, 100);
    if (window.PromocionesVenta) {
      PromocionesVenta.recalcular(function(){ listarProductos(); });
    }
  }
});
<?php endif; ?>

function agregarProductoAVenta(producto) {
  var esInventariable = Number(producto.inventariable) === 1;

  if(Number(producto.stock) <= 0) {
    swal({
      title: esInventariable ? "No hay stock disponible" : "Producto agotado",
      type: "error",
      confirmButtonText: "¡Cerrar!"
    });
    return;
  }

   // Bloque condicional para agregar el dropdown si producto.inventariable es 0
   let extra = '';
   
  if (!esInventariable) {
    extra = htmlNotasProductoLinea();
  }
  var formaAtencionGeneral = $("#formaAtencion").val();

  $(".nuevoProducto").append(construirHtmlLineaVenta({
    idProducto: producto.id,
    descripcion: producto.descripcion,
    codigo: producto.codigo || "",
    imagen: producto.imagen || "",
    stock: esInventariable ? producto.stock : 1,
    inventariable: esInventariable,
    precioVenta: Number(producto.precio_venta) || 0,
    precioCompra: producto.precio_compra,
    qty: 1,
    factor: 1,
    idPresentacion: 0,
    nombrePresentacion: "Unidad",
    presentaciones: producto.presentaciones || [],
    formaAtencion: formaAtencionGeneral,
    subtotalBruto: Number(producto.precio_venta || 0),
    unidadesLabel: "",
    extra: extra,
    mostrarDuplicar: true
  }));

  // Después de agregar el producto, verificar el estado actual del selector general
  var nuevoSelector = $(".nuevoProducto").find("select[name='formaAtencionDetalle']").last();
  
  if(formaAtencionGeneral !== "3") { // Si no es mixto
    nuevoSelector.prop("disabled", true); // Deshabilitar el selector del nuevo producto
  }

  setTimeout(function() {
    inicializarSelect2NotasEnFila($(".nuevoProducto .linea-venta").last());
  }, 100);

  sumarTotalPrecios();
  calcularPago();
  listarProductos();
  $(".nuevoPrecioProducto").number(true, 2);

  if (window.PromocionesVenta) {
    PromocionesVenta.recalcular(function() {
      listarProductos();
      if (typeof calcularPago === "function") calcularPago();
    });
  }
}

// Preferencias: dropdown Bootstrap (como sistema_old), sin modal
$(document).ready(function() {
  $(document).on("click", ".nota-dropdown", function(e) {
    e.stopPropagation();
  });

  $(document).on("click", ".select2-selection__choice__remove, .select2-container", function(e) {
    e.stopPropagation();
  });

  $(document).on("show.bs.dropdown", ".lv-notas-wrap", function() {
    $(this).closest("tr.linea-venta").addClass("dropdown-notas-abierto");
  });

  $(document).on("hide.bs.dropdown", ".lv-notas-wrap", function() {
    var $fila = $(this).closest("tr.linea-venta");
    $fila.removeClass("dropdown-notas-abierto");
    if (typeof actualizarEstadoBotonNotas === "function") {
      actualizarEstadoBotonNotas($fila);
    }
  });
});
</script>
<script src="vistas/js/catalogo-productos.js"></script>