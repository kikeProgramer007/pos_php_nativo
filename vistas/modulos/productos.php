<?php

if ($_SESSION["perfil"] == "Vendedor") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>




<div class="content-wrapper text-uppercase ">

  <section class="content-header">


    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar productos

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="f-a fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar productos</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <!--  <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
    

          Agregar producto

        </button> -->

        <a href="agregar-producto" class="btn btn-primary">
          <i class="fa fa-plus"></i>
          Agregar Producto
        </a>
        &nbsp;

        <a class="btn btn-primary" target="_blank" href="reporte_producto.php">
          <i class="fa fa-print"></i>
          <span class="icon-name"> Imprimir </span>
        </a>
        &nbsp;

        <a class="btn btn-danger" href="productos-eliminados">
          <i class="fa fa-trash"></i>
          <span> Eliminados </span>
        </a>

        <div class="box-body">

          <table class="table table-bordered table-striped dt-responsive tablaProductos text-uppercase " width="100%">

            <thead>

              <tr>

                <th style="width:10px">#</th>
                <th style="width:10px">Imagen</th>
                <th>Código</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Agregado</th>
                <th>Acciones</th>

              </tr>

            </thead>



          </table>
          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </div>

      </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

<div id="modalAgregarProducto" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA SELECCIONAR LA CATEGORIA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <select class="form-control input-lg select2" id="nuevaCategoria" name="nuevaCategoria" required>

                  <option value="">Categoria</option>

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



            <!-- ENTRADA PARA EL CÓDIGO    DE PRODUCTOOOOOOO -->

            <div class="form-group">

              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-code"></i></span>

                <input type="text" class="form-control input-lg" id="nuevoCodigo" name="nuevoCodigo"
                  placeholder="código" readonly required>

              </div>

            </div>


            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaDescripcion" placeholder="descripción" required>

              </div>

            </div>



            <!-- ENTRADA PARA La STOCK -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-check"></i></span>


                <input value="0" readonly type="number" class="form-control input-lg" name="nuevoStock" min="0" placeholder="cantidad" required>

              </div>

            </div>

            <!--  ENTRADA PARA PRECIO COMPRAS -->
            <div class="form-group row">
              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>

                  <input type="number" class="form-control input-lg" id="nuevoPrecioCompra" name="nuevoPrecioCompra" min="0" step="any" placeholder="Precio Compra" required>
                </div>
              </div>


              <!-- ENTRADA PARA PRECIO VENTA -->
              <div class="col-xs-6">
                <div class="input-group">

                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>

                  <input type="number" class="form-control input-lg" id="nuevoPrecioVenta" name="nuevoPrecioVenta" min="0" step="any" placeholder="Precio Venta" required>

                </div>
                <br>

                <!-- CHECKBOX PARA PORCENTAJE -->

                <div class="col-xs-6">

                  <div class="form-group">

                    <label>

                      <input type="checkbox" class="minimal porcentaje">
                      Utilizar procentaje
                    </label>

                  </div>

                </div>

                <!-- ENTRADA PARA PORCENTAJE -->

                <div class="col-xs-6" style="padding:0">

                  <div class="input-group">

                    <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>

                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                  </div>

                </div>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

            <div class="form-group">

              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn" style="background:#6c757d; color:white">Guardar producto</button>

        </div>

      </form>

      <?php

      $crearProducto = new ControladorProductos();
      $crearProducto->ctrCrearProducto();

      ?>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

<div id="modalEditarProducto" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA SELECCIONAR LA CATEGORIA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CATEGORIA</span>

                <select class="form-control input-lg" name="editarCategoria" readonly required>

                  <option id="editarCategoria"></option>

                </select>

              </div>

            </div>

            <!-- ENTRADA PARA EL CÓDIGO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CODIGO</span>

                <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" placeholder="Ingresar código" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">DESCRIPCIÓN</span>

                <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion" required>

              </div>

            </div>


            <!-- ENTRADA PARA La STOCK -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CANTIDAD</span>

                <input class="form-control input-lg" id="editarStock" name="editarStock" min="0" required>

              </div>

            </div>

            <!-- ENTRADA PARA PRECIO COMPRA -->

            <div class="form-group row">
              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">PRECIO_COMPRA</span>

                  <input type="number" class="form-control input-lg" id="editarPrecioCompra" name="editarPrecioCompra" min="0" step="any" placeholder="Precio Compra" required>
                </div>

              </div>

              <!-- ENTRADA PARA PRECIO VENTA -->

              <div class="col-xs-6">

                <div class="input-group">
                  <span class="input-group-addon">PRECIO_VENTA</span>

                  <input type="number" class="form-control input-lg" id="editarPrecioVenta" name="editarPrecioVenta" min="0" step="any" placeholder="Precio Venta" required>

                </div>

              </div>

            </div>

            <div class=" form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">ES INVETARIABLE?</span>
                  <select class="form-control input-lg" id="editarInventariable" name="editarInventariable" required>
                    <option value="" disabled>Es inventariable:</option>
                    <option value="0">NO</option>
                    <option value="1" selected>SI</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <!-- CHECKBOX PARA PORCENTAJE -->
                <div class="col-xs-6">
                  <div class="form-group">
                    <label>
                      <input type="checkbox" class="minimal porcentaje">
                      Utilizar porcentaje
                      (Opcional)
                    </label>
                  </div>
                </div>

                <!-- ENTRADA PARA PORCENTAJE -->
                <div class="col-xs-6" style="padding:0">
                  <div class="input-group">
                    <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                  </div>
                </div>
              </div>

            </div>
            <!-- ENTRADA PARA SUBIR FOTO -->

            <div class="form-group">

              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="imagenActual" id="imagenActual">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-" style="background:#6c757d; color:white">Guardar cambios</button>

        </div>

      </form>

      <?php


      $editarProducto = new ControladorProductos();
      $editarProducto->ctrEditarProducto();



      ?>

    </div>

  </div>

</div>

<?php

$eliminarProducto = new ControladorProductos();
$eliminarProducto->ctrEliminarProducto();
$restaurarProducto = new ControladorProductos();
$restaurarProducto->ctrRestaurarProducto();

?>

<script>
    // Obtener los elementos del DOM
    const inventariableSelect = document.getElementById('editarInventariable');
    const stockInput = document.getElementById('editarStock');

    // Escuchar el evento 'change' del select
    inventariableSelect.addEventListener('change', function () {
        if (this.value === "0") { // Si selecciona "NO"
            stockInput.removeAttribute('readonly'); // Activar el campo
        } else { // Si selecciona "SI"
            stockInput.setAttribute('readonly', true); // Deshabilitar el campo
        }
    });
</script>