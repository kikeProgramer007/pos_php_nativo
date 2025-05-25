<?php

if ($_SESSION["perfil"] == "") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

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
    justify-content: space-between;
    
    margin-bottom: 0px;
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
    background-color: #6c757d;
    cursor: not-allowed;
  }

  /* Estilos para el filtro y búsqueda */
  .catalogo-filtros {
    display: flex;
    gap: 15px;
    margin-bottom: 0px;
  }

  .catalogo-busqueda {
    flex: 1;
    max-width: 300px;
  }

  .catalogo-busqueda input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  /* Responsividad */
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
    margin-bottom: 0px;
    flex-wrap: wrap;
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
</style>

<div class="content-wrapper text-uppercase ">

  <section class="content-header">



    <h1 style="font-weight: bold; font-family: Arial, sans-serif;">
      Crear ventas
    </h1>




    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Crear venta</li>

    </ol>

  </section>

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
                  <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar productos...">
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

          <div class="box-header "></div>

          <form role="form" method="post" class="formularioVenta" id="ventaForm">

            <div class="box-body">

              <div class="">

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon">USUARIO</span>

                    <input type="text" class="form-control text-uppercase " id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================-->
             
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon">N° TICKET</span>

                    <?php

                    $item = null;

                    $ultimoNroTicket = 0;
                    if (isset($_SESSION["idArqueoCaja"])) {
                      echo'<input type="hidden" name="idArqueoCaja" value="'.$_SESSION["idArqueoCaja"].'" hidden>
                          <input type="hidden" name="idCaja" value="'.$_SESSION["idCaja"].'" hidden>
                          ';
                      $ultimoNroTicket = ControladorArqueo::ctrObtenerUltimoNroTicket($_SESSION["idArqueoCaja"]);
                      $ultimoNroTicket++;
                      echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' .$ultimoNroTicket. '" readonly>';
                    } else {
                       echo '<input type="text" class="form-control" value="0" readonly>';
                    }
                    ?>

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
                        echo "<option value='" . $value['id'] . "' " . ($value['id'] == 1 ? 'selected' : '') . ">" . $value['nombre'] . "</option>";
                      }




                      ?>

                    </select>

                    <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs text-uppercase" data-toggle="modal" data-target="#modalAgregarMesero" data-dismiss="modal">Agregar Gastos</button></span>
                    <span class="input-group-addon">
                                      <a href="arqueo-de-caja">
                   <button type="button" class="btn btn-default btn-xs text-uppercase">Cerrar Caja</button>
                 </a>
               </span>


                  </div>

                </div>

               <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================-->

                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon">CLIENTES</span>
                    <input type="text" class="form-control text-uppercase" id="cliente" name="cliente" value="" placeholder="Ingrese el Cliente (Opcional)" autocomplete="off"  >
                    <input type="hidden" id="id_cliente" name="id_cliente" value="0"/>
                  </div>
                </div>

                <div class="row ">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tipo_pago text-right">FORMA DE ATENCIÓN:</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                    <select class="form-control input-sm" id="formaAtencion" name="formaAtencion">
                        <option value="1" selected>🍽️ En Mesa</option>
                        <option value="2">🚚 Para Llevar</option>
                        <option value="3">🔀 Mixto</option>
                      </select>
                    </div>
                  </div>
                </div>
               

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->
              
                <hr style="border-top: 2px solid rgba(69, 69, 69, 0.82); margin:4px">

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

                  <div class="col-xs-6 pull-right">

                    <table style="width:100%">

                      <thead>

                        <tr>
                          <th class="text-uppercase">Total</th>
                        </tr>

                      </thead>

                      <tbody>

                        <tr>
                          <input type="hidden" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="0">

                          <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>

                          <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>

                          <td>

                            <div class="input-group">

                              <span class="input-group-addon"><i><b>Bs</b></i></span>

                              <input type="text" class="form-control " id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="0" readonly disabled required>

                              <input type="hidden" name="totalVenta" id="totalVenta">

                            </div>

                          </td>
                    
                        </tr>

                      </tbody>
                   
                    </div>
                    </table>

                  </div>

                </div>
                <hr>


                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->


                <div class="row">


                
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="tipo_pago">TIPO DE PAGO:</label>
                      <select class="form-control input-sm" id="tipoPago" name="tipoPago">
                        <option value="1">Efectivo</option>
                        <option value="2">QR</option>
                        <option value="3">Transferencia</option>
                        <option value="4">Qr y Efectivo(Mixto)</option>
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
                            <textarea class="form-control text-uppercase" id="nota" cols="100" rows="2" name="nota" aria-label="With textarea"></textarea>
                          </div>
                        </div>
                      </div>

                  </div>

                  <div class="col-md-6 cajasMetodoPago">
                    <div class="form-group ">
                      <label for="nuevoValorEfectivo">Pagado:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i><b>Bs</b></i></span>
                        <input type="text" class="form-control input-sm" id="nuevoValorEfectivo" name="nuevoValorEfectivo" placeholder="000000" required>
                      </div>
                    </div>
                    <div class="form-group " id="capturarCambioEfectivo">
                      <label for="nuevoCambioEfectivo">Cambio:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i><b>Bs</b></i></span>
                        <input type="text" class="form-control input-sm" id="nuevoCambioEfectivo" name="nuevoCambioEfectivo" placeholder="000000" readonly
                          required>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">
                </div>


              </div>

            </div>


            <div class="box-footer">
              <div class="row">
                <div class="col-xs-6 text-left">
               <!--    <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="sinImprimir" name="sinImprimir">
                    <label class="form-check-label" for="sinImprimir"> Sin Imprimir</label>
                  </div> -->
                </div>
                <div class="col-xs-6 text-right">
                  <button type="button" id="guardarVentaBtn" class="btn btn-primary pull-right">Guardar venta</button>
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
                  <select class="form-control input-sm" id="id_tipo_gasto" name="id_tipo_gasto" required>

                    <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorTipoGasto::ctrMostrarTipoGasto($item, $valor);
                      foreach ($categorias as $key => $value) {
                        echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                      }
                    ?>
                  </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">DESCRIPCIÓN</span>
                <input type="text" class="form-control input-sm" name="descripcion_gasto" id="descripcion_gasto" placeholder="Ingresar Descripción" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA FECHA DE GASTO-->
       
             <div class="form-group">
              <div class="input-group">

                <!-- ENTRADA PARA LA FECHA DE GASTO-->
                <span class="input-group-addon">FECHA DE GASTO</span>
                <div class="input-group date">
                  <input type="date" id="fecha_gasto" name="fecha_gasto" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm" required />
                </div>

                <!-- ENTRADA PARA EL MONTO-->
                <span class="input-group-addon">MONTO</span>
                <input type="number" class="form-control input-sm" name="monto_gasto" placeholder="Ingresar Monto" required>
           
              </div>
            </div>
        

            <!-- ENTRADA PARA EL TIPO DE PAGO -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">FORMAS DE PAGO</span>
                <select class="form-control input-sm" id="tipo_pago_gasto" name="tipo_pago_gasto">
                        <option value="1">Efectivo</option>
                        <option value="2">QR</option>
                        <option value="3">Transferencia</option>
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

<script>
  const idUsuario = <?php echo $_SESSION["id"]; ?>;
</script>
<script src="vistas/js/validar-caja.js"></script>

<script>
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

document.getElementById("guardarVentaBtn").addEventListener("click", function(e) {
  e.preventDefault(); // Evita el submit tradicional

  var totalVenta = Number($('#nuevoTotalVenta').val());
  var efectivo = Number($('#nuevoValorEfectivo').val());
  var cambio = Number(efectivo) - totalVenta;
  if(!efectivo){
    swal({
      type: "error",
      title: "El campo pagado es requerido",
      showConfirmButton: true,
      confirmButtonText: "Cerrar"
    });
    return;
  }

  if(cambio >= 0 ){
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
      success: function(respuesta) {
        if(respuesta.status == "ok") {
          imprimirFactura(respuesta.idVenta);
           window.location.href = "crear-venta"; 
        } else {
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
  } else {
    swal({
      type: "error",
      title: "Lo que Canceló debe ser igual o mayor al total",
      showConfirmButton: true,
      confirmButtonText: "Cerrar"
    });
  }
});

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
                      <option value="1">Sin fideo ❌</option>
                      <option value="2">Sin arroz ❌</option>
                      <option value="3">Sin papas ❌</option>
                      <option value="4">Más arroz ✅</option>
                      <option value="5">Más fideo ✅</option>
                      <option value="6">Más papas ✅</option>
                      <option value="7">Solo papas</option>
                      <option value="8">Solo arroz</option>
                      <option value="9">Solo fideo </option>
                      <option value="10">Poco arroz</option>
                      <option value="11">Poco fideo</option>
                      <option value="12">Poca papas</option>
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
        <input type="number" class="form-control input-sm nuevaCantidadProducto" 
               name="nuevaCantidadProducto" min="1" value="1" 
               stock="${producto.stock}" data-idProducto="${producto.id}" required>
      </div>

      <!-- Columna para precio -->
      <div class="col-xs-4 ingresoPrecio" style="padding-left:0px">
        <div class="input-group">
          <span class="input-group-addon"><i><b>Bs</b></i></span>
          <input type="text" class="form-control input-sm nuevoPrecioProducto" 
                 precioReal="${producto.precio_venta}" name="nuevoPrecioProducto" 
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
  listarProductos();
  $(".nuevoPrecioProducto").number(true, 2);
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