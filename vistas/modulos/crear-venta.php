<?php

if ($_SESSION["perfil"] == "") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>
 <style>
    .dropdown-menu {
      padding: 15px; /* Para darle un buen espaciado */
      min-width: 300px; /* Ancho mínimo del formulario */
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
      -webkit-line-clamp: 2;
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
  </style>

<style>

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
              <div class="" id="catalogoProductos">
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

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control text-uppercase " id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                    <?php

                    $item = null;
                    $valor = null;


                    $ventas = ControladorVentas::ctrMostrarVentas($item, $valor,);

                    if (!$ventas) {

                      echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="10001" readonly>';
                    } else {

                      foreach ($ventas as $key => $value) {
                      }



                      $codigo = $value["codigo"] + 1;

                      echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' . $codigo . '" readonly>';
                    }

                    ?>


                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL MESERO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

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

                    <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs text-uppercase" data-toggle="modal" data-target="#modalAgregarMesero" data-dismiss="modal">Agregar Meseros</button></span>

                  </div>

                </div>

               <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================-->

                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control text-uppercase" id="cliente" name="cliente" value="" placeholder="Ingrese el Cliente" autocomplete="off"  >
                    <input type="hidden" id="id_cliente" name="id_cliente" value="0"/>
                  </div>
                </div>

                <div class="row ">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tipo_pago">FORMA DE ATENCIÓN:</label>
                      <select class="form-control input-sm" id="formaAtencion" name="formaAtencion">
                        <option value="1">🥡 Para Llevar</option>
                        <option value="2" selected>🍽️ En Mesa</option>
                        <option value="3" >🔀 Mixto</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <select class="select2 text-uppercase form-control input-sm" name="states[]" multiple="multiple">
                            <option value="1">✔️Solo Arroz</option>
                            <option value="2">✔️Solo Fideo</option>
                            <option value="3">❌No Fideo</option>
                            <option value="4">❌No Papas</option>
                            <option value="5">✔️Solo Papas</option>
                            <option value="6">➕ Más fideos</option>
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

                              <input type="text" class="form-control " id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="00000" readonly required>

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


          


                <div class="dropdown">
                  <!-- Botón que activa el dropdown -->
                  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-file-text-o" aria-hidden="true"></i><span class="caret"></span>
                  </button>
                  <!-- Dropdown con el formulario -->
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                      <form id="noteForm" onsubmit="return false;">
                        <div class="form-group">
                          <label for="noteTitle">Título</label>
                          <input type="text" class="form-control" id="noteTitle" placeholder="Título de la nota">
                        </div>
                        <div class="form-group">
                          <label for="noteType">Tipo</label>
                            <select class="select2 text-uppercase form-control input-sm" name="states[]" multiple="multiple">
                                <option value="1">✔️Solo Arroz</option>
                                <option value="2">✔️Solo Fideo</option>
                                <option value="3">❌No Fideo</option>
                                <option value="4">❌No Papas</option>
                                <option value="5">✔️Solo Papas</option>
                                <option value="6">➕ Más fideos</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="noteDescription">Descripción</label>
                          <textarea class="form-control" id="noteDescription" rows="3" placeholder="Escribe tu nota..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Guardar Nota</button>
                      </form>
                    </li>
                  </ul>
                </div>



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
                              <label class="input-group-text">NOTA (OPCIONAL)</label>
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
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="sinImprimir" name="sinImprimir">
                    <label class="form-check-label" for="sinImprimir"> Sin Imprimir</label>
                  </div>
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

          <h4 class="modal-title">Agregar Mesero</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA7ElEQVR4nMXUwSqEURQH8B9Zi2RjOytZDAtLXsGSKWvvIUuyoMGChWnK2htYKmXlHUQRK5GiW3dqFue75Q459a9b5/Trfl+nyz/UBFax/hvYGu7xlXOIdi22jI8hbJB3LNaA5wE2SLcGvCqAlzXgaQHs1YBHBXC/BuwWwIMacBJPAZbWaFplHQfgnhFqLgCnRgHbAdiqgcawggXs4AGP2MU8ln7yEGziLt/mDWf501NO8Jp7t/nBGG/CZnHTsCbbOVHvGjMReFHYu42cpn4/Al8ahj/zDdLupXM08xyBHWwFGX5Y0zmaSf/9b+obeeR7t6oVkbEAAAAASUVORK5CYII=">
                </span>

                <input type="text" class="form-control input-lg" name="nuevoMesero" placeholder="Ingresar Nombre" required>

              </div>

            </div>


            <!-- ENTRADA PARA EL CI/NIT -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABQklEQVR4nO3UPUoDURQF4A8bQYm/VTobcQMuwDbaxeAK1C2kFhUUJBYBdQEK7kAsUpgtiIXaGS0EQdEFRB48wzBM0JgZsPDAYX7OnXfeve/O5R9/FcvooJsjO6gkTfI26EY+JE26BbKHfgGXOMBTUSbthD6KqyJMTpIBOEzp87IxP4jJWerj8yIyaaVM2in9CHsZPB704KtRX83QGqhnsDGoSTPqzSK66xU7mI56uO7iLa+Dv0W5zwJl3OWRSbtPreuR6QboxrFUQymymtjMt2fyE3Ywk8p4BAt4xFIeA7KWUdZ9nGINF18vK3Fi/sakhDmMxbXWcYMpTOLZkHjHBLZwjc242WAqmoSYodBK/LAbeMFiQq9lTI6BsRJbfjY+jye00Az3MWZobMcuCoccShcYMggGQcsNYbehLB+R4b6XwSerJzNlafpJlwAAAABJRU5ErkJggg==">
                </span>

                <input type="text" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar CI/NIT" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABH0lEQVR4nO3VsUoDQRAG4E877fI4omBnJwiSLoWVr2GnFlqHNNaSB1CIPoEE7BQ0NtHKQgmCJjbKwgjHYQjkNsHCgZ+df2bY/3Znd4/qto4vNMzQtuchsltVZAmbOMAZrvAQY+L7OK0isoG3mCDhE+0CPgu5qUUapUmGOCxgmFOkV1pBu4ReDpHmhLrmv8jct6s3puHnuMBjDpF3jErHtXis33OdrkWs4T5iaVzFQq7tSl/7EvhZ0agQG+YQuUELr+iH3w/einy2y3gbzRZj4nJt1zO6+MAg/EHwbuQri3RQxxOuw78OXo98ZZET1HCHy/Avg9cin+2p/5qAv7+Szix7svXLL3YcUl2qn8qWsYId7OE4LuBR8BRP+VQ31r4BxB63UdWtCsYAAAAASUVORK5CYII=">

                </span>

                <input type="number" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" required>


              </div>

            </div>


            <!-- ENTRADA PARA LA DIRECCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABbklEQVR4nK2WTSsFURjHf7lsKTbiA0jJwku3fAPKgpXylkSy8ta1lh17PoEkScpGsqB0LdhStnRtZOflRkanHnV7OnNe7p1/PZ2m+f/nN3POzHMG/GoH1oEroASUZTTHBaCNGtQAbALvQOIoc34DqI8FNAEXnosnqs4lF6QccBoJSKTOJO/VmiX8BewAg0AvMATsyvpo73LINL2p0BPQleLvBp6V/xVodEGmVOAH6PHcWJ/4KnMTrsC+Mh8QpkOV23OZb5R5NhAyp3JFl/lRmUcCIaMqZ66TqjtlXgqErKjcbcyaXAdCijFrsmB576c9gBlLZt4VaLF8YGWB1ymvOV5M8Tf7Hv0kpWXcA1sy/9vAQ4rvmADlgd8qe1cCDIRAXE+TeOqICHVKU4wBfAIdRKoQCVmlCuVkiw0BXFrevmC1Sqt3AUryH1CT8jLfiaU+gH4y0jDwrQBmDzGNMVNNVmxOZhzPGvCvMeBFxmD9Ac+v9APTJwF8AAAAAElFTkSuQmCC">

                </span>

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>




          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-" style="background:#6c757d; color:white">Guardar Mesero </button>

        </div>

      </form>


      
      <?php

      $crearMesero = new ControladorMeseros();
      $crearMesero->ctrCrearMesero();

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

document.getElementById("guardarVentaBtn").addEventListener("click", function() {
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

  if(cambio>=0){
    document.getElementById("ventaForm").submit();
  }else{
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

  $(".nuevoProducto").append(`
    <div class="row" style="padding:5px 15px">
      <div class="col-xs-6" style="padding-right:0px">
        <div class="input-group">
          <span class="input-group-addon" style="padding: 0px 4px 0px 4px;">
            <button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="${producto.id}">
              <i class="fa fa-times"></i>
            </button>
          </span>
          <input type="text" class="form-control input-sm nuevaDescripcionProducto text-uppercase"
                 idProducto="${producto.id}" name="agregarProducto" 
                 value="${producto.descripcion}" readonly required>
        </div>
      </div>
      <div class="col-xs-3">
        <input type="number" class="form-control input-sm nuevaCantidadProducto" 
               name="nuevaCantidadProducto" min="1" value="1" 
               stock="${producto.stock}" nuevoStock="${Number(producto.stock-1)}" required>
      </div>
      <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
        <div class="input-group">
          <span class="input-group-addon"><i><b>Bs</b></i></span>
          <input type="text" class="form-control input-sm nuevoPrecioProducto" 
                 precioReal="${producto.precio_venta}" name="nuevoPrecioProducto" 
                 value="${producto.precio_venta}" readonly required>
          <input type="hidden" precioRealCompra="${producto.precio_compra}" 
                 name="nuevoPrecioCompraProducto" class="nuevoPrecioCompraProducto" 
                 value="${producto.precio_compra}">
        </div>
      </div>
    </div>`);

  sumarTotalPrecios();
  listarProductos();
  $(".nuevoPrecioProducto").number(true, 2);
}
</script>
<script src="vistas/js/catalogo-productos.js"></script>