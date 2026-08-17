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
  /*  min-width: 280px;*/
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
  }

  .nota-dropdown.show {
    display: block;
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

  /* Asegurar que el dropdown de Select2 esté por encima de otros elementos */
  .select2-dropdown {
    z-index: 10001 !important;
  }

  /* Ajustar el tamaño del botón de notas */
  .btn-xs.dropdown-toggle {
    padding: 1px 5px;
  }

  /* Estilos para el catálogo de productos */
  .catalogo-productos {
    padding: 0px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }

  .catalogo-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    justify-content: space-between;
    margin-bottom: 0;
    padding: 15px;
    border-bottom: 2px solid #f4f4f4;
  }

  .catalogo-header h3 {
    margin: 0;
    color: #333;
    font-weight: 600;
  }

  /*.catalogo-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(150px, 1fr));
    gap: 20px;
    padding: 15px;
  } */

  .thumbnail {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 0px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    padding: 0px;
    background-color: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }

  .thumbnail:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.5), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }

  .producto-imagen {
    object-fit: cover;
    border-radius: 4px;
    margin: 0 auto 10px;
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
    color: white;
    border: none;
    padding: 5px 15px;
    border-radius: 4px;
    width: 100%;
    transition: background-color 0.3s ease;
  }

  .btn-agregar:hover {
    background-color: #218838;
  }

  .btn-agregar.disabled {
    background-color: #7d6c6c;
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
  }

  .promo-etiqueta {
    display: inline-block;
    max-width: 100%;
    font-size: 11px;
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

  /* Resumen compacto de totales */
  .resumen-venta-totales {
    width: 100%;
    padding: 2px 0 4px;
  }

  .resumen-fila {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 8px;
    padding: 3px 0;
    line-height: 1.25;
  }

  .resumen-label {
    flex: 1 1 auto;
    min-width: 0;
    font-size: 13px;
    color: #555;
    white-space: nowrap;
  }

  .resumen-monto {
    flex: 0 0 auto;
    font-size: 13px;
    color: #333;
    white-space: nowrap;
    text-align: right;
  }

  .resumen-fila-descuento .resumen-monto {
    color: #c0392b;
    font-weight: 600;
  }

  .resumen-fila-descuento .resumen-label .fa {
    font-size: 11px;
    margin-left: 3px;
    color: #999;
    cursor: help;
  }

  .resumen-fila-total {
    margin-top: 4px;
    padding-top: 6px;
    border-top: 1px dashed #ccc;
  }

  .resumen-fila-total .resumen-label {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    text-transform: uppercase;
  }

  .resumen-fila-total .resumen-monto {
    font-size: 16px;
    font-weight: 700;
    color: #28a745;
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
      border-top-left-radius: 10px !important;
      border-top-right-radius: 10px !important;
      width: 100%;
  }

  .first {
      position: absolute;
      width: 100%;
      padding: 9px
  }

  .dress-name {
      font-size: 13px;
      font-weight: bold;
      width: 75%
  }

  .new-price {
      font-size: 13px;
      font-weight: bold;
      color: red
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

  /* Cabecera de venta: datos a la izquierda, acciones de caja a la derecha */
  .caja-cabecera-venta {
    margin-bottom: 0;
  }

  .caja-cabecera-venta .form-group {
    margin-bottom: 8px;
  }

  .caja-cabecera-venta .col-sm-8 .form-group:last-child {
    margin-bottom: 0;
  }

  .caja-acciones-botones {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .caja-acciones-botones .btn {
    width: 100%;
    white-space: nowrap;
  }

  .forma-atencion-venta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 12px;
    margin-bottom: 10px;
  }

  .forma-atencion-venta label {
    margin: 0;
    white-space: nowrap;
    flex-shrink: 0;
    font-weight: 700;
  }

  .forma-atencion-venta select {
    flex: 0 1 260px;
    width: 260px;
    max-width: 100%;
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

  /* Estilos para campos de pago y cambio */
  .cajasMetodoPago .form-group label {
    font-size: 15px;
    margin-bottom: 5px;
  }
  .cajasMetodoPago .form-control {
    font-size: 22px;
    font-weight: bold;
    height: 45px;
  }
  .cajasMetodoPago .input-group-addon {
    font-size: 20px;
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

  .cantidad-stepper {
    display: flex;
    align-items: stretch;
    gap: 0;
    width: 100%;
  }

  .cantidad-stepper .btn-cantidad-ajuste {
    width: 30px;
    min-width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0;
  }

  .cantidad-stepper .btn-cantidad-ajuste.btn-minus {
    background-color: #f01111;
    border-color: #9e9e9e;
    color: #fff;
  }

  .cantidad-stepper .btn-cantidad-ajuste.btn-plus {
    background-color: #28a745;
    border-color: #1f8a39;
    color: #fff;
  }

  .cantidad-stepper .nuevaCantidadProducto {
    flex: 1 1 auto;
    min-width: 0;
    height: 30px;
    text-align: center;
    padding-left: 4px;
    padding-right: 4px;
    border-left: 0;
    border-right: 0;
    border-radius: 0;
  }

  .cantidad-stepper .nuevaCantidadProducto::-webkit-outer-spin-button,
  .cantidad-stepper .nuevaCantidadProducto::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .cantidad-stepper .nuevaCantidadProducto[type=number] {
    -moz-appearance: textfield;
  }

  .cantidad-stepper .btn-minus {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
  }

  .cantidad-stepper .btn-plus {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
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

      <div class="col-lg-7 hidden-md hidden-sm hidden-xs  ">

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
              <div style="padding-left: 15px;" id="catalogoProductos">
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

      <div class="col-lg-5 col-xs-12">

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
                        $selectedMesero = $modoEdicionCuenta && $ventaEditar["id_mesero"] == $value['id'] ? 'selected' : ($value['id'] == 1 ? 'selected' : '');
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

                      if (Permisos::tiene("meseros.crear")) {
                        echo '<button type="button" class="btn btn-default btn-sm text-uppercase" data-toggle="modal" data-target="#modalAgregarMesero">Agregar gastos</button>';
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
              
                <hr style="border-top: 2px solid rgba(69, 69, 69, 0.82); margin:8px 0 6px">

                <div class="form-group row nuevoProducto  ">

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
                        <span class="resumen-label">TOTAL</span>
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
                            <input type="text" class="form-control" id="nuevoValorEfectivo" name="nuevoValorEfectivo" placeholder="0" min="0" step="0.01"  inputmode="decimal"  required>
                          </div>
                        </div>
                      </div>

                      <div class="form-group position-relative" id="contenedorQR">
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
                          <option value="1"selected>CAJA Y COCINA</option>
                          <option value="2">CAJA</option>
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

<div id="modalAgregarMesero" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

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
            <div class="gastos-flex-container">
              <div class="form-group fecha-gasto-container">
                <div class="input-group">
                  <span class="input-group-addon">FECHA DE GASTO</span>
                  <input type="date" id="fecha_gasto" name="fecha_gasto" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
                </div>
              </div>

              <div class="form-group monto-gasto-container">
                <div class="input-group">
                  <span class="input-group-addon">MONTO </span>
                  <input type="number" class="form-control" name="monto_gasto" placeholder="Ingresa el monto" min="1" step="0.01">
                </div>
              </div>
            </div>
        

            <!-- ENTRADA PARA EL TIPO DE PAGO -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">FORMAS DE PAGO</span>
                <select class="form-control" id="tipo_pago_gasto" name="tipo_pago_gasto">
                        <option value="1">Efectivo</option>
                        <option value="2">QR</option>
                        <option value="4">Qr y Efectivo(Mixto)</option>
                      </select>
              </div>
            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
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
    else { // Transferencia
      efectivo.style.display = "none";
      qr.style.display = "none";
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

document.getElementById("guardarVentaBtn") && document.getElementById("guardarVentaBtn").addEventListener("click", function(e) {
  e.preventDefault(); // Evita el submit tradicional

  var totalVenta = Number($('#nuevoTotalVenta').val());
  var efectivo = Number($('#nuevoValorEfectivo').val());
  var tipopagovalue = $('#tipoPago').val();
  
  switch(tipopagovalue) {
    case "1": // Efectivo
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
      var valorQR = Number($('#nuevoValorQR').val());
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
      var valorQR = Number($('#nuevoValorQR').val());
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
  $("#guardarVentaBtn").prop("disabled", true);
    $.ajax({
      url: "ajax/ventas.ajax.php", // Cambia aquí
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success:  function(respuesta) {
        if(respuesta.status == "ok") {
            imprimirVentaSegunTipo(respuesta.idVenta);
        }else if(respuesta.status == "recargar"){
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
          $("#guardarVentaBtn").prop("disabled", false);
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
        $("#guardarVentaBtn").prop("disabled", false);
        
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

function enviarVentaAjax(extraData, onSuccess) {
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
    if (!validarProductosEnVenta()) return;
    listarProductos();
    $("#cuentaPendienteBtn").prop("disabled", true);
    enviarVentaAjax({ estadoPago: "PENDIENTE" }, function(respuesta) {
      swal({
        type: "success",
        title: respuesta.mensaje,
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      }).then(function() {
        var idImpresion = $("#idTipoImpresion").val();
        if (idImpresion == "2" ) {
          imprimirSoloCaja(respuesta.idVenta).finally(function() {
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
      });
    });
    $("#cuentaPendienteBtn").prop("disabled", false);
  });
}

var actualizarCuentaBtn = document.getElementById("actualizarCuentaBtn");
if (actualizarCuentaBtn) {
  actualizarCuentaBtn.addEventListener("click", function(e) {
    e.preventDefault();
    if (!validarProductosEnVenta()) return;
    listarProductos();
    $("#actualizarCuentaBtn").prop("disabled", true);
    enviarVentaAjax({ actualizarCuentaPendiente: "1" }, function(respuesta) {
      var idsNuevos = respuesta.idsDetalleNuevos || [];
      swal({
        type: "success",
        title: respuesta.mensaje,
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      }).then(function() {
        if (idsNuevos.length > 0) {
          var idsDetalle = idsNuevos.join(",");
          var idImpresion = $("#idTipoImpresion").val();
          if (idImpresion == "2") {
            imprimirSoloCaja(respuesta.idVenta, idsDetalle).finally(function() {
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
      });
    });
    $("#actualizarCuentaBtn").prop("disabled", false);
  });
}

function agregarLineaProductoEdicion(linea) {
  var formaAtencionLinea = linea.forma_atencion === "LL" ? "2" : "1";
  var formaAtencionGeneral = $("#formaAtencion").val();
  var stockLinea = parseInt(linea.stock_actual || 0) + parseInt(linea.cantidad || 0);
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

  $(".nuevoProducto").append(`
    <div class="row" style="padding:4px 15px">
      <div class="col-xs-4" style="padding-right:0px">
        <div class="input-group">
          <span class="input-group-addon" style="padding: 0px 4px">
           <button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="${linea.id_producto}">
              <i class="fa fa-times"></i>
            </button>
          </span>
          <input type="text" class="form-control input-sm nuevaDescripcionProducto text-uppercase"
                 idProducto="${linea.id_producto}" data-idDetalle="${linea.id}" name="agregarProducto"
                 value="${linea.producto}" readonly required>
        </div>
      </div>
      <div class="col-xs-2" style="padding-right:0px">
        <select class="form-control input-sm" name="formaAtencionDetalle" style="padding:2px">
          <option value="1" ${formaAtencionLinea === "1" ? "selected" : ""}>🍽️ M</option>
          <option value="2" ${formaAtencionLinea === "2" ? "selected" : ""}>🚚 LL</option>
        </select>
      </div>
      <div class="col-xs-2">
        <div class="cantidad-stepper">
          <button type="button" class="btn btn-default btn-sm btn-cantidad-ajuste btn-minus" data-action="decrementar" title="Disminuir cantidad">
            <i class="fa fa-minus"></i>
          </button>
          <input type="number" class="form-control input-sm nuevaCantidadProducto"
                 name="nuevaCantidadProducto" min="1" value="${linea.cantidad}"
                 stock="${stockLinea}" data-idProducto="${linea.id_producto}" required>
          <button type="button" class="btn btn-success btn-sm btn-cantidad-ajuste btn-plus" data-action="incrementar" title="Aumentar cantidad">
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="col-xs-4 ingresoPrecio" style="padding-left:0px">
        <div class="input-group">
          <span class="input-group-addon"><i><b>Bs</b></i></span>
          <input type="text" class="form-control input-sm nuevoPrecioProducto"
                 precioReal="${precioOrigNum}" precioOriginal="${precioOrigNum}"
                 data-promo='${promoAttr}' data-subtotal-final="${subtotalFinal}" data-precio-final="${precioFinal}"
                 name="nuevoPrecioProducto"
                 value="${subtotalOriginal.toFixed(2)}" readonly required>
          <input type="hidden" precioRealCompra="${linea.precio_compra}"
                 name="nuevoPrecioCompraProducto" class="nuevoPrecioCompraProducto"
                 value="${linea.precio_compra}">
        </div>
      </div>
    </div>`);

  var nuevoSelector = $(".nuevoProducto").find("select[name='formaAtencionDetalle']").last();
  if (formaAtencionGeneral !== "3") {
    nuevoSelector.prop("disabled", true);
  }
}

<?php if ($modoEdicionCuenta): ?>
var detalleCuentaEditar = <?php echo json_encode($detalleEditar, JSON_UNESCAPED_UNICODE); ?>;
$(document).ready(function() {
  if (detalleCuentaEditar && detalleCuentaEditar.length) {
    detalleCuentaEditar.forEach(function(linea) {
      agregarLineaProductoEdicion(linea);
    });
    sumarTotalPrecios();
    listarProductos();
    $(".nuevoPrecioProducto").number(true, 2);
    if (window.PromocionesVenta) {
      PromocionesVenta.recalcular(function(){ listarProductos(); });
    }
  }
});
<?php endif; ?>

function agregarProductoAVenta(producto) {
  if(producto.stock == 0) {
    swal({
      title: "No hay stock disponible",
      type: "error",
      confirmButtonText: "¡Cerrar!"
    });
    return;
  }

   // Bloque condicional para agregar el dropdown si producto.inventariable es 0
   let extra = '';
   
  if (producto.inventariable === 0) {
    extra = `
      <span class="input-group-addon" style="padding: 0px 4px">
        <div class="dropdown">
          <button class="btn btn-default btn-xs dropdown-toggle" type="button" 
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-file-text-o"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-right nota-dropdown">
            <li style="width: 280px; padding: 10px;">
              <form class="noteForm" onsubmit="return false;">
                <label for="nota">Preferencias</label>
                <div class="form-group">
                  <select class="select2-nota form-control input-sm nota-producto" multiple="multiple" name="states[]">
                      <option value="1">Bien cocido </option>
                      <option value="2">Tres cuartos </option>
                      <option value="3">Término medio </option>
                      <option value="4">Medio rojo 🥩</option>
                      <option value="5">Rojo (Inglés) </option>

                      <!-- Sin... -->
                      <option value="6">Sin yuca ❌</option>
                      <option value="7">Sin arroz ❌</option>
                      <option value="8">Sin ensalada ❌</option>
                      <option value="9">Sin chorizo ❌</option>
                      <option value="10">Sin Cordon Blue ❌</option>
                      <option value="11">Sin papas fritas ❌</option>
                      <option value="12">Sin arroz con queso ❌</option>

                      <!-- Más... -->
                      <option value="13">Más yuca ✅</option>
                      <option value="14">Más arroz ✅</option>
                      <option value="15">Más ensalada ✅</option>
                      <option value="16">Más chorizo ✅</option>
                      <option value="17">Más Cordon Blue ✅</option>
                      <option value="18">Más papas fritas ✅</option>
                      <option value="19">Más arroz con queso ✅</option>

                      <!-- Solo... -->
                      <option value="20">Solo yuca</option>
                      <option value="21">Solo arroz</option>
                      <option value="22">Solo ensalada</option>
                      <option value="23">Solo papas fritas</option>
                      <option value="24">Solo chorizo</option>

                      <!-- Poco... -->
                      <option value="25">Poca yuca</option>
                      <option value="26">Poco arroz</option>
                      <option value="27">Poca ensalada</option>
                      <option value="28">Pocas papas fritas</option>
                      <option value="29">Poco chorizo</option>

                      <!-- Otros -->
                      <option value="30">Salsa aparte</option>
                      <option value="31">Ají aparte 🌶️</option>
                      <option value="32">Sin sal</option>
                      <option value="33">Poca sal</option>
                      <option value="34">Bien sazonado</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="descripcion">Nota Adicional (Opcional)</label>
                  <textarea class="form-control input-sm nota-adicional" rows="2" 
                            placeholder="Nota adicional..."></textarea>
                </div>
              </form>
            </li>
          </ul>
        </div>
      </span>`;
  }
  var formaAtencionGeneral = $("#formaAtencion").val();

  $(".nuevoProducto").append(`
    <div class="row" style="padding:4px 15px">
      <!-- Columna para descripción y botones -->
      <div class="col-xs-4" style="padding-right:0px">
        <div class="input-group">
          <span class="input-group-addon" style="padding: 0px 4px">
           <button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="${producto.id}">
              <i class="fa fa-times"></i>
            </button>
          </span>
          <input type="text" class="form-control input-sm nuevaDescripcionProducto text-uppercase"
                 idProducto="${producto.id}" name="agregarProducto" 
                 value="${producto.descripcion}" readonly required>
                 ${extra}
        </div>
      </div>

      <!-- Columna para tipo de servicio -->
      <div class="col-xs-2" style="padding-right:0px">
        <select class="form-control input-sm" name="formaAtencionDetalle" id="formaAtencionDetalle" style="padding:2px" >
          <option value="1" ${formaAtencionGeneral === "1" ? "selected" : ""}>🍽️ M</option>
          <option value="2" ${formaAtencionGeneral === "2" ? "selected" : ""}>🚚 LL</option>
        </select>
      </div>

      <!-- Columna para cantidad -->
      <div class="col-xs-2">
        <div class="cantidad-stepper">
          <button type="button" class="btn btn-default btn-sm btn-cantidad-ajuste btn-minus" data-action="decrementar" title="Disminuir cantidad">
            <i class="fa fa-minus"></i>
          </button>
          <input type="number" class="form-control input-sm nuevaCantidadProducto" 
                 name="nuevaCantidadProducto" min="1" value="1" 
                 stock="${producto.stock}" data-idProducto="${producto.id}" required>
          <button type="button" class="btn btn-success btn-sm btn-cantidad-ajuste btn-plus" data-action="incrementar" title="Aumentar cantidad">
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>

      <!-- Columna para precio -->
      <div class="col-xs-4 ingresoPrecio" style="padding-left:0px">
        <div class="input-group">
          <span class="input-group-addon"><i><b>Bs</b></i></span>
          <input type="text" class="form-control input-sm nuevoPrecioProducto" 
                 precioReal="${producto.precio_venta}" precioOriginal="${producto.precio_venta}"
                 name="nuevoPrecioProducto" 
                 value="${producto.precio_venta}" readonly required>
          <input type="hidden" precioRealCompra="${producto.precio_compra}" 
                 name="nuevoPrecioCompraProducto" class="nuevoPrecioCompraProducto" 
                 value="${producto.precio_compra}">
          <span class="input-group-addon" style="padding: 0px 4px">
            <button type="button" class="btn btn-primary btn-xs" title="Duplicar Producto"">
            <i class="fa fa-files-o" aria-hidden="true"></i>
            </button>
          </span>
        </div>
      </div>
    </div>`);


 
  // Después de agregar el producto, verificar el estado actual del selector general
  var nuevoSelector = $(".nuevoProducto").find("select[name='formaAtencionDetalle']").last();
  
  if(formaAtencionGeneral !== "3") { // Si no es mixto
    nuevoSelector.prop("disabled", true); // Deshabilitar el selector del nuevo producto
  }

  // Inicializar Select2 para las notas con un timeout para asegurar que el DOM esté listo
  setTimeout(function() {
    $('.select2-nota').each(function() {
      if (!$(this).hasClass('select2-hidden-accessible')) {
        $(this).select2({
          theme: "classic",
          multiple: true,
          width: '100%',
          dropdownParent: $(this).closest('.nota-dropdown'),
          language: {
            noResults: function() {
              return "No hay resultados";
            }
          }
        }).on('change', function() {
          listarProductos();
        });
      }
    });
  }, 100);

  // Agregar evento change para la descripción
  $('.nota-adicional').on('change keyup', function() {
    listarProductos();
  });

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

// Actualizar el código de inicialización de Select2
$(document).ready(function() {
  // Prevenir que el dropdown se cierre al hacer clic dentro
  $(document).on('click', '.nota-dropdown', function(e) {
    e.stopPropagation();
  });
  
  $(document).on('click', '.select2-selection__choice__remove', function(e) {
    e.stopPropagation();
  });

  // Detener la propagación de eventos en el select2
  $(document).on('click', '.select2-container', function(e) {
    e.stopPropagation();
  });

});
</script>
<script src="vistas/js/catalogo-productos.js"></script>